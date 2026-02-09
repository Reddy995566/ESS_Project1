@extends('website.layouts.master')

@section('title', 'Return #' . $return->return_number)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#FAF5ED] to-[#E5E0D8] py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <a href="{{ route('user.returns.index') }}" class="flex items-center text-[#4b0f27] hover:underline font-medium">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Back to Returns
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <!-- Header -->
            <div class="px-6 py-6 border-b border-gray-100 bg-gray-50">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-[#3D0C1F] font-serif-elegant">Return {{ $return->return_number }}</h1>
                        <p class="text-sm text-gray-500 mt-1">
                            Requested on {{ $return->requested_at->format('F j, Y \a\t g:i A') }}
                        </p>
                    </div>
                    <span class="px-4 py-1.5 {{ $return->status_badge }} font-medium rounded-full capitalize">
                        {{ $return->status }}
                    </span>
                </div>
            </div>

            <div class="p-6 md:p-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Return Details -->
                    <div class="space-y-6">
                        <h2 class="text-lg font-semibold text-gray-900 border-b border-gray-100 pb-3">Return Details</h2>
                        
                        <!-- Product Info -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h3 class="font-medium text-gray-900 mb-3">Product</h3>
                            <div class="flex gap-4">
                                <img src="{{ $return->orderItem->product->image_url ?? 'https://via.placeholder.com/100' }}" 
                                     alt="{{ $return->orderItem->product_name }}" 
                                     class="w-16 h-16 object-cover rounded-lg bg-gray-100">
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-900">{{ $return->orderItem->product_name }}</h4>
                                    @if($return->orderItem->variant_name)
                                        <p class="text-sm text-gray-500">{{ $return->orderItem->variant_name }}</p>
                                    @endif
                                    <p class="text-sm text-gray-600 mt-1">
                                        Return Qty: {{ $return->quantity }} | Amount: Rs. {{ number_format($return->amount) }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Return Reason -->
                        <div>
                            <h3 class="font-medium text-gray-900 mb-2">Return Reason</h3>
                            <p class="text-gray-700">{{ $return->reason_label }}</p>
                            @if($return->reason_details)
                                <p class="text-sm text-gray-600 mt-2">{{ $return->reason_details }}</p>
                            @endif
                        </div>

                        <!-- Images -->
                        @if($return->images && count($return->images) > 0)
                        <div>
                            <h3 class="font-medium text-gray-900 mb-2">Uploaded Images</h3>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                                @foreach($return->images as $image)
                                    <img src="{{ asset($image) }}" alt="Return Image" 
                                         class="w-full h-20 object-cover rounded-lg border border-gray-200 cursor-pointer"
                                         onclick="openImageModal('{{ asset($image) }}')">
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Status Timeline -->
                        <div>
                            <h3 class="font-medium text-gray-900 mb-4">Status Timeline</h3>
                            <div class="space-y-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Return Requested</p>
                                        <p class="text-xs text-gray-500">{{ $return->requested_at->format('M j, Y g:i A') }}</p>
                                    </div>
                                </div>
                                
                                @if($return->approved_at)
                                <div class="flex items-center gap-3">
                                    <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Return Approved</p>
                                        <p class="text-xs text-gray-500">{{ $return->approved_at->format('M j, Y g:i A') }}</p>
                                    </div>
                                </div>
                                @endif

                                @if($return->rejected_at)
                                <div class="flex items-center gap-3">
                                    <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Return Rejected</p>
                                        <p class="text-xs text-gray-500">{{ $return->rejected_at->format('M j, Y g:i A') }}</p>
                                        @if($return->rejection_reason)
                                            <p class="text-xs text-red-600 mt-1">{{ $return->rejection_reason }}</p>
                                        @endif
                                    </div>
                                </div>
                                @endif

                                @if($return->picked_up_at)
                                <div class="flex items-center gap-3">
                                    <div class="w-3 h-3 bg-indigo-500 rounded-full"></div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Product Picked Up</p>
                                        <p class="text-xs text-gray-500">{{ $return->picked_up_at->format('M j, Y g:i A') }}</p>
                                    </div>
                                </div>
                                @endif

                                @if($return->refunded_at)
                                <div class="flex items-center gap-3">
                                    <div class="w-3 h-3 bg-green-600 rounded-full"></div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Refund Processed</p>
                                        <p class="text-xs text-gray-500">{{ $return->refunded_at->format('M j, Y g:i A') }}</p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Order & Refund Info -->
                    <div class="space-y-6">
                        <h2 class="text-lg font-semibold text-gray-900 border-b border-gray-100 pb-3">Order Information</h2>
                        
                        <!-- Order Details -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h3 class="font-medium text-gray-900 mb-3">Original Order</h3>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Order Number:</span>
                                    <a href="{{ route('user.orders.show', $return->order->id) }}" 
                                       class="text-[#3D0C1F] hover:underline font-medium">
                                        {{ $return->order->order_number }}
                                    </a>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Order Date:</span>
                                    <span class="text-gray-900">{{ $return->order->created_at->format('M j, Y') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Order Status:</span>
                                    <span class="text-gray-900 capitalize">{{ $return->order->status }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Refund Information -->
                        <div class="bg-blue-50 rounded-lg p-4">
                            <h3 class="font-medium text-blue-900 mb-3">Refund Information</h3>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-blue-700">Return Amount:</span>
                                    <span class="text-blue-900 font-medium">Rs. {{ number_format($return->amount) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-blue-700">Refund Method:</span>
                                    <span class="text-blue-900 capitalize">{{ str_replace('_', ' ', $return->refund_method) }}</span>
                                </div>
                                @if($return->refund_amount)
                                <div class="flex justify-between">
                                    <span class="text-blue-700">Refund Amount:</span>
                                    <span class="text-blue-900 font-medium">Rs. {{ number_format($return->refund_amount) }}</span>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Pickup Address -->
                        @if($return->pickup_address)
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h3 class="font-medium text-gray-900 mb-3">Pickup Address</h3>
                            <p class="text-sm text-gray-700">{{ $return->pickup_address }}</p>
                        </div>
                        @endif

                        <!-- Tracking -->
                        @if($return->tracking_number)
                        <div class="bg-indigo-50 rounded-lg p-4">
                            <h3 class="font-medium text-indigo-900 mb-3">Tracking Information</h3>
                            <p class="text-sm text-indigo-700">Tracking Number: {{ $return->tracking_number }}</p>
                        </div>
                        @endif

                        <!-- Actions -->
                        @if($return->status === 'pending')
                        <div class="pt-4">
                            <form action="{{ route('user.returns.cancel', $return->id) }}" method="POST" 
                                  onsubmit="return confirm('Are you sure you want to cancel this return request?')">
                                @csrf
                                @method('PATCH')
                                <button type="submit" 
                                        class="w-full px-4 py-2 border border-red-300 text-red-700 rounded-lg hover:bg-red-50 transition-all">
                                    Cancel Return Request
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 hidden z-50 flex items-center justify-center p-4">
    <div class="relative max-w-4xl max-h-full">
        <img id="modalImage" src="" alt="Return Image" class="max-w-full max-h-full object-contain">
        <button onclick="closeImageModal()" 
                class="absolute top-4 right-4 text-white bg-black bg-opacity-50 rounded-full p-2 hover:bg-opacity-75">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
</div>

<script>
function openImageModal(imageSrc) {
    document.getElementById('modalImage').src = imageSrc;
    document.getElementById('imageModal').classList.remove('hidden');
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
}

// Close modal on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImageModal();
    }
});
</script>
@endsection