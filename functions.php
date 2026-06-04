<?php
session_start();

// Creează folderele necesare dacă nu există
$folders = ['data', 'lang', 'css', 'js', 'images'];
foreach($folders as $folder) {
    if(!is_dir($folder)) {
        mkdir($folder, 0777, true);
    }
}

// Fișierele JSON
define('USERS_FILE', 'data/users.json');
define('REVIEWS_FILE', 'data/reviews.json');

// Creează fișierele JSON dacă nu există
if(!file_exists(USERS_FILE)) {
    file_put_contents(USERS_FILE, json_encode([]));
}
if(!file_exists(REVIEWS_FILE)) {
    file_put_contents(REVIEWS_FILE, json_encode([]));
}

// ========== SISTEM LIMBĂ ==========
// Setează limba implicită dacă nu există
if(!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'ro';
}

$lang = $_SESSION['lang'];

// Funcție pentru încărcarea traducerilor
function loadTranslations($lang) {
    $langFile = "lang/{$lang}.json";
    
    // Dacă fișierul nu există, creează-l pe cel implicit
    if(!file_exists($langFile)) {
        // Creează fișierul română ca bază
        $defaultTranslations = [
            "home" => "Acasă",
            "about" => "Despre",
            "features" => "Funcționalități",
            "contact" => "Contact",
            "login" => "Autentificare",
            "register" => "Înregistrare",
            "logout" => "Deconectare",
            "dashboard" => "Panou control",
            "welcome_title" => "Bine ai venit la ReviewHub",
            "welcome_desc" => "Platforma ta pentru recenzii de filme și cărți. Află ce părere au alții sau împărtășește-ți propria experiență!",
            "movies" => "filme",
            "books" => "cărți",
            "total_reviews" => "recenzii totale",
            "recent_reviews" => "Recenzii recente",
            "add_review" => "Adaugă recenzie",
            "my_reviews" => "Recenziile mele",
            "edit" => "Editează",
            "delete" => "Șterge",
            "title" => "Titlu",
            "your_review" => "Recenzia ta",
            "submit" => "Trimite",
            "all_fields_required" => "Toate câmpurile sunt obligatorii",
            "invalid_email" => "Adresă email invalidă",
            "password_too_short" => "Parola trebuie să aibă cel puțin 6 caractere",
            "passwords_not_match" => "Parolele nu se potrivesc",
            "email_exists" => "Acest email este deja înregistrat",
            "register_success" => "Înregistrare reușită!",
            "invalid_credentials" => "Email sau parolă incorecte",
            "invalid_rating" => "Rating invalid (1-5)",
            "review_added" => "Recenzie adăugată cu succes!",
            "confirm_delete" => "Ești sigur că vrei să ștergi această recenzie?",
            "footer_text" => "Toate drepturile rezervate",
            "home_title" => "ReviewHub - Recenzii Filme și Cărți",
            "welcome" => "Bine ai venit",
            "have_account" => "Ai deja cont?",
            "username" => "Nume utilizator",
            "password" => "Parolă",
            "confirm_password" => "Confirmă parola",
            "password_hint" => "Minim 6 caractere",
            "your_name" => "Numele tău",
            "your_message" => "Mesajul tău",
            "send" => "Trimite",
            "message_sent" => "Mesaj trimis cu succes!"
        ];
        
        // Salvează traducerile implicite
        file_put_contents($langFile, json_encode($defaultTranslations, JSON_PRETTY_PRINT));
    }
    
    return json_decode(file_get_contents($langFile), true);
}

// Încarcă traducerile pentru limba curentă
$translations = loadTranslations($lang);

// Funcția __ pentru traducere
function __($key) {
    global $translations;
    
    // Verifică dacă cheia există în traduceri
    if(isset($translations[$key])) {
        return $translations[$key];
    }
    
    // Dacă nu există, returnează cheia însăși
    return $key;
}

// ========== FUNCȚII UTILIZATORI ȘI RECENZII ==========

function getUsers() {
    if(!file_exists(USERS_FILE)) file_put_contents(USERS_FILE, json_encode([]));
    $content = file_get_contents(USERS_FILE);
    return json_decode($content, true) ?: [];
}

function saveUsers($users) {
    file_put_contents(USERS_FILE, json_encode($users, JSON_PRETTY_PRINT));
}

function getReviews() {
    if(!file_exists(REVIEWS_FILE)) file_put_contents(REVIEWS_FILE, json_encode([]));
    $content = file_get_contents(REVIEWS_FILE);
    return json_decode($content, true) ?: [];
}

