<header class="bg-white border-b border-gray-200 shadow-sm">
    <div class="flex items-center justify-between px-6 py-4">
        <!-- Left Section -->
        <div class="flex items-center">
            <h2 class="text-xl font-semibold text-gray-900">
                @if(request()->routeIs('admin.dashboard'))
                    Dashboard
                @elseif(request()->routeIs('admin.categories.*'))
                    Categories
                @elseif(request()->routeIs('admin.products*'))
                    Products
                @elseif(request()->routeIs('admin.settings*'))
                    Settings
                @else
                    Admin Panel
                @endif
            </h2>
        </div>

        <!-- Right Section -->
        <div class="flex items-center space-x-4">
            <!-- Search -->
            <div class="hidden md:block">
                <div class="relative">
                    <input type="text" placeholder="Search..." class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent">
                    <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>

            <!-- Notifications -->
            <button class="relative p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-3.22-3.22a9.16 9.16 0 01-1.27-3.8 6.16 6.16 0 01-2-11.6A6.16 6.16 0 0010.5 3.2a9.16 9.16 0 01-1.27 3.8L6 10h5m4 7v1a3 3 0 01-6 0v-1m6 0H9"/>
                </svg>
                <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
            </button>

            <!-- Profile -->
            <div class="flex items-center">
                <div class="w-8 h-8 bg-gradient-to-br from-gray-800 to-black rounded-lg flex items-center justify-center">
                    <span class="text-white font-semibold text-sm">S</span>
                </div>
            </div>
        </div>
    </div>
</header>