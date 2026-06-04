<?php include 'functions.php'; ?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo __('features'); ?> - ReviewHub</title>
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
        <h1><?php echo __('features'); ?></h1>
        
        <div class="features-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; margin: 30px 0;">
            <div class="feature-card" style="padding: 25px; background: #f0f0f0; border-radius: 10px; text-align: center;">
                <div style="font-size: 48px;">🎬📚</div>
                <h3>Filme & Cărți</h3>
                <p>Poți adăuga recenzii atât pentru filme, cât și pentru cărți, într-o singură platformă.</p>
            </div>
            
            <div class="feature-card" style="padding: 25px; background: #f0f0f0; border-radius: 10px; text-align: center;">
                <div style="font-size: 48px;">⭐⭐⭐⭐⭐</div>
                <h3>Sistem de Rating</h3>
                <p>Evaluează filmele și cărțile cu scor de la 1 la 5 stele.</p>
            </div>
            
            <div class="feature-card" style="padding: 25px; background: #f0f0f0; border-radius: 10px; text-align: center;">
                <div style="font-size: 48px;">🌓</div>
                <h3>Dark / Light Mode</h3>
                <p>Comută între tema luminoasă și cea întunecată pentru confortul ochilor.</p>
            </div>
            
            <div class="feature-card" style="padding: 25px; background: #f0f0f0; border-radius: 10px; text-align: center;">
                <div style="font-size: 48px;">🌐</div>
                <h3>Multi-Limbă</h3>
                <p>Suport pentru Română, English și Русский.</p>
            </div>
            
            <div class="feature-card" style="padding: 25px; background: #f0f0f0; border-radius: 10px; text-align: center;">
                <div style="font-size: 48px;">🔐</div>
                <h3>Autentificare</h3>
                <p>Sistem complet de înregistrare și autentificare a utilizatorilor.</p>
            </div>
            
            <div class="feature-card" style="padding: 25px; background: #f0f0f0; border-radius: 10px; text-align: center;">
                <div style="font-size: 48px;">📱</div>
                <h3>Design Responsive</h3>
                <p>Funcționează perfect pe desktop, tabletă și telefon mobil.</p>
            </div>
        </div>
        
        <div style="text-align: center; margin: 40px 0;">
            <?php if(isLoggedIn()): ?>
                <a href="add_review.php" class="btn" style="background: #28a745;">➕ Adaugă prima recenzie</a>
            <?php else: ?>
                <a href="register.php" class="btn" style="background: #28a745;">📝 Înregistrează-te acum</a>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 ReviewHub - <?php echo __('footer_text'); ?></p>
    </footer>

    <script src="js/script.js"></script>
</body>
</html>