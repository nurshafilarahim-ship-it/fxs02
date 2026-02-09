<?php
session_start();
include 'db.php';

if ($_SESSION['role'] != 'admin') {
    die("Access denied");
}

$pump_id = $_GET['pump_id'];

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=pump_inspection.xls");

echo "Item\tStatus\tDate\tCreated By\n";

$sql = "SELECT pi.*, u.username FROM pump_inspection pi
        JOIN users u ON pi.created_by = u.id
        WHERE pump_id = ?
        ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $pump_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    echo "{$row['item_name']}\t{$row['checklist']}\t{$row['created_at']}\t{$row['username']}\n";
}
