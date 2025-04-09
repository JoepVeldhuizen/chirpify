<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registreren</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fira+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="main.css">
    <link rel="icon" type="image/x-icon" href="/images/icon.ico">
</head>
<body>

<form action="register_account.php" method="post" autocomplete="off">
    <label for="gebruikersnaam">Uw gebruikersnaam</label>
    <input type="text" name="gebruikersnaam" id="gebruikersnaam" placeholder="Username">
    <label for="wachtwoord">Uw wachtwoord</label>
    <input type="password" name="wachtwoord" id="wachtwoord" placeholder="Password">
    <input type="submit" value="Register">
</form>

<a href="inloggen.php">Inloggen</a>

<div class="line"></div>

<img class="logo" src="logo.png">



</body>
</html>