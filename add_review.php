<?php 
include 'functions.php';
requireLogin(); // Only for utilizatori autentificati

$error = '';
$success = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $type = $_POST['type'];
    $rating = (int)$_POST['rating'];
    $comment = trim($_POST['comment']);
    
    // Validări
    if(empty($title) || empty($comment)) {
        $error = __('all_fields_required');
    } elseif($rating < 1 || $rating > 5) {
        $error = __('invalid_rating');
    } else {
        $reviews = getReviews();
        $newReview = [
            'id' => uniqid(),
            'user_id' => $_SESSION['user_id'],
            'author' => $_SESSION['username'],
            'title' => $title,
            'type' => $type,
            'rating' => $rating,
            'comment' => $comment,
            'created_at' => date('Y-m-d H:i:s')
        ];
        $reviews[] = $newReview;
        saveReviews($reviews);
        // Check si acordarea badgeurilor
checkAndUpdateBadges($_SESSION['user_id'], $_SESSION['username']);
        
        // Redirect la dashboard dupa 2 secunde
        $success = __('review_added');
        header("refresh:2;url=dashboard.php");
    }
}
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo __('add_review'); ?> - ReviewHub</title>
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
        <div class="form-container">
            <h2>➕ <?php echo __('add_review'); ?></h2>
            
            <?php if($error): ?>
                <div class="message error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if($success): ?>
                <div class="message success"><?php echo $success; ?> Redirecționare...</div>
            <?php endif; ?>
            
            <form method="POST" class="form" id="reviewForm">
                <div class="form-group">
                    <label><?php echo __('title'); ?>:</label>
                    <input type="text" name="title" required placeholder="Ex: Inception, Dune, Harry Potter...">
                </div>
                
                <div class="form-group">
                    <label>Tip:</label>
                    <select name="type" required>
                        <option value="movie">🎬 <?php echo __('movies'); ?></option>
                        <option value="book">📖 <?php echo __('books'); ?></option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Rating:</label>
                    <div class="rating-input">
                        <select name="rating" required class="rating-select">
                            <option value="">Alege rating</option>
                            <option value="5">⭐⭐⭐⭐⭐ - Excelent</option>
                            <option value="4">⭐⭐⭐⭐ - Foarte bun</option>
                            <option value="3">⭐⭐⭐ - Bun</option>
                            <option value="2">⭐⭐ - Slab</option>
                            <option value="1">⭐ - Foarte slab</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label><?php echo __('your_review'); ?>:</label>
                    <textarea name="comment" rows="6" required placeholder="Scrie aici părerea ta despre acest film/carte..."></textarea>
                </div>
                
                <div class="form-buttons">
                    <button type="submit" class="btn btn-primary">📝 <?php echo __('submit'); ?></button>
                    <a href="dashboard.php" class="btn btn-cancel">❌ Anulează</a>
                </div>
            </form>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 ReviewHub - <?php echo __('footer_text'); ?></p>
    </footer>

    <script src="js/script.js"></script>
    <script>
        // Validare forma in real-time
        document.getElementById('reviewForm')?.addEventListener('submit', function(e) {
            const title = document.querySelector('input[name="title"]').value;
            const comment = document.querySelector('textarea[name="comment"]').value;
            const rating = document.querySelector('select[name="rating"]').value;
            
            if(!title.trim()) {
                e.preventDefault();
                alert('<?php echo __('all_fields_required'); ?>');
                return false;
            }
            
            if(!rating) {
                e.preventDefault();
                alert('Te rog să alegi un rating!');
                return false;
            }
            
            if(!comment.trim()) {
                e.preventDefault();
                alert('<?php echo __('all_fields_required'); ?>');
                return false;
            }
            
            return true;
        });
        
        // Add prveiw la rating
        const ratingSelect = document.querySelector('.rating-select');
        if(ratingSelect) {
            ratingSelect.addEventListener('change', function() {
                console.log('Rating selectat: ' + this.value);
            });
        }
    </script>
</body>
</html>
