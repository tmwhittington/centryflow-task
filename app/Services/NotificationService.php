<?php

namespace App\Services;

class NotificationService
{
    public static function sendLowStockAlert($productId) {
        $_SESSION['notifications'] = $_SESSION['notifications'] ?? [];

        $_SESSION['notifications'][] = [
            'product_id' => $productId,
            'notification_type' => 'low_stock_alert',
        ];
    }
}