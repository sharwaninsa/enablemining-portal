<?php
session_start();
require_once("../../utils/auth.php");
require_role("Employee");
require_once("../../utils/conn.php");
require_once("../../components/header.php");

$stmt = mysqli_prepare($conn,
    "SELECT * FROM timesheet WHERE employee_id = ? ORDER BY today_date DESC, id DESC");
mysqli_stmt_bind_param($stmt, "s", $_SESSION['employee_id']);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
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

<div class="container mt-4">
  <?php show_flash(); ?>

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">My Timesheets</h3>
    <a href="create.php" class="btn btn-primary">+ Fill Today's Timesheet</a>
  </div>

  <div class="table-responsive">
    <table class="table table-bordered table-hover align-middle">
      <thead class="table-light">
        <tr>
          <th>Date</th><th>Day</th><th>Week</th>
          <th>Login</th><th>Break</th><th>Resume</th><th>Logout</th>
          <th>Hours</th><th>Progress</th><th>Approved</th>
        </tr>
      </thead>
      <tbody>
        <?php if (mysqli_num_rows($res) === 0): ?>
          <tr><td colspan="10" class="text-center text-muted">No timesheets yet.</td></tr>
        <?php else: while ($t = mysqli_fetch_assoc($res)):
          $approved = $t['is_approved'] === 'true';
        ?>
          <tr>
            <td><?= htmlspecialchars($t['today_date']) ?></td>
            <td><?= htmlspecialchars($t['today_day']) ?></td>
            <td><?= htmlspecialchars($t['week_day']) ?></td>
            <td><?= htmlspecialchars($t['login_time']) ?></td>
            <td><?= htmlspecialchars($t['break_time']) ?></td>
            <td><?= htmlspecialchars($t['resume_time']) ?></td>
            <td><?= htmlspecialchars($t['logout_time']) ?></td>
            <td><strong><?= htmlspecialchars($t['hours_worked']) ?></strong></td>
            <td style="max-width:260px;"><?= nl2br(htmlspecialchars($t['progress'])) ?></td>
            <td>
              <?php if ($approved): ?>
                <span class="badge bg-success">Approved</span>
              <?php else: ?>
                <span class="badge bg-secondary">Pending</span>
              <?php endif; ?>
            </td>
          </tr>
        <?php endwhile; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php
mysqli_stmt_close($stmt);
mysqli_close($conn);
require_once("../../components/footer.php");
?>