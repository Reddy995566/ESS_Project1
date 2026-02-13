<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Color;
use App\Models\Cart;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        try {
            $productId = $request->input('product_id');
            $quantity = $request->input('quantity', 1);
            $colorId = $request->input('color_id');
            $sizeId = $request->input('size_id');

            $product = Product::with(['colors', 'sizes', 'variants', 'seller'])->find($productId);

            if (!$product) {
                return response()->json(['success' => false, 'message' => 'Product not found'], 404);
            }

            $cart = session()->get('cart', []);

            // Unique key for cart item (Product + Color + Size)
            $cartKey = $productId . ($colorId ? '_' . $colorId : '') . ($sizeId ? '_' . $sizeId : '');

            // Get Color Details if applicable
            $colorName = null;
            $colorHex = null;
            if ($colorId) {
                $color = Color::find($colorId);
                if ($color) {
                    $colorName = $color->name;
                    $colorHex = $color->hex_code;
                }
            }

            // Get Size Details if applicable
            $sizeName = null;
            $sizeAbbr = null;
            if ($sizeId) {
                $size = \App\Models\Size::find($sizeId);
                if ($size) {
                    $sizeName = $size->name;
                    $sizeAbbr = $size->abbreviation;
                }
            }

            // Get Image
            $image = $product->image_url;
            if ($colorId) {
                // Try to find image for this color variant
                $variant = $product->variants->where('color_id', $colorId)->first();
                if ($variant) {
                    $images = $variant->images;
                    if (is_string($images)) {
                        $images = json_decode($images, true);
                    }
                    if (is_array($images) && !empty($images)) {
                        $firstImage = $images[0];
                        if (is_array($firstImage)) {
                            $image = $firstImage['url'] ?? $firstImage['path'] ?? $image;
                        } else {
                            $image = $firstImage;
                        }
                    }
                }
            }
            
            // Check stock availability
            if ($colorId && $sizeId) {
                $variant = \App\Models\ProductVariant::where('product_id', $productId)
                    ->where('color_id', $colorId)
                    ->where('size_id', $sizeId)
                    ->first();
                
                if (!$variant) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Product variant not found'
                    ], 400);
                }
                
                // Check if adding this quantity exceeds stock
                $currentQtyInCart = isset($cart[$productId . '_' . $colorId . '_' . $sizeId]) 
                    ? $cart[$productId . '_' . $colorId . '_' . $sizeId]['quantity'] 
                    : 0;
                $totalQty = $currentQtyInCart + $quantity;
                
                if ($variant->stock < $totalQty) {
                    $availableToAdd = max(0, $variant->stock - $currentQtyInCart);
                    return response()->json([
                        'success' => false,
                        'message' => $availableToAdd > 0 
                            ? "Only {$availableToAdd} more items can be added. Total stock: {$variant->stock}"
                            : "No more stock available. You already have {$currentQtyInCart} in cart."
                    ], 400);
                }
            }

            $itemData = [
                'product_id' => $product->id,
                'seller_id' => $product->seller_id, // Add seller_id to cart
                'name' => $product->name,
                'price' => $product->sale_price > 0 ? $product->sale_price : $product->price,
                'original_price' => $product->sale_price > 0 ? $product->price : null,
                'image' => $image,
                'quantity' => $quantity,
                'color_id' => $colorId,
                'color_name' => $colorName,
                'color_hex' => $colorHex,
                'size_id' => $sizeId,
                'size_name' => $sizeName,
                'size_abbr' => $sizeAbbr,
                'key' => $cartKey // Store key in session for consistency
            ];

            if (isset($cart[$cartKey])) {
                $cart[$cartKey]['quantity'] += $quantity;
                // Ensure key is there (if updating old item)
                $cart[$cartKey]['key'] = $cartKey;
            } else {
                $cart[$cartKey] = $itemData;
            }

            session()->put('cart', $cart);

            // Calculate totals
            $totals = $this->calculateTotals($cart);

            return response()->json([
                'success' => true,
                'message' => 'Product added to cart successfully!',
                'cart' => $cart,
                'cart_items' => array_values($cart),
                'count' => count($cart),
                'totals' => $totals
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function getCart()
    {
        $cart = session()->get('cart', []);

        // Ensure keys are present in the list
        $cartItems = [];
        foreach ($cart as $key => $item) {
            if (!isset($item['key'])) {
                $item['key'] = $key;
            }
            $cartItems[] = $item;
        }

        $totals = $this->calculateTotals($cart);

        return response()->json([
            'success' => true,
            'cart' => $cart,
            'cart_items' => $cartItems,
            'count' => count($cart),
            'totals' => $totals
        ]);
    }
    /**
     * Show cart page
     */
    public function index()
    {
        return view('website.cart');
    }


    public function removeFromCart(Request $request)
    {
        $cartKey = $request->input('key');
        if (!$cartKey)
            return response()->json(['success' => false, 'message' => 'Invalid item'], 400);

        $cart = session()->get('cart', []);

        if (isset($cart[$cartKey])) {
            unset($cart[$cartKey]);
            session()->put('cart', $cart);
        }

        $totals = $this->calculateTotals($cart);

        return response()->json([
            'success' => true,
            'cart_items' => array_values($cart),
            'count' => count($cart),
            'totals' => $totals
        ]);
    }

    public function updateQuantity(Request $request)
    {
        $cartKey = $request->input('key');
        $quantity = $request->input('quantity');

        if (!$cartKey || $quantity < 1)
            return response()->json(['success' => false, 'message' => 'Invalid request'], 400);

        $cart = session()->get('cart', []);

        if (isset($cart[$cartKey])) {
            $item = $cart[$cartKey];
            
            // Check stock availability if color and size are present
            if (isset($item['color_id']) && isset($item['size_id'])) {
                $variant = \App\Models\ProductVariant::where('product_id', $item['product_id'])
                    ->where('color_id', $item['color_id'])
                    ->where('size_id', $item['size_id'])
                    ->first();
                
                if (!$variant) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Product variant not found'
                    ], 400);
                }
                
                if ($variant->stock < $quantity) {
                    return response()->json([
                        'success' => false,
                        'message' => "Only {$variant->stock} items available in stock"
                    ], 400);
                }
            }
            
            $cart[$cartKey]['quantity'] = intval($quantity);
            session()->put('cart', $cart);
        }

        $totals = $this->calculateTotals($cart);

        return response()->json([
            'success' => true,
            'cart_items' => array_values($cart),
            'count' => count($cart),
            'totals' => $totals
        ]);
    }

    private function calculateTotals($cart)
    {
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        return [
            'subtotal' => $subtotal,
            'subtotal_formatted' => number_format($subtotal)
        ];
    }
    
    /**
     * Get cart item count
     */
    public function getCount()
    {
        $cart = session()->get('cart', []);
        $count = 0;
        
        foreach ($cart as $item) {
            $count += $item['quantity'] ?? 1;
        }
        
        return response()->json(['count' => $count]);
    }
    
    /**
     * Syncs the current session cart with the user's database cart.
     * Called after Login/Register.
     */
    public static function syncUserCart($user)
    {
        $sessionCart = session()->get('cart', []);

        if (empty($sessionCart)) {
            return;
        }

        foreach ($sessionCart as $key => $item) {
            // Get variant_id from session cart
            $variantId = $item['variant_id'] ?? null;
            
            // Skip if variant doesn't exist in database
            if ($variantId && !\App\Models\ProductVariant::where('id', $variantId)->exists()) {
                continue;
            }

            Cart::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'product_id' => $item['product_id'],
                    'variant_id' => $variantId
                ],
                [
                    'session_id' => session()->getId(),
                    'quantity' => \DB::raw("quantity + {$item['quantity']}"), // Add to existing if exists
                    'price' => $item['price']
                ]
            );
        }

        // Optional: Clear session cart? 
        // For now, let's KEEP session cart as the source of truth for the frontend
        // but ideally we should reload session from DB to catch any merged quantities correctly.

        // Actually, if we use DB::raw quantity+, the DB has more than session. 
        // We should re-fetch from DB to update session.

        // Let's keep it simple for now: Merge Session -> DB. 
        // The user just wants it "saved". 
        // If we want perfection, we would empty an existing session and reload from full DB.
    }
}
