<?php 
include 'functions.php';
requireLogin();

$userId = $_SESSION['user_id'];
$notifications = [];

// Read notificarile din file
$notifFile = "data/notifications_{$userId}.json";
if(file_exists($notifFile)) {
    $content = file_get_contents($notifFile);
    $notifications = json_decode($content, true);
    if(!is_array($notifications)) $notifications = [];
}
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo __('notifications_title'); ?> - ReviewHub</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .notifications-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-top: 30px;
        }
        .notification-card {
            display: flex;
            gap: 15px;
            padding: 20px;
            border-radius: 12px;
            align-items: center;
            transition: transform 0.2s;
        }
        .notification-card:hover {
            transform: translateX(5px);
        }
        body.light .notification-card {
            background: white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        body.dark .notification-card {
            background: #16213e;
            box-shadow: 0 2px 8px rgba(0,0,0,0.3);
        }
        .notification-icon {
            font-size: 40px;
            min-width: 60px;
            text-align: center;
        }
        .notification-content {
            flex: 1;
        }
        .notification-content p {
            margin-bottom: 8px;
            line-height: 1.4;
        }
        .notification-content small {
            opacity: 0.7;
            font-size: 12px;
        }
        .notification-card.achievement {
            border-left: 4px solid #ffd700;
        }
        .notification-card.info {
            border-left: 4px solid #007bff;
        }
        body.dark .notification-card.achievement { border-left-color: #ffd700; }
        body.dark .notification-card.info { border-left-color: #007bff; }
        .empty-notifications {
            text-align: center;
            padding: 60px 20px;
        }
        .empty-icon {
            font-size: 64px;
            margin-bottom: 20px;
        }
        .btn-clear {
            background: #dc3545;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 20px;
            cursor: pointer;
            margin-top: 20px;
        }
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body class="<?php echo $_SESSION['theme'] ?? 'light'; ?>">
    <!-- ========== NAVBAR COMPLET ========== -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="index.php" class="nav-logo">🎬📚 ReviewHub</a>
            <div class="nav-menu">
                <a href="index.php" class="nav-link"><?php echo __('home'); ?></a>
                <a href="about.php" class="nav-link"><?php echo __('about'); ?></a>
                <a href="features.php" class="nav-link"><?php echo __('features'); ?></a>
                <a href="contact.php" class="nav-link"><?php echo __('contact'); ?></a>
                <?php if(isLoggedIn()): ?>
                    <a href="dashboard.php" class="nav-link"><?php echo __('dashboard'); ?></a>
                    <a href="notifications.php" class="nav-link">🔔 <?php echo __('notifications_title'); ?></a>
                    <a href="logout.php" class="nav-link"><?php echo __('logout'); ?></a>
                    <span class="nav-user">👤 <?php echo $_SESSION['username']; ?></span>
                <?php else: ?>
                    <a href="login.php" class="nav-link"><?php echo __('login'); ?></a>
                    <a href="register.php" class="nav-link"><?php echo __('register'); ?></a>
                <?php endif; ?>
            </div>
            <div class="nav-controls">
              <div class="nav-controls">
                <button onclick="toggleTheme()" class="theme-btn">🌓</button>
                <select onchange="changeLanguage(this.value)" class="lang-select">
                    <option value="ro" <?php echo $lang=='ro'?'selected':''; ?>>Română</option>
                    <option value="en" <?php echo $lang=='en'?'selected':''; ?>>English</option>
                    <option value="ru" <?php echo $lang=='ru'?'selected':''; ?>>Русский</option>
                </select>
            </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main class="container">
        <div class="page-header">
            <h1>🔔 <?php echo __('notifications_title'); ?></h1>
            <?php if(count($notifications) > 0): ?>
                <button onclick="clearNotifications()" class="btn-clear">🗑️ Șterge toate</button>
            <?php endif; ?>
        </div>
        
        <?php if(empty($notifications)): ?>
            <div class="empty-notifications">
                <div class="empty-icon">🔕</div>
                <h3>Nu ai notificări</h3>
                <p>Primești notificări când primești badge-uri sau atingi obiective.</p>
                <a href="add_review.php" class="btn" style="background: #28a745; margin-top: 20px;">➕ Adaugă prima recenzie</a>
            </div>
        <?php else: ?>
            <div class="notifications-list">
                <?php foreach(array_reverse($notifications) as $notif): ?>
                <div class="notification-card <?php echo $notif['type']; ?>">
                    <div class="notification-icon">
                        <?php echo $notif['type'] == 'achievement' ? '🏅' : '📢'; ?>
                    </div>
                    <div class="notification-content">
                        <p><?php echo htmlspecialchars($notif['message']); ?></p>
                        <small><?php echo $notif['date']; ?></small>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; 2025 ReviewHub - <?php echo __('footer_text'); ?></p>
    </footer>

    <script src="js/script.js"></script>
    <script>
        function clearNotifications() {
            if(confirm('Ești sigur că vrei să ștergi toate notificările?')) {
                fetch('clear_notifications.php', {
                    method: 'POST'
                }).then(() => {
                    location.reload();
                });
            }
        }
    </script>
</body>
</html>