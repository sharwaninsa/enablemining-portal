<?php
session_start();
require_once("../utils/auth.php");
require_role("Employee");
require_once("../components/header.php");
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container">
    <span class="navbar-brand">Enablemining ERP · Employee</span>
    <div class="ms-auto text-white">
      <?= htmlspecialchars($_SESSION['employee_id']) ?> ·
      <a href="../logout.php" class="btn btn-sm btn-outline-light">Logout</a>
    </div>
  </div>
</nav>

<div class="container mt-4">
  <?php show_flash(); ?>
  <h3>Employee Dashboard</h3>

  <div class="row g-3 mt-2">
    <div class="col-md-4">
      <a href="timesheet/index.php" class="card card-body text-decoration-none">⏱️ My Timesheets</a>
    </div>
    <div class="col-md-4">
      <a href="timesheet/create.php" class="card card-body text-decoration-none">➕ Fill Today's Timesheet</a>
    </div>
    <div class="col-md-4">
      <a href="#" class="card card-body text-decoration-none">📅 My Leaves (soon)</a>
    </div>
    <div class="col-md-4">
      <a href="#" class="card card-body text-decoration-none">💰 My Payslips (soon)</a>
    </div>
  </div>
</div>

<?php require_once("../components/footer.php"); ?>