<?php 
include 'functions.php'; 
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo __('home_title'); ?></title>
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
        <section class="hero">
            <h1><?php echo __('welcome_title'); ?></h1>
            <p><?php echo __('welcome_desc'); ?></p>
            <div class="stats">
                <?php 
                $reviews = getReviews();
                $movies = count(array_filter($reviews, function($r) { return $r['type'] == 'movie'; }));
                $books = count(array_filter($reviews, function($r) { return $r['type'] == 'book'; }));
                ?>
                <div class="stat-card">🎬 <?php echo $movies; ?> <?php echo __('movies'); ?></div>
                <div class="stat-card">📚 <?php echo $books; ?> <?php echo __('books'); ?></div>
                <div class="stat-card">📝 <?php echo count($reviews); ?> <?php echo __('total_reviews'); ?></div>
            </div>
        </section>

        <section class="recent-reviews">
            <h2><?php echo __('recent_reviews'); ?></h2>
            <div class="reviews-grid">
                <?php 
                $reviews = array_reverse(getReviews());
                $reviews = array_slice($reviews, 0, 6);
                foreach($reviews as $review): ?>
                <div class="review-card">
                    <div class="review-type"><?php echo $review['type'] == 'movie' ? '🎬 Film' : '📖 Carte'; ?></div>
                    <h3><?php echo htmlspecialchars($review['title']); ?></h3>
                    <div class="rating"><?php echo str_repeat('⭐', $review['rating']); ?></div>
                    <p><?php echo htmlspecialchars(substr($review['comment'], 0, 100)) . '...'; ?></p>
                    <small>by <?php echo htmlspecialchars($review['author']); ?></small>
                </div>
                <?php endforeach; ?>
                <?php if(count($reviews) == 0): ?>
                    <p>Nu există recenzii încă. <a href="register.php">Înregistrează-te</a> și adaugă prima recenzie!</p>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 ReviewHub - <?php echo __('footer_text'); ?></p>
    </footer>

    <script src="js/script.js"></script>
</body>
</html>