@extends('admin.layouts.app')

@section('title', 'Return Details - ' . $return->return_number)

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 shadow-lg">
        <div class="px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-4 mb-2">
                        <a href="{{ route('admin.returns.index') }}" class="text-white hover:text-indigo-200">
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
        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

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
                        <h3 class="font-medium text-gray-900 mb-3">Uploaded Images</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            @foreach($return->images as $image)
                                <img src="{{ asset($image) }}" alt="Return Image" 
                                     class="w-full h-24 object-cover rounded-lg border border-gray-200 cursor-pointer hover:opacity-75 transition-opacity"
                                     onclick="openImageModal('{{ asset($image) }}')">
                            @endforeach
                        </div>
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
                            <a href="{{ route('admin.orders.show', $return->order->id) }}" 
                               class="text-indigo-600 hover:text-indigo-800 font-medium">
                                {{ $return->order->order_number }}
                            </a>
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

                <!-- Refund Info -->
                <div class="bg-blue-50 rounded-xl border border-blue-200 p-6">
                    <h3 class="text-lg font-semibold text-blue-900 mb-4">Refund Information</h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm font-medium text-blue-700">Return Amount</p>
                            <p class="text-blue-900 font-semibold">Rs. {{ number_format($return->amount) }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-blue-700">Refund Method</p>
                            <p class="text-blue-900 capitalize">{{ str_replace('_', ' ', $return->refund_method) }}</p>
                        </div>
                        @if($return->refund_amount)
                        <div>
                            <p class="text-sm font-medium text-blue-700">Refunded Amount</p>
                            <p class="text-blue-900 font-semibold">Rs. {{ number_format($return->refund_amount) }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions</h3>
                    <div class="space-y-3">
                        @if($return->status === 'pending')
                            <form method="POST" action="{{ route('admin.returns.approve', $return->id) }}" class="inline-block w-full">
                                @csrf
                                <button type="submit" 
                                        class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors"
                                        onclick="return confirm('Are you sure you want to approve this return?')">
                                    Approve Return
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.returns.reject', $return->id) }}" class="inline-block w-full">
                                @csrf
                                <input type="hidden" name="rejection_reason" id="rejectionReason">
                                <button type="button" 
                                        class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors"
                                        onclick="handleReject(this)">
                                    Reject Return
                                </button>
                            </form>
                        @elseif($return->status === 'approved')
                            <form method="POST" action="{{ route('admin.returns.pickup', $return->id) }}" class="inline-block w-full">
                                @csrf
                                <button type="submit" 
                                        class="w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors"
                                        onclick="return confirm('Mark this return as picked up?')">
                                    Mark as Picked Up
                                </button>
                            </form>
                        @elseif($return->status === 'picked_up')
                            <form method="POST" action="{{ route('admin.returns.refund', $return->id) }}" class="inline-block w-full">
                                @csrf
                                <input type="hidden" name="refund_amount" id="refundAmount">
                                <button type="button" 
                                        class="w-full px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors"
                                        onclick="handleRefund(this)">
                                    Process Refund
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('admin.returns.modals')

<script>
// Define functions immediately when script loads
window.handleReject = function(button) {
    console.log('handleReject called');
    const reason = prompt('Please enter rejection reason:');
    if (!reason || !reason.trim()) {
        alert('Rejection reason is required');
        return false;
    }
    
    // Set the rejection reason in the hidden input
    document.getElementById('rejectionReason').value = reason.trim();
    
    // Submit the form
    button.closest('form').submit();
};

window.handleRefund = function(button) {
    console.log('handleRefund called');
    const refundAmount = prompt('Enter refund amount:', '{{ $return->amount }}');
    if (!refundAmount || isNaN(refundAmount) || parseFloat(refundAmount) <= 0) {
        alert('Please enter a valid refund amount');
        return false;
    }
    
    // Set the refund amount in the hidden input
    document.getElementById('refundAmount').value = parseFloat(refundAmount);
    
    // Submit the form
    button.closest('form').submit();
};

console.log('Functions defined:', typeof window.handleReject, typeof window.handleRefund);
</script>

@endsection

