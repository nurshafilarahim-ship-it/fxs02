<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Fire Extinguisher Modal</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
.dropdown-menu .dropdown-item:hover {
  background-color: var(--hover-color);
  color: black !important;
}
</style>
</head>
<body>

<button class="btn btn-primary mt-3 ms-3" data-bs-toggle="modal" data-bs-target="#addModal">
  Add Fire Extinguisher
</button>

<div class="modal fade" id="addModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

      <form action="save_item.php" method="post">
        <div class="modal-header">
          <h5 class="modal-title">Add Fire Extinguisher</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

          <label>Name:</label>
          <input class="form-control mb-2" name="name" required>

          <label>Type:</label>
          <div class="dropdown mb-2">
            <button class="btn btn-light dropdown-toggle w-100" type="button" id="typeDropdown" data-bs-toggle="dropdown">
              Select Type
            </button>

            <ul class="dropdown-menu w-100">
              <li><a class="dropdown-item" href="#" data-value="A (Water)" style="--hover-color:#ff4d4d;">Water Extinguisher – Class A</a></li>
              <li><a class="dropdown-item" href="#" data-value="B (Foam)" style="--hover-color:#fff0b3;">Foam Extinguisher – Class A & B</a></li>
              <li><a class="dropdown-item" href="#" data-value="C (Dry Powder)" style="--hover-color:#4da6ff;">Dry Powder – Class A, B & C</a></li>
              <li><a class="dropdown-item text-white" href="#" data-value="CO2" style="--hover-color:#333;">CO₂ – Class B & C</a></li>
              <li><a class="dropdown-item" href="#" data-value="K (Wet Chemical)" style="--hover-color:#ffff66;">Wet Chemical – Class K</a></li>
            </ul>

            <input type="hidden" name="type" id="typeInput" required>
          </div>

          <label>Serial No:</label>
          <input class="form-control mb-2" name="serial_no" required>

          <!-- DATE & STATUS REMOVED (SERVER HANDLES IT) -->

        </div>

        <div class="modal-footer">
          <button class="btn btn-primary">Save</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </form>

    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
const dropdownItems = document.querySelectorAll('.dropdown-menu a');
const dropdownButton = document.getElementById('typeDropdown');
const typeInput = document.getElementById('typeInput');

dropdownItems.forEach(item => {
  item.addEventListener('click', function(e) {
    e.preventDefault();
    dropdownButton.textContent = this.textContent;
    typeInput.value = this.dataset.value;
  });
});
</script>

</body>
</html>
