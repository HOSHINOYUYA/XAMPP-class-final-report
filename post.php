<?php
session_start();
require('dbconnect.php');

if (isset($_SESSION['id']) && ($_SESSION['time'] + 3600 > time())) {
    $_SESSION['time'] = time();

    $members = mysqli_prepare($db, 'SELECT * FROM members WHERE id=?');
    mysqli_stmt_bind_param($members, 'i', $_SESSION['id']);
    mysqli_stmt_execute($members);
    $result = mysqli_stmt_get_result($members);
    $member = mysqli_fetch_assoc($result);
    mysqli_stmt_close($members);
} else {
    header('Location: login.php');
    exit();
}

if (!empty($_POST)) {
    if (isset($_POST['token']) && $_POST['token'] === $_SESSION['token']) {
        $post = mysqli_prepare($db, 'INSERT INTO posts (created_by, post, created_time) VALUES (?, ?, NOW())');
        mysqli_stmt_bind_param($post, 'is', $member['id'], $_POST['post']);
        mysqli_stmt_execute($post);
        mysqli_stmt_close($post);
        header('Location: post.php');
        exit();
    } else {
        header('Location: login.php');
        exit();
    }
}

$posts = mysqli_query($db, 'SELECT m.name, p.* FROM members m JOIN posts p ON m.id=p.created_by ORDER BY p.created_time DESC');

$TOKEN_LENGTH = 16;
$tokenByte = openssl_random_pseudo_bytes($TOKEN_LENGTH);
$token = bin2hex($tokenByte);
$_SESSION['token'] = $token;

?>

<!DOCTYPE html>
<html>
<head>
    <title>發布貼文</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    
</head>
<body>
    <div class="container">
      
        <header>
            <div class="head">
            <h1>發布貼文區</h1>
            </div>
        </header>

        <form action='' method="post">
            <input type="hidden" name="token" value="<?=$token?>">
            <?php if (isset($error['login']) && ($error['login'] =='token')): ?>
                <p class="error">非法訪問</p>
            <?php endif; ?>
            <span class="logout"><a href="login.php">登出</a></span>
            <div class="edit">
                <p>
                    <?php echo htmlspecialchars($member['name'], ENT_QUOTES); ?>先生、小姐,歡迎來 
                </p>
                <textarea name="post" cols='50' rows='10'></textarea>
            </div>

            <input type="submit" value="發布貼文" class="button02">
        </form>

        <?php while ($post = mysqli_fetch_assoc($posts)): ?>
        <div class="post" style="margin-top:10px;">
            <?php echo htmlspecialchars($post['post'], ENT_QUOTES); ?> | 
            <span class="name">
                <?php echo htmlspecialchars($post['name'], ENT_QUOTES); ?> | 
                <?php echo htmlspecialchars($post['created_time'], ENT_QUOTES); ?> | 

               
                <?php if($_SESSION['id'] == $post['created_by']): ?>
                [<a href="delete.php?id=<?php echo htmlspecialchars($post['id'], ENT_QUOTES); ?>">刪除</a>]
				[<a href="edit.php?id=<?php echo htmlspecialchars($post['id'], ENT_QUOTES); ?>">編輯</a>]
                <?php endif; ?>
                
            </span>
        </div>
        <?php endwhile; ?>
    </div>
</body>
</html>