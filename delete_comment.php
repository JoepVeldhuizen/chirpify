<?php
session_start();
require "database/conn.php";

// Controleer of de gebruiker is ingelogd
if (!isset($_SESSION['user_id'])) {
    header("Location: inloggen.php");
    exit();
}

// Controleer of er een comment_id via POST is verzonden
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['comment_id'])) {
    $user_id = $_SESSION['user_id'];
    $comment_id = $_POST['comment_id'];

    // Zoek de reactie in de database
    $stmt = $conn->prepare("SELECT * FROM comments WHERE id = :comment_id");
    $stmt->bindParam(':comment_id', $comment_id);
    $stmt->execute();
    $comment = $stmt->fetch(PDO::FETCH_ASSOC);

    // Controleer of de reactie bestaat en of de gebruiker eigenaar is van de reactie of een beheerder is
    if (!$comment || ($comment['user_id'] != $user_id && (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1))) {
        echo "Je hebt geen toestemming om deze reactie te verwijderen.";
        exit();
    }

    try {
        // Verwijder de reactie
        $stmt = $conn->prepare("DELETE FROM comments WHERE id = :comment_id");
        $stmt->bindParam(':comment_id', $comment_id);
        $stmt->execute();

        // Redirect naar de hoofdpagina na verwijderen
        header("Location: main.php");
        exit();
    } catch (PDOException $e) {
        // Als er een fout optreedt bij het verwijderen, toon dan een foutmelding
        echo "Fout bij het verwijderen van de reactie: " . $e->getMessage();
    }
} else {
    echo "Geen comment_id opgegeven.";
}
?>
