<?php
include "db.php";

$msg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $token = md5(rand());

    $conn->query("UPDATE users SET password='$token' WHERE email='$email'");

    mail($email, "Password Reset",
        "Your temporary password: $token");

    $msg = "Check your email for reset password";
}
?>

<form method="post">
<input name="email" placeholder="Your Email" required>
<button>Reset</button>
<p><?= $msg ?></p>
</form>
