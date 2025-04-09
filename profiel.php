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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bio'])) {
    $bio = trim($_POST['bio']);
    $stmt = $conn->prepare("UPDATE accounts SET bio = :bio WHERE id = :user_id");
    $stmt->bindParam(':bio', $bio);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    header("Location: profiel.php"); 
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="profiel.css">
    <link rel="icon" type="image/x-icon" href="/images/icon.ico">
    <title>Profiel</title>
</head>
<body>

<div class="leftsquare"></div>

<button class="btnprofiel" onclick="window.location.href='main.php'">Home</button>
<button class="btnuitloggen" onclick="window.location.href='logout.php'">Uitloggen</button>

<img class="logo" src="logo.png">

<div class="profile-container">
    <h2>Profiel van <?php echo htmlspecialchars($user['username']); ?></h2>
    <p><strong>Gebruikersnaam:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
    
    <!-- Display bio -->
    <p><strong>Bio:</strong> <?php echo htmlspecialchars($user['bio'] ?? 'Geen bio beschikbaar.'); ?></p>

    <!-- Bio bewerken formulier -->
    <form action="profiel.php" method="POST">
        <textarea name="bio" rows="4" cols="50" placeholder="Schrijf je bio hier..."><?php echo htmlspecialchars($user['bio']); ?></textarea><br>
        <input type="submit" value="Bio bijwerken">
    </form>

    <a href="change_password.php" class="wachtwoord">Wachtwoord wijzigen</a>
</div>

</body>
</html>
