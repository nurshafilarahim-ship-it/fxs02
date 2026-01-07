<!-- ADD MODAL -->
<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <form action="save_item.php" method="post" id="addForm">
        <div class="modal-header">
          <h5 class="modal-title">Add Fire Extinguisher</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

          <!-- NAME -->
          <label>Name:</label>
          <input type="text" class="form-control mb-2" name="name" required>

          <!-- TYPE (CUSTOM DROPDOWN) -->
          <label>Type:</label>
          <div class="dropdown mb-2">
            <button class="btn btn-light dropdown-toggle w-100" type="button"
                    id="typeDropdown" data-bs-toggle="dropdown">
              Select Type
            </button>

            <ul class="dropdown-menu w-100">
              <li>
                <a class="dropdown-item" href="#"
                   data-value="A (Water)"
                   style="--hover-color:#ff4d4d;">
                   Water Extinguisher – Class A
                </a>
              </li>
              <li>
                <a class="dropdown-item" href="#"
                   data-value="B (Foam)"
                   style="--hover-color:#fff0b3;">
                   Foam Extinguisher – Class A & B
                </a>
              </li>
              <li>
                <a class="dropdown-item" href="#"
                   data-value="C (Dry Powder)"
                   style="--hover-color:#4da6ff;">
                   Dry Powder – Class A, B & C
                </a>
              </li>
              <li>
                <a class="dropdown-item text-white" href="#"
                   data-value="CO2"
                   style="--hover-color:#333333;">
                   CO₂ – Class B & C
                </a>
              </li>
              <li>
                <a class="dropdown-item" href="#"
                   data-value="K (Wet Chemical)"
                   style="--hover-color:#ffff66;">
                   Wet Chemical – Class K
                </a>
              </li>
            </ul>

            <!-- REAL VALUE SENT TO SERVER -->
            <input type="hidden" name="type" id="typeInput" required>
          </div>

          <!-- SERIAL NUMBER -->
          <label>Serial No:</label>
          <input type="text" class="form-control mb-2" name="serial_no" required>

          <!-- EXPIRY DATE -->
          <label>Expiry Date:</label>
          <input type="date" class="form-control mb-2" name="expiry_date" id="expiryDate" required>
          <small class="text-danger" id="expiryWarning" style="display:none;">
            Expiry date cannot be more than 10 years from today!
          </small>

        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Save</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            Close
          </button>
        </div>
      </form>

    </div>
  </div>
</div>

<style>
/* Hover color for dropdown items */
.dropdown-menu .dropdown-item:hover {
  background-color: var(--hover-color);
  color: black !important;
}
</style>

<script>
/* ---------------- Custom dropdown logic ---------------- */
const dropdownItems = document.querySelectorAll('#addModal .dropdown-menu a');
const dropdownButton = document.getElementById('typeDropdown');
const typeInput = document.getElementById('typeInput');

dropdownItems.forEach(item => {
  item.addEventListener('click', function(e) {
    e.preventDefault();
    dropdownButton.textContent = this.textContent;
    typeInput.value = this.dataset.value;
  });
});

/* ---------------- Expiry date validation ---------------- */
const expiryDateInput = document.getElementById('expiryDate');
const expiryWarning = document.getElementById('expiryWarning');

expiryDateInput.addEventListener('change', function() {
  const selectedDate = new Date(this.value);
  const today = new Date();
  const maxDate = new Date();
  maxDate.setFullYear(today.getFullYear() + 10);

  if (selectedDate > maxDate) {
    expiryWarning.style.display = 'block';
    this.value = '';
  } else {
    expiryWarning.style.display = 'none';
  }
});

/* Optional: prevent form submit if type not selected */
document.getElementById('addForm').addEventListener('submit', function(e) {
  if (!typeInput.value) {
    e.preventDefault();
    alert('Please select a fire extinguisher type.');
  }
});
</script>
