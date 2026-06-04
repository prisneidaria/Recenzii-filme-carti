<?php 
include 'functions.php';

// Dacă e deja logat, trimite la dashboard
if(isLoggedIn()) {
    header('Location: dashboard.php');
    exit();
}

$error = '';
$success = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];
    
    // Validări
    if(empty($username) || empty($email) || empty($password)) {
        $error = __('all_fields_required');
    } elseif(!validateEmail($email)) {
        $error = __('invalid_email');
    } elseif(!validatePassword($password)) {
        $error = __('password_too_short');
    } elseif($password !== $confirm) {
        $error = __('passwords_not_match');
    } else {
        $users = getUsers();
        // Verifică dacă email există deja
        foreach($users as $user) {
            if($user['email'] === $email) {
                $error = __('email_exists');
                break;
            }
        }
        
        if(!$error) {
            $newUser = [
                'id' => uniqid(),
                'username' => $username,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'created_at' => date('Y-m-d H:i:s')
            ];
            $users[] = $newUser;
            saveUsers($users);
            $success = __('register_success');
            // Adaugă notificare de bun venit
            $notifFile = "data/notifications_{$newUser['id']}.json";
            $welcomeNotif = [[
        'id' => uniqid(),
        'message' => __('welcome_notification'),
        'type' => 'info',
        'date' => date('Y-m-d H:i:s'),
        'read' => false
]];
file_put_contents($notifFile, json_encode($welcomeNotif));
            
            // Auto-login după înregistrare
            $_SESSION['user_id'] = $newUser['id'];
            $_SESSION['username'] = $newUser['username'];
            $_SESSION['user_email'] = $newUser['email'];
            
            // Redirect după 2 secunde
            header("refresh:2;url=dashboard.php");
        }
    }
}
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo __('register'); ?> - ReviewHub</title>
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
            <h2><?php echo __('register'); ?></h2>
            
            <?php if($error): ?>
                <div class="message error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if($success): ?>
                <div class="message success"><?php echo $success; ?> Redirecționare...</div>
            <?php endif; ?>
            
            <form method="POST" class="form" id="registerForm">
                <div class="form-group">
                    <label><?php echo __('username'); ?>:</label>
                    <input type="text" name="username" required 
                           value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label>Email:</label>
                    <input type="email" name="email" required 
                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label><?php echo __('password'); ?>:</label>
                    <input type="password" name="password" id="password" required>
                    <small><?php echo __('password_hint'); ?></small>
                </div>
                
                <div class="form-group">
                    <label><?php echo __('confirm_password'); ?>:</label>
                    <input type="password" name="confirm_password" id="confirm_password" required>
                </div>
                
                <button type="submit" class="btn"><?php echo __('register'); ?></button>
            </form>
            
            <p style="text-align: center; margin-top: 20px;">
                <?php echo __('have_account'); ?> 
                <a href="login.php"><?php echo __('login'); ?></a>
            </p>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 ReviewHub - <?php echo __('footer_text'); ?></p>
    </footer>

    <script src="js/script.js"></script>
    <script>
        document.getElementById('registerForm')?.addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirm = document.getElementById('confirm_password').value;
            
            if(password.length < 6) {
                e.preventDefault();
                alert('<?php echo __('password_too_short'); ?>');
                return false;
            }
            
            if(password !== confirm) {
                e.preventDefault();
                alert('<?php echo __('passwords_not_match'); ?>');
                return false;
            }
            
            return true;
        });
    </script>
</body>

</html>