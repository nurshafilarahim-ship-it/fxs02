<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content custom-dark-modal">
      <form action="save_item.php" method="post" id="addForm">
        <div class="modal-header">
          <h5 class="modal-title">Add Fire Extinguisher</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <label class="form-label text-info-custom">Name:</label>
          <input type="text" class="form-control custom-input mb-2" name="name" required>

          <label class="form-label text-info-custom">Location:</label>
          <input type="text" class="form-control custom-input mb-2" name="location" required>

          <label class="form-label text-info-custom">Type:</label>
          <div class="dropdown mb-2">
            <button class="btn btn-outline-info w-100 dropdown-toggle text-start" type="button"
                    id="typeDropdown" data-bs-toggle="dropdown" style="color:#fff; border-color:#334155;">
              Select Type
            </button>
            <ul class="dropdown-menu w-100 dropdown-menu-dark">
              <li><a class="dropdown-item" href="#" data-value="A (Water)" style="--hover-color:#ff4d4d;">Water – Class A</a></li>
              <li><a class="dropdown-item" href="#" data-value="B (Foam)" style="--hover-color:#fff0b3;">Foam – Class A & B</a></li>
              <li><a class="dropdown-item" href="#" data-value="C (Dry Powder)" style="--hover-color:#4da6ff;">Dry Powder – Class A,B,C</a></li>
              <li><a class="dropdown-item" href="#" data-value="CO2" style="--hover-color:#333333;">CO₂ – Class B,C</a></li>
              <li><a class="dropdown-item" href="#" data-value="K (Wet Chemical)" style="--hover-color:#ffff66;">Wet Chemical – Class K</a></li>
            </ul>
            <input type="hidden" name="type" id="typeInput" required>
          </div>

          <label class="form-label text-info-custom">Serial No:</label>
          <input type="text" class="form-control custom-input mb-2" name="serial_no" required>

          <label class="form-label text-info-custom">Expiry Date:</label>
          <input type="date" class="form-control custom-input mb-2" name="expiry_date" id="expiryDate" required>
          <small class="text-danger" id="expiryWarning" style="display:none;">
            Expiry date cannot be more than 10 years from today!
          </small>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary px-4">Save</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>

<style>
.custom-dark-modal { background:#487CB8 !important; color:#fff !important; border:1px solid #1e293b; }
.text-info-custom { color:#EDF2F8 !important; font-weight:600; }
.custom-input { background:#1e293b !important; border:1px solid #334155 !important; color:#fff !important; }
.custom-input:focus { background:#EDF2F8; color:#fff; border-color:#38bdf8; box-shadow:0 0 0 0.25rem rgba(56,189,248,0.25); }
.dropdown-menu .dropdown-item:hover { background-color: var(--hover-color); color:#000 !important; }
input[type="date"]::-webkit-calendar-picker-indicator { filter: invert(1); }
</style>

<script>
const dropdownItems = document.querySelectorAll('#addModal .dropdown-menu a');
const dropdownButton = document.getElementById('typeDropdown');
const typeInput = document.getElementById('typeInput');
dropdownItems.forEach(item => {
  item.addEventListener('click', function(e){
    e.preventDefault();
    dropdownButton.textContent = this.textContent;
    typeInput.value = this.dataset.value;
  });
});

const expiryDateInput = document.getElementById('expiryDate');
const expiryWarning = document.getElementById('expiryWarning');
expiryDateInput.addEventListener('change', function(){
  const selectedDate = new Date(this.value);
  const today = new Date();
  const maxDate = new Date();
  maxDate.setFullYear(today.getFullYear()+10);
  if(selectedDate>maxDate){ expiryWarning.style.display='block'; this.value=''; }
  else expiryWarning.style.display='none';
});

document.getElementById('addForm').addEventListener('submit', function(e){
  if(!typeInput.value){ e.preventDefault(); alert('Please select a fire extinguisher type.'); }
});
</script>
