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
            'pickup_location' => 'Primary', // Default pickup location name configured in Shiprocket
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


    public function getAvailableCouriers($order)
    {
        if (!$this->token) {
            $this->login();
        }

        if (!$order->shiprocket_order_id) {
             throw new \Exception('Order must be pushed to Shiprocket first.');
        }

        // 1. Get pickup location (assuming Primary for now, ideally strictly from settings or previous context)
        $pickupPostcode = '110001'; // Default or fetch from settings if implemented

        $deliveryPostcode = $order->billing_zipcode ?? $order->zipcode;
        $weight = 0.5; // Default or calculate from order items
        $cod = $order->payment_method === 'cod' ? 1 : 0;

        $params = [
            'pickup_postcode' => $pickupPostcode,
            'delivery_postcode' => $deliveryPostcode,
            'weight' => $weight,
            'cod' => $cod,
            'order_id' => $order->shiprocket_order_id,
        ];

        $response = Http::withToken($this->token)->get("{$this->baseUrl}/courier/serviceability", $params);

        if ($response->successful()) {
            $data = $response->json();
            if (isset($data['data']['available_courier_companies']) && !empty($data['data']['available_courier_companies'])) {
                return $data['data']['available_courier_companies'];
            }
             // Sometimes recommendations are in 'courier_company_id' key in different endpoint, but for serviceability:
            return []; // Return empty if no couriers found or structure differs
        }

        Log::error('Shiprocket Fetch Couriers Failed: ' . $response->body());
        throw new \Exception('Failed to fetch available couriers: ' . ($response->json()['message'] ?? 'Unknown error'));
    }

    public function generateAwb($shipmentId, $courierId)
    {
         if (!$this->token) {
            $this->login();
        }

        $data = [
            'shipment_id' => $shipmentId,
            'courier_id' => $courierId,
        ];

        $response = Http::withToken($this->token)->post("{$this->baseUrl}/courier/assign/awb", $data);

        if ($response->successful()) {
            return $response->json();
        }

        Log::error('Shiprocket Generate AWB Failed: ' . $response->body());
        throw new \Exception('Failed to generate AWB: ' . ($response->json()['message'] ?? 'Unknown error'));
    }
}
