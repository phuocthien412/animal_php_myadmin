<?php
require_once __DIR__ . '/../config/env.php';
require_once __DIR__ . '/../model/Notification.php';

function getAdminNotifications() {
    return Notification::getRecent(100);
}

if (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'text/event-stream') !== false) {
    header('Content-Type: text/event-stream');
    header('Cache-Control: no-cache');
    header('Connection: keep-alive');

    $lastEventId = $_SERVER['HTTP_LAST_EVENT_ID'] ?? 0;
    $currentId = (int)$lastEventId;

    while (true) {
        $notifications = getAdminNotifications();
        $newNotifications = array_values(array_filter($notifications, function ($notification) use ($lastEventId) {
            return (int)($notification['id'] ?? 0) > (int)$lastEventId;
        }));

        usort($newNotifications, function ($left, $right) {
            return (int)($left['id'] ?? 0) <=> (int)($right['id'] ?? 0);
        });

        if (!empty($newNotifications)) {
            $currentId = (int)($newNotifications[count($newNotifications) - 1]['id'] ?? $currentId);
            echo "id: " . $currentId . "\n";
            echo "data: " . json_encode(array_slice($newNotifications, 0, 5)) . "\n\n";
            $lastEventId = $currentId;
        }

        ob_flush();
        flush();
        sleep(5); // Check for new notifications every 5 seconds
    }
} else {
    $all_notifications = getAdminNotifications();
    Notification::markAllAsRead();
    include __DIR__ . '/../view/admin/notifications/notification-list.php';
}
?>