<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    die("Access denied.");
}

$id = $_GET['id'];
$admin_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM pump_inspection WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

if (!$data) {
    die("Record not found.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $item = trim($_POST['item_name']);
    $check = $_POST['checklist'];

    $stmt = $conn->prepare("
        UPDATE pump_inspection
        SET item_name = ?, checklist = ?, edited_by = ?, edited_at = NOW()
        WHERE id = ?
    ");
    $stmt->bind_param("ssii", $item, $check, $admin_id, $id);
    $stmt->execute();

    header("Location: pump_inspection.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Inspection</title>
</head>
<body>

<h2>Edit Inspection</h2>

<form method="post">
    <input type="text" name="item_name" value="<?= htmlspecialchars($data['item_name']) ?>" required>
    <select name="checklist" required>
        <option value="Done" <?= $data['checklist'] == 'Done' ? 'selected' : '' ?>>Done</option>
        <option value="Not Done" <?= $data['checklist'] == 'Not Done' ? 'selected' : '' ?>>Not Done</option>
    </select>
    <button type="submit">Update</button>
</form>

</body>
</html>
