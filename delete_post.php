<?php
session_start();
require "database/conn.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: inloggen.php");
    exit();
}

if (isset($_POST['post_id'])) {
    $post_id = $_POST['post_id'];

    $stmt = $conn->prepare("SELECT user_id FROM `post` WHERE id = :post_id");
    $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
    $stmt->execute();
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($post && ($post['user_id'] == $_SESSION['user_id'] || isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1)) {
        try {
            $deleteStmt = $conn->prepare("DELETE FROM `post` WHERE id = :post_id");
            $deleteStmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
            $deleteStmt->execute();
            
            header("Location: main.php");
            exit();
        } catch (PDOException $e) {
            echo "Fout bij het verwijderen van de post: " . $e->getMessage();
        }
    } else {
        echo "Je hebt geen toestemming om deze post te verwijderen.";
    }
} else {
    echo "Geen post_id opgegeven.";
}
?>
