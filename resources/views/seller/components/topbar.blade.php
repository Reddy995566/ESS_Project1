<header class="bg-white border-b border-gray-200 shadow-sm">
    <div class="flex items-center justify-between px-6 py-4">
        <!-- Left Section -->
        <div class="flex items-center">
            <h2 class="text-xl font-semibold text-gray-900">
                @if(request()->routeIs('seller.dashboard'))
                    Dashboard
                @elseif(request()->routeIs('seller.products*'))
                    Products
                @elseif(request()->routeIs('seller.orders*'))
                    Orders
                @elseif(request()->routeIs('seller.payouts*'))
                    Payouts & Commissions
                @elseif(request()->routeIs('seller.analytics*'))
                    Analytics
                @elseif(request()->routeIs('seller.notifications*'))
                    Notifications
                @elseif(request()->routeIs('seller.settings*'))
                    Settings
                @elseif(request()->routeIs('seller.profile*'))
                    Profile
                @else
                    Seller Portal
                @endif
            </h2>
        </div>

        <!-- Right Section -->
        <div class="flex items-center space-x-4">
            <!-- Notifications Bell -->
            <a href="{{ route('seller.notifications.index') }}" class="relative p-2 text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                @php
                    $unreadCount = auth('seller')->user() ? auth('seller')->user()->notifications()->where('is_read', false)->count() : 0;
                @endphp
                @if($unreadCount > 0)
                    <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                @endif
            </a>

            <!-- Profile -->
            <a href="{{ route('seller.settings.index') }}" class="flex items-center space-x-2 hover:bg-gray-50 rounded-lg px-3 py-2 transition-all">
                <div class="w-8 h-8 bg-gradient-to-br from-indigo-600 to-indigo-500 rounded-lg flex items-center justify-center">
                    <span class="text-white font-semibold text-sm">
                        {{ auth('seller')->user() ? substr(auth('seller')->user()->business_name, 0, 1) : 'S' }}
                    </span>
                </div>
                <div class="hidden md:block text-left">
                    <p class="text-sm font-medium text-gray-900">
                        {{ auth('seller')->user() ? auth('seller')->user()->business_name : 'Seller' }}
                    </p>
                    <p class="text-xs text-gray-500">
                        {{ auth('seller')->user() ? ucfirst(auth('seller')->user()->status) : '' }}
                    </p>
                </div>
            </a>
        </div>
    </div>
</header>
