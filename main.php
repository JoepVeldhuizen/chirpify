<?php
session_start();
require "database/conn.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: inloggen.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Haal alle posts op uit de database, samen met het aantal likes en de gebruikers die geliket hebben
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
    <link rel="icon" type="image/x-icon" href="/images/icon.ico">
    <title>Main Page</title>
</head>
<body>
<div class="leftsquare"></div>

<button class="btnprofiel" onclick="window.location.href='profiel.php'">Profiel</button>
<button class="btnuitloggen" onclick="window.location.href='logout.php'">Uitloggen</button>

<img class="logo" src="logo.png">

<form class="postform" action="post.php" method="post" enctype="multipart/form-data">
    <label for="post">Post</label>
    <input type="text" name="post" id="post" placeholder="Schrijf hier wat u wilt posten..." required>
    
    <label for="image">Afbeelding (optioneel)</label>
    <input class="imgupload" type="file" name="image" id="image" accept="image/*">

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

            <?php if (!empty($post['image_blob'])): ?>
                <?php
                    $finfo = new finfo(FILEINFO_MIME_TYPE);
                    $mimeType = $finfo->buffer($post['image_blob']);
                ?>
                <div class="post-image">
                    <img src="data:<?php echo $mimeType; ?>;base64,<?php echo base64_encode($post['image_blob']); ?>" 
                         alt="Afbeelding bij post">
                </div>
            <?php endif; ?>

            <div class="post-likes">
                <?php
                if (!empty($post['liked_by'])) {
                    echo "Geliked door: " . htmlspecialchars($post['liked_by']);
                } else {
                    echo "Nog geen likes.";
                }
                ?>
            </div>

            <div class="comment-form-container" id="comment-form-container-<?php echo $post['id']; ?>" style="display: none;">
                <form action="post_comment.php" method="post">
                    <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                    <textarea name="comment" placeholder="Schrijf hier je reactie..." required></textarea>
                    <input type="submit" value="Plaats reactie">
                </form>
            </div>

            <div class="post-actions">
                <button class="like-btn" onclick="likePost(<?php echo $post['id']; ?>)">
                    ❤️ Like (<span id="like-count-<?php echo $post['id']; ?>"><?php echo $post['like_count']; ?></span>)
                </button>

                <button class="comment-btn" onclick="toggleCommentForm(<?php echo $post['id']; ?>)">💬 Commentaar</button>

                <?php if ($_SESSION['user_id'] == $post['user_id'] || $_SESSION['is_admin'] == 1): ?>
                    <form action="delete_post.php" method="post" style="display: inline;">
                        <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                        <input type="submit" class="delete-btn" value="Verwijderen">
                    </form>
                <?php endif; ?>
            </div>

            <div class="comments-container">
                <?php
                $stmt_comments = $conn->prepare("SELECT comments.*, accounts.username 
                                                FROM comments
                                                JOIN accounts ON comments.user_id = accounts.id
                                                WHERE comments.post_id = :post_id
                                                ORDER BY comments.created_at ASC");
                $stmt_comments->bindParam(':post_id', $post['id']);
                $stmt_comments->execute();
                $comments = $stmt_comments->fetchAll(PDO::FETCH_ASSOC);

                foreach ($comments as $comment):
                ?>
                    <div class="comment">
                        <span class="comment-username"><?php echo htmlspecialchars($comment['username']); ?>:</span>
                        <p class="comment-text"><?php echo nl2br(htmlspecialchars($comment['comment'])); ?></p>

                        <?php if ($_SESSION['user_id'] == $comment['user_id'] || $_SESSION['is_admin'] == 1): ?>
                            <form action="delete_comment.php" method="POST" style="display: inline;">
                                <input type="hidden" name="comment_id" value="<?php echo $comment['id']; ?>">
                                <input type="submit" value="Verwijder" class="delete-btn">
                            </form>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<script>
    function toggleCommentForm(postId) {
        var formContainer = document.getElementById("comment-form-container-" + postId);
        formContainer.style.display = (formContainer.style.display === "none" || formContainer.style.display === "") ? "block" : "none";
    }

    function likePost(postId) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "like.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange =  ()  => {
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
