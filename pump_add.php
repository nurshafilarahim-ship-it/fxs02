<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    die("Access denied.");
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

// For users, we check if they come from QR
$from_qr = $_GET['from_qr'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $item = trim($_POST['item_name']);
    $check = $_POST['checklist'];

    // Only allow adding:
    // Admin can always add
    // User can add only from QR
    if ($role === 'admin' || ($role === 'user' && $from_qr == 1)) {
        $stmt = $conn->prepare("
            INSERT INTO pump_inspection (item_name, checklist, created_by)
            VALUES (?, ?, ?)
        ");
        $stmt->bind_param("ssi", $item, $check, $user_id);
        $stmt->execute();

        echo "<script>alert('Inspection submitted successfully'); window.location.href='pump_inspection.php';</script>";
        exit();
    } else {
        die("You are not allowed to add here.");
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Pump House Inspection - Add</title>
</head>
<body>

<h2>Pump House Inspection</h2>

<?php
// Only show form if admin OR user from QR
if ($role === 'admin' || ($role === 'user' && $from_qr == 1)):
?>
<form method="post">
    <input type="text" name="item_name" placeholder="Item Name" required>
    <select name="checklist" required>
        <option value="">-- Select Status --</option>
        <option value="Done">Done</option>
        <option value="Not Done">Not Done</option>
    </select>
    <button type="submit">Submit</button>
</form>
<?php else: ?>
<p>You cannot add inspection here. Users must scan the QR at the pump house to add.</p>
<?php endif; ?>

</body>
</html>
