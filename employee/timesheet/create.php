<?php
session_start();
require_once("../../utils/auth.php");
require_role("Employee");
require_once("../../utils/conn.php");
require_once("../../components/header.php");

// Already filled today?
$today = date('Y-m-d');
$chk = mysqli_prepare($conn, "SELECT id FROM timesheet WHERE employee_id = ? AND today_date = ? LIMIT 1");
mysqli_stmt_bind_param($chk, "ss", $_SESSION['employee_id'], $today);
mysqli_stmt_execute($chk);
mysqli_stmt_store_result($chk);
$alreadyFilled = mysqli_stmt_num_rows($chk) > 0;
mysqli_stmt_close($chk);
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container">
    <a href="../dashboard.php" class="navbar-brand">Enablemining ERP · Employee</a>
    <div class="ms-auto text-white">
      <?= htmlspecialchars($_SESSION['employee_id']) ?> ·
      <a href="../../logout.php" class="btn btn-sm btn-outline-light">Logout</a>
    </div>
  </div>
</nav>

<div class="container mt-4" style="max-width:640px;">
  <h3>Fill Today's Timesheet</h3>
  <p class="text-muted">Date: <strong><?= date('l, d M Y') ?></strong></p>

  <?php show_flash(); ?>

  <?php if ($alreadyFilled): ?>
    <div class="alert alert-info">
      You have already submitted today's timesheet.
      <a href="index.php" class="alert-link">View your timesheets →</a>
    </div>
  <?php else: ?>
    <form action="../../action/timesheet_create.php" method="POST" class="card card-body shadow-sm">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Login Time</label>
          <input type="time" name="login_time" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Logout Time</label>
          <input type="time" name="logout_time" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Break Start (optional)</label>
          <input type="time" name="break_time" class="form-control">
        </div>
        <div class="col-md-6">
          <label class="form-label">Break End / Resume (optional)</label>
          <input type="time" name="resume_time" class="form-control">
        </div>
        <div class="col-12">
          <label class="form-label">Today's Progress</label>
          <textarea name="progress" class="form-control" rows="4" required></textarea>
        </div>
        <div class="col-12">
          <button class="btn btn-primary">Submit Timesheet</button>
          <a href="../dashboard.php" class="btn btn-secondary">Cancel</a>
        </div>
      </div>
    </form>
  <?php endif; ?>
</div>

<?php require_once("../../components/footer.php"); ?>