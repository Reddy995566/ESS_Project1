<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Setting;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function generate(Order $order, $skipUserCheck = false)
    {
        // Ensure user can only access their own invoices (skip for seller/admin access)
        if (!$skipUserCheck && auth()->check() && $order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to invoice');
        }

        // Get site settings with fallback values
        try {
            $siteSettings = [
                'name' => Setting::get('site_name', 'Switch2kart'),
                'logo' => Setting::get('site_logo', ''),
                'email' => Setting::get('site_email', 'support@switch2kart.com'),
                'phone' => Setting::get('site_phone', '9951478735'),
                'address' => Setting::get('site_address', 'India'),
                'gst' => Setting::get('gst_number', ''),
                'website' => Setting::get('site_url', ''),
            ];
        } catch (\Exception $e) {
            // Fallback if settings fail to load
            $siteSettings = [
                'name' => 'Switch2kart',
                'logo' => '',
                'email' => 'support@switch2kart.com',
                'phone' => '9951478735',
                'address' => 'India',
                'gst' => '',
                'website' => '',
            ];
        }

        return view('invoice.template', compact('order', 'siteSettings'));
    }

    public function adminGenerate($id)
    {
        $order = Order::with(['items.product', 'items.variant', 'user'])->findOrFail($id);
        return $this->generate($order, true); // Skip user check for admin
    }

    public function sellerGenerate($id)
    {
        $sellerId = auth('seller')->id();
        $order = Order::with(['items.product', 'items.variant', 'user'])
            ->whereHas('items', function($query) use ($sellerId) {
                $query->where('seller_id', $sellerId);
            })
            ->findOrFail($id);
        
        // Additional security check - ensure at least one item belongs to this seller
        $hasSellerItems = $order->items->where('seller_id', $sellerId)->count() > 0;
        if (!$hasSellerItems) {
            abort(403, 'Unauthorized access to invoice');
        }
        
        return $this->generate($order, true); // Skip user check for seller
    }
}