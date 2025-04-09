<?php
session_start();
require "database/conn.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: inloggen.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $post_id = $_POST['post_id'];
    $comment = $_POST['comment'];

    try {
        $stmt = $conn->prepare("INSERT INTO comments (user_id, post_id, comment) VALUES (:user_id, :post_id, :comment)");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':post_id', $post_id);
        $stmt->bindParam(':comment', $comment);
        $stmt->execute();
        
        header("Location: main.php");
        exit();
    } catch (PDOException $e) {
        echo "Fout bij het toevoegen van de reactie: " . $e->getMessage();
    }
}
?>
