<?php

namespace App\Services;

use App\Models\AdminActivityLog;

class AdminLogService
{
    /**
     * Log an admin action.
     */
    public function log(int $adminId, string $action, string $description, ?string $targetType = null, ?int $targetId = null, array $metadata = []): AdminActivityLog
    {
        return AdminActivityLog::create([
            'admin_id' => $adminId,
            'action' => $action,
            'description' => $description,
            'target_type' => $targetType,
            'target_id' => $targetId,
            'metadata' => $metadata,
            'ip_address' => request()->ip(),
        ]);
    }
}