function saveReviews($reviews) {
    file_put_contents(REVIEWS_FILE, json_encode($reviews, JSON_PRETTY_PRINT));
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function requireLogin() {
    if(!isLoggedIn()) {
        header('Location: login.php');
        exit();
    }
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validatePassword($password) {
    return strlen($password) >= 6;
}
// ========== FUNCȚII NOI PENTRU FEATURES ==========

// Obține notificările utilizatorului
function getUserNotifications($userId) {
    $notifFile = "data/notifications_{$userId}.json";
    if(!file_exists($notifFile)) {
        return [];
    }
    $content = file_get_contents($notifFile);
    $notifications = json_decode($content, true);
    return is_array($notifications) ? $notifications : [];
}

// Adaugă o notificare
function addNotification($userId, $message, $type = 'info') {
    $notifFile = "data/notifications_{$userId}.json";
    $notifications = [];
    if(file_exists($notifFile)) {
        $content = file_get_contents($notifFile);
        $notifications = json_decode($content, true);
        if(!is_array($notifications)) $notifications = [];
    }
    
    $notifications[] = [
        'id' => uniqid(),
        'message' => $message,
        'type' => $type,
        'date' => date('Y-m-d H:i:s'),
        'read' => false
    ];
    
    // Păstrează doar ultimele 20 de notificări
    $notifications = array_slice($notifications, -20);
    file_put_contents($notifFile, json_encode($notifications, JSON_PRETTY_PRINT));
}

// Obține badge-urile utilizatorului
function getUserBadges($userId) {
    $badgesFile = "data/badges_{$userId}.json";
    if(!file_exists($badgesFile)) {
        return [];
    }
    $content = file_get_contents($badgesFile);
    $badges = json_decode($content, true);
    return is_array($badges) ? $badges : [];
}

// Adaugă un badge
function addBadge($userId, $badgeName, $badgeIcon, $badgeDesc) {
    $badgesFile = "data/badges_{$userId}.json";
    $badges = [];
    if(file_exists($badgesFile)) {
        $content = file_get_contents($badgesFile);
        $badges = json_decode($content, true);
        if(!is_array($badges)) $badges = [];
    }
    
    // Verifică dacă badge-ul există deja
    foreach($badges as $badge) {
        if($badge['name'] == $badgeName) {
            return false;
        }
    }
    
    $badges[] = [
        'name' => $badgeName,
        'icon' => $badgeIcon,
        'description' => $badgeDesc,
        'earned_at' => date('Y-m-d H:i:s')
    ];
    
    file_put_contents($badgesFile, json_encode($badges, JSON_PRETTY_PRINT));
    return true;
}

// Verifică și actualizează badge-urile (apelează după adăugarea unei recenzii)
function checkAndUpdateBadges($userId, $username) {
    $reviews = getReviews();
    $userReviews = array_filter($reviews, function($r) use ($userId) {
        return $r['user_id'] == $userId;
    });
    
    $reviewCount = count($userReviews);
    $hasMovie = false;
    $hasBook = false;
    
    foreach($userReviews as $review) {
        if($review['type'] == 'movie') $hasMovie = true;
        if($review['type'] == 'book') $hasBook = true;
    }
    
    // Badge Începător (prima recenzie)
    if($reviewCount >= 1) {
        if(addBadge($userId, __('badge_beginner'), '📝', __('badge_beginner_desc'))) {
            addNotification($userId, __('first_review_notification'), 'achievement');
        }
    }
    
    // Badge Critic (5 recenzii)
    if($reviewCount >= 5) {
        if(addBadge($userId, __('badge_critic'), '⭐', __('badge_critic_desc'))) {
            addNotification($userId, __('review_milestone_notification'), 'achievement');
        }
    }
    
    // Badge Maestru (10+ recenzii)
    if($reviewCount >= 10) {
        addBadge($userId, __('badge_master'), '👑', __('badge_master_desc'));
    }
    
    // Badge Explorator (filme și cărți)
    if($hasMovie && $hasBook) {
        if(addBadge($userId, __('badge_explorer'), '🌍', __('badge_explorer_desc'))) {
            addNotification($userId, __('genre_notification'), 'achievement');
        }
    }
}

// Obține o recomandare random
function getRandomRecommendation() {
    $reviews = getReviews();
    if(count($reviews) == 0) {
        return null;
    }
    return $reviews[array_rand($reviews)];
}

// Obține top 5 cele mai bine cotate
function getTopRated($type = null, $limit = 5) {
    $reviews = getReviews();
    if($type) {
        $reviews = array_filter($reviews, function($r) use ($type) {
            return $r['type'] == $type;
        });
    }
    
    // Grupează după titlu și calculează media
    $ratings = [];
    foreach($reviews as $review) {
        if(!isset($ratings[$review['title']])) {
            $ratings[$review['title']] = ['total' => 0, 'count' => 0, 'type' => $review['type']];
        }
        $ratings[$review['title']]['total'] += $review['rating'];
        $ratings[$review['title']]['count']++;
    }
    
    // Calculează media
    foreach($ratings as $title => &$data) {
        $data['avg'] = $data['total'] / $data['count'];
    }
    
    // Sortează după medie
    uasort($ratings, function($a, $b) {
        return $b['avg'] <=> $a['avg'];
    });
    
    return array_slice($ratings, 0, $limit);
}
?>