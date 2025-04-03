<?php
session_start();
require "database/conn.php";

#check of gebruiker is ingelogd
if (!isset($_SESSION['user_id'])) {
    header("Location: inloggen.php");
    exit();
}

$user_id = $_SESSION['user_id'];

#posts ophalen uit de database
$stmt = $conn->prepare("
    SELECT post.*, 
           (SELECT COUNT(*) FROM likes WHERE likes.post_id = post.id) AS like_count,
           (SELECT GROUP_CONCAT(accounts.username SEPARATOR ', ') 
            FROM likes 
            JOIN accounts ON likes.user_id = accounts.id 
            WHERE likes.post_id = post.id) AS liked_by
    FROM post 
    ORDER BY post.date DESC
");
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

<!-- post form -->
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
            <div class="post-actions">
                <button class="like-btn" onclick="likePost(<?php echo $post['id']; ?>)">
                    ❤️ Like (<span id="like-count-<?php echo $post['id']; ?>"><?php echo $post['like_count']; ?></span>)
                </button>

                <?php if ($_SESSION['user_id'] == $post['user_id']): ?>
                    <form action="delete_post.php" method="post" style="display: inline;">
                        <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                        <input type="submit" class="delete-btn" value="Verwijderen">
                    </form>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<script>

//like functie

    function likePost(postId) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "like.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                document.getElementById("like-count-" + postId).innerText = response.like_count;
            }
        };
        xhr.send("post_id=" + postId);
    }
</script>

</body>
</html>
