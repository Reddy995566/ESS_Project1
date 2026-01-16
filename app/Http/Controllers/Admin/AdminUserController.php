<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Role;
use App\Models\ActivityLog;
use App\Models\LoginHistory;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    use LogsActivity;

    public function index(Request $request)
    {
        $query = Admin::with('role');
        
        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        // Role filter
        if ($request->filled('role')) {
            $query->where('role_id', $request->role);
        }
        
        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $admins = $query->latest()->paginate(10);
        $totalAdmins = Admin::count();
        $activeAdmins = Admin::where('status', 'active')->count();
        $inactiveAdmins = Admin::where('status', 'inactive')->count();
        $totalRoles = Role::count();
        $roles = Role::all();
        
        return view('admin.admin-users.index', compact('admins', 'totalAdmins', 'activeAdmins', 'inactiveAdmins', 'totalRoles', 'roles'));
    }

    public function show(Admin $adminUser)
    {
        $adminUser->load(['role', 'activityLogs' => function($query) {
            $query->latest()->take(5);
        }, 'loginHistories' => function($query) {
            $query->latest('login_at')->take(5);
        }]);
        
        return response()->json([
            'admin' => [
                'id' => $adminUser->id,
                'name' => $adminUser->name,
                'email' => $adminUser->email,
                'phone' => $adminUser->phone,
                'avatar' => $adminUser->avatar,
                'status' => $adminUser->status,
                'last_login' => $adminUser->last_login ? $adminUser->last_login->format('M d, Y H:i') : null,
                'created_at' => $adminUser->created_at->format('M d, Y H:i'),
                'role' => [
                    'name' => $adminUser->role->name,
                    'slug' => $adminUser->role->slug
                ],
                'recent_activities' => $adminUser->activityLogs->map(function($log) {
                    return [
                        'action' => $log->action,
                        'description' => $log->description,
                        'created_at' => $log->created_at->format('M d, Y H:i')
                    ];
                }),
                'recent_logins' => $adminUser->loginHistories->map(function($login) {
                    return [
                        'ip_address' => $login->ip_address,
                        'status' => $login->status,
                        'login_at' => $login->login_at->format('M d, Y H:i')
                    ];
                })
            ]
        ]);
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.admin-users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|min:6|confirmed',
            'phone' => 'nullable|string|max:20',
            'role_id' => 'required|exists:roles,id',
            'status' => 'required|in:active,inactive',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = $validated['status'] === 'active';
        
        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $admin = Admin::create($validated);

        self::logActivity('created', "Created new admin user: {$admin->name}", $admin);

        return response()->json([
            'success' => true,
            'message' => 'Admin user created successfully!'
        ]);
    }

    public function edit(Admin $adminUser)
    {
        return response()->json([
            'admin' => [
                'id' => $adminUser->id,
                'name' => $adminUser->name,
                'email' => $adminUser->email,
                'phone' => $adminUser->phone,
                'role_id' => $adminUser->role_id,
                'status' => $adminUser->status,
                'avatar' => $adminUser->avatar
            ]
        ]);
    }

    public function update(Request $request, Admin $adminUser)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email,' . $adminUser->id,
            'password' => 'nullable|min:6|confirmed',
            'phone' => 'nullable|string|max:20',
            'role_id' => 'required|exists:roles,id',
            'status' => 'required|in:active,inactive',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $oldData = $adminUser->only(['name', 'email', 'role_id', 'status']);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }
        
        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($adminUser->avatar) {
                \Storage::disk('public')->delete($adminUser->avatar);
            }
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $validated['is_active'] = $validated['status'] === 'active';

        $adminUser->update($validated);

        self::logActivity('updated', "Updated admin user: {$adminUser->name}", $adminUser, $oldData, $validated);

        return response()->json([
            'success' => true,
            'message' => 'Admin user updated successfully!'
        ]);
    }

    public function destroy(Admin $adminUser)
    {
        // Prevent deleting self
        if ($adminUser->id == session('admin_id')) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot delete your own account!'
            ], 400);
        }

        $name = $adminUser->name;
        
        // Delete avatar if exists
        if ($adminUser->avatar) {
            \Storage::disk('public')->delete($adminUser->avatar);
        }
        
        $adminUser->delete();

        self::logActivity('deleted', "Deleted admin user: {$name}");

        return response()->json([
            'success' => true,
            'message' => 'Admin user deleted successfully!'
        ]);
    }

    public function activityLogs(Request $request)
    {
        $query = ActivityLog::with('admin');
        
        // Admin filter
        if ($request->filled('admin_id')) {
            $query->where('admin_id', $request->admin_id);
        }
        
        // Action filter
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }
        
        // Date filter
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }
        
        $logs = $query->latest()->paginate(50);
        return view('admin.activity-logs', compact('logs'));
    }

    public function loginHistory(Request $request)
    {
        $query = LoginHistory::with('admin');
        
        // Admin filter
        if ($request->filled('admin_id')) {
            $query->where('admin_id', $request->admin_id);
        }
        
        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Date filter
        if ($request->filled('date')) {
            $query->whereDate('login_at', $request->date);
        }
        
        $histories = $query->latest('login_at')->paginate(50);
        return view('admin.login-history', compact('histories'));
    }

    public function toggleStatus(Request $request, Admin $adminUser)
    {
        // Prevent toggling self
        if ($adminUser->id == session('admin_id')) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot change your own status!'
            ], 400);
        }

        $newStatus = $adminUser->status === 'active' ? 'inactive' : 'active';
        $adminUser->update([
            'status' => $newStatus,
            'is_active' => $newStatus === 'active'
        ]);

        self::logActivity('updated', "Changed {$adminUser->name} status to: {$newStatus}", $adminUser);

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully!'
        ]);
    }
}
