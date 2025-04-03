<?php
session_start();
require "database/conn.php";

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(["error" => "Niet ingelogd"]);
    exit();
}

$user_id = $_SESSION['user_id'];
$post_id = $_POST['post_id'];

$stmt = $conn->prepare("SELECT * FROM likes WHERE user_id = :user_id AND post_id = :post_id");
$stmt->execute(['user_id' => $user_id, 'post_id' => $post_id]);

if ($stmt->rowCount() > 0) {
    $stmt = $conn->prepare("DELETE FROM likes WHERE user_id = :user_id AND post_id = :post_id");
    $stmt->execute(['user_id' => $user_id, 'post_id' => $post_id]);
} else {
    $stmt = $conn->prepare("INSERT INTO likes (user_id, post_id) VALUES (:user_id, :post_id)");
    $stmt->execute(['user_id' => $user_id, 'post_id' => $post_id]);
}

$stmt = $conn->prepare("SELECT COUNT(*) FROM likes WHERE post_id = :post_id");
$stmt->execute(['post_id' => $post_id]);
$like_count = $stmt->fetchColumn();

echo json_encode(["like_count" => $like_count]);
