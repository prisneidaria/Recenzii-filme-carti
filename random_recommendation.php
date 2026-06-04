<?php
include 'functions.php';

$reviews = getReviews();
if(count($reviews) > 0) {
    $random = $reviews[array_rand($reviews)];
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Recomandare Random - ReviewHub</title>
        <link rel="stylesheet" href="css/style.css">
        <style>
            body {
                display: flex;
                justify-content: center;
                align-items: center;
                min-height: 100vh;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            }
            .recommendation-card {
                text-align: center;
                padding: 40px;
                border-radius: 20px;
                max-width: 500px;
                margin: 20px;
                animation: fadeIn 0.5s ease;
            }
            @keyframes fadeIn {
                from { opacity: 0; transform: scale(0.9); }
                to { opacity: 1; transform: scale(1); }
            }
            .recommendation-icon {
                font-size: 80px;
                margin-bottom: 20px;
            }
            .recommendation-title {
                font-size: 32px;
                margin-bottom: 20px;
            }
            .recommendation-rating {
                font-size: 30px;
                margin-bottom: 20px;
            }
            .recommendation-comment {
                font-size: 16px;
                line-height: 1.6;
                margin-bottom: 30px;
                padding: 20px;
                border-radius: 10px;
            }
            .buttons {
                display: flex;
                gap: 15px;
                justify-content: center;
            }
            body.light .recommendation-card { background: white; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
            body.dark .recommendation-card { background: #16213e; box-shadow: 0 10px 30px rgba(0,0,0,0.3); }
            body.light .recommendation-comment { background: #f5f5f5; }
            body.dark .recommendation-comment { background: #0f3460; }
        </style>
    </head>
    <body class="<?php echo $_SESSION['theme'] ?? 'light'; ?>">
        <div class="recommendation-card">
            <div class="recommendation-icon"><?php echo $random['type'] == 'movie' ? '🎬' : '📖'; ?></div>
            <h2>Recomandarea noastră pentru tine:</h2>
            <div class="recommendation-title"><?php echo htmlspecialchars($random['title']); ?></div>
            <div class="recommendation-rating"><?php echo str_repeat('⭐', $random['rating']); ?></div>
            <div class="recommendation-comment">
                "<?php echo htmlspecialchars(substr($random['comment'], 0, 150)); ?>..."
            </div>
            <div class="buttons">
                <button onclick="window.location.href='random_recommendation.php'" class="btn">🎲 Altă recomandare</button>
                <button onclick="window.location.href='features.php'" class="btn-cancel">← Înapoi</button>
            </div>
        </div>
        <script src="js/script.js"></script>
    </body>
    </html>
    <?php
} else {
    header('Location: features.php');
}
?>