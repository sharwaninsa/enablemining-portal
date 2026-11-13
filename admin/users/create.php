<?php
session_start();
require_once("../../utils/auth.php");
require_role("Admin");
require_once("../../components/header.php");
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a href="../dashboard.php" class="navbar-brand">Enablemining ERP · Admin</a>
    <div class="ms-auto text-white">
      <?= htmlspecialchars($_SESSION['employee_id']) ?> ·
      <a href="../../logout.php" class="btn btn-sm btn-outline-light">Logout</a>
    </div>
  </div>
</nav>

<div class="container mt-4" style="max-width:760px;">
  <h3>Add User</h3>
  <?php show_flash(); ?>

  <form action="../../action/user_create.php" method="POST" class="card card-body shadow-sm">
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Employee ID</label>
        <input name="employee_id" class="form-control" placeholder="EMC0010" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Name</label>
        <input name="name" class="form-control" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Designation</label>
        <input name="designation" class="form-control" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Role</label>
        <select name="role" class="form-select" required>
          <option value="Employee">Employee</option>
          <option value="Admin">Admin</option>
        </select>
      </div>
      <div class="col-md-6">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Phone</label>
        <input name="phone" class="form-control" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" minlength="6" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Joining Date</label>
        <input name="joining_date" class="form-control" placeholder="DD/MM/YYYY" required>
      </div>

      <div class="col-md-4">
        <label class="form-label">PAN Number</label>
        <input name="pan_number" class="form-control" maxlength="10" required>
      </div>
      <div class="col-md-4">
        <label class="form-label">Bank Account No.</label>
        <input name="bank_acc_number" class="form-control" required>
      </div>
      <div class="col-md-4">
        <label class="form-label">Bank IFSC</label>
        <input name="bank_ifsc" class="form-control" required>
      </div>

      <div class="col-md-6">
        <label class="form-label">Annual CTC (₹)</label>
        <input name="annual_ctc" type="number" class="form-control" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Monthly Salary (₹)</label>
        <input name="monthly_salary" type="number" class="form-control" required>
      </div>

      <div class="col-12">
        <button class="btn btn-primary">Create User</button>
        <a href="index.php" class="btn btn-secondary">Cancel</a>
      </div>
    </div>
  </form>
</div>

<?php require_once("../../components/footer.php"); ?>