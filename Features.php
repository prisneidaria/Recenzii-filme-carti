<?php include 'functions.php'; ?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo __('features'); ?> - ReviewHub</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .randomizer-card {
            text-align: center;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
        }
        .random-recommendation {
            margin-top: 20px;
            padding: 20px;
            border-radius: 10px;
            animation: fadeIn 0.5s ease;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .top-items {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .top-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 12px;
            border-radius: 10px;
        }
        .top-rank {
            font-size: 24px;
            font-weight: bold;
            min-width: 50px;
        }
        .badges-container {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: center;
        }
        .badge-item {
            text-align: center;
            padding: 15px;
            border-radius: 12px;
            min-width: 120px;
        }
        .badge-icon {
            font-size: 40px;
            margin-bottom: 10px;
        }
        .notification-preview {
            padding: 15px;
            border-radius: 10px;
            margin-top: 15px;
        }
        body.light .randomizer-card { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
        body.dark .randomizer-card { background: linear-gradient(135deg, #1a4a7a 0%, #0f3460 100%); color: white; }
        body.light .random-recommendation { background: #f8f9fa; }
        body.dark .random-recommendation { background: #0f3460; }
        body.light .top-item { background: #f8f9fa; }
        body.dark .top-item { background: #0f3460; }
        body.light .badge-item { background: #f8f9fa; }
        body.dark .badge-item { background: #0f3460; }
        .btn-random {
            background: #ff6b6b;
            color: white;
            padding: 12px 30px;
            font-size: 18px;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            transition: transform 0.3s;
        }
        .btn-random:hover { transform: scale(1.05); }
        .features-header {
            text-align: center;
            margin-bottom: 50px;
            padding: 40px 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            color: white;
        }
        .features-header h1 {
            font-size: 48px;
            margin-bottom: 15px;
        }
        .features-subtitle {
            font-size: 18px;
            opacity: 0.9;
        }
        .new-features-section {
            margin: 60px 0;
        }
        .new-features-section h2 {
            text-align: center;
            margin-bottom: 40px;
            font-size: 32px;
        }
        .new-features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 30px;
        }
        .new-feature-card {
            display: flex;
            gap: 20px;
            padding: 25px;
            border-radius: 15px;
            transition: transform 0.3s ease;
        }
        .new-feature-card:hover {
            transform: translateY(-5px);
        }
        body.light .new-feature-card {
            background: white;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        }
        body.dark .new-feature-card {
            background: #16213e;
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
        }
        .new-feature-icon {
            font-size: 48px;
            min-width: 70px;
            text-align: center;
        }
        .new-feature-content h3 {
            margin-bottom: 10px;
            font-size: 20px;
        }
        .new-feature-content p {
            margin-bottom: 15px;
            line-height: 1.5;
        }
        .cta-section {
            text-align: center;
            padding: 50px;
            margin: 50px 0;
            border-radius: 20px;
        }
        body.light .cta-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        body.dark .cta-section {
            background: linear-gradient(135deg, #1a4a7a 0%, #0f3460 100%);
            color: white;
        }
        .cta-section h3 {
            font-size: 28px;
            margin-bottom: 20px;
        }
        .cta-btn {
            background: white !important;
            color: #667eea !important;
            padding: 12px 30px !important;
            font-size: 18px !important;
            margin: 5px;
        }
        body.dark .cta-btn {
            color: #1a4a7a !important;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 25px;
            text-decoration: none;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
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
                    <a href="logout.php" class="nav-link"><?php echo __('logout'); ?></a>
                    <span class="nav-user">👤 <?php echo $_SESSION['username']; ?></span>
                <?php else: ?>
                    <a href="login.php" class="nav-link"><?php echo __('login'); ?></a>
                    <a href="register.php" class="nav-link"><?php echo __('register'); ?></a>
                <?php endif; ?>
            </div>
            
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
        <div class="features-header">
            <h1>✨ <?php echo __('features'); ?> ✨</h1>
            <p class="features-subtitle">Descoperă toate funcționalitățile platformei ReviewHub</p>
        </div>

        <!-- NOUTĂȚI - Funcții noi creative -->
        <div class="new-features-section">
            <h2>🔥 <?php echo __('notifications_title'); ?> & <?php echo __('badges_title'); ?></h2>
            <div class="new-features-grid">
                
                <!-- Funcția 1: Randomizer -->
<div class="new-feature-card">
    <div class="new-feature-icon">🎲</div>
    <div class="new-feature-content">
        <h3><?php echo __('randomizer_title'); ?></h3>
        <p><?php echo __('randomizer_desc'); ?></p>
        <div class="randomizer-card">
            <button onclick="getRandomRec()" class="btn-random"><?php echo __('randomizer_btn'); ?></button>
            <div id="randomResult" class="random-recommendation" style="display:none;"></div>
        </div>
    </div>
</div>

                <!-- Funcția 2: Top 5 -->
                <div class="new-feature-card">
                    <div class="new-feature-icon">🏆</div>
                    <div class="new-feature-content">
                        <h3><?php echo __('top5_title'); ?></h3>
                        <p><?php echo __('top5_desc'); ?></p>
                        <div class="top-items">
                            <?php 
                            $topMovies = getTopRated('movie', 5);
                            $topBooks = getTopRated('book', 5);
                            ?>
                            <div>
                                <strong><?php echo __('top5_movies'); ?></strong>
                                <?php if(count($topMovies) > 0): ?>
                                    <?php foreach($topMovies as $title => $data): ?>
                                    <div class="top-item">
                                        <span class="top-rank">⭐ <?php echo round($data['avg'], 1); ?></span>
                                        <span>🎬 <?php echo htmlspecialchars($title); ?></span>
                                    </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p>Nu există filme încă.</p>
                                <?php endif; ?>
                            </div>
                            <div>
                                <strong><?php echo __('top5_books'); ?></strong>
                                <?php if(count($topBooks) > 0): ?>
                                    <?php foreach($topBooks as $title => $data): ?>
                                    <div class="top-item">
                                        <span class="top-rank">⭐ <?php echo round($data['avg'], 1); ?></span>
                                        <span>📖 <?php echo htmlspecialchars($title); ?></span>
                                    </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p>Nu există cărți încă.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Funcția 3: Badge-uri -->
                <div class="new-feature-card">
                    <div class="new-feature-icon">🏅</div>
                    <div class="new-feature-content">
                        <h3><?php echo __('badges_title'); ?></h3>
                        <p><?php echo __('badges_desc'); ?></p>
                        <div class="badges-container">
                            <div class="badge-item"><div class="badge-icon">📝</div><div><strong><?php echo __('badge_beginner'); ?></strong><br><small><?php echo __('badge_beginner_desc'); ?></small></div></div>
                            <div class="badge-item"><div class="badge-icon">⭐</div><div><strong><?php echo __('badge_critic'); ?></strong><br><small><?php echo __('badge_critic_desc'); ?></small></div></div>
                            <div class="badge-item"><div class="badge-icon">👑</div><div><strong><?php echo __('badge_master'); ?></strong><br><small><?php echo __('badge_master_desc'); ?></small></div></div>
                            <div class="badge-item"><div class="badge-icon">🌍</div><div><strong><?php echo __('badge_explorer'); ?></strong><br><small><?php echo __('badge_explorer_desc'); ?></small></div></div>
                        </div>
                        <?php if(isLoggedIn()): ?>
                        <div class="notification-preview">
                            <a href="notifications.php" class="btn" style="background:#007bff; color:white;">🔔 <?php echo __('notifications_title'); ?></a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="cta-section">
            <h3>Gata să începi?</h3>
            <?php if(isLoggedIn()): ?>
                <a href="add_review.php" class="btn cta-btn">➕ <?php echo __('add_review'); ?></a>
                <a href="notifications.php" class="btn cta-btn">🔔 <?php echo __('notifications_title'); ?></a>
            <?php else: ?>
                <a href="register.php" class="btn cta-btn">📝 <?php echo __('register'); ?></a>
                <a href="login.php" class="btn cta-btn">🔐 <?php echo __('login'); ?></a>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 ReviewHub - <?php echo __('footer_text'); ?></p>
    </footer>

    <script src="js/script.js"></script>
    <script>
   function getRandomRec() {
    const resultDiv = document.getElementById('randomResult');
    resultDiv.style.display = 'block';
    resultDiv.innerHTML = '<div style="text-align:center">🔄 Se caută o recomandare...</div>';
    
    fetch('random_api.php')
        .then(response => response.json())
        .then(data => {
            console.log('Răspuns API:', data); // For debugging
            
            if(data.success === false || data.error) {
                resultDiv.innerHTML = `
                    <div style="text-align:center; padding:20px;">
                        <div style="font-size:48px">📭</div>
                        <p>Nu există recenzii încă.</p>
                        <a href="add_review.php" class="btn-random" style="display:inline-block; margin-top:10px;">➕ Adaugă prima recenzie</a>
                    </div>
                `;
            } else {
                let stars = '';
                for(let i = 0; i < data.rating; i++) {
                    stars += '⭐';
                }
                
                resultDiv.innerHTML = `
                    <div style="text-align:center; padding:20px;">
                        <div style="font-size:64px; margin-bottom:10px;">${data.type === 'movie' ? '🎬' : '📖'}</div>
                        <h3 style="margin:10px 0; color:inherit;">${escapeHtml(data.title)}</h3>
                        <div style="font-size:28px; margin:10px 0;">${stars}</div>
                        <p style="margin:15px 0; line-height:1.5;">"${escapeHtml(data.comment.substring(0, 200))}${data.comment.length > 200 ? '...' : ''}"</p>
                        <button onclick="getRandomRec()" class="btn-random" style="margin-top:15px;">🎲 Altă recomandare</button>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Eroare:', error);
            resultDiv.innerHTML = `
                <div style="text-align:center; padding:20px; color: #ff6b6b;">
                    <div style="font-size:48px">⚠️</div>
                    <p>Eroare de conexiune. Încearcă din nou.</p>
                    <button onclick="getRandomRec()" class="btn-random" style="margin-top:10px;">🔄 Reîncearcă</button>
                </div>
            `;
        });
}

// Functionpentru prevenirea XSS
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

    </script>
</body>
</html>