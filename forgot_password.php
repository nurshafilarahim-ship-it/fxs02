<?php
include "db.php";
session_start();

$msg = "";
$success = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $new_pass = $_POST['password'];
    $confirm_pass = $_POST['confirm_password'];

    // 1. Check if passwords match
    if ($new_pass !== $confirm_pass) {
        $msg = "Passwords do not match!";
    } elseif (strlen($new_pass) < 6) {
        $msg = "Password must be at least 6 characters.";
    } else {
        // 2. Check if the email exists in your users table
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows > 0) {
            // 3. Hash the password so it works with your login.php
            $hashed_pass = password_hash($new_pass, PASSWORD_DEFAULT);

            // 4. Update the password directly
            $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
            $update_stmt->bind_param("ss", $hashed_pass, $email);
            
            if ($update_stmt->execute()) {
                $success = true; // This will trigger the JavaScript Alert below
            } else {
                $msg = "Database error. Please try again.";
            }
        } else {
            $msg = "That email is not registered in our system.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        body { background: #0a192f; color: #e6f1ff; font-family: "Segoe UI", sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .card { background: #0f172a; padding: 35px; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); width: 100%; max-width: 400px; text-align: center; }
        h2 { color: #38bdf8; margin-bottom: 25px; font-weight: 600; }
        input { background: #1e293b; color: #fff; border: 1px solid #334155; border-radius: 8px; padding: 12px; width: 100%; margin-bottom: 15px; outline: none; }
        input:focus { border-color: #38bdf8; }
        button { background: linear-gradient(135deg, #38bdf8, #2563eb); color: #fff; border: none; padding: 12px; border-radius: 8px; font-weight: 600; width: 100%; cursor: pointer; transition: 0.3s; }
        button:hover { opacity: 0.9; transform: translateY(-1px); }
        .msg { color: #facc15; margin-top: 15px; font-size: 0.9rem; }
        a { color: #38bdf8; text-decoration: none; font-size: 0.9rem; display: block; margin-top: 20px; }
    </style>

    <?php if ($success): ?>
    <script>
        // Redirect logic requested: Show OK button, then go to login
        alert("Password updated successfully! You can now login with your new password.");
        window.location.href = "login.php"; 
    </script>
    <?php endif; ?>
</head>
<body>

<div class="card">
    <h2>Reset Password</h2>
    <form method="post">
        <input type="email" name="email" placeholder="Enter Registered Email" required>
        <input type="password" name="password" placeholder="New Password" required minlength="6">
        <input type="password" name="confirm_password" placeholder="Confirm New Password" required>
        <button type="submit">Update Password</button>
    </form>

    <?php if ($msg): ?>
        <div class="msg"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>

    <a href="login.php">← Back to Login</a>
</div>

</body>
</html>