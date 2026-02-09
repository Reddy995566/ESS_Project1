<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $seller = Auth::guard('seller')->user();
        
        $query = $seller->notifications();
        
        // Filter by type
        if ($request->has('type') && $request->type != '') {
            $query->where('type', $request->type);
        }
        
        // Filter by read status
        if ($request->has('read') && $request->read != '') {
            if ($request->read == 'unread') {
                $query->where(function($q) {
                    $q->whereNull('read_at')->orWhere('is_read', false);
                });
            } else {
                $query->where(function($q) {
                    $q->whereNotNull('read_at')->orWhere('is_read', true);
                });
            }
        }
        
        $notifications = $query->latest()->paginate(25);
        
        // Calculate statistics
        $unreadCount = $seller->notifications()->where(function($q) {
            $q->whereNull('read_at')->orWhere('is_read', false);
        })->count();
        $totalNotifications = $seller->notifications()->count();
        $readCount = $totalNotifications - $unreadCount;
        $todayCount = $seller->notifications()->whereDate('created_at', today())->count();
        
        return view('seller.notifications.index', compact(
            'notifications', 
            'unreadCount', 
            'totalNotifications', 
            'readCount', 
            'todayCount'
        ));
    }
    
    public function markAsRead($id)
    {
        $seller = Auth::guard('seller')->user();
        $notification = $seller->notifications()->findOrFail($id);
        
        $notification->update([
            'is_read' => true,
            'read_at' => now()
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read',
        ]);
    }
    
    public function markAllAsRead()
    {
        $seller = Auth::guard('seller')->user();
        
        $seller->notifications()
            ->where(function($q) {
                $q->whereNull('read_at')->orWhere('is_read', false);
            })
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);
        
        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read',
        ]);
    }
    
    public function destroy($id)
    {
        $seller = Auth::guard('seller')->user();
        $notification = $seller->notifications()->findOrFail($id);
        
        $notification->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Notification deleted',
        ]);
    }
    
    public function getUnreadCount()
    {
        $seller = Auth::guard('seller')->user();
        $count = $seller->notifications()->where(function($q) {
            $q->whereNull('read_at')->orWhere('is_read', false);
        })->count();
        
        return response()->json([
            'count' => $count,
        ]);
    }
}
