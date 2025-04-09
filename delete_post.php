<?php
session_start();
require "database/conn.php";

// Controleer of de gebruiker is ingelogd
if (!isset($_SESSION['user_id'])) {
    header("Location: inloggen.php");
    exit();
}

// Controleer of de post_id is ingesteld via POST
if (isset($_POST['post_id'])) {
    $post_id = $_POST['post_id'];

    // Controleer of de post bestaat en of de gebruiker eigenaar is van de post of een beheerder is
    $stmt = $conn->prepare("SELECT user_id FROM `post` WHERE id = :post_id");
    $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
    $stmt->execute();
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    // Als de post bestaat en de gebruiker eigenaar is of een beheerder is
    if ($post && ($post['user_id'] == $_SESSION['user_id'] || isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1)) {
        try {
            // Verwijder de post (de 'ON DELETE CASCADE' in de database zorgt ervoor dat de bijbehorende likes en comments ook worden verwijderd)
            $deleteStmt = $conn->prepare("DELETE FROM `post` WHERE id = :post_id");
            $deleteStmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
            $deleteStmt->execute();
            
            // Redirect naar de hoofdpagina na verwijderen
            header("Location: main.php");
            exit();
        } catch (PDOException $e) {
            // Als er een fout optreedt bij het verwijderen, toon dan een foutmelding
            echo "Fout bij het verwijderen van de post: " . $e->getMessage();
        }
    } else {
        echo "Je hebt geen toestemming om deze post te verwijderen.";
    }
} else {
    echo "Geen post_id opgegeven.";
}
?>
