@extends('admin.layouts.app')

@section('title', 'Video Reels Management')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50 to-indigo-50 p-6">
        
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <div class="flex items-center justify-center w-14 h-14 bg-gradient-to-br from-pink-500 to-rose-600 rounded-2xl shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-black text-gray-900 tracking-tight">Video Reels</h1>
                        <p class="mt-1 text-sm text-gray-600">Manage video reels with linked products</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <button id="refreshBtn" class="inline-flex items-center justify-center w-11 h-11 bg-white text-gray-700 rounded-xl border-2 border-gray-200 hover:border-blue-400 hover:bg-blue-50 hover:text-blue-700 transition-all duration-200 shadow-sm hover:shadow-md">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                    </button>
                    <button onclick="openAddModal()" class="inline-flex items-center px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm font-bold rounded-xl hover:from-blue-700 hover:to-indigo-700 shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                        </svg>
                        Add Video Reel
                    </button>
                </div>
            </div>
        </div>

        <!-- Flash Messages -->
        @if (session('success'))
            <div class="mb-6 bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 p-4 rounded-xl shadow-md">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <p class="ml-3 text-sm font-semibold text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-blue-600 rounded-2xl shadow-xl p-6 transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center justify-center w-12 h-12 bg-white bg-opacity-20 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <span class="px-3 py-1 bg-white bg-opacity-20 rounded-full text-xs font-bold text-white">Total</span>
                </div>
                <p class="text-4xl font-black text-white mb-1">{{ $totalReels }}</p>
                <p class="text-blue-100 text-sm font-medium">Total Reels</p>
            </div>

            <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl shadow-xl p-6 transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center justify-center w-12 h-12 bg-white bg-opacity-20 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="px-3 py-1 bg-white bg-opacity-20 rounded-full text-xs font-bold text-white">Live</span>
                </div>
                <p class="text-4xl font-black text-white mb-1">{{ $activeReels }}</p>
                <p class="text-green-100 text-sm font-medium">Active Reels</p>
            </div>

            <div class="bg-gradient-to-br from-purple-500 to-indigo-600 rounded-2xl shadow-xl p-6 transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center justify-center w-12 h-12 bg-white bg-opacity-20 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <span class="px-3 py-1 bg-white bg-opacity-20 rounded-full text-xs font-bold text-white">Views</span>
                </div>
                <p class="text-4xl font-black text-white mb-1">{{ number_format($totalViews) }}</p>
                <p class="text-purple-100 text-sm font-medium">Total Views</p>
            </div>
        </div>

        <!-- Main Data Table Card -->
        <div class="bg-white rounded-2xl shadow-2xl border border-gray-200 overflow-hidden">
            
            <!-- Table Toolbar -->
            <div class="bg-gradient-to-r from-gray-50 to-slate-50 px-6 py-5 border-b border-gray-200">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <h2 class="text-xl font-bold text-gray-900 flex items-center">
                        <span class="flex items-center justify-center w-8 h-8 bg-pink-100 text-pink-600 rounded-lg mr-3">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                        </span>
                        Video Reels Data Table
                    </h2>
                    <div class="flex items-center space-x-2">
                        <select id="bulkAction" class="px-3 py-2 bg-white border-2 border-gray-300 rounded-lg text-sm font-medium focus:ring-2 focus:ring-blue-500">
                            <option value="">Select Action</option>
                            <option value="activate">Activate</option>
                            <option value="deactivate">Deactivate</option>
                            <option value="delete">Delete Selected</option>
                        </select>
                        <button id="applyBulkAction" disabled class="px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition-all">Apply</button>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-gray-50 px-6 py-5 border-b border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-700 mb-2 uppercase">Search</label>
                        <input type="text" id="globalSearch" class="block w-full px-4 py-3 border-2 border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Search by title or product...">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2 uppercase">Status</label>
                        <select id="statusFilter" class="block w-full px-4 py-3 bg-white border-2 border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                            <option value="">All Status</option>
                            <option value="1">✓ Active</option>
                            <option value="0">✗ Inactive</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2 uppercase">&nbsp;</label>
                        <button id="resetFilters" class="w-full px-3 py-3 bg-gray-200 text-gray-700 text-sm font-bold rounded-xl hover:bg-gray-300 transition-all">Reset</button>
                    </div>
                </div>
            </div>

            <!-- Data Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-slate-100 to-gray-100">
                        <tr>
                            <th class="px-4 py-4 text-center w-12">
                                <input type="checkbox" id="selectAll" class="w-4 h-4 text-blue-600 border-gray-300 rounded">
                            </th>
                            <th class="px-4 py-4 text-center w-16"><span class="text-xs font-black text-gray-700 uppercase">ID</span></th>
                            <th class="px-4 py-4 text-center w-32"><span class="text-xs font-black text-gray-700 uppercase">Video</span></th>
                            <th class="px-4 py-4 text-left min-w-[200px]"><span class="text-xs font-black text-gray-700 uppercase">Product</span></th>
                            <th class="px-4 py-4 text-center w-28"><span class="text-xs font-black text-gray-700 uppercase">Badge</span></th>
                            <th class="px-4 py-4 text-center w-24"><span class="text-xs font-black text-gray-700 uppercase">Views</span></th>
                            <th class="px-4 py-4 text-center w-20"><span class="text-xs font-black text-gray-700 uppercase">Order</span></th>
                            <th class="px-4 py-4 text-center w-28"><span class="text-xs font-black text-gray-700 uppercase">Status</span></th>
                            <th class="px-4 py-4 text-center w-28"><span class="text-xs font-black text-gray-700 uppercase">Autoplay</span></th>
                            <th class="px-4 py-4 text-center w-28"><span class="text-xs font-black text-gray-700 uppercase">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($videoReels as $reel)
                        <tr class="group hover:bg-blue-50 transition-all duration-200">
                            <td class="px-4 py-4 text-center">
                                <input type="checkbox" class="row-select w-4 h-4 text-blue-600 border-gray-300 rounded" value="{{ $reel->id }}">
                            </td>
                            <td class="px-4 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-gray-100 text-gray-700">#{{ $reel->id }}</span>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <div class="w-20 h-28 mx-auto rounded-lg overflow-hidden bg-gray-200">
                                    <video src="{{ $reel->video_url }}" class="w-full h-full object-cover" muted></video>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center space-x-3">
                                    @if($reel->product && $reel->product->display_images)
                                        <img src="{{ $reel->product->display_images[0] ?? '' }}" class="w-12 h-12 rounded-lg object-cover">
                                    @endif
                                    <div>
                                        <p class="text-sm font-bold text-gray-900">{{ $reel->product->name ?? 'N/A' }}</p>
                                        <p class="text-xs text-gray-500">Rs. {{ number_format($reel->product->price ?? 0) }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4 text-center">
                                @if($reel->badge)
                                    <span class="inline-flex px-2 py-1 rounded text-xs font-bold text-white" style="background-color: {{ $reel->badge_color }}">{{ $reel->badge }}</span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-purple-100 text-purple-700">{{ $reel->formatted_views }}</span>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-indigo-100 text-indigo-700">{{ $reel->sort_order }}</span>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" class="sr-only peer feature-toggle" data-id="{{ $reel->id }}" data-field="is_active" {{ $reel->is_active ? 'checked' : '' }}>
                                    <div class="w-11 h-6 bg-gray-300 peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                                </label>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" class="sr-only peer feature-toggle" data-id="{{ $reel->id }}" data-field="autoplay" {{ $reel->autoplay ? 'checked' : '' }}>
                                    <div class="w-11 h-6 bg-gray-300 peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-500"></div>
                                </label>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center justify-center space-x-2">
                                    <button class="edit-btn w-9 h-9 text-blue-600 bg-blue-50 border-2 border-blue-200 rounded-lg hover:bg-blue-100 transition-all"
                                            data-id="{{ $reel->id }}"
                                            data-product-id="{{ $reel->product_id }}"
                                            data-video-url="{{ $reel->video_url }}"
                                            data-title="{{ $reel->title }}"
                                            data-badge="{{ $reel->badge }}"
                                            data-badge-color="{{ $reel->badge_color }}"
                                            data-views-count="{{ $reel->views_count }}"
                                            data-sort-order="{{ $reel->sort_order }}"
                                            data-is-active="{{ $reel->is_active ? 'true' : 'false' }}"
                                            data-autoplay="{{ $reel->autoplay ? 'true' : 'false' }}">
                                        <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button class="delete-btn w-9 h-9 text-red-600 bg-red-50 border-2 border-red-200 rounded-lg hover:bg-red-100 transition-all"
                                            data-id="{{ $reel->id }}"
                                            data-name="{{ $reel->product->name ?? 'Reel' }}">
                                        <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="px-6 py-20 text-center">
                                <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-6">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">No Video Reels Found</h3>
                                <p class="text-gray-500 mb-6">Start adding video reels to showcase on your homepage.</p>
                                <button onclick="openAddModal()" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm font-bold rounded-xl">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Add First Video Reel
                                </button>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="text-sm text-gray-700">
                        Showing <span class="font-bold text-blue-600">{{ $videoReels->firstItem() ?? 0 }}</span> to <span class="font-bold text-blue-600">{{ $videoReels->lastItem() ?? 0 }}</span> of <span class="font-bold text-blue-600">{{ $videoReels->total() }}</span>
                    </div>
                    @if ($videoReels->hasPages())
                        {{ $videoReels->links() }}
                    @endif
                </div>
            </div>
        </div>
    </div>


    <!-- Add/Edit Modal -->
    <div id="reelModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
            <div id="modalOverlay" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            
            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full">
                <form id="reelForm" method="POST" action="{{ route('admin.video-reels.store') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="methodField" name="_method" value="POST">
                    <input type="hidden" id="reelId" name="reel_id">
                    <input type="hidden" id="video_file_id" name="video_file_id">
                    
                    <!-- Modal Header -->
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 id="modalTitle" class="text-xl font-bold text-gray-900">Add New Video Reel</h3>
                            <button type="button" id="closeModalBtn" class="text-gray-400 hover:text-gray-600 rounded-lg p-2">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Modal Body -->
                    <div class="px-6 py-6 space-y-5 max-h-[70vh] overflow-y-auto">
                        <!-- Product Select - Searchable -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Select Product *</label>
                            <div class="relative" id="productSelectWrapper">
                                <input type="hidden" name="product_id" id="product_id" required>
                                <div id="productSelectBtn" onclick="toggleProductDropdown()" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl cursor-pointer bg-white flex items-center justify-between hover:border-blue-400 transition-colors">
                                    <span id="selectedProductText" class="text-gray-500">-- Select Product --</span>
                                    <svg class="w-5 h-5 text-gray-400 transition-transform" id="productDropdownArrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                                <div id="productDropdown" class="absolute z-50 w-full mt-1 bg-white border-2 border-gray-200 rounded-xl shadow-xl hidden max-h-72 overflow-hidden">
                                    <div class="p-2 border-b border-gray-200">
                                        <input type="text" id="productSearchInput" placeholder="Search products..." class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" autocomplete="off">
                                    </div>
                                    <div id="productOptionsList" class="overflow-y-auto max-h-52">
                                        @foreach($products as $product)
                                        <div class="product-option px-4 py-3 hover:bg-blue-50 cursor-pointer flex items-center gap-3 transition-colors" data-id="{{ $product->id }}" data-name="{{ $product->name }}" data-price="{{ number_format($product->price) }}">
                                            @if($product->display_images && count($product->display_images) > 0)
                                            <img src="{{ $product->display_images[0] }}" class="w-10 h-10 rounded-lg object-cover flex-shrink-0">
                                            @else
                                            <div class="w-10 h-10 rounded-lg bg-gray-200 flex-shrink-0"></div>
                                            @endif
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900 truncate">{{ $product->name }}</p>
                                                <p class="text-xs text-gray-500">Rs. {{ number_format($product->price) }}</p>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    <div id="noProductResults" class="hidden px-4 py-6 text-center text-gray-500 text-sm">
                                        No products found
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Video Upload -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Upload Video *</label>
                            <input type="file" id="video_file" accept="video/mp4,video/webm,video/quicktime" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <p class="text-xs text-gray-500 mt-1">MP4, WebM, MOV (Max 50MB)</p>
                            
                            <!-- Upload Progress -->
                            <div id="videoUploadProgress" class="mt-3 hidden">
                                <div class="bg-blue-50 border border-blue-200 rounded-xl p-3">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm font-bold text-blue-900">Uploading...</span>
                                        <span id="videoUploadPercent" class="text-sm font-bold text-blue-600">0%</span>
                                    </div>
                                    <div class="w-full bg-blue-200 rounded-full h-2">
                                        <div id="videoProgressBar" class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Upload Success -->
                            <div id="videoUploadSuccess" class="mt-3 hidden">
                                <div class="bg-green-50 border border-green-200 rounded-xl p-3">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-sm font-bold text-green-800">Video uploaded successfully!</span>
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="video_url" id="video_url" required>
                            <input type="hidden" name="video_file_id" id="video_file_id">
                        </div>

                        <!-- Title -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Title (Optional)</label>
                            <input type="text" name="title" id="title" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="e.g., Summer Collection">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <!-- Badge -->
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Badge Text</label>
                                <input type="text" name="badge" id="badge" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="e.g., LIMITED STOCK">
                            </div>

                            <!-- Badge Color -->
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Badge Color</label>
                                <input type="color" name="badge_color" id="badge_color" value="#3D5A3C" class="w-full h-12 border-2 border-gray-300 rounded-xl cursor-pointer">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <!-- Views Count -->
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Views Count</label>
                                <input type="number" name="views_count" id="views_count" min="0" value="0" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <!-- Sort Order -->
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Sort Order</label>
                                <input type="number" name="sort_order" id="sort_order" min="0" value="0" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="flex items-center gap-6">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_active" id="is_active" value="1" checked class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-500">
                                <span class="ml-2 text-sm font-medium text-gray-700">Active</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="autoplay" id="autoplay" value="1" class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-500">
                                <span class="ml-2 text-sm font-medium text-gray-700">Autoplay</span>
                            </label>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                        <button type="button" id="cancelBtn" class="px-5 py-2.5 bg-white text-gray-700 text-sm font-semibold rounded-xl border-2 border-gray-300 hover:bg-gray-50">Cancel</button>
                        <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm font-bold rounded-xl hover:from-blue-700 hover:to-indigo-700 shadow-lg">Save Video Reel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @include('admin.video-reels.scripts')
@endpush
