<?php include 'functions.php'; ?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo __('contact'); ?> - ReviewHub</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="<?php echo $_SESSION['theme'] ?? 'light'; ?>">
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
                    <a href="logout.php" class="nav-link"><?php echo __('logout'); ?></a>
                    <span class="nav-user">👤 <?php echo $_SESSION['username']; ?></span>
                <?php else: ?>
                    <a href="login.php" class="nav-link"><?php echo __('login'); ?></a>
                    <a href="register.php" class="nav-link"><?php echo __('register'); ?></a>
                <?php endif; ?>
            </div>
            <div class="nav-controls">
                <button onclick="toggleTheme()" class="theme-btn">🌓</button>
                <select onchange="changeLanguage(this.value)" class="lang-select">
                    <option value="ro" <?php echo $lang=='ro'?'selected':''; ?>>Română</option>
                    <option value="en" <?php echo $lang=='en'?'selected':''; ?>>English</option>
                    <option value="ru" <?php echo $lang=='ru'?'selected':''; ?>>Русский</option>
                </select>
            </div>
        </div>
    </nav>

    <main class="container">
        <div class="form-container">
            <h1><?php echo __('contact'); ?></h1>
            
            <?php
            $message_sent = '';
            if($_SERVER['REQUEST_METHOD'] == 'POST') {
                $name = trim($_POST['name']);
                $email = trim($_POST['email']);
                $message = trim($_POST['message']);
                
                if(empty($name) || empty($email) || empty($message)) {
                    $error = __('all_fields_required');
                } elseif(!validateEmail($email)) {
                    $error = __('invalid_email');
                } else {
                    // Save mesajul
                    $messages = [];
                    if(file_exists('data/messages.json')) {
                        $messages = json_decode(file_get_contents('data/messages.json'), true);
                    }
                    $messages[] = [
                        'name' => $name,
                        'email' => $email,
                        'message' => $message,
                        'date' => date('Y-m-d H:i:s')
                    ];
                    file_put_contents('data/messages.json', json_encode($messages, JSON_PRETTY_PRINT));
                    $message_sent = __('message_sent');
                }
            }
            ?>
            
            <?php if(isset($error)): ?>
                <div class="message error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if($message_sent): ?>
                <div class="message success"><?php echo $message_sent; ?></div>
            <?php endif; ?>
            
            <form method="POST" class="form">
                <div class="form-group">
                    <label><?php echo __('your_name'); ?>:</label>
                    <input type="text" name="name" required>
                </div>
                <div class="form-group">
                    <label>Email:</label>
                    <input type="email" name="email" required>
                </div>
                <div class="form-group">
                    <label><?php echo __('your_message'); ?>:</label>
                    <textarea name="message" rows="5" required></textarea>
                </div>
                <button type="submit" class="btn"><?php echo __('send'); ?></button>
            </form>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 ReviewHub - <?php echo __('footer_text'); ?></p>
    </footer>

    <script src="js/script.js"></script>
</body>
</html>