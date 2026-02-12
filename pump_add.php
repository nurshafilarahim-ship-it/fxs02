<?php
session_start();
include 'db.php';

// ----------------------------
// Access control
// ----------------------------
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if user is normal role or QR logged in
$user_id = $_SESSION['user_id'];
$role = strtolower($_SESSION['role'] ?? '');
$from_qr = $_GET['from_qr'] ?? 0;

$allow_add = false;
if ($role === 'admin') {
    $allow_add = true;
} elseif ($role === 'user' && isset($_SESSION['qr_logged_in'])) {
    // QR logged-in users are allowed
    $allow_add = true;
}

// ----------------------------
// Handle form submission
// ----------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_item']) && $allow_add) {
    $item_name = $_POST['item_name'] ?? '';
    $checklist = $_POST['checklist'] ?? '';

    // Insert into pump_inspection table
    $stmt = $conn->prepare("INSERT INTO pump_inspection (item_name, checklist, created_by) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $item_name, $checklist, $user_id);
    $stmt->execute();

    // Optional: unset QR login flag after first add
    if (isset($_SESSION['qr_logged_in'])) {
        unset($_SESSION['qr_logged_in']);
    }

    header("Location: pump_inspection.php?success=1");
    exit();
}

// ----------------------------
// Deny access if not allowed
// ----------------------------
if (!$allow_add) {
    die("Access denied.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Pump Add</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h3>Add Pump Inspection Item</h3>

    <form method="post">
        <div class="mb-3">
            <label>Item Name</label>
            <input type="text" name="item_name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Checklist</label>
            <textarea name="checklist" class="form-control" required></textarea>
        </div>

        <button type="submit" name="add_item" class="btn btn-primary">Add Item</button>
    </form>
</div>
</body>
</html>
