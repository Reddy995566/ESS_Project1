<div class="lg:col-span-1">
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <!-- User Info -->
        <div class="text-center pb-6 border-b border-gray-200">
            <div class="w-20 h-20 bg-gradient-to-br from-[#3D0C1F] to-[#4A0F23] rounded-full mx-auto flex items-center justify-center text-white text-2xl font-bold">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <h3 class="mt-4 font-serif-elegant text-xl font-semibold text-[#3D0C1F]">{{ Auth::user()->name }}</h3>
            <p class="text-sm text-gray-500">{{ Auth::user()->email }}</p>
        </div>

        <!-- Navigation -->
        <nav class="mt-6 space-y-2">
            <a href="{{ route('user.dashboard') }}" class="dashboard-nav-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-lg transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('user.profile') }}" class="dashboard-nav-link {{ request()->routeIs('user.profile') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-lg transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <span>My Profile</span>
            </a>
            <a href="{{ route('user.orders') }}" class="dashboard-nav-link {{ request()->routeIs('user.orders*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-lg transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
                <span>My Orders</span>
            </a>

            <a href="{{ route('user.returns.index') }}" class="dashboard-nav-link {{ request()->routeIs('user.returns*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-lg transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z"></path>
                </svg>
                <span>My Returns</span>
            </a>

            <a href="{{ route('user.addresses') }}" class="dashboard-nav-link {{ request()->routeIs('user.addresses') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-lg transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <span>Addresses</span>
            </a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="dashboard-nav-link w-full flex items-center gap-3 px-4 py-3 rounded-lg transition-all text-red-600 hover:bg-red-50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    <span>Logout</span>
                </button>
            </form>
        </nav>
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
