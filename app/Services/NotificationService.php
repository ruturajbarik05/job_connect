<?php

namespace App\Services;

use App\Models\AppNotification;

class NotificationService
{
    /**
     * Send a notification to a user.
     */
    public function send(int $userId, string $type, string $title, string $message, ?string $link = null, array $data = []): AppNotification
    {
        return AppNotification::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'link' => $link,
            'data' => $data,
        ]);
    }

    /**
     * Mark a notification as read.
     */
    public function markAsRead(AppNotification $notification): void
    {
        $notification->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    /**
     * Mark all notifications as read for a user.
     */
    public function markAllAsRead(int $userId): void
    {
        AppNotification::where('user_id', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);
    }
}
