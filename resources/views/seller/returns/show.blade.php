@extends('seller.layouts.app')

@section('title', 'Return Details - ' . $return->return_number)

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 shadow-lg">
        <div class="px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-4 mb-2">
                        <a href="{{ route('seller.returns.index') }}" class="text-white hover:text-indigo-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </a>
                        <h1 class="text-3xl font-bold text-white">Return {{ $return->return_number }}</h1>
                    </div>
                    <p class="text-indigo-100">Requested on {{ $return->requested_at->format('F j, Y \a\t g:i A') }}</p>
                </div>
                <span class="px-4 py-2 {{ $return->status_badge }} font-medium rounded-full capitalize text-sm">
                    {{ $return->status }}
                </span>
            </div>
        </div>
    </div>

    <div class="px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Return Details -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Return Information</h2>
                    
                    <!-- Product Info -->
                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <h3 class="font-medium text-gray-900 mb-3">Product Details</h3>
                        <div class="flex gap-4">
                            <img src="{{ $return->orderItem->product->image_url ?? 'https://via.placeholder.com/100' }}" 
                                 alt="{{ $return->orderItem->product_name }}" 
                                 class="w-20 h-20 object-cover rounded-lg border border-gray-200">
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900">{{ $return->orderItem->product_name }}</h4>
                                @if($return->orderItem->variant_name)
                                    <p class="text-sm text-gray-500">{{ $return->orderItem->variant_name }}</p>
                                @endif
                                <div class="mt-2 text-sm text-gray-600">
                                    <p>Return Quantity: {{ $return->quantity }}</p>
                                    <p>Return Amount: Rs. {{ number_format($return->amount) }}</p>
                                    <p>Original Price: Rs. {{ number_format($return->orderItem->price) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Return Reason -->
                    <div class="mb-6">
                        <h3 class="font-medium text-gray-900 mb-2">Return Reason</h3>
                        <p class="text-gray-700 bg-gray-50 p-3 rounded-lg">{{ $return->reason_label }}</p>
                        @if($return->reason_details)
                            <div class="mt-3">
                                <h4 class="text-sm font-medium text-gray-700 mb-1">Additional Details:</h4>
                                <p class="text-sm text-gray-600 bg-gray-50 p-3 rounded-lg">{{ $return->reason_details }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- Images -->
                    @if($return->images && count($return->images) > 0)
                    <div class="mb-6">
                        <h3 class="font-medium text-gray-900 mb-3">Customer Images</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            @foreach($return->images as $image)
                                <img src="{{ asset($image) }}" alt="Return Image" 
                                     class="w-full h-24 object-cover rounded-lg border border-gray-200 cursor-pointer hover:opacity-75 transition-opacity"
                                     onclick="openImageModal('{{ asset($image) }}')">
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Seller Notes -->
                    @if($return->seller_notes)
                    <div class="mb-6">
                        <h3 class="font-medium text-gray-900 mb-2">Your Notes</h3>
                        <p class="text-gray-700 bg-indigo-50 p-3 rounded-lg border border-indigo-200">{{ $return->seller_notes }}</p>
                    </div>
                    @endif

                    <!-- Admin Notes -->
                    @if($return->admin_notes)
                    <div class="mb-6">
                        <h3 class="font-medium text-gray-900 mb-2">Admin Notes</h3>
                        <p class="text-gray-700 bg-blue-50 p-3 rounded-lg border border-blue-200">{{ $return->admin_notes }}</p>
                    </div>
                    @endif

                    <!-- Rejection Reason -->
                    @if($return->rejection_reason)
                    <div class="mb-6">
                        <h3 class="font-medium text-gray-900 mb-2">Rejection Reason</h3>
                        <p class="text-red-700 bg-red-50 p-3 rounded-lg border border-red-200">{{ $return->rejection_reason }}</p>
                    </div>
                    @endif
                </div>

                <!-- Status Timeline -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Status Timeline</h2>
                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Return Requested</p>
                                <p class="text-xs text-gray-500">{{ $return->requested_at->format('M j, Y g:i A') }}</p>
                            </div>
                        </div>
                        
                        @if($return->processed_by_seller)
                        <div class="flex items-center gap-3">
                            <div class="w-3 h-3 bg-indigo-500 rounded-full"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Acknowledged by Seller</p>
                                <p class="text-xs text-gray-500">You acknowledged this return</p>
                            </div>
                        </div>
                        @endif

                        @if($return->approved_at)
                        <div class="flex items-center gap-3">
                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Return Approved</p>
                                <p class="text-xs text-gray-500">{{ $return->approved_at->format('M j, Y g:i A') }}</p>
                                @if($return->processedByAdmin)
                                    <p class="text-xs text-gray-500">by {{ $return->processedByAdmin->name }}</p>
                                @endif
                            </div>
                        </div>
                        @endif

                        @if($return->rejected_at)
                        <div class="flex items-center gap-3">
                            <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Return Rejected</p>
                                <p class="text-xs text-gray-500">{{ $return->rejected_at->format('M j, Y g:i A') }}</p>
                                @if($return->processedByAdmin)
                                    <p class="text-xs text-gray-500">by {{ $return->processedByAdmin->name }}</p>
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
                                @if($return->tracking_number)
                                    <p class="text-xs text-gray-500">Tracking: {{ $return->tracking_number }}</p>
                                @endif
                            </div>
                        </div>
                        @endif

                        @if($return->refunded_at)
                        <div class="flex items-center gap-3">
                            <div class="w-3 h-3 bg-green-600 rounded-full"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Refund Processed</p>
                                <p class="text-xs text-gray-500">{{ $return->refunded_at->format('M j, Y g:i A') }}</p>
                                @if($return->refund_amount)
                                    <p class="text-xs text-gray-500">Amount: Rs. {{ number_format($return->refund_amount) }}</p>
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Customer Info -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Customer Information</h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Name</p>
                            <p class="text-gray-900">{{ $return->user->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Email</p>
                            <p class="text-gray-900">{{ $return->user->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Phone</p>
                            <p class="text-gray-900">{{ $return->user->phone ?? 'Not provided' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Order Info -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Information</h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Order Number</p>
                            <p class="text-gray-900 font-medium">{{ $return->order->order_number }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Order Date</p>
                            <p class="text-gray-900">{{ $return->order->created_at->format('M j, Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Order Status</p>
                            <p class="text-gray-900 capitalize">{{ $return->order->status }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Order Total</p>
                            <p class="text-gray-900">Rs. {{ number_format($return->order->total) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Return Info -->
                <div class="bg-indigo-50 rounded-xl border border-indigo-200 p-6">
                    <h3 class="text-lg font-semibold text-indigo-900 mb-4">Return Information</h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm font-medium text-indigo-700">Return Amount</p>
                            <p class="text-indigo-900 font-semibold">Rs. {{ number_format($return->amount) }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-indigo-700">Refund Method</p>
                            <p class="text-indigo-900 capitalize">{{ str_replace('_', ' ', $return->refund_method) }}</p>
                        </div>
                        @if($return->refund_amount)
                        <div>
                            <p class="text-sm font-medium text-indigo-700">Refunded Amount</p>
                            <p class="text-indigo-900 font-semibold">Rs. {{ number_format($return->refund_amount) }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions</h3>
                    <div class="space-y-3">
                        @if($return->status === 'pending' && !$return->processed_by_seller)
                            <button onclick="acknowledgeReturn()" 
                                    class="w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                                Acknowledge Return
                            </button>
                        @endif
                        
                        <button onclick="addNotes()" 
                                class="w-full px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                            {{ $return->seller_notes ? 'Update Notes' : 'Add Notes' }}
                        </button>
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
@endsection

@section('scripts')
<script>
function acknowledgeReturn() {
    const notes = prompt('Add seller notes (optional):');
    
    fetch(`/seller/returns/{{ $return->id }}/acknowledge`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            seller_notes: notes
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Failed to acknowledge return');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while acknowledging the return');
    });
}

function addNotes() {
    const currentNotes = '{{ $return->seller_notes ?? '' }}';
    const notes = prompt('Enter seller notes:', currentNotes);
    
    if (notes !== null && notes.trim()) {
        fetch(`/seller/returns/{{ $return->id }}/notes`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                seller_notes: notes.trim()
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Failed to add notes');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while adding notes');
        });
    }
}

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