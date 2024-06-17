<?php
session_start();
require('dbconnect.php');


if (!empty($_POST)) {
    $error = array();

   
    if ($_POST['name'] == "") {
        $error['name'] = 'blank';
    }
    if ($_POST['email'] == "") {
        $error['email'] = 'blank';
    } else {
        $member = mysqli_prepare($db, 'SELECT COUNT(*) AS cnt FROM members WHERE email=?');
        mysqli_stmt_bind_param($member, 's', $_POST['email']);
        mysqli_stmt_execute($member);
        mysqli_stmt_bind_result($member, $cnt);
        mysqli_stmt_fetch($member);
        mysqli_stmt_close($member);
        if ($cnt > 0) {
            $error['email'] = 'duplicate';
        }
    }
    if ($_POST['password'] == "") {
        $error['password'] = 'blank';
    }
    if ($_POST['password2'] == "") {
        $error['password2'] = 'blank';
    }
    if (strlen($_POST['password']) < 6) {
        $error['password'] = 'length';
    }
    if ($_POST['password'] != $_POST['password2']) {
        $error['password2'] = 'difference';
    }

    if (empty($error)) {
        $_SESSION['join'] = $_POST;
        header('Location: confirm.php');
        exit();
    }
}


if (isset($_SESSION['join']) && isset($_REQUEST['action']) && ($_REQUEST['action'] == 'rewrite')) {
    $_POST = $_SESSION['join'];
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>註冊</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <link rel ="stylesheet" href= "style.css">
    <style>
        .error { color: red;font-size: 0.8em; }
    </style>
</head>
<body>
    <div class="container">
        <h1>註冊區</h1>
        <form action="" method="post" class="registrationform">
            <label>
                <span class="label-text">名字<span class="red">*</span></span>
                <input type="text" name="name" value="<?php echo htmlspecialchars($_POST['name'] ?? '', ENT_QUOTES); ?>">
                <?php if (isset($error['name']) && $error['name'] == "blank"): ?>
                <p class="error">請輸入你的名字</p>
                <?php endif; ?>
                <?php if (isset($error['name']) && $error['name'] == "duplicate"): ?>
                <p class="error">你輸入的名字已存在。</p>
                <?php endif; ?>
            </label>
            <br>
            <label>
                <span class="label-text">email<span class="red">*</span></span>
                <input type="text" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES); ?>">
                <?php if (isset($error['email']) && $error['email'] == "blank"): ?>
                <p class="error">請輸入你的電子信箱</p>
                <?php endif; ?>
                <?php if (isset($error['email']) && $error['email'] == "duplicate"): ?>
                <p class="error">你輸入的電子信箱已存在。</p>
                
                <?php endif; ?>
            </label>
            <br>
            <label>
                <span class="label-text">密碼<span class="red">*</span></span>
                <input type="password" name="password"  value="<?php echo htmlspecialchars($_POST['password'] ?? '', ENT_QUOTES); ?>">
                <?php if (isset($error['password']) && $error['password'] == "blank"): ?>
                <p class="error"> 請輸入任何密碼</p>
                <?php endif; ?>
                <?php if (isset($error['password']) && $error['password'] == "length"): ?>
                <p class="error"> 請輸入六個以上的密碼</p>
                <?php endif; ?>
            </label>
            <br>
            <label>
                <span class="label-text">再輸入密碼<span class="red">*</span></span>
                <input type="password" name="password2">
                <?php if (isset($error['password2']) && $error['password2'] == "blank"): ?>
                <p class="error"> 請輸入密碼</p>
                <?php endif; ?>
                <?php if (isset($error['password2']) && $error['password2'] == "difference"): ?>
                <p class="error"> 跟上面的密碼錯誤</p>
                <?php endif; ?>
            </label>
            <br>
            <input type="submit" value="確認" class="button">
        </form>
        <div style="text-align:center; margin-top:10px;">
            <a href="login.php">回到登入區</a>
        </div>
    </div>
</body>
</html>