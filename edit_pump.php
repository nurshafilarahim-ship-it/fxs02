<?php
session_start();
include 'db.php';

if ($_SESSION['role'] != 'admin') {
    die("Access denied");
}

$id = $_GET['id'];
$admin_id = $_SESSION['user_id'];

// Fetch existing data
$stmt = $conn->prepare("SELECT * FROM pump_inspection WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) {
    die("Record not found");
}

// Update data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $item = $_POST['item_name'];
    $check = $_POST['checklist'];

    $stmt = $conn->prepare("
        UPDATE pump_inspection 
        SET item_name = ?, checklist = ?, edited_by = ?, edited_at = NOW()
        WHERE id = ?
    ");
    $stmt->bind_param("ssii", $item, $check, $admin_id, $id);
    $stmt->execute();

    echo "<script>alert('Inspection record updated'); window.location.href='pump_inspection.php?pump_id={$data['pump_id']}';</script>";
}
?>

<h2>Edit Inspection</h2>

<form method="post">
  <input type="text" name="item_name" value="<?= htmlspecialchars($data['item_name']) ?>" required>
  <select name="checklist" required>
    <option value="Done" <?= $data['checklist'] == 'Done' ? 'selected' : '' ?>>Done</option>
    <option value="Not Done" <?= $data['checklist'] == 'Not Done' ? 'selected' : '' ?>>Not Done</option>
  </select>
  <button type="submit">Update</button>
</form>
