<?php 
include 'functions.php';
requireLogin();

$error = '';
$success = '';

// Preia ID-ul recenziei din URL
$id = isset($_GET['id']) ? $_GET['id'] : '';

if(empty($id)) {
    header('Location: dashboard.php');
    exit();
}

// Găsește recenzia
$reviews = getReviews();
$review = null;
$index = null;

foreach($reviews as $i => $r) {
    if($r['id'] == $id && $r['user_id'] == $_SESSION['user_id']) {
        $review = $r;
        $index = $i;
        break;
    }
}

// Dacă nu există recenzia, redirecționează
if($review === null) {
    header('Location: dashboard.php');
    exit();
}

// Procesare formular
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
        // Actualizează recenzia
        $reviews[$index]['title'] = $title;
        $reviews[$index]['type'] = $type;
        $reviews[$index]['rating'] = $rating;
        $reviews[$index]['comment'] = $comment;
        saveReviews($reviews);
        
        // Redirecționează la dashboard
        header('Location: dashboard.php?edited=1');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo __('edit'); ?> - ReviewHub</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .rating-stars {
            display: flex;
            gap: 10px;
            font-size: 30px;
            cursor: pointer;
            margin: 10px 0;
        }
        .rating-stars span {
            cursor: pointer;
            transition: transform 0.2s;
        }
        .rating-stars span:hover {
            transform: scale(1.2);
        }
        .star-active {
            color: #ffd700;
            text-shadow: 0 0 5px #ffd700;
        }
        .form-container {
            max-width: 600px;
            margin: 0 auto;
        }
    </style>
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
                    <option value="ro" <?php echo $lang=='ro'?'selected':''; ?>>🇷🇴 Română</option>
                    <option value="en" <?php echo $lang=='en'?'selected':''; ?>>🇬🇧 English</option>
                    <option value="ru" <?php echo $lang=='ru'?'selected':''; ?>>🇷🇺 Русский</option>
                </select>
            </div>
        </div>
    </nav>

    <main class="container">
        <div class="form-container">
            <h1>✏️ <?php echo __('edit'); ?> Recenzie</h1>
            
            <?php if($error): ?>
                <div class="message error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" class="form" id="editForm">
                <div class="form-group">
                    <label><?php echo __('title'); ?>:</label>
                    <input type="text" name="title" required placeholder="Ex: Inception, Dune, Harry Potter..." value="<?php echo htmlspecialchars($review['title']); ?>">
                </div>
                
                <div class="form-group">
                    <label>Tip:</label>
                    <select name="type" required>
                        <option value="movie" <?php echo $review['type'] == 'movie' ? 'selected' : ''; ?>>🎬 Film</option>
                        <option value="book" <?php echo $review['type'] == 'book' ? 'selected' : ''; ?>>📖 Carte</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Rating:</label>
                    <div class="rating-stars" id="ratingStars">
                        <span data-value="1"><?php echo $review['rating'] >= 1 ? '★' : '☆'; ?></span>
                        <span data-value="2"><?php echo $review['rating'] >= 2 ? '★' : '☆'; ?></span>
                        <span data-value="3"><?php echo $review['rating'] >= 3 ? '★' : '☆'; ?></span>
                        <span data-value="4"><?php echo $review['rating'] >= 4 ? '★' : '☆'; ?></span>
                        <span data-value="5"><?php echo $review['rating'] >= 5 ? '★' : '☆'; ?></span>
                    </div>
                    <input type="hidden" name="rating" id="ratingValue" value="<?php echo $review['rating']; ?>" required>
                </div>
                
                <div class="form-group">
                    <label><?php echo __('your_review'); ?>:</label>
                    <textarea name="comment" rows="6" required placeholder="Scrie aici părerea ta despre acest film/carte..."><?php echo htmlspecialchars($review['comment']); ?></textarea>
                </div>
                
                <div class="form-buttons" style="display: flex; gap: 15px; margin-top: 20px;">
                    <button type="submit" class="btn" style="background: #28a745;">💾 Salvează modificările</button>
                    <a href="dashboard.php" class="btn" style="background: #6c757d;">❌ Anulează</a>
                </div>
            </form>
        </div>
    </main>

    <footer>
        <p>&copy; 2026 ReviewHub - <?php echo __('footer_text'); ?></p>
    </footer>

    <script src="js/script.js"></script>
    <script>
        // Sistem rating cu stele
        const stars = document.querySelectorAll('#ratingStars span');
        const ratingInput = document.getElementById('ratingValue');
        let currentRating = parseInt(ratingInput.value) || 0;
        
        // Inițializează stelele
        function updateStars(value) {
            stars.forEach((star, index) => {
                if(index < value) {
                    star.innerHTML = '★';
                    star.classList.add('star-active');
                } else {
                    star.innerHTML = '☆';
                    star.classList.remove('star-active');
                }
            });
        }
        
        updateStars(currentRating);
        
        stars.forEach(star => {
            star.addEventListener('click', function() {
                const value = parseInt(this.getAttribute('data-value'));
                ratingInput.value = value;
                currentRating = value;
                updateStars(value);
            });
            
            star.addEventListener('mouseenter', function() {
                const value = parseInt(this.getAttribute('data-value'));
                stars.forEach((s, index) => {
                    if(index < value) {
                        s.innerHTML = '★';
                    } else {
                        s.innerHTML = '☆';
                    }
                });
            });
            
            star.addEventListener('mouseleave', function() {
                updateStars(currentRating);
            });
        });
        
        // Validare formular
        document.getElementById('editForm').addEventListener('submit', function(e) {
            const title = document.querySelector('input[name="title"]').value.trim();
            const comment = document.querySelector('textarea[name="comment"]').value.trim();
            const rating = document.querySelector('input[name="rating"]').value;
            
            if(title === '') {
                e.preventDefault();
                alert('Te rog să completezi titlul!');
                return false;
            }
            
            if(rating === '' || parseInt(rating) < 1 || parseInt(rating) > 5) {
                e.preventDefault();
                alert('Te rog să selectezi un rating (1-5 stele)!');
                return false;
            }
            
            if(comment === '') {
                e.preventDefault();
                alert('Te rog să scrii o recenzie!');
                return false;
            }
            
            return true;
        });
    </script>
</body>
</html>