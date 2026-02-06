<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Pump House Inspection Form</title>
</head>
<body>
<h2>Pump House Inspection</h2>

<form method="post" action="#">
    <label>Check-up Date:</label><br>
    <input type="date" name="checkup_date" required><br><br>

    <label>Check-up Time:</label><br>
    <input type="time" name="checkup_time" required><br><br>

    <label>Checked By:</label><br>
    <input type="text" name="checked_by" required><br><br>

    <label>Item Name:</label><br>
    <input type="text" name="item_name" required><br><br>

    <h3>Checklist</h3>
    <input type="checkbox" name="check1"> Item 1 OK<br>
    <input type="checkbox" name="check2"> Item 2 OK<br>
    <input type="checkbox" name="check3"> Item 3 OK<br><br>

    <button type="submit">Submit Inspection</button>
</form>
</body>
</html>
