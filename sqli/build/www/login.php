<?php
session_start();
if (isset($_SESSION['login'])) {
    header("Location: index.php");
    die();
}
?>

<!DOCTYPE html>
<html>
<meta charset="utf-8">
<title>login</title>

<head>
    <link href="//cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/login_and_reg.css" rel="stylesheet">
    <link href="css/panel.css" rel="stylesheet">
    <style>
        .input {
            margin-top: 2px;
        }
    </style>
</head>

<body class="text-center">
<form class="input-form" action="login.php" method="POST">
    <h1 class="h3 font-weight-normal">login</h1>
    <div class="input">
        <input type="text" name="username" class="form-control" placeholder="username" required autofocus>
    </div>
    <div>
        <input type="password" name="password" class="form-control" placeholder="password" required>
    </div>
    <div class="input">
        <button class="btn btn-lg btn-primary btn-block" type="submit">submit</button>
    </div>
    <p class="mt-5 text-muted">No account? <a href="register.php">Register now</a></p>
</form>
<div class="top" id="prompt_bar"></div>
</body>

<script src="//cdn.jsdelivr.net/npm/jquery@3.4.0/dist/jquery.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/prompt.js"></script>
</html>

<?php
include "class.php";

if (isset($_GET['register'])) {
    echo "<script>prompt_func('Register Successful', 'info');</script>";
}
if (isset($_POST["username"]) && isset($_POST["password"])) {
    $user = new User();
    $username = (string)$_POST["username"];
    $password = (string)$_POST["password"];
    if ($user->waf($username) && $user->waf($password) && $user->verify_user($username, $password)) {
        $_SESSION['login'] = true;
        echo("<script>window.location.href='index.php';</script>");
        die();
    }
    echo "<script>prompt_func('Wrong account or password', 'warning');</script>";
}
?>
