<?php
session_start();
require "database/conn.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: inloggen.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['comment_id'])) {
    $user_id = $_SESSION['user_id'];
    $comment_id = $_POST['comment_id'];

    $stmt = $conn->prepare("SELECT * FROM comments WHERE id = :comment_id");
    $stmt->bindParam(':comment_id', $comment_id);
    $stmt->execute();
    $comment = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$comment || ($comment['user_id'] != $user_id && (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1))) {
        echo "Je hebt geen toestemming om deze reactie te verwijderen.";
        exit();
    }

    try {
        $stmt = $conn->prepare("DELETE FROM comments WHERE id = :comment_id");
        $stmt->bindParam(':comment_id', $comment_id);
        $stmt->execute();

        header("Location: main.php");
        exit();
    } catch (PDOException $e) {
        echo "Fout bij het verwijderen van de reactie: " . $e->getMessage();
    }
} else {
    echo "Geen comment_id opgegeven.";
}
?>
