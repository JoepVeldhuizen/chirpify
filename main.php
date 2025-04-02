<?php
session_start();
require "database/conn.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: inloggen.php");
    exit();
}


$stmt = $conn->prepare("SELECT * FROM `post` ORDER BY `date` DESC");
$stmt->execute();
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="mainpage.css">
    <title>Main Page</title>
</head>
<body>
<div class="leftsquare"></div>

<button class="btnprofiel" onclick="Profile()">Profiel</button>

<button class="btnuitloggen" onclick="window.location.href='logout.php'">Uitloggen</button>

<img class="logo" src="logo.png">


<form class="postform" action="post.php" method="post">
    <label for="post">Post</label>
    <input type="text" name="post" id="post" placeholder="Schrijf hier wat u wilt posten..." required>
    <input type="submit" value="Post">
</form>

<div class="posts-container">
    <?php foreach ($posts as $post): ?>
        <div class="post">
            <div class="post-header">
                <span class="post-username"><?php echo htmlspecialchars($post['username']); ?></span>
                <span class="post-date"><?php echo $post['date']; ?></span>
            </div>
            <div class="post-content">
                <?php echo nl2br(htmlspecialchars($post['post'])); ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>


</body>
</html>
