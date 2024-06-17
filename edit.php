<?php
session_start();
require('dbconnect.php');

if (isset($_SESSION['id']) && isset($_REQUEST['id'])) {
    $id = $_REQUEST['id'];

    
    $posts = mysqli_prepare($db, 'SELECT created_by FROM posts WHERE id=?');
    mysqli_stmt_bind_param($posts, 'i', $id);
    mysqli_stmt_execute($posts);
    mysqli_stmt_bind_result($posts, $created_by);
    mysqli_stmt_fetch($posts);
    mysqli_stmt_close($posts);

    
    if ($created_by == $_SESSION['id']) {
        if (!empty($_POST["post"])) {
            $edit = mysqli_prepare($db, 'UPDATE posts SET post = ? , modified_time = NOW() WHERE id = ?');
            mysqli_stmt_bind_param($edit, 'si', $_POST["post"], $id);
            mysqli_stmt_execute($edit);
            mysqli_stmt_close($edit);
            header('Location: post.php'); 
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>編輯貼文</title>
    <link rel ="stylesheet" href= "style.css">
</head>

<header>
    <div class="head">
        <h1>編輯區</h1>
        
    </div>
</header>

<body>
    <div class="container">
    <span class="logout"><a href="post.php">回到</a></span>
    
    <form action="" method="post">
        <textarea name="post" cols="50" rows="10"></textarea><br>
        <input type="submit" value="改變" class="button">
    </form>
    </div>
</body>
</html>