<?php
include "auth.php";
include "db.php";
$result = $conn->query("SELECT * FROM extinguisher");
?>
<!DOCTYPE html>
<html>
<head>
<title>View All</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

<h3>View All Fire Extinguishers</h3>

<table class="table table-bordered text-center">
<tr>
<th>Name</th><th>Type</th><th>Serial</th><th>Location</th><th>Action</th>
</tr>

<?php while($row=$result->fetch_assoc()): ?>
<tr>
<td><?=htmlspecialchars($row['name'])?></td>
<td><?=htmlspecialchars($row['type'])?></td>
<td><?=htmlspecialchars($row['serial_no'])?></td>
<td><?=htmlspecialchars($row['location'])?></td>
<td>
<a href="view.php?id=<?=$row['id']?>" class="btn btn-success btn-sm">View / Print</a>
</td>
</tr>
<?php endwhile; ?>

</table>
</body>
</html>
