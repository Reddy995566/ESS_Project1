@extends('website.layouts.master')

@section('title', 'My Account')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#FAF5ED] to-[#E5E0D8] py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            
            <!-- Sidebar -->
            @include('website.user.partials.sidebar')

            <!-- Main Content -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <h2 class="font-serif-elegant text-3xl font-bold text-[#3D0C1F] mb-6">Welcome Back!</h2>
                    
                    <!-- Stats Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div class="bg-gradient-to-br from-[#3D0C1F] to-[#4A0F23] rounded-xl p-6 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm opacity-80">Total Orders</p>
                                    <h3 class="text-3xl font-bold mt-1">0</h3>
                                </div>
                                <svg class="w-12 h-12 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                            </div>
                        </div>
                        
                        <div class="bg-gradient-to-br from-[#B91C1C] to-[#991B1B] rounded-xl p-6 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm opacity-80">Cart Items</p>
                                    <h3 class="text-3xl font-bold mt-1">0</h3>
                                </div>
                                <svg class="w-12 h-12 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Recent Orders</h3>
                        <div class="text-center py-12 bg-gray-50 rounded-lg">
                            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                            <p class="text-gray-500 text-lg">No orders yet</p>
                            <a href="{{ route('shop') }}" class="inline-block mt-4 px-6 py-2 bg-[#3D0C1F] text-white rounded-lg hover:bg-[#2a0815] transition">
                                Start Shopping
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.dashboard-nav-link {
    color: #6B7280;
    font-weight: 500;
}
.dashboard-nav-link:hover {
    background-color: #F3F4F6;
    color: #3D0C1F;
}
.dashboard-nav-link.active {
    background-color: #3D0C1F;
    color: white;
}
</style>
@endsection
