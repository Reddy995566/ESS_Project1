<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    public static function logActivity($action, $description, $model = null, $oldValues = null, $newValues = null)
    {
        if (!Auth::guard('admin')->check()) {
            return;
        }

        ActivityLog::create([
            'admin_id' => Auth::guard('admin')->id(),
            'action' => $action,
            'description' => $description,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model ? $model->id : null,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
