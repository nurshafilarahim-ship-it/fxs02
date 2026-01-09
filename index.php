<?php
include "db.php";

$msg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name     = $_POST['name'];
    $email    = $_POST['email'];
    $position = $_POST['position'];
    $pass1    = $_POST['password'];
    $pass2    = $_POST['confirm_password'];

    if ($pass1 != $pass2) {
        $msg = "Passwords do not match";
    } else {
        $hash = password_hash($pass1, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (name,email,password,position)
                VALUES ('$name','$email','$hash','$position')";

        if ($conn->query($sql)) {
            header("Location: login.php");
            exit;
        } else {
            $msg = "Email already registered";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Register</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="d-flex justify-content-center align-items-center vh-100 bg-light">

<form method="post" class="p-4 bg-white shadow rounded" style="width:350px">
<h4 class="text-center mb-3">SIGN UP</h4>

<input class="form-control mb-2" name="name" placeholder="Name" required>
<input class="form-control mb-2" name="email" type="email" placeholder="Email" required>
<input class="form-control mb-2" name="position" placeholder="Position" required>
<input class="form-control mb-2" name="password" type="password" placeholder="Password" required>
<input class="form-control mb-2" name="confirm_password" type="password" placeholder="Confirm Password" required>

<div class="text-danger mb-2"><?= $msg ?></div>

<button class="btn btn-primary w-100">Create Account</button>

<p class="text-center mt-3">
Already have an account?
<a href="login.php">Login here</a>
</p>
</form>

</body>
</html>
