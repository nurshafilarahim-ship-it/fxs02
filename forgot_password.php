<?php
include "db.php";

$msg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $new_password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($new_password !== $confirm_password) {
        $msg = "Passwords do not match!";
    } else {
        // Optional: hash the password for security
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update the password in the database
        $stmt = $conn->prepare("UPDATE users SET password=? WHERE email=?");
        $stmt->bind_param("ss", $hashed_password, $email);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $msg = "Your password has been reset successfully!";
        } else {
            $msg = "Email not found!";
        }

        $stmt->close();
    }
}
?>

<form method="post">
    <input type="email" name="email" placeholder="Your Email" required><br>
    <input type="password" name="password" placeholder="New Password" required><br>
    <input type="password" name="confirm_password" placeholder="Confirm Password" required><br>
    <button type="submit">Reset Password</button>
    <p><?= $msg ?></p>
</form>
