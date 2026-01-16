@extends('website.layouts.master')

@section('title', 'My Wishlist')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#FAF5ED] to-[#E5E0D8] py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            
            <!-- Sidebar -->
            @include('website.user.partials.sidebar')

            <!-- Main Content -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="font-serif-elegant text-3xl font-bold text-[#3D0C1F]">My Wishlist</h2>
                        <span class="text-sm text-gray-500">0 items</span>
                    </div>
                    
                    <!-- Empty State -->
                    <div class="text-center py-16">
                        <svg class="w-24 h-24 mx-auto text-gray-300 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                        <h3 class="text-2xl font-semibold text-gray-700 mb-2">Your Wishlist is Empty</h3>
                        <p class="text-gray-500 mb-6">Save your favorite items here for later</p>
                        <a href="{{ route('shop') }}" class="inline-block px-8 py-3 bg-[#3D0C1F] text-white font-medium rounded-lg hover:bg-[#2a0815] transition-all shadow-lg">
                            Explore Products
                        </a>
                    </div>

                    <!-- Wishlist Grid (When items exist) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 hidden" id="wishlistGrid">
                        <!-- Wishlist Item Example -->
                        <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition-shadow group">
                            <div class="relative">
                                <img src="https://via.placeholder.com/300" alt="Product" class="w-full h-64 object-cover" loading="lazy">
                                <button class="absolute top-3 right-3 p-2 bg-white rounded-full shadow-md hover:bg-red-50 transition-all">
                                    <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                </button>
                            </div>
                            <div class="p-4">
                                <h3 class="font-semibold text-gray-900 mb-1">Product Name</h3>
                                <p class="text-sm text-gray-500 mb-2">Category Name</p>
                                <div class="flex items-center justify-between">
                                    <span class="text-lg font-bold text-[#3D0C1F]">₹1,999</span>
                                    <button class="px-4 py-2 bg-[#3D0C1F] text-white text-sm rounded-lg hover:bg-[#2a0815] transition-all">
                                        Add to Cart
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
