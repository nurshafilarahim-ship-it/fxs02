<?php
session_start();
include "db.php";

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $pass  = $_POST['password'];

    $res = $conn->query("SELECT * FROM users WHERE email='$email'");
    $user = $res->fetch_assoc();

    if ($user && password_verify($pass, $user['password'])) {
        $_SESSION['user'] = $user['name'];
        $_SESSION['email'] = $user['email'];
        header("Location: main.php");
        exit;
    } else {
        $error = "Invalid login";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Login</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="d-flex justify-content-center align-items-center vh-100 bg-light">

<form method="post" class="p-4 bg-white shadow rounded" style="width:350px">
<h4 class="text-center mb-3">LOGIN</h4>

<input class="form-control mb-2" name="email" type="email" placeholder="Email" required>
<input class="form-control mb-2" name="password" type="password" placeholder="Password" required>

<div class="text-danger mb-2"><?= $error ?></div>

<button class="btn btn-success w-100">Login</button>

<p class="text-center mt-3">
<a href="forgot_password.php">Forgot password?</a>
</p>
</form>

</body>
</html>
