<?php
$conn = new mysqli("localhost", "fire_user", "LnS050106LnS", "fire_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
session_start();
?>

