<?php
session_start();
include 'db.php';

// Security checks
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['pump_id'])) {
    die("Pump ID missing.");
}

$pump_id = intval($_GET['pump_id']);
$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Handle Add Inspection
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $item = trim($_POST['item_name']);
    $check = $_POST['checklist'];

    $stmt = $conn->prepare("
        INSERT INTO pump_inspection (pump_id, item_name, checklist, created_by)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->bind_param("issi", $pump_id, $item, $check, $user_id);
    $stmt->execute();

    echo "<script>alert('Inspection record added successfully'); window.location.href='pump_inspection.php?pump_id=$pump_id';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pump House Inspection</title>
    <style>
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .btn { padding: 6px 12px; text-decoration: none; border-radius: 4px; }
        .btn-edit { background: #ffc107; color: #000; }
        .btn-delete { background: #dc3545; color: #fff; }
        .btn-export { background: #198754; color: #fff; display: inline-block; margin-top: 10px; }
    </style>
</head>
<body>

<h2>Pump House Inspection</h2>

<!-- Add Inspection Form -->
<form method="post">
    <input type="text" name="item_name" placeholder="Item Name" required>
    <select name="checklist" required>
        <option value="">-- Select Status --</option>
        <option value="Done">Done</option>
        <option value="Not Done">Not Done</option>
    </select>
    <button type="submit">Submit</button>
</form>

<!-- Inspection Records Table -->
<table>
    <tr>
        <th>Item Name</th>
        <th>Status</th>
        <th>Date / Time</th>
        <th>Created By</th>
        <th>Edited Info</th>
        <?php if ($role === 'admin'): ?>
            <th>Action</th>
        <?php endif; ?>
    </tr>

<?php
$sql = "
SELECT pi.*, 
       u.username AS created_name,
       a.username AS edited_name
FROM pump_inspection pi
JOIN users u ON pi.created_by = u.id
LEFT JOIN users a ON pi.edited_by = a.id
WHERE pi.pump_id = ?
ORDER BY pi.created_at DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $pump_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
            <td>" . htmlspecialchars($row['item_name']) . "</td>
            <td>" . htmlspecialchars($row['checklist']) . "</td>
            <td>" . htmlspecialchars($row['created_at']) . "</td>
            <td>" . htmlspecialchars($row['created_name']) . "</td>
            <td>";

        if (!empty($row['edited_by'])) {
            echo "Edited by " . htmlspecialchars($row['edited_name']) . " on " . htmlspecialchars($row['edited_at']);
        } else {
            echo "-";
        }

        echo "</td>";

        if ($role === 'admin') {
            echo "<td>
                <a class='btn btn-edit' href='edit_inspection.php?id={$row['id']}'>Edit</a>
                <a class='btn btn-delete' href='delete_inspection.php?id={$row['id']}' onclick=\"return confirm('Are you sure you want to delete this record?');\">Delete</a>
            </td>";
        }

        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='6'>No inspection records found.</td></tr>";
}
?>
</table>

<?php if ($role === 'admin'): ?>
    <a class="btn btn-export" href="export_excel.php?pump_id=<?= $pump_id ?>">Export to Excel</a>
<?php endif; ?>

</body>
</html>
