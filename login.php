<?php
include "db.php";
session_start();

// ----------------------------
// Store QR flag in session if user comes from QR
// ----------------------------
if (isset($_GET['from_qr']) && $_GET['from_qr'] == 1) {
    $_SESSION['from_qr'] = true;
}

// ----------------------------
// If already logged in, go to main
// ----------------------------
if (isset($_SESSION['user_id'])) {
    // Check if user came from QR
    if (isset($_SESSION['from_qr']) && $_SESSION['from_qr']) {
        // Clear QR flag
        unset($_SESSION['from_qr']);

        // Set QR session flag for next page
        $_SESSION['qr_logged_in'] = true;

        // Redirect to pump_add.php
        header("Location: pump_add.php");
        exit();
    } else {
        // Normal login
        header("Location: main.php");
        exit();
    }
}

$msg = "";

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $pass  = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, name, password, position FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {
        $row = $res->fetch_assoc();
        if (password_verify($pass, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user']    = $row['name'];
            $_SESSION['role']    = $row['position']; // store role

            // ----------------------------
            // QR attendance handling
            // ----------------------------
            $from_qr = $_SESSION['from_qr'] ?? false;

            if ($from_qr) {
                // Record attendance in pump_attendance table
                $stmt2 = $conn->prepare("INSERT INTO pump_attendance (user_id) VALUES (?)");
                if ($stmt2) {
                    $stmt2->bind_param("i", $row['id']);
                    $stmt2->execute();
                }

                // Clear QR flag
                unset($_SESSION['from_qr']);

                // Set session flag so pump_add knows user came from QR
                $_SESSION['qr_logged_in'] = true;

                // Redirect directly to pump_add.php
                echo "<script>
                    alert('Attendance done ✅');
                    window.location.href='pump_add.php';
                </script>";
                exit();
            }

            // ----------------------------
            // Normal login (non-QR)
            header("Location: main.php");
            exit;
        }
    }
    $msg = "Invalid email or password";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Login</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="theme_css.php">

<style>
/* ---- Your existing CSS ---- */
.auth-page { display: flex; justify-content: center; align-items: center; min-height: 100vh; }
.auth-page .card { padding: 25px; border-radius: 12px; background: #ffffff !important; box-shadow: 0 10px 30px rgba(0,0,0,0.3); width: 360px; color: #333333 !important; }
.auth-page .card h4 { color: #4169E1 !important; font-weight: 700; letter-spacing: -0.5px; }
.auth-page .card small, .auth-page .card .text-center { color: #64748b !important; font-weight: 500; }
.auth-page .card input, .auth-page .card select, .auth-page .card button { width: 100%; margin-bottom: 12px; padding: 10px; border-radius: 6px; border: 1px solid #cbd5e1; color: #333 !important; }
.auth-page .card a { color: #0d6efd !important; text-decoration: none; font-weight: bold; }
.auth-page .card a:hover { text-decoration: underline; }
</style>
</head>

<body>
<div class="auth-page">
    <div class="card">
        <h4 class="mb-3">Login</h4>

        <?php if($msg): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($msg) ?></div>
        <?php endif; ?>

        <form method="post">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login" class="btn btn-primary">Login</button>
        </form>

        <div class="mt-3 text-center">
            <small>No account? <a href="register.php">Register</a></small>
        </div>
    </div>
</div>
</body>
</html>
