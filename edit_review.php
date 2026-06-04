<?php 
include 'functions.php';
requireLogin();

$id = $_GET['id'];
$reviews = getReviews();
$index = array_search($id, array_column($reviews, 'id'));

if($index === false || $reviews[$index]['user_id'] != $_SESSION['user_id']) {
    header('Location: dashboard.php');
    exit();
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $reviews[$index]['title'] = $_POST['title'];
    $reviews[$index]['type'] = $_POST['type'];
    $reviews[$index]['rating'] = (int)$_POST['rating'];
    $reviews[$index]['comment'] = $_POST['comment'];
    saveReviews($reviews);
    header('Location: dashboard.php');
    exit();
}
// Afișează formular cu datele existente
?>