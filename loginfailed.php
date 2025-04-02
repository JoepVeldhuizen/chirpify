<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
    <title>Login Gefaald</title>
</head>
<body>
    <h2>Login Gefaald</h2>
    <button onclick="backtologin()">Opnieuw proberen</button>

    <h3>Gebruikersnaam of wachtwoord is onjuist</h3>

    <script>
        function backtologin() {
            window.location.href = "inloggen.php";
        }
    </script>
</body>
</html>