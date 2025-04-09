<?php
session_start();
require "database/conn.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stmt = $conn->prepare("SELECT id, password, is_admin FROM accounts WHERE username = :username");
    $stmt->bindParam(':username', $_POST['gebruikersnaam']);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || !password_verify($_POST['wachtwoord'], $user['password'])) {
        header('Location: loginfailed.php');
        exit();
    }

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $_POST['gebruikersnaam'];
    $_SESSION['is_admin'] = $user['is_admin'];

    header('Location: main.php');
    exit();
}
?>
