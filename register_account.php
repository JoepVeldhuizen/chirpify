<?php
require "database/conn.php";


$stmt = $conn->prepare("SELECT COUNT(*) FROM accounts WHERE username = :username");
$stmt->bindParam(':username', $_POST['gebruikersnaam']);
$stmt->execute();
$count = $stmt->fetchColumn();

if ($count > 0) {
    header('Location: registratiefailed.php');;
    exit();
}

$hashed_password = password_hash($_POST['wachtwoord'], PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO accounts (username, password) VALUES (:username, :password)");
$stmt->bindParam(':username', $_POST['gebruikersnaam']);
$stmt->bindParam(':password', $hashed_password);
$stmt->execute();

header('Location: main.php');
?>
