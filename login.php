<?php 
include 'functions.php';

// Dacă e deja logat, trimite la dashboard
if(isLoggedIn()) {
    header('Location: dashboard.php');
    exit();
}

$error = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    // Validare câmpuri goale
    if(empty($email) || empty($password)) {
        $error = __('all_fields_required');
    } else {
        $users = getUsers();
        $found = false;
        
        foreach($users as $user) {
            if($user['email'] === $email && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['user_email'] = $user['email'];
                header('Location: dashboard.php');
                exit();
            }
        }
        
        if(!$found) {
            $error = __('invalid_credentials');
        }
    }
}
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo __('login'); ?> - ReviewHub</title>
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
            <h2><?php echo __('login'); ?></h2>
            
            <?php if($error): ?>
                <div class="message error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" class="form" id="loginForm">
                <div class="form-group">
                    <label>Email:</label>
                    <input type="email" name="email" id="email" required 
                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label><?php echo __('password'); ?>:</label>
                    <input type="password" name="password" id="password" required>
                </div>
                
                <button type="submit" class="btn"><?php echo __('login'); ?></button>
            </form>
            
            <p style="text-align: center; margin-top: 20px;">
                <?php echo __('no_account'); ?> 
                <a href="register.php"><?php echo __('register'); ?></a>
            </p>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 ReviewHub - <?php echo __('footer_text'); ?></p>
    </footer>

    <script src="js/script.js"></script>
    <script>
        // Validare formular în timp real
        document.getElementById('loginForm')?.addEventListener('submit', function(e) {
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            
            if(!email || !password) {
                e.preventDefault();
                alert('<?php echo __('all_fields_required'); ?>');
                return false;
            }
            
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if(!emailRegex.test(email)) {
                e.preventDefault();
                alert('<?php echo __('invalid_email'); ?>');
                return false;
            }
            
            return true;
        });
    </script>
</body>
</html>