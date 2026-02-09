@extends('website.layouts.master')

@section('title', 'My Returns')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#FAF5ED] to-[#E5E0D8] py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            
            <!-- Sidebar -->
            @include('website.user.partials.sidebar')

            <!-- Main Content -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <h2 class="font-serif-elegant text-3xl font-bold text-[#3D0C1F] mb-6">My Returns</h2>
                    
                    <!-- Empty State -->
                    @if($returns->isEmpty())
                    <div class="text-center py-16">
                        <svg class="w-24 h-24 mx-auto text-gray-300 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z"></path>
                        </svg>
                        <h3 class="text-2xl font-semibold text-gray-700 mb-2">No Returns Yet</h3>
                        <p class="text-gray-500 mb-6">You haven't requested any returns.</p>
                        <a href="{{ route('user.orders') }}" class="inline-block px-8 py-3 bg-[#3D0C1F] text-white font-medium rounded-lg hover:bg-[#2a0815] transition-all shadow-lg">
                            View Orders
                        </a>
                    </div>
                    @else
                    
                    <!-- Returns List -->
                    <div class="space-y-6">
                        @foreach($returns as $return)
                        <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                            <div class="flex flex-col sm:flex-row justify-between items-start mb-4 gap-4">
                                <div>
                                    <h3 class="font-semibold text-lg text-gray-900">{{ $return->return_number }}</h3>
                                    <p class="text-sm text-gray-500">
                                        Requested on {{ $return->requested_at->format('F j, Y') }}
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        Order: {{ $return->order->order_number }}
                                    </p>
                                </div>
                                <span class="px-4 py-1 {{ $return->status_badge }} text-sm font-medium rounded-full capitalize">
                                    {{ $return->status }}
                                </span>
                            </div>
                            
                            <!-- Product Info -->
                            <div class="flex gap-4 mb-4">
                                <img src="{{ $return->orderItem->product->image_url ?? 'https://via.placeholder.com/100' }}" 
                                     alt="{{ $return->orderItem->product_name }}" 
                                     class="w-16 h-16 object-cover rounded-lg bg-gray-50">
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-900">{{ $return->orderItem->product_name }}</h4>
                                    @if($return->orderItem->variant_name)
                                        <p class="text-sm text-gray-500">{{ $return->orderItem->variant_name }}</p>
                                    @endif
                                    <p class="text-sm text-gray-600 mt-1">
                                        Qty: {{ $return->quantity }} | Amount: Rs. {{ number_format($return->amount) }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        Reason: {{ $return->reason_label }}
                                    </p>
                                </div>
                            </div>
                            
                            <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-gray-200">
                                <a href="{{ route('user.returns.show', $return->id) }}" 
                                   class="text-center px-4 py-2 border border-[#3D0C1F] text-[#3D0C1F] rounded-lg hover:bg-[#3D0C1F] hover:text-white transition-all">
                                    View Details
                                </a>
                                @if($return->status === 'pending')
                                <form action="{{ route('user.returns.cancel', $return->id) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Are you sure you want to cancel this return request?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-all">
                                        Cancel Request
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($returns->hasPages())
                    <div class="mt-8">
                        {{ $returns->links() }}
                    </div>
                    @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection