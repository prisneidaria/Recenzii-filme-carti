<?php
header('Content-Type: application/json');

// Include functions
require_once 'functions.php';

$reviews = getReviews();

if(count($reviews) > 0) {
    $random = $reviews[array_rand($reviews)];
    echo json_encode([
        'success' => true,
        'title' => $random['title'],
        'type' => $random['type'],
        'rating' => $random['rating'],
        'comment' => $random['comment']
    ]);
} else {
    echo json_encode([
        'success' => false,
        'error' => 'Nu există recenzii'
    ]);
}
?>