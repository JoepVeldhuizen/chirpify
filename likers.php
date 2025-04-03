<?php
session_start();
require "database/conn.php";

if (!isset($_GET['post_id'])) {
    echo "Geen data";
    exit();
}

$post_id = $_GET['post_id'];

$stmt = $conn->prepare("SELECT accounts.username FROM likes 
                        JOIN accounts ON likes.user_id = accounts.id 
                        WHERE likes.post_id = :post_id");
$stmt->execute(['post_id' => $post_id]);
$likers = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($likers) {
    echo "Geliked door: ";
    foreach ($likers as $liker) {
        echo htmlspecialchars($liker['username']) . ", ";
    }
} else {
    echo "Nog geen likes.";
}
?>
