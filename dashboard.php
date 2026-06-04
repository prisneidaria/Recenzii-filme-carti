<?php 
include 'functions.php';
requireLogin(); // Only utilizatori autentificati
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo __('dashboard'); ?> - ReviewHub</title>
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
                <a href="dashboard.php" class="nav-link"><?php echo __('dashboard'); ?></a>
                <a href="logout.php" class="nav-link"><?php echo __('logout'); ?></a>
                <span class="nav-user">👤 <?php echo $_SESSION['username']; ?></span>
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
        <h2><?php echo __('welcome'); ?>, <?php echo htmlspecialchars($_SESSION['username']); ?>! 👋</h2>
        
        <div class="dashboard-actions" style="margin: 30px 0;">
            <a href="add_review.php" class="btn" style="background: #28a745;">➕ <?php echo __('add_review'); ?></a>
        </div>
        
        <h3><?php echo __('my_reviews'); ?></h3>
        <div class="reviews-grid">
            <?php 
            $reviews = getReviews();
            $myReviews = array_filter($reviews, function($r) {
                return $r['user_id'] == $_SESSION['user_id'];
            });
            
            if(count($myReviews) == 0) {
                echo '<p>Nu ai scris nicio recenzie încă. <a href="add_review.php">Adaugă prima recenzie!</a></p>';
            }
            
            foreach(array_reverse($myReviews) as $review): 
            ?>
                <div class="review-card">
                    <div class="review-type"><?php echo $review['type'] == 'movie' ? '🎬 Film' : '📖 Carte'; ?></div>
                    <h3><?php echo htmlspecialchars($review['title']); ?></h3>
                    <div class="rating"><?php echo str_repeat('⭐', $review['rating']); ?></div>
                    <p><?php echo htmlspecialchars($review['comment']); ?></p>
                    <small>📅 <?php echo $review['created_at']; ?></small>
                    <div class="review-actions" style="margin-top: 15px;">
                        <a href="edit_review.php?id=<?php echo $review['id']; ?>" class="btn" style="background: #ffc107; color:#333;">✏️ <?php echo __('edit'); ?></a>
                        <a href="delete_review.php?id=<?php echo $review['id']; ?>" class="btn" style="background: #dc3545;" onclick="return confirm('<?php echo __('confirm_delete'); ?>')">🗑️ <?php echo __('delete'); ?></a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 ReviewHub - <?php echo __('footer_text'); ?></p>
    </footer>

    <script src="js/script.js"></script>
</body>
</html>