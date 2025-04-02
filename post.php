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


    $stmt = $conn->prepare("INSERT INTO `post` (`user_id`, `username`, `post`, `date`) 
                            VALUES (:user_id, :username, :post, :date)");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':post', $post_content);
    $stmt->bindParam(':date', $date);

    if ($stmt->execute()) {
        
        header("Location: main.php");
        exit();
    } else {
  
        echo "Er is een fout opgetreden bij het posten.";
    }
}
?>
