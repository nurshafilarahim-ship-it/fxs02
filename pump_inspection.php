<?php
session_start();
include 'db.php';

$pump_id = $_GET['pump_id'];
$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $item = $_POST['item_name'];
    $check = $_POST['checklist'];

    $stmt = $conn->prepare("INSERT INTO pump_inspection (pump_id, item_name, checklist, created_by) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("issi", $pump_id, $item, $check, $user_id);
    $stmt->execute();
}
?>

<h2>Pump House Inspection</h2>

<form method="post">
  <input type="text" name="item_name" placeholder="Item Name" required>
  <select name="checklist" required>
    <option value="Done">Done</option>
    <option value="Not Done">Not Done</option>
  </select>
  <button type="submit">Submit</button>
</form>

<h3>Inspection Records</h3>
<table border="1">
<tr>
  <th>Item</th>
  <th>Status</th>
  <th>Date</th>
  <th>Created By</th>
  <?php if ($role == 'admin') echo "<th>Action</th>"; ?>
</tr>

<?php
$sql = "SELECT pi.*, u.username FROM pump_inspection pi
        JOIN users u ON pi.created_by = u.id
        WHERE pump_id = ?
        ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $pump_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    echo "<tr>
        <td>{$row['item_name']}</td>
        <td>{$row['checklist']}</td>
        <td>{$row['created_at']}</td>
        <td>{$row['username']}</td>";
    
    if ($role == 'admin') {
        echo "<td>
            <a href='edit_inspection.php?id={$row['id']}'>Edit</a> |
            <a href='delete_inspection.php?id={$row['id']}'>Delete</a>
        </td>";
    }
    echo "</tr>";
}
?>
</table>

<?php if ($role == 'admin'): ?>
<a href="export_excel.php?pump_id=<?= $pump_id ?>">Export to Excel</a>
<?php endif; ?>
