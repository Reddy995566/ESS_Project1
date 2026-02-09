@extends('website.layouts.master')

@section('title', 'Request Return')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#FAF5ED] to-[#E5E0D8] py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <a href="{{ route('user.orders.show', $order->id) }}" class="flex items-center text-[#4b0f27] hover:underline font-medium">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Back to Order Details
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <!-- Header -->
            <div class="px-6 py-6 border-b border-gray-100 bg-gray-50">
                <h1 class="text-2xl font-bold text-[#3D0C1F] font-serif-elegant">Request Return</h1>
                <p class="text-sm text-gray-500 mt-1">Order {{ $order->order_number }}</p>
            </div>

            <div class="p-6 md:p-8">
                <!-- Product Info -->
                <div class="mb-8 p-4 bg-gray-50 rounded-lg">
                    <h3 class="font-semibold text-gray-900 mb-3">Product Details</h3>
                    <div class="flex gap-4">
                        <img src="{{ $orderItem->product->image_url ?? 'https://via.placeholder.com/100' }}" 
                             alt="{{ $orderItem->product_name }}" 
                             class="w-20 h-20 object-cover rounded-lg bg-gray-100">
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-900">{{ $orderItem->product_name }}</h4>
                            @if($orderItem->variant_name)
                                <p class="text-sm text-gray-500">{{ $orderItem->variant_name }}</p>
                            @endif
                            <p class="text-sm text-gray-600 mt-1">
                                Ordered Quantity: {{ $orderItem->quantity }} | Price: Rs. {{ number_format($orderItem->price) }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Return Form -->
                <form action="{{ route('user.returns.store', [$order->id, $orderItem->id]) }}" method="POST" enctype="multipart/form-data" id="returnForm">
                    @csrf
                    
                    <div class="space-y-6">
                        <!-- Return Quantity -->
                        <div>
                            <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">Return Quantity</label>
                            <select name="quantity" id="quantity" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3D0C1F] focus:border-transparent" required>
                                @for($i = 1; $i <= $orderItem->quantity; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>

                        <!-- Return Reason -->
                        <div>
                            <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">Reason for Return</label>
                            <select name="reason" id="reason" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3D0C1F] focus:border-transparent" required>
                                <option value="">Select a reason</option>
                                <option value="defective">Defective Product</option>
                                <option value="wrong_item">Wrong Item Received</option>
                                <option value="size_issue">Size Issue</option>
                                <option value="quality_issue">Quality Issue</option>
                                <option value="not_as_described">Not as Described</option>
                                <option value="damaged_shipping">Damaged in Shipping</option>
                                <option value="changed_mind">Changed Mind</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        <!-- Additional Details -->
                        <div>
                            <label for="reason_details" class="block text-sm font-medium text-gray-700 mb-2">Additional Details (Optional)</label>
                            <textarea name="reason_details" id="reason_details" rows="4" 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3D0C1F] focus:border-transparent"
                                      placeholder="Please provide additional details about your return request..."></textarea>
                        </div>

                        <!-- Images -->
                        <div>
                            <label for="images" class="block text-sm font-medium text-gray-700 mb-2">Upload Images (Optional)</label>
                            <input type="file" name="images[]" id="images" multiple accept="image/*" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3D0C1F] focus:border-transparent">
                            <p class="text-xs text-gray-500 mt-1">You can upload multiple images (JPEG, PNG only, max 2MB each)</p>
                        </div>

                        <!-- Return Policy Notice -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <h4 class="font-medium text-blue-900 mb-2">Return Policy</h4>
                            <ul class="text-sm text-blue-800 space-y-1">
                                <li>• Returns are accepted within 3 days of delivery</li>
                                <li>• Products must be in original condition</li>
                                <li>• Refund will be processed to original payment method</li>
                                <li>• Pickup will be arranged once return is approved</li>
                            </ul>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex gap-4 pt-4">
                            <button type="submit" 
                                    class="flex-1 bg-[#3D0C1F] text-white py-3 px-6 rounded-lg hover:bg-[#2a0815] transition-all font-medium">
                                Submit Return Request
                            </button>
                            <a href="{{ route('user.orders.show', $order->id) }}" 
                               class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all font-medium">
                                Cancel
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('returnForm').addEventListener('submit', function(e) {
    const submitBtn = e.target.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.textContent = 'Submitting...';
});
</script>
@endsection