<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class AuditService
{
    /**
     * Log an action in the audit trail.
     *
     * @param string $action The action performed (e.g., CREATE, UPDATE)
     * @param mixed $target The model instance involved
     * @param array|null $details Additional details (e.g., old vs new values)
     * @return void
     */
    public function log(string $action, $target, ?array $details = null): void
    {
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'target_type' => get_class($target),
            'target_id' => $target->id,
            'details' => $details,
            'ip_address' => request()->ip(),
        ]);
    }
}
