<?php
session_start();
require('dbconnect.php');


if (!empty($_POST)) {
    if (($_POST['email'] != '') && ($_POST['password'] != '')) {
        
        $login = mysqli_prepare($db, 'SELECT id, email, password FROM members WHERE email=?');       
        mysqli_stmt_bind_param($login, 's', $_POST['email']);       
        mysqli_stmt_execute($login);       
        mysqli_stmt_bind_result($login, $id, $email, $hashed_password);        
        mysqli_stmt_fetch($login);       
        mysqli_stmt_close($login);

       
        if ($email != false && password_verify($_POST['password'], $hashed_password)) {
            $_SESSION['id'] = $id;
            $_SESSION['time'] = time();
            header('Location: post.php');
            exit();
        } else {
            $error['login'] = 'failed';
        } 
    } else {
        $error['login'] = 'blank';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>登入區</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel ="stylesheet" href= "style.css">
    <style>
        .error { color: red; font-size: 0.8em; }
    </style>
</head>
<body>
    <div class="container">
    <h1>登入區</h1>
    <form action='' method="post">

        <label>
            電子信箱
            <input type="text" name="email" style="width:150px" 
            value="<?php echo htmlspecialchars($_POST['email'] ?? "", ENT_QUOTES); ?>">
            <?php if (isset($error['login']) && ($error['login'] == 'blank')): ?>
            <p class="error">請輸入密碼</p>
            <?php endif; ?>

            <?php if (isset($error['login']) && $error['login'] == 'failed'): ?>
            <p class="error">你輸入的電子信箱或密碼錯誤</p>
            <?php endif; ?>
        </label>
        <br />
        <label>
            密碼
            <input type="password" name="password" style="width:150px" 
            value="<?php echo htmlspecialchars($_POST['password'] ?? "", ENT_QUOTES); ?>">
        </label>

        
            <input type="submit" value="登入" class="button">
        
    </form>
    <div style="text-align:center; margin-top:10px;">
    <a href="register.php">註冊</a>
    </div>
    </div>

</body>
</html>