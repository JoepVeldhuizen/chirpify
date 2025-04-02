<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
    <title>Registratie Gefaald</title>
</head>
<body>
    <h2 class="registratieh2">Registratie Gefaald</h2>
    <button onclick="backtoregistratie()">Opnieuw proberen</button>

    <h3 class="registratieh3">Gebruikersnaam is al in gebruik</h3>

    <script>
        function backtoregistratie() {
            window.location.href = "registratie.php";
        }
    </script>
</body>
</html>