<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::withCount('orders')->orderBy('created_at', 'desc')->get();
        $totalUsers = $users->count();
        $activeUsers = $users->where('is_active', true)->count();
        $verifiedUsers = $users->whereNotNull('email_verified_at')->count();

        return view('admin.users.index', compact('users', 'totalUsers', 'activeUsers', 'verifiedUsers'));
    }

    public function show(User $user)
    {
        $user->load(['orders' => function($query) {
            $query->latest()->take(10);
        }, 'addresses', 'wishlists']);
        
        return view('admin.users.show', compact('user'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'mobile' => 'nullable|string|max:20',
            'password' => 'required|string|min:6',
            'is_active' => 'boolean',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        $user = User::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'User created successfully!',
            'user' => $user
        ]);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'mobile' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6',
            'is_active' => 'boolean',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        $user->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully!',
            'user' => $user->fresh()
        ]);
    }

    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully!'
        ]);
    }

    public function toggle(Request $request, User $user)
    {
        $field = $request->input('field');
        $value = $request->input('value');

        if (!in_array($field, ['is_active'])) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid field'
            ], 400);
        }

        $user->update([$field => $value]);

        return response()->json([
            'success' => true,
            'message' => 'User status updated successfully!'
        ]);
    }

    public function bulkAction(Request $request)
    {
        $action = $request->input('action');
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'No users selected'
            ], 400);
        }

        switch ($action) {
            case 'activate':
                User::whereIn('id', $ids)->update(['is_active' => 1]);
                $message = 'Users activated successfully!';
                break;
            case 'deactivate':
                User::whereIn('id', $ids)->update(['is_active' => 0]);
                $message = 'Users deactivated successfully!';
                break;
            case 'delete':
                User::whereIn('id', $ids)->delete();
                $message = 'Users deleted successfully!';
                break;
            default:
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid action'
                ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    public function export()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        
        $filename = 'users_' . date('Y-m-d_His') . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        fputcsv($output, ['ID', 'Name', 'Email', 'Mobile', 'Status', 'Email Verified', 'Created At']);
        
        foreach ($users as $user) {
            fputcsv($output, [
                $user->id,
                $user->name,
                $user->email,
                $user->mobile,
                $user->is_active ? 'Active' : 'Inactive',
                $user->email_verified_at ? 'Yes' : 'No',
                $user->created_at->format('Y-m-d H:i:s')
            ]);
        }
        
        fclose($output);
        exit;
    }
}
