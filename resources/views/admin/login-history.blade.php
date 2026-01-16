@extends('admin.layouts.app')

@section('title', 'Login History')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-sign-in-alt"></i> Login History
        </h1>
        <div class="d-flex gap-2">
            <select id="adminFilter" class="form-control form-control-sm" style="width: 200px;">
                <option value="">All Admins</option>
                @foreach(\App\Models\Admin::all() as $admin)
                    <option value="{{ $admin->id }}" {{ request('admin_id') == $admin->id ? 'selected' : '' }}>
                        {{ $admin->name }}
                    </option>
                @endforeach
            </select>
            <select id="statusFilter" class="form-control form-control-sm" style="width: 150px;">
                <option value="">All Status</option>
                <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>Success</option>
                <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
            </select>
            <input type="date" id="dateFilter" class="form-control form-control-sm" style="width: 150px;" value="{{ request('date') }}">
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Logins
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\LoginHistory::count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-sign-in-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Successful Logins
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\LoginHistory::where('status', 'success')->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Failed Logins
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\LoginHistory::where('status', 'failed')->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Today's Logins
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\LoginHistory::whereDate('login_at', today())->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Login History Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Login Attempts</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="loginHistoryTable">
                    <thead>
                        <tr>
                            <th width="5%">ID</th>
                            <th width="20%">Admin</th>
                            <th width="15%">IP Address</th>
                            <th width="25%">User Agent</th>
                            <th width="10%">Status</th>
                            <th width="15%">Login Time</th>
                            <th width="10%">Location</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($histories as $history)
                            <tr>
                                <td>{{ $history->id }}</td>
                                <td>
                                    @if($history->admin)
                                        <div class="d-flex align-items-center">
                                            @if($history->admin->avatar)
                                                <img src="{{ asset('storage/' . $history->admin->avatar) }}" 
                                                     alt="Avatar" 
                                                     class="rounded-circle me-2" 
                                                     width="30" 
                                                     height="30">
                                            @else
                                                <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center me-2" 
                                                     style="width: 30px; height: 30px; font-size: 12px;">
                                                    {{ strtoupper(substr($history->admin->name, 0, 1)) }}
                                                </div>
                                            @endif
                                            <div>
                                                <div>{{ $history->admin->name }}</div>
                                                <small class="text-muted">{{ $history->admin->email }}</small>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted">Unknown</span>
                                    @endif
                                </td>
                                <td>
                                    <code>{{ $history->ip_address }}</code>
                                </td>
                                <td>
                                    <small class="text-muted">{{ Str::limit($history->user_agent, 40) }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $history->status === 'success' ? 'success' : 'danger' }}">
                                        <i class="fas fa-{{ $history->status === 'success' ? 'check' : 'times' }}"></i>
                                        {{ ucfirst($history->status) }}
                                    </span>
                                </td>
                                <td>
                                    <small>{{ $history->login_at->format('M d, Y H:i:s') }}</small>
                                    <br>
                                    <small class="text-muted">{{ $history->login_at->diffForHumans() }}</small>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-info" onclick="showLocation('{{ $history->ip_address }}')" title="View Location">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No login history found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                {{ $histories->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Location Modal -->
<div class="modal fade" id="locationModal" tabindex="-1" aria-labelledby="locationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="locationModalLabel">IP Location Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="locationContent">
                <!-- Location content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#adminFilter, #statusFilter, #dateFilter').on('change', function() {
        filterHistory();
    });
});

function filterHistory() {
    let adminId = $('#adminFilter').val();
    let status = $('#statusFilter').val();
    let date = $('#dateFilter').val();
    
    let url = new URL(window.location.href);
    url.searchParams.set('admin_id', adminId);
    url.searchParams.set('status', status);
    url.searchParams.set('date', date);
    
    window.location.href = url.toString();
}

function showLocation(ip) {
    $('#locationContent').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading...</div>');
    $('#locationModal').modal('show');
    
    // Mock location data - In production, you would call an IP geolocation API
    setTimeout(() => {
        $('#locationContent').html(`
            <table class="table table-borderless">
                <tr>
                    <th width="40%">IP Address:</th>
                    <td><code>${ip}</code></td>
                </tr>
                <tr>
                    <th>Location:</th>
                    <td>This is a local IP address</td>
                </tr>
                <tr>
                    <th>Note:</th>
                    <td><small class="text-muted">Integrate with IP geolocation service (e.g., ipapi.co, ipinfo.io) for detailed location information.</small></td>
                </tr>
            </table>
        `);
    }, 500);
}
</script>
@endsection
