@extends('website.layouts.master')

@section('title', 'Order #' . $order->order_number)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#FAF5ED] to-[#E5E0D8] py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <a href="{{ route('user.orders') }}" class="flex items-center text-[#4b0f27] hover:underline font-medium">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Back to Orders
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <!-- Header -->
            <div class="px-6 py-6 border-b border-gray-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-gray-50">
                <div>
                    <h1 class="text-2xl font-bold text-[#3D0C1F] font-serif-elegant">Order {{ $order->order_number }}</h1>
                    <p class="text-sm text-gray-500 mt-1">Placed on {{ $order->created_at->format('F j, Y \a\t g:i A') }}</p>
                </div>
                @php
                    $statusColors = [
                        'pending' => 'bg-yellow-100 text-yellow-800',
                        'processing' => 'bg-blue-100 text-blue-800',
                        'shipped' => 'bg-indigo-100 text-indigo-800',
                        'delivered' => 'bg-green-100 text-green-800',
                        'cancelled' => 'bg-red-100 text-red-800',
                    ];
                    $statusClass = $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800';
                    $canCancel = in_array($order->status, ['pending', 'processing']);
                @endphp
                <div class="flex items-center gap-3">
                    <span class="px-4 py-1.5 {{ $statusClass }} font-medium rounded-full capitalize">{{ $order->status }}</span>
                    @if($canCancel)
                        <button onclick="showCancelModal()" class="px-4 py-2 text-sm border border-red-500 text-red-600 rounded hover:bg-red-50 bg-white transition-all">
                            Cancel Order
                        </button>
                    @endif
                </div>
            </div>

            <div class="p-6 md:p-8">
                <div class="flex flex-col lg:flex-row gap-8">
                    <!-- Order Items (Left) -->
                    <div class="flex-1 space-y-6">
                        <h2 class="text-lg font-semibold text-gray-900 border-b border-gray-100 pb-3">Items ({{ $order->items->count() }})</h2>
                        
                        <div class="space-y-6">
                            @foreach($order->items as $item)
                            <div class="flex gap-4">
                                <div class="w-20 h-24 flex-shrink-0 border border-gray-200 rounded-lg overflow-hidden bg-gray-50">
                                    <img src="{{ $item->image ?? $item->product->image_url ?? 'https://via.placeholder.com/100' }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover" loading="lazy">
                                </div>
                                <div class="flex-1">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="font-medium text-gray-900 text-lg">{{ $item->product_name }}</h3>
                                            @if($item->variant_name)
                                                <p class="text-sm text-gray-500 mt-0.5">{{ $item->variant_name }}</p>
                                            @endif
                                        </div>
                                        <p class="font-semibold text-[#3D0C1F]">Rs. {{ number_format($item->total) }}</p>
                                    </div>
                                    <div class="flex items-center justify-between mt-4">
                                        <div class="text-sm text-gray-500">
                                            Qty: {{ $item->quantity }} &times; Rs. {{ number_format($item->price) }}
                                        </div>
                                        
                                        @php
                                            $canReturn = false;
                                            $returnMessage = '';
                                            $existingReturn = $item->returns()->first();
                                            
                                            if ($existingReturn) {
                                                $returnMessage = 'Return ' . $existingReturn->status;
                                            } elseif ($order->status !== 'delivered') {
                                                $returnMessage = 'Return available after delivery';
                                            } elseif (!$order->delivered_at) {
                                                $returnMessage = 'Delivery date not confirmed';
                                            } elseif ($order->delivered_at->lt(now()->subDays(3))) {
                                                $returnMessage = 'Return period expired';
                                            } else {
                                                $canReturn = true;
                                            }
                                        @endphp
                                        
                                        @if($canReturn)
                                            <a href="{{ route('user.returns.create', [$order->id, $item->id]) }}" 
                                               class="text-sm px-3 py-1 bg-red-100 text-red-700 rounded hover:bg-red-200 transition-all">
                                                Request Return
                                            </a>
                                        @elseif($existingReturn)
                                            <a href="{{ route('user.returns.show', $existingReturn->id) }}" 
                                               class="text-sm px-3 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition-all">
                                                {{ $returnMessage }}
                                            </a>
                                        @else
                                            <span class="text-sm px-3 py-1 bg-gray-100 text-gray-500 rounded">
                                                {{ $returnMessage }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Order Summary & Info (Right) -->
                    <div class="lg:w-1/3 space-y-8">
                        
                        <!-- Shipping Address -->
                        <div class="bg-gray-50 rounded-xl p-6">
                            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-4">Shipping Address</h3>
                            <div class="text-sm text-gray-900 leading-relaxed">
                                <p class="font-semibold text-base mb-1">{{ $order->first_name }}{{ $order->last_name ? ' ' . $order->last_name : '' }}</p>
                                <p>{{ $order->address }}</p>
                                @if($order->address_line_2) <p>{{ $order->address_line_2 }}</p> @endif
                                <p>{{ $order->city }}, {{ $order->state }} - {{ $order->zipcode }}</p>
                                <p>{{ $order->country }}</p>
                                <p class="mt-2 text-gray-600">Phone: {{ $order->phone }}</p>
                            </div>
                        </div>

                        <!-- Payment Info -->
                        <div class="bg-gray-50 rounded-xl p-6">
                            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-4">Payment Information</h3>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-gray-600">Method</span>
                                <span class="text-sm font-medium text-gray-900 capitalize">{{ $order->payment_method == 'cod' ? 'Cash on Delivery' : 'Online Payment' }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Status</span>
                                <span class="text-xs px-2 py-1 {{ $order->payment_status == 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }} rounded uppercase font-medium">{{ $order->payment_status }}</span>
                            </div>
                        </div>

                        <!-- Cost Summary -->
                        <div class="rounded-xl p-6 border border-opacity-10" style="background-color: var(--color-bg-tertiary); border-color: var(--color-primary);">
                            <h3 class="text-sm font-medium text-[#4b0f27] uppercase tracking-wider mb-4">Order Summary</h3>
                            <div class="space-y-2 text-sm text-gray-600">
                                <div class="flex justify-between">
                                    <span>Subtotal</span>
                                    <span>Rs. {{ number_format($order->subtotal) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Shipping</span>
                                    <span>{{ $order->shipping > 0 ? 'Rs. ' . number_format($order->shipping) : 'Free' }}</span>
                                </div>
                                @if($order->discount > 0)
                                <div class="flex justify-between text-green-600">
                                    <span>Discount @if($order->coupon_code)({{ $order->coupon_code }})@endif</span>
                                    <span>- Rs. {{ number_format($order->discount) }}</span>
                                </div>
                                @endif
                                @if($order->tax > 0)
                                <div class="flex justify-between">
                                    <span>Tax</span>
                                    <span>Rs. {{ number_format($order->tax) }}</span>
                                </div>
                                @endif
                            </div>
                            <div class="flex justify-between items-center pt-4 mt-4 border-t border-[#4b0f27] border-opacity-10">
                                <span class="font-bold text-[#3D0C1F] text-lg">Total</span>
                                <span class="font-bold text-[#3D0C1F] text-lg">Rs. {{ number_format($order->total) }}</span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Order Modal -->
<div id="cancelModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeCancelModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Cancel Order</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500 mb-4">Are you sure you want to cancel this order? This action cannot be undone.</p>
                            <form id="cancelForm">
                                @csrf
                                <label class="block text-sm font-medium text-gray-700 mb-2">Reason for cancellation</label>
                                <textarea id="cancelReason" name="reason" rows="4" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3D0C1F] focus:border-[#3D0C1F]" placeholder="Please tell us why you're cancelling this order..."></textarea>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                <button type="button" onclick="cancelOrder()" id="confirmCancelBtn" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Cancel Order
                </button>
                <button type="button" onclick="closeCancelModal()" class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#3D0C1F] sm:mt-0 sm:w-auto sm:text-sm">
                    Keep Order
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function showCancelModal() {
    document.getElementById('cancelModal').classList.remove('hidden');
}

function closeCancelModal() {
    document.getElementById('cancelModal').classList.add('hidden');
    document.getElementById('cancelReason').value = '';
}

function cancelOrder() {
    const reason = document.getElementById('cancelReason').value.trim();
    
    if (!reason) {
        alert('Please provide a reason for cancellation');
        return;
    }
    
    const btn = document.getElementById('confirmCancelBtn');
    btn.disabled = true;
    btn.innerHTML = '<svg class="animate-spin h-5 w-5 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
    
    fetch('{{ route('user.orders.cancel', $order->id) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ reason: reason })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Order cancelled successfully');
            window.location.reload();
        } else {
            alert(data.message || 'Failed to cancel order');
            btn.disabled = false;
            btn.textContent = 'Cancel Order';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
        btn.disabled = false;
        btn.textContent = 'Cancel Order';
    });
}
</script>
@endsection
