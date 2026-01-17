<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\UserAddress;
use App\Models\Setting;
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
        $subtotal = 0;
        foreach($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $tax = 0;
        $shipping = 0;
        $total = $subtotal + $tax + $shipping;

        // Get Razorpay Key for frontend
        $razorpayKey = Setting::get('razorpay_key_id', '');

        return view('website.checkout', compact('cart', 'user', 'addresses', 'subtotal', 'tax', 'shipping', 'total', 'razorpayKey'));
    }

    public function store(Request $request)
    {

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
            'zipcode' => 'required_without:shipping_address_id',
            'phone' => 'required_without:shipping_address_id',
            // Billing Address Validation
            'billing_selection' => 'required|in:same,different',
            'billing_first_name' => 'required_if:billing_selection,different',
            'billing_address' => 'required_if:billing_selection,different',
            'billing_city' => 'required_if:billing_selection,different',
            'billing_state' => 'required_if:billing_selection,different',
            'billing_zipcode' => 'required_if:billing_selection,different',
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
                    'zipcode' => $request->zipcode,
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
                    'billing_zipcode' => $request->billing_zipcode,
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
            $total = $subtotal; // Add tax/shipping logic here if needed

            // Create Order
            $orderNumber = 'ORD-' . strtoupper(Str::random(10));
            $order = Order::create(array_merge($addressData, $billingData, [
                'order_number' => $orderNumber,
                'user_id' => $user->id,
                'session_id' => session()->getId(),
                'subtotal' => $subtotal,
                'total' => $total,
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'status' => 'pending',
            ]));

            // Create Order Items
            foreach($cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'product_name' => $item['name'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'total' => $item['price'] * $item['quantity'],
                    'variant_id' => null,
                    'variant_name' => $item['color_name'] ?? null,
                ]);
            }

            // If online payment, create Razorpay order
            if($request->payment_method == 'online') {
                $razorpayKeyId = Setting::get('razorpay_key_id');
                $razorpaySecret = Setting::get('razorpay_key_secret');
                
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
                
                // Store Razorpay order ID
                $order->update(['transaction_id' => $razorpayOrder['id']]);
                
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
            DB::commit();
            return redirect()->route('order.success', $order->id);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Order placement failed: ' . $e->getMessage());
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
        $order = Order::findOrFail($id);
        if($order->user_id != Auth::id()) abort(403);
        return view('website.order_success', compact('order'));
    }
}
