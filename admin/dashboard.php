<?php

session_start();
require_once("../utils/auth.php");
require_role("Admin");
require_once("../components/header.php");
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <span class="navbar-brand">Enablemining ERP · Admin</span>
    <div class="ms-auto text-white">
      <?= htmlspecialchars($_SESSION['employee_id']) ?> ·
      <a href="../logout.php" class="btn btn-sm btn-outline-light">Logout</a>
    </div>
  </div>
</nav>

<div class="container mt-4">
  <?php show_flash(); ?>
  <h3>Admin Dashboard</h3>

  <div class="row g-3 mt-2">
    <div class="col-md-4">
      <a href="users/index.php" class="card card-body text-decoration-none">👥 Manage Users</a>
    </div>
    <div class="col-md-4">
      <a href="timesheets/index.php" class="card card-body text-decoration-none">⏱️ Timesheets</a>
    </div>
    <div class="col-md-4">
      <a href="#" class="card card-body text-decoration-none">📅 Leaves (soon)</a>
    </div>
    <div class="col-md-4">
      <a href="#" class="card card-body text-decoration-none">💰 Payroll (soon)</a>
    </div>
  </div>
</div>

<?php require_once("../components/footer.php"); ?>