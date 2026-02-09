<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    die("Access denied.");
}

$id = $_GET['id'];

$stmt = $conn->prepare("DELETE FROM pump_inspection WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: pump_inspection.php");
exit();
