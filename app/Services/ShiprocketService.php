<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ShiprocketService
{
    protected $baseUrl = 'https://apiv2.shiprocket.in/v1/external';
    protected $email;
    protected $password;
    protected $token;

    public function __construct()
    {
        $this->email = Setting::get('shiprocket_email');
        $this->password = Setting::get('shiprocket_password');
    }

    public function login()
    {
        try {
            if (!$this->email || !$this->password) {
                throw new \Exception('Shiprocket credentials not configured.');
            }

            $response = Http::post("{$this->baseUrl}/auth/login", [
                'email' => $this->email,
                'password' => $this->password,
            ]);

            if ($response->successful()) {
                $this->token = $response->json()['token'];
                return $this->token;
            }

            Log::error('Shiprocket Login Failed: ' . $response->body());
            throw new \Exception('Shiprocket login failed: ' . $response->json()['message'] ?? 'Unknown error');

        } catch (\Exception $e) {
            Log::error('Shiprocket Login Exception: ' . $e->getMessage());
            throw $e;
        }
    }

    public function createOrder(Order $order)
    {
        if (!$this->token) {
            $this->login();
        }

        $items = $order->items->map(function ($item) {
            return [
                'name' => $item->product_name,
                'sku' => $item->variant ? $item->variant->sku : ($item->product->sku ?? 'SKU-' . $item->product_id),
                'units' => $item->quantity,
                'selling_price' => $item->price,
                'discount' => 0,
                'tax' => 0,
                'hsn' => 0, // Should be populated from product if available
            ];
        })->toArray();

        // Format Date
        $orderDate = $order->created_at->format('Y-m-d H:i');

        // Payment Method
        $paymentMethod = $order->payment_method === 'cod' ? 'COD' : 'Prepaid';

        $data = [
            'order_id' => (string) $order->order_number,
            'order_date' => $orderDate,
            'pickup_location' => 'Home', // Default pickup location from Shiprocket account
            'billing_customer_name' => $order->billing_first_name ?? $order->first_name,
            'billing_last_name' => $order->billing_last_name ?? $order->last_name,
            'billing_address' => $order->billing_address ?? $order->address,
            'billing_address_2' => $order->billing_address_line_2 ?? $order->address_line_2,
            'billing_city' => $order->billing_city ?? $order->city,
            'billing_pincode' => $order->billing_zipcode ?? $order->zipcode,
            'billing_state' => $order->billing_state ?? $order->state,
            'billing_country' => $order->billing_country ?? 'India',
            'billing_email' => $order->billing_email ?? $order->email,
            'billing_phone' => $order->billing_phone ?? $order->phone,
            'shipping_is_billing' => true,
            'shipping_customer_name' => $order->first_name,
            'shipping_last_name' => $order->last_name,
            'shipping_address' => $order->address,
            'shipping_address_2' => $order->address_line_2,
            'shipping_city' => $order->city,
            'shipping_pincode' => $order->zipcode,
            'shipping_country' => $order->country ?? 'India',
            'shipping_state' => $order->state,
            'shipping_email' => $order->email,
            'shipping_phone' => $order->phone,
            'order_items' => $items,
            'payment_method' => $paymentMethod,
            'sub_total' => $order->subtotal,
            'length' => 10, // Default dimensions
            'breadth' => 10,
            'height' => 10,
            'weight' => 0.5, // Default weight
        ];

        
        $response = Http::withToken($this->token)->post("{$this->baseUrl}/orders/create/adhoc", $data);

        if ($response->successful()) {
            $data = $response->json();
            
            // Check for direct order_id
            if (isset($data['order_id'])) {
                return $data;
            }
            
            // Check for order_id inside data key (common in v2)
            if (isset($data['data']) && isset($data['data']['order_id'])) {
                return $data['data'];
            }

            // If neither, throw exception with body for debugging
            throw new \Exception('Unexpected Shiprocket Response (Missing order_id): ' . $response->body());
        }

        Log::error('Shiprocket Create Order Failed: ' . $response->body());
        $errorMessage = $response->json()['message'] ?? 'Unknown error';
        
        // Handle validation errors specific to Shiprocket
        if (isset($response->json()['errors'])) {
             $errorMessage .= ' - ' . json_encode($response->json()['errors']);
        }

        throw new \Exception('Shiprocket order creation failed: ' . $errorMessage);
    }


    public function getAvailableCouriers($order, $sellerSettings = null)
    {
        $token = $this->token;
        
        // Use seller's token if seller settings provided
        if ($sellerSettings) {
            $token = $this->authenticate($sellerSettings['shiprocket_email'], $sellerSettings['shiprocket_password']);
            if (!$token) {
                throw new \Exception('Invalid Shiprocket credentials');
            }
        } else if (!$this->token) {
            $this->login();
            $token = $this->token;
        }

        if (!$order->shiprocket_order_id) {
             throw new \Exception('Order must be pushed to Shiprocket first.');
        }

        $pickupPostcode = Setting::get('pickup_pincode', '110001'); // Use admin's pickup pincode
        $deliveryPostcode = $order->billing_zipcode ?? $order->zipcode;
        $weight = 0.5;
        $cod = $order->payment_method === 'cod' ? 1 : 0;

        $params = [
            'pickup_postcode' => $pickupPostcode,
            'delivery_postcode' => $deliveryPostcode,
            'weight' => $weight,
            'cod' => $cod,
            'order_id' => $order->shiprocket_order_id,
        ];

        $response = Http::withToken($token)->get("{$this->baseUrl}/courier/serviceability", $params);

        if ($response->successful()) {
            $data = $response->json();
            if (isset($data['data']['available_courier_companies']) && !empty($data['data']['available_courier_companies'])) {
                return $data['data']['available_courier_companies'];
            }
            return [];
        }

        Log::error('Shiprocket Fetch Couriers Failed: ' . $response->body());
        throw new \Exception('Failed to fetch available couriers: ' . ($response->json()['message'] ?? 'Unknown error'));
    }

    public function generateAwb($shipmentId, $courierId, $sellerSettings = null)
    {
        $token = $this->token;
        
        // Use seller's token if seller settings provided
        if ($sellerSettings) {
            $token = $this->authenticate($sellerSettings['shiprocket_email'], $sellerSettings['shiprocket_password']);
            if (!$token) {
                throw new \Exception('Invalid Shiprocket credentials');
            }
        } else if (!$this->token) {
            $this->login();
            $token = $this->token;
        }

        $data = [
            'shipment_id' => $shipmentId,
            'courier_id' => $courierId,
        ];

        $response = Http::withToken($token)->post("{$this->baseUrl}/courier/assign/awb", $data);

        if ($response->successful()) {
            return $response->json();
        }

        Log::error('Shiprocket Generate AWB Failed: ' . $response->body());
        throw new \Exception('Failed to generate AWB: ' . ($response->json()['message'] ?? 'Unknown error'));
    }
    
    // Seller-specific methods
    public function authenticate($email, $password)
    {
        try {
            $response = Http::post("{$this->baseUrl}/auth/login", [
                'email' => $email,
                'password' => $password,
            ]);

            if ($response->successful()) {
                return $response->json()['token'];
            }

            return false;

        } catch (\Exception $e) {
            Log::error('Shiprocket Authentication Exception: ' . $e->getMessage());
            return false;
        }
    }
    
    public function createSellerOrder(Order $order, $seller, $sellerSettings)
    {
        // Use seller's credentials but admin's pickup settings
        $token = $this->authenticate($sellerSettings['shiprocket_email'], $sellerSettings['shiprocket_password']);
        
        if (!$token) {
            throw new \Exception('Invalid Shiprocket credentials for seller');
        }

        $items = $order->items->where('seller_id', $seller->id)->map(function ($item) {
            return [
                'name' => $item->product_name,
                'sku' => $item->variant ? $item->variant->sku : ($item->product->sku ?? 'SKU-' . $item->product_id),
                'units' => $item->quantity,
                'selling_price' => $item->price,
                'discount' => 0,
                'tax' => 0,
                'hsn' => 0,
            ];
        })->toArray();

        $orderDate = $order->created_at->format('Y-m-d H:i');
        $paymentMethod = $order->payment_method === 'cod' ? 'COD' : 'Prepaid';

        // Use admin's pickup settings for consistency
        $adminPickupAddress = Setting::get('pickup_address', 'Default Pickup Address');
        $adminPickupCity = Setting::get('pickup_city', 'Mumbai');
        $adminPickupState = Setting::get('pickup_state', 'Maharashtra');
        $adminPickupPincode = Setting::get('pickup_pincode', '400001');
        $adminPickupPerson = Setting::get('pickup_contact_person', 'Admin');
        $adminPickupPhone = Setting::get('pickup_contact_phone', '9999999999');

        $data = [
            'order_id' => (string) $order->order_number . '-S' . $seller->id,
            'order_date' => $orderDate,
            'pickup_location' => $adminPickupPerson,
            'billing_customer_name' => $order->billing_first_name ?? $order->first_name,
            'billing_last_name' => $order->billing_last_name ?? $order->last_name,
            'billing_address' => $order->billing_address ?? $order->address,
            'billing_address_2' => $order->billing_address_line_2 ?? $order->address_line_2,
            'billing_city' => $order->billing_city ?? $order->city,
            'billing_pincode' => $order->billing_zipcode ?? $order->zipcode,
            'billing_state' => $order->billing_state ?? $order->state,
            'billing_country' => $order->billing_country ?? 'India',
            'billing_email' => $order->billing_email ?? $order->email,
            'billing_phone' => $order->billing_phone ?? $order->phone,
            'shipping_is_billing' => true,
            'shipping_customer_name' => $order->first_name,
            'shipping_last_name' => $order->last_name,
            'shipping_address' => $order->address,
            'shipping_address_2' => $order->address_line_2,
            'shipping_city' => $order->city,
            'shipping_pincode' => $order->zipcode,
            'shipping_country' => $order->country ?? 'India',
            'shipping_state' => $order->state,
            'shipping_email' => $order->email,
            'shipping_phone' => $order->phone,
            'order_items' => $items,
            'payment_method' => $paymentMethod,
            'sub_total' => $order->items->where('seller_id', $seller->id)->sum(function($item) {
                return $item->quantity * $item->price;
            }),
            'length' => 10,
            'breadth' => 10,
            'height' => 10,
            'weight' => 0.5,
        ];

        $response = Http::withToken($token)->post("{$this->baseUrl}/orders/create/adhoc", $data);

        if ($response->successful()) {
            $responseData = $response->json();
            
            if (isset($responseData['order_id'])) {
                return $responseData;
            }
            
            if (isset($responseData['data']) && isset($responseData['data']['order_id'])) {
                return $responseData['data'];
            }

            throw new \Exception('Unexpected Shiprocket Response: ' . $response->body());
        }

        Log::error('Shiprocket Seller Order Failed: ' . $response->body());
        $errorMessage = $response->json()['message'] ?? 'Unknown error';
        
        if (isset($response->json()['errors'])) {
             $errorMessage .= ' - ' . json_encode($response->json()['errors']);
        }

        throw new \Exception('Shiprocket order creation failed: ' . $errorMessage);
    }
}
