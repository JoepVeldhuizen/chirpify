<?php
session_start();
require "database/conn.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stmt = $conn->prepare("SELECT password FROM accounts WHERE username = :username");
    $stmt->bindParam(':username', $_POST['gebruikersnaam']);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        // Als de gebruiker niet bestaat, stuur door naar loginfailed.php en reset de velden
        header('Location: loginfailed.php');
        exit();
    }

    if (!password_verify($_POST['wachtwoord'], $user['password'])) {
        // Als het wachtwoord onjuist is, stuur door naar loginfailed.php en reset de velden
        header('Location: loginfailed.php');
        exit();
    }

    // Login geslaagd, start de sessie en stuur door naar main.php
    $_SESSION['user_id'] = $user['id']; // Gebruik het juiste ID voor sessie
    $_SESSION['username'] = $_POST['gebruikersnaam'];
    header('Location: main.php');
    exit();
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inloggen</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fira+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="main.css">
</head>
<body>

<form action="login.php" method="post" id="loginForm">
    <label for="gebruikersnaam">Your Username</label>
    <input type="text" name="gebruikersnaam" id="gebruikersnaam" placeholder="Username" required>
    <label for="wachtwoord">Your Password</label>
    <input type="password" name="wachtwoord" id="wachtwoord" placeholder="Password" required>
    <input type="submit" value="Login">
</form>

<a href="registratie.php">Registreren</a>

<div class="line"></div>

<img class="logo" src="logo.png">

<script>
    // Voeg deze script toe om de formulier velden te resetten na een mislukte login
    const form = document.getElementById('loginForm');

    // Controleer of er een parameter is doorgegeven in de URL (login failed)
    const urlParams = new URLSearchParams(window.location.search);
    const loginFailed = urlParams.has('loginfailed');
    if (loginFailed) {
        // Als de login mislukt is, reset de velden
        form.reset();
        alert("Login failed, please try again.");
    }
</script>

</body>
</html>
