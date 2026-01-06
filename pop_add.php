<div class="modal fade" id="addModal">
<div class="modal-dialog">
<div class="modal-content">

<form action="save_item.php" method="post">
<div class="modal-header">
<h5>Add Fire Extinguisher</h5>
</div>

<div class="modal-body">

Name:
<input class="form-control" name="name" required>

Type:
<select class="form-control" name="type">
<option>Water</option>
<option>Dry Powder</option>
<option>Foam</option>
<option>CO2</option>
<option>Wet Chemical</option>
</select>

Serial No:
<input class="form-control" name="serial_no">

Expired Date:
<input type="date" class="form-control" name="expired_date">

Date Checkup:
<input type="date" class="form-control" name="date_checkup">

Status:
<select class="form-control" name="status">
<option>Active</option>
<option>Expired</option>
</select>

</div>

<div class="modal-footer">
<button class="btn btn-primary">Save</button>
</div>

</form>

</div>
</div>
</div>
