git remote set-url origin https://github.com/YOUR_USERNAME/YOUR_REPO.git
<?php
$conn = new mysqli("localhost", "root", "", "fire_db");
if ($conn->connect_error) {
    die("Connection failed");
}
session_start();
?>
