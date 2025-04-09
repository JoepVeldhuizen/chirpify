<?php
session_start();
require "database/conn.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: inloggen.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM accounts WHERE id = :user_id");
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "Gebruiker niet gevonden.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['current_password'], $_POST['new_password'], $_POST['confirm_password'])) {

    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    if ($new_password !== $confirm_password) {
        echo "De nieuwe wachtwoorden komen niet overeen.";
        exit();
    }

    if (!password_verify($current_password, $user['password'])) {
        echo "Het huidige wachtwoord is incorrect.";
        exit();
    }
    $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);

    try {
        $update_stmt = $conn->prepare("UPDATE accounts SET password = :password WHERE id = :user_id");
        $update_stmt->bindParam(':password', $hashed_new_password);
        $update_stmt->bindParam(':user_id', $user_id);

        if ($update_stmt->execute()) {
            echo "Wachtwoord succesvol gewijzigd!";
            header("Location: profiel.php");
            exit();
        } else {
            echo "Er is een fout opgetreden bij het bijwerken van het wachtwoord. Probeer het opnieuw.";
        }
    } catch (PDOException $e) {
        echo "Fout bij het bijwerken van het wachtwoord: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="profiel.css">
    <link rel="icon" type="image/x-icon" href="/images/icon.ico">
    <title>Wachtwoord wijzigen</title>
</head>
<body>

<div class="leftsquare"></div>

<button class="btnprofiel" onclick="window.location.href='profiel.php'">Terug naar profiel</button>
<button class="btnuitloggen" onclick="window.location.href='logout.php'">Uitloggen</button>

<img class="logo" src="logo.png">

<div class="profile-container">
    <h2>Wachtwoord wijzigen</h2>

    <form action="change_password.php" method="POST">
        <label for="current_password">Huidig wachtwoord:</label><br>
        <input type="password" id="current_password" name="current_password" required><br><br>

        <label for="new_password">Nieuw wachtwoord:</label><br>
        <input type="password" id="new_password" name="new_password" required><br><br>

        <label for="confirm_password">Bevestig nieuw wachtwoord:</label><br>
        <input type="password" id="confirm_password" name="confirm_password" required><br><br>

        <input type="submit" value="Wachtwoord bijwerken">
    </form>
</div>

</body>
</html>
