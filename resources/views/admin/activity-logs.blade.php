@extends('admin.layouts.app')

@section('title', 'Activity Logs')

@section('content')
    <!-- Main Container -->
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50 to-indigo-50 p-6">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <div class="flex items-center justify-center w-14 h-14 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-black text-gray-900 tracking-tight">Activity Logs</h1>
                        <p class="mt-1 text-sm text-gray-600">Track all admin activities and changes</p>
                    </div>
                </div>
                <button id="refreshBtn" class="inline-flex items-center justify-center w-11 h-11 bg-white text-gray-700 rounded-xl border-2 border-gray-200 hover:border-blue-400 hover:bg-blue-50 hover:text-blue-700 transition-all duration-200 shadow-sm hover:shadow-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Main Data Table Card -->
        <div class="bg-white rounded-2xl shadow-2xl border border-gray-200 overflow-hidden">
            
            <!-- Table Toolbar -->
            <div class="bg-gradient-to-r from-gray-50 to-slate-50 px-6 py-5 border-b border-gray-200">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 flex items-center">
                            <span class="flex items-center justify-center w-8 h-8 bg-blue-100 text-blue-600 rounded-lg mr-3">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                                </svg>
                            </span>
                            Activity Logs
                        </h2>
                        <p class="text-sm text-gray-600 mt-1 ml-11">Complete history of admin actions</p>
                    </div>
                </div>
            </div>

            <!-- Advanced Filters Section -->
            <div class="bg-gray-50 px-6 py-5 border-b border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wider">Admin Filter</label>
                        <select id="adminFilter" class="block w-full px-4 py-3 bg-white border-2 border-gray-300 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Admins</option>
                            @foreach(\App\Models\Admin::all() as $admin)
                                <option value="{{ $admin->id }}" {{ request('admin_id') == $admin->id ? 'selected' : '' }}>
                                    {{ $admin->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wider">Action Filter</label>
                        <select id="actionFilter" class="block w-full px-4 py-3 bg-white border-2 border-gray-300 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Actions</option>
                            <option value="created" {{ request('action') == 'created' ? 'selected' : '' }}>Created</option>
                            <option value="updated" {{ request('action') == 'updated' ? 'selected' : '' }}>Updated</option>
                            <option value="deleted" {{ request('action') == 'deleted' ? 'selected' : '' }}>Deleted</option>
                            <option value="viewed" {{ request('action') == 'viewed' ? 'selected' : '' }}>Viewed</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wider">Date Filter</label>
                        <input type="date" id="dateFilter" class="block w-full px-4 py-3 bg-white border-2 border-gray-300 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ request('date') }}">
                    </div>
                </div>

                <div class="mt-4">
                    <button id="resetFilters" class="px-4 py-2.5 bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700 text-sm font-bold rounded-lg hover:from-gray-200 hover:to-gray-300 transition-all flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Reset Filters
                    </button>
                </div>
            </div>

            <!-- Data Table -->
            <div class="overflow-x-auto">
                <table id="logsTable" class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-slate-100 to-gray-100">
                        <tr>
                            <th class="px-4 py-4 text-center w-16">
                                <span class="text-xs font-black text-gray-700 uppercase">ID</span>
                            </th>
                            <th class="px-4 py-4 text-left min-w-[150px]">
                                <span class="text-xs font-black text-gray-700 uppercase">Admin</span>
                            </th>
                            <th class="px-4 py-4 text-center w-28">
                                <span class="text-xs font-black text-gray-700 uppercase">Action</span>
                            </th>
                            <th class="px-4 py-4 text-left">
                                <span class="text-xs font-black text-gray-700 uppercase">Description</span>
                            </th>
                            <th class="px-4 py-4 text-center w-32">
                                <span class="text-xs font-black text-gray-700 uppercase">Model</span>
                            </th>
                            <th class="px-4 py-4 text-center w-32">
                                <span class="text-xs font-black text-gray-700 uppercase">IP Address</span>
                            </th>
                            <th class="px-4 py-4 text-center w-40">
                                <span class="text-xs font-black text-gray-700 uppercase">Date/Time</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($logs as $log)
                            <tr class="group hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-all duration-200">
                                <!-- ID -->
                                <td class="px-4 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700 border border-gray-300">#{{ $log->id }}</span>
                                </td>

                                <!-- Admin -->
                                <td class="px-4 py-4">
                                    @if($log->admin)
                                        <div class="flex items-center space-x-2">
                                            @if($log->admin->avatar)
                                                <img src="{{ asset('storage/' . $log->admin->avatar) }}" alt="Avatar" class="w-8 h-8 rounded-full border-2 border-blue-200">
                                            @else
                                                <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-full flex items-center justify-center text-white font-bold text-xs border-2 border-blue-200">
                                                    {{ strtoupper(substr($log->admin->name, 0, 1)) }}
                                                </div>
                                            @endif
                                            <span class="text-sm font-medium text-gray-900">{{ $log->admin->name }}</span>
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-500 italic">System</span>
                                    @endif
                                </td>

                                <!-- Action -->
                                <td class="px-4 py-4 text-center">
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold 
                                        @if($log->action === 'created') bg-gradient-to-r from-green-100 to-emerald-100 text-green-700 border border-green-300
                                        @elseif($log->action === 'updated') bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-700 border border-blue-300
                                        @elseif($log->action === 'deleted') bg-gradient-to-r from-red-100 to-pink-100 text-red-700 border border-red-300
                                        @else bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700 border border-gray-300
                                        @endif">
                                        {{ ucfirst($log->action) }}
                                    </span>
                                </td>

                                <!-- Description -->
                                <td class="px-4 py-4">
                                    <p class="text-sm text-gray-700 line-clamp-2">{{ $log->description }}</p>
                                </td>

                                <!-- Model -->
                                <td class="px-4 py-4 text-center">
                                    @if($log->model_type)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-gradient-to-r from-purple-100 to-pink-100 text-purple-700 border border-purple-300">
                                            {{ class_basename($log->model_type) }}
                                        </span>
                                    @else
                                        <span class="text-sm text-gray-400">-</span>
                                    @endif
                                </td>

                                <!-- IP Address -->
                                <td class="px-4 py-4 text-center">
                                    <code class="text-xs bg-gray-100 px-2 py-1 rounded border border-gray-300">{{ $log->ip_address ?? '-' }}</code>
                                </td>

                                <!-- Date/Time -->
                                <td class="px-4 py-4 text-center">
                                    <span class="text-xs text-gray-600">{{ $log->created_at->format('M d, Y') }}</span>
                                    <br>
                                    <span class="text-xs text-gray-500">{{ $log->created_at->format('H:i:s') }}</span>
                                </td>
                            </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-20">
                                <div class="text-center">
                                    <div class="mx-auto flex items-center justify-center w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full mb-6">
                                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-900 mb-2">No Activity Logs Found</h3>
                                    <p class="text-gray-500 max-w-md mx-auto">There are no activity logs to display yet.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Table Footer -->
            <div class="bg-gradient-to-r from-gray-50 to-slate-50 px-6 py-4 border-t border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="text-sm font-medium text-gray-700">
                        Showing <span class="font-bold text-blue-600">{{ $logs->firstItem() ?? 0 }}</span> to <span class="font-bold text-blue-600">{{ $logs->lastItem() ?? 0 }}</span> of <span class="font-bold text-blue-600">{{ $logs->total() }}</span> entries
                    </div>
                    <div>
                        {{ $logs->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

<style>
    @keyframes slideIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-slideIn { animation: slideIn 0.3s ease-out forwards; }
    ::-webkit-scrollbar { width: 8px; height: 8px; }
    ::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 4px; }
    ::-webkit-scrollbar-thumb { background: linear-gradient(135deg, #3b82f6 0%, #6366f1 100%); border-radius: 4px; }
    ::-webkit-scrollbar-thumb:hover { background: linear-gradient(135deg, #2563eb 0%, #4f46e5 100%); }
</style>

<script>
    // Refresh Button
    document.getElementById('refreshBtn')?.addEventListener('click', () => location.reload());

    // Filters
    document.getElementById('adminFilter')?.addEventListener('change', filterLogs);
    document.getElementById('actionFilter')?.addEventListener('change', filterLogs);
    document.getElementById('dateFilter')?.addEventListener('change', filterLogs);
    
    document.getElementById('resetFilters')?.addEventListener('click', function() {
        document.getElementById('adminFilter').value = '';
        document.getElementById('actionFilter').value = '';
        document.getElementById('dateFilter').value = '';
        filterLogs();
    });

    function filterLogs() {
        const url = new URL(window.location.href);
        url.searchParams.set('admin_id', document.getElementById('adminFilter').value);
        url.searchParams.set('action', document.getElementById('actionFilter').value);
        url.searchParams.set('date', document.getElementById('dateFilter').value);
        window.location.href = url.toString();
    }
</script>
@endsection
