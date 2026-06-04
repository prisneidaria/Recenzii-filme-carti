<?php 
include 'functions.php';
requireLogin();

$id = $_GET['id'];
$reviews = getReviews();
$reviews = array_filter($reviews, fn($r) => $r['id'] != $id || $r['user_id'] != $_SESSION['user_id']);
saveReviews(array_values($reviews));
header('Location: dashboard.php');
?>