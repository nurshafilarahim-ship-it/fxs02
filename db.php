<?php
$conn = new mysqli("localhost", "root", "", "fire_db");
if ($conn->connect_error) {
    die("Connection failed");
}
session_start();
?>
