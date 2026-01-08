<?php
include "db.php";

$username = $_POST['username'];
$password = $_POST['password'];

if ($password === "admin123") {
    $_SESSION['user'] = $username;
    header("Location: main.php");
} else {
    echo "Wrong password!";
}
