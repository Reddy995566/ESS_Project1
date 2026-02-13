<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\UserAddress;
use App\Models\Setting;
use App\Mail\OrderPlaced;
use Illuminate\Support\Str;
use Razorpay\Api\Api;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        
        if(count($cart) == 0) {
            return redirect()->route('cart.data')->with('error', 'Your cart is empty');
        }

        $user = Auth::user();
        $addresses = $user->addresses;
        
        // Get last order's address for prefilling
        $lastOrder = Order::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->first();
        
        $subtotal = 0;
        foreach($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        // Check for applied coupon
        $discount = 0;
        $appliedCoupon = session('applied_coupon');
        if ($appliedCoupon) {
            // Validate coupon is still valid
            $coupon = \App\Models\Coupon::find($appliedCoupon['id']);
            if ($coupon && $coupon->isValid() && (!$coupon->min_purchase || $subtotal >= $coupon->min_purchase)) {
                $discount = $appliedCoupon['discount_amount'];
            } else {
                // Coupon is no longer valid, remove it
                session()->forget('applied_coupon');
                $appliedCoupon = null;
            }
        }

        $tax = 0;
        $shipping = 0;
        $total = $subtotal + $tax + $shipping - $discount;

        // Get Razorpay Key for frontend
        $razorpayKey = Setting::get('razorpay_key_id', '');

        return view('website.checkout', compact('cart', 'user', 'addresses', 'lastOrder', 'subtotal', 'tax', 'shipping', 'discount', 'total', 'razorpayKey', 'appliedCoupon'));
    }

    public function store(Request $request)
    {
        // Check if cart is empty
        $cart = session()->get('cart', []);
        if(count($cart) == 0) {
            return response()->json([
                'success' => false,
                'message' => 'Your cart is empty. Please add items before checkout.'
            ], 400);
        }

        // Validation
        $request->validate([
            'payment_method' => 'required',
            // If selecting existing address
            'shipping_address_id' => 'nullable|exists:user_addresses,id',
            // If new address (basic validation, refine as needed)
            'first_name' => 'required_without:shipping_address_id',
            'address' => 'required_without:shipping_address_id',
            'city' => 'required_without:shipping_address_id',
            'state' => 'required_without:shipping_address_id',
            'pincode' => 'required_without:shipping_address_id',
            'phone' => 'required_without:shipping_address_id',
            // Billing Address Validation
            'billing_selection' => 'required|in:same,different',
            'billing_first_name' => 'required_if:billing_selection,different',
            'billing_address' => 'required_if:billing_selection,different',
            'billing_city' => 'required_if:billing_selection,different',
            'billing_state' => 'required_if:billing_selection,different',
            'billing_pincode' => 'required_if:billing_selection,different',
        ], [
            'first_name.required_without' => 'First name is required',
            'address.required_without' => 'Address is required',
            'city.required_without' => 'City is required',
            'state.required_without' => 'State is required',
            'pincode.required_without' => 'PIN code is required',
            'phone.required_without' => 'Phone number is required',
            'billing_first_name.required_if' => 'Billing first name is required',
            'billing_address.required_if' => 'Billing address is required',
            'billing_city.required_if' => 'Billing city is required',
            'billing_state.required_if' => 'Billing state is required',
            'billing_pincode.required_if' => 'Billing PIN code is required',
        ]);

        $cart = session()->get('cart', []);
        if(count($cart) == 0) {
            return back()->with('error', 'Cart is empty');
        }

        try {
            DB::beginTransaction();

            $user = Auth::user();
            
            // Determine Address Data
            if ($request->shipping_address_id) {
                $address = UserAddress::find($request->shipping_address_id);
                $addressData = [
                    'first_name' => filter_var($address->full_name, FILTER_SANITIZE_STRING), // Adapting because schema expects first/last
                    'last_name' => '', 
                    'email' => $user->email,
                    'phone' => $address->phone,
                    'country' => 'India',
                    'address' => $address->address,
                    'address_line_2' => $address->apartment,
                    'city' => $address->city,
                    'state' => $address->state,
                    'zipcode' => $address->pincode,
                ];
                
                // Explode full name if possible or just store in first name
                $names = explode(' ', $address->full_name, 2);
                $addressData['first_name'] = $names[0];
                $addressData['last_name'] = $names[1] ?? '';

            } else {
                $addressData = [
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name ?? '',
                    'email' => $request->email ?? $user->email,
                    'phone' => $request->phone,
                    'country' => $request->country ?? 'India',
                    'address' => $request->address,
                    'address_line_2' => $request->apartment,
                    'city' => $request->city,
                    'state' => $request->state,
                    'zipcode' => $request->pincode,
                ];
            }

            // Billing Address Data
            if($request->billing_selection == 'different') {
                $billingData = [
                    'billing_first_name' => $request->billing_first_name,
                    'billing_last_name' => $request->billing_last_name ?? '',
                    'billing_email' => $request->billing_email ?? $user->email, // Often same email, or add field
                    'billing_phone' => $request->billing_phone,
                    'billing_country' => $request->billing_country ?? 'India',
                    'billing_address' => $request->billing_address,
                    'billing_address_line_2' => $request->billing_address_line_2,
                    'billing_city' => $request->billing_city,
                    'billing_state' => $request->billing_state,
                    'billing_zipcode' => $request->billing_pincode,
                ];
            } else {
                // Copy shipping address to billing address
                 $billingData = [
                    'billing_first_name' => $addressData['first_name'],
                    'billing_last_name' => $addressData['last_name'],
                    'billing_email' => $addressData['email'],
                    'billing_phone' => $addressData['phone'],
                    'billing_country' => $addressData['country'],
                    'billing_address' => $addressData['address'],
                    'billing_address_line_2' => $addressData['address_line_2'],
                    'billing_city' => $addressData['city'],
                    'billing_state' => $addressData['state'],
                    'billing_zipcode' => $addressData['zipcode'],
                ];
            }

            // Calculate Totals again for security
            $subtotal = 0;
            foreach($cart as $item) {
                $subtotal += $item['price'] * $item['quantity'];
            }
            
            // Validate stock availability before processing order
            foreach($cart as $item) {
                if (isset($item['color_id']) && isset($item['size_id'])) {
                    $variant = \App\Models\ProductVariant::where('product_id', $item['product_id'])
                        ->where('color_id', $item['color_id'])
                        ->where('size_id', $item['size_id'])
                        ->first();
                    
                    if (!$variant) {
                        DB::rollBack();
                        return response()->json([
                            'success' => false,
                            'message' => "Product variant not found for {$item['name']}"
                        ], 400);
                    }
                    
                    if ($variant->stock < $item['quantity']) {
                        DB::rollBack();
                        return response()->json([
                            'success' => false,
                            'message' => "Insufficient stock for {$item['name']}. Only {$variant->stock} available."
                        ], 400);
                    }
                }
            }
            
            // Apply discount if coupon was applied
            $discount = 0;
            $appliedCoupon = session('applied_coupon');
            if ($appliedCoupon) {
                $discount = $appliedCoupon['discount_amount'];
                
                // Validate coupon is still valid
                $coupon = \App\Models\Coupon::find($appliedCoupon['id']);
                if (!$coupon || !$coupon->isValid() || ($coupon->min_purchase && $subtotal < $coupon->min_purchase)) {
                    // Coupon is no longer valid, remove it
                    session()->forget('applied_coupon');
                    $discount = 0;
                }
            }
            
            $total = $subtotal - $discount; // Add tax/shipping logic here if needed

            // Generate Order Number - Pure digits with leading zeros
            $previousOrder = Order::orderBy('id', 'desc')->first();
            $nextNumber = $previousOrder ? $previousOrder->id + 1 : 1;
            $orderNumber = str_pad($nextNumber, 9, '0', STR_PAD_LEFT); // 000000001, 000000002, etc.
            
            $order = Order::create(array_merge($addressData, $billingData, [
                'order_number' => $orderNumber,
                'user_id' => $user->id,
                'session_id' => session()->getId(),
                'subtotal' => $subtotal,
                'discount' => $discount,
                'coupon_code' => $appliedCoupon ? $appliedCoupon['code'] : null,
                'coupon_id' => $appliedCoupon ? $appliedCoupon['id'] : null,
                'total' => $total,
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'status' => 'pending',
            ]));

            // Create Order Items
            foreach($cart as $item) {
                // Calculate commission (assuming 10% commission rate, you can make this configurable)
                $commissionRate = 10.00; // 10%
                $itemTotal = $item['price'] * $item['quantity'];
                $commissionAmount = ($itemTotal * $commissionRate) / 100;
                $sellerAmount = $itemTotal - $commissionAmount;
                
                // Find variant_id if color and size are present
                $variantId = null;
                if (isset($item['color_id']) && isset($item['size_id'])) {
                    $variant = \App\Models\ProductVariant::where('product_id', $item['product_id'])
                        ->where('color_id', $item['color_id'])
                        ->where('size_id', $item['size_id'])
                        ->first();
                    if ($variant) {
                        $variantId = $variant->id;
                    }
                }
                
                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'seller_id' => $item['seller_id'] ?? null, // Add seller_id to order items
                    'product_name' => $item['name'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'total' => $itemTotal,
                    'commission_rate' => $commissionRate,
                    'commission_amount' => $commissionAmount,
                    'seller_amount' => $sellerAmount,
                    'variant_id' => $variantId,
                    'variant_name' => implode(' | ', array_filter([
                        isset($item['color_name']) ? 'Color: ' . $item['color_name'] : null,
                        isset($item['size_abbr']) ? 'Size: ' . $item['size_abbr'] : (isset($item['size_name']) ? 'Size: ' . $item['size_name'] : null),
                    ])) ?: null,
                    'image' => $item['image'] ?? null, // Save variant-specific image from cart
                ]);

                // Decrease stock for the variant
                if ($variantId) {
                    $variant = \App\Models\ProductVariant::find($variantId);
                    if ($variant) {
                        // Decrease stock
                        $newStock = max(0, $variant->stock - $item['quantity']);
                        $variant->update(['stock' => $newStock]);
                    }
                }

                // Add to seller wallet if seller exists
                if ($item['seller_id']) {
                    $seller = \App\Models\Seller::find($item['seller_id']);
                    if ($seller) {
                        // Get or create wallet
                        $wallet = $seller->wallet;
                        if (!$wallet) {
                            $wallet = \App\Models\SellerWallet::create([
                                'seller_id' => $seller->id,
                                'balance' => 0.00,
                                'pending_balance' => 0.00,
                                'total_earned' => 0.00,
                                'total_withdrawn' => 0.00,
                                'is_active' => true,
                            ]);
                        }

                        // Add commission to wallet
                        $wallet->addFunds(
                            $sellerAmount,
                            'order_commission',
                            "Commission from order #{$order->order_number} - {$item['name']}",
                            [
                                'order_id' => $order->id,
                                'order_item_id' => $orderItem->id,
                                'product_id' => $item['product_id'],
                                'commission_rate' => $commissionRate,
                                'commission_amount' => $commissionAmount,
                            ],
                            $order->id
                        );

                        // Send notification to seller about new order
                        $seller->sendNotification(
                            'order_placed',
                            'New Order Received!',
                            "You have received a new order #{$order->order_number} for {$item['name']} (Qty: {$item['quantity']}). Amount: ₹{$sellerAmount}",
                            [
                                'order_id' => $order->id,
                                'order_number' => $order->order_number,
                                'product_name' => $item['name'],
                                'quantity' => $item['quantity'],
                                'amount' => $sellerAmount,
                            ]
                        );
                    }
                }
            }

            // If online payment, create Razorpay order
            if($request->payment_method == 'online') {
                $razorpayKeyId = Setting::get('razorpay_key_id');
                $razorpaySecret = Setting::get('razorpay_key_secret');
                
                // Check if Razorpay credentials are configured
                if (empty($razorpayKeyId) || empty($razorpaySecret)) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Online payment is not configured. Please contact support or use Cash on Delivery.'
                    ], 400);
                }
                
                try {
                    $api = new Api($razorpayKeyId, $razorpaySecret);
                    
                    $razorpayOrder = $api->order->create([
                        'amount' => $total * 100, // Amount in paise
                        'currency' => 'INR',
                        'receipt' => $orderNumber,
                        'notes' => [
                            'order_id' => $order->id,
                            'customer_name' => $user->name,
                        ]
                    ]);
                } catch (\Exception $e) {
                    DB::rollBack();
                    \Log::error('Razorpay API error: ' . $e->getMessage());
                    return response()->json([
                        'success' => false,
                        'message' => 'Payment gateway error. Please try Cash on Delivery or contact support.'
                    ], 500);
                }
                
                // Store Razorpay order ID
                $order->update(['transaction_id' => $razorpayOrder['id']]);
                
                // Increment coupon usage if applied
                if ($appliedCoupon) {
                    $coupon = \App\Models\Coupon::find($appliedCoupon['id']);
                    if ($coupon) {
                        $coupon->increment('used_count');
                    }
                    session()->forget('applied_coupon');
                }
                
                DB::commit();
                
                // Return JSON for frontend to open Razorpay modal
                return response()->json([
                    'success' => true,
                    'razorpay_order_id' => $razorpayOrder['id'],
                    'amount' => $total,
                    'order_id' => $order->id,
                    'key' => $razorpayKeyId,
                    'name' => $user->name,
                    'email' => $user->email,
                    'contact' => $user->mobile,
                ]);
            }

            // For COD, clear cart and redirect
            session()->forget('cart');
            
            // Increment coupon usage if applied
            if ($appliedCoupon) {
                $coupon = \App\Models\Coupon::find($appliedCoupon['id']);
                if ($coupon) {
                    $coupon->increment('used_count');
                }
                session()->forget('applied_coupon');
            }
            
            DB::commit();

            // Send order confirmation email to customer
            try {
                Mail::to($user->email)->send(new OrderPlaced($order));
            } catch (\Exception $e) {
                // Log email error but don't fail the order
                \Log::error('Order confirmation email failed: ' . $e->getMessage());
            }

            // Send order notification email to admin
            try {
                $adminEmail = config('mail.from.address');
                Mail::to($adminEmail)->send(new \App\Mail\AdminOrderNotificationMail($order));
            } catch (\Exception $e) {
                \Log::error('Admin order notification email failed: ' . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'redirect' => route('order.success', $order->id),
                'order_id' => $order->id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Checkout error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Order placement failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function verifyPayment(Request $request)
    {
        try {
            $razorpayKeyId = Setting::get('razorpay_key_id');
            $razorpaySecret = Setting::get('razorpay_key_secret');
            
            $api = new Api($razorpayKeyId, $razorpaySecret);
            
            // Verify signature
            $attributes = [
                'razorpay_order_id' => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature' => $request->razorpay_signature
            ];
            
            $api->utility->verifyPaymentSignature($attributes);
            
            // Update order
            $order = Order::where('transaction_id', $request->razorpay_order_id)->firstOrFail();
            $order->update([
                'payment_status' => 'paid',
                'status' => 'processing',
                'paid_at' => now(),
            ]);
            
            // Clear cart
            session()->forget('cart');

            // Send order confirmation email to customer
            try {
                Mail::to($order->email)->send(new OrderPlaced($order));
            } catch (\Exception $e) {
                // Log email error but don't fail the order
                \Log::error('Order confirmation email failed: ' . $e->getMessage());
            }

            // Send order notification email to admin
            try {
                $adminEmail = config('mail.from.address');
                Mail::to($adminEmail)->send(new \App\Mail\AdminOrderNotificationMail($order));
            } catch (\Exception $e) {
                \Log::error('Admin order notification email failed: ' . $e->getMessage());
            }
            
            return response()->json([
                'success' => true,
                'order_id' => $order->id,
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Payment verification failed: ' . $e->getMessage()
            ], 400);
        }
    }

    public function success($id)
    {
        $order = Order::with(['items.product'])->findOrFail($id);
        if($order->user_id != Auth::id()) abort(403);
        return view('website.order_success', compact('order'));
    }

    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string',
            'subtotal' => 'required|numeric|min:0'
        ]);

        $couponCode = strtoupper(trim($request->coupon_code));
        $subtotal = $request->subtotal;

        // Find and validate coupon
        $coupon = \App\Models\Coupon::where('code', $couponCode)
            ->valid()
            ->first();

        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired coupon code'
            ], 400);
        }

        // Check minimum purchase requirement
        if ($coupon->min_purchase && $subtotal < $coupon->min_purchase) {
            return response()->json([
                'success' => false,
                'message' => "Minimum purchase of Rs. " . number_format($coupon->min_purchase) . " required for this coupon"
            ], 400);
        }

        // Calculate discount
        $discountAmount = 0;
        if ($coupon->discount_type === 'percentage') {
            $discountAmount = ($subtotal * $coupon->discount_value) / 100;
            
            // Apply max discount limit if set
            if ($coupon->max_discount && $discountAmount > $coupon->max_discount) {
                $discountAmount = $coupon->max_discount;
            }
        } else {
            // Fixed discount
            $discountAmount = $coupon->discount_value;
        }

        // Ensure discount doesn't exceed subtotal
        $discountAmount = min($discountAmount, $subtotal);

        // Store coupon in session for checkout
        session(['applied_coupon' => [
            'id' => $coupon->id,
            'code' => $coupon->code,
            'discount_amount' => $discountAmount,
            'discount_type' => $coupon->discount_type,
            'discount_value' => $coupon->discount_value
        ]]);

        return response()->json([
            'success' => true,
            'message' => 'Coupon applied successfully',
            'coupon' => [
                'id' => $coupon->id,
                'code' => $coupon->code,
                'discount_type' => $coupon->discount_type,
                'discount_value' => $coupon->discount_value
            ],
            'discount_amount' => $discountAmount,
            'new_total' => $subtotal - $discountAmount
        ]);
    }
}
