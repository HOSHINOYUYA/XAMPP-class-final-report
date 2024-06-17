<?php
session_start();
require('dbconnect.php');


if (!isset($_SESSION['join'])) {
    header('Location: register.php');
    exit();
}


$hash = password_hash($_SESSION['join']['password'], PASSWORD_BCRYPT);


if (!empty($_POST)) {
    $statement = mysqli_prepare($db, 'INSERT INTO members (name, email, password, created_time) VALUES (?, ?, ?, NOW())');
    mysqli_stmt_bind_param($statement, 'sss', $_SESSION['join']['name'], $_SESSION['join']['email'], $hash);
    mysqli_stmt_execute($statement);
    unset($_SESSION['join']);
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <title>註冊確認區</title>
</head>
<body>
    <h1>註冊確認區</h1>
    <form action="" method="post">

        <input type="hidden" name="action" value="submit">
        <p>
            名字
            <span class="check"><?php echo htmlspecialchars($_SESSION['join']['name'], ENT_QUOTES); ?></span>
        </p>
        <p>
            email
            <span class="check"><?php echo htmlspecialchars($_SESSION['join']['email'], ENT_QUOTES); ?></span>
        </p>
        <p>
            密碼
            <span class="check">[為了安全不會顯示]</span>
        </p>

     
        <input type="button" onclick="location.href='register.php?action=rewrite'" value="修改" name="rewrite" class="button02">
        <input type="submit" value="註冊" name="registration" class="button">
    </form>
</body>
</html>