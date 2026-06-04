<?php
session_start();

if(isset($_GET['lang'])) {
    $lang = $_GET['lang'];
    // Validare limbă (doar ro, en, ru)
    if(in_array($lang, ['ro', 'en', 'ru'])) {
        $_SESSION['lang'] = $lang;
    }
}

// Redirecționează înapoi la pagina anterioară
if(isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) {
    header('Location: ' . $_SERVER['HTTP_REFERER']);
} else {
    header('Location: index.php');
}
exit();
?>