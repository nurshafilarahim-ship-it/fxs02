<?php
// db.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$conn = new mysqli("localhost", "fire_user", "LnS050106LnS", "fire_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
