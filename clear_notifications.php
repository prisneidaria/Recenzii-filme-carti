<?php
session_start();
if(isset($_SESSION['user_id'])) {
    $notifFile = "data/notifications_{$_SESSION['user_id']}.json";
    if(file_exists($notifFile)) {
        file_put_contents($notifFile, json_encode([]));
    }
}
header('Location: notifications.php');
exit();
?>