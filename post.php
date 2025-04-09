<?php
session_start();
require "database/conn.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: inloggen.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['post']) && !empty(trim($_POST['post']))) {
    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'];
    $post_content = trim($_POST['post']);
    $date = date('Y-m-d H:i:s');

    $imageData = null;

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageTmpPath = $_FILES['image']['tmp_name'];
        $imageSize = $_FILES['image']['size'];
        $imageType = mime_content_type($imageTmpPath);

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

        if (in_array($imageType, $allowedTypes)) {
            if ($imageSize <= 5 * 1024 * 1024) {
                $imageData = file_get_contents($imageTmpPath);
            } else {
                echo "Afbeelding is te groot. Maximaal 5MB toegestaan.";
                exit();
            }
        } else {
            echo "Ongeldig afbeeldingsformaat. Alleen JPG, PNG en GIF zijn toegestaan.";
            exit();
        }
    }

    $stmt = $conn->prepare("INSERT INTO `post` (`user_id`, `username`, `post`, `date`, `image_blob`) 
                            VALUES (:user_id, :username, :post, :date, :image_blob)");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':post', $post_content);
    $stmt->bindParam(':date', $date);
    $stmt->bindParam(':image_blob', $imageData, PDO::PARAM_LOB);

    if ($stmt->execute()) {
        header("Location: main.php");
        exit();
    } else {
        echo "Er is een fout opgetreden bij het opslaan van de post.";
    }
} else {
    echo "Ongeldige invoer.";
}
?>
