<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Fire Extinguisher Modal</title>

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
/* Hover color for dropdown items */
.dropdown-menu .dropdown-item:hover {
  background-color: var(--hover-color);
  color: black !important;
}
</style>
</head>
<body>

<!-- Button to open modal -->
<button class="btn btn-primary mt-3 ms-3" data-bs-toggle="modal" data-bs-target="#addModal">
  Add Fire Extinguisher
</button>

<!-- Modal -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="save_item.php" method="post">
        <div class="modal-header">
          <h5 class="modal-title" id="addModalLabel">Add Fire Extinguisher</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">

          <label>Name:</label>
          <input class="form-control mb-2" name="name" required>

          <label>Type:</label>
          <div class="dropdown mb-2">
            <button class="btn btn-light dropdown-toggle w-100" type="button" id="typeDropdown" data-bs-toggle="dropdown" aria-expanded="false">
              Select Type
            </button>
            <ul class="dropdown-menu w-100" aria-labelledby="typeDropdown">
              <li><a class="dropdown-item" href="#" data-value="Water" style="--hover-color: #ff4d4d;">Water Extinguishers: Class A (🔥) - Red label</a></li>
              <li><a class="dropdown-item" href="#" data-value="Foam" style="--hover-color: #fff0b3;">Foam Extinguishers: Class A & B (🔥💧) - Cream label</a></li>
              <li><a class="dropdown-item" href="#" data-value="Dry Powder" style="--hover-color: #4da6ff;">Dry Powder Extinguishers: Class A, B & C (🔥💧⚡) - Blue label</a></li>
              <li><a class="dropdown-item text-white" href="#" data-value="CO2" style="--hover-color: #333333;">CO2 Extinguishers: Class B & C (💧⚡) - Black label</a></li>
              <li><a class="dropdown-item" href="#" data-value="Wet Chemical" style="--hover-color: #ffff66;">Wet Chemical Extinguishers: Class K (🍳) - Yellow label</a></li>
            </ul>
            <input type="hidden" name="type" id="typeInput">
          </div>

          <label>Serial No:</label>
          <input class="form-control mb-2" name="serial_no">

          <label>Expired Date:</label>
          <input type="date" class="form-control mb-2" name="expired_date">

          <label>Date Checkup:</label>
          <input type="date" class="form-control mb-2" name="date_checkup">

          <label>Status:</label>
          <select class="form-control" name="status">
            <option>Active</option>
            <option>Expired</option>
          </select>

        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Save</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Bootstrap JS (must be loaded at the end of body) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Make custom dropdown work
const dropdownItems = document.querySelectorAll('.dropdown-menu a.dropdown-item');
const dropdownButton = document.getElementById('typeDropdown');
const typeInput = document.getElementById('typeInput');

dropdownItems.forEach(item => {
  item.addEventListener('click', function(e) {
    e.preventDefault(); // prevent page jump
    dropdownButton.textContent = this.textContent; // show selected text
    typeInput.value = this.getAttribute('data-value'); // save value for form submission
  });
});
</script>

</body>
</html>
