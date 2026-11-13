<?php
session_start();
require_once("../../utils/auth.php");
require_role("Admin");
require_once("../../utils/conn.php");
require_once("../../components/header.php");

$filter = $_GET['filter'] ?? 'pending'; // pending | approved | all
$where  = "";
if ($filter === 'pending')  $where = "WHERE t.is_approved = 'false'";
if ($filter === 'approved') $where = "WHERE t.is_approved = 'true'";

$sql = "SELECT t.*, u.name
        FROM timesheet t
        JOIN users u ON u.employee_id = t.employee_id
        $where
        ORDER BY t.today_date DESC, t.id DESC";
$res = mysqli_query($conn, $sql);
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

<div class="container mt-4">
  <?php show_flash(); ?>

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Timesheets</h3>
    <div class="btn-group">
      <a href="?filter=pending"  class="btn btn-sm btn-<?= $filter==='pending'?'primary':'outline-primary' ?>">Pending</a>
      <a href="?filter=approved" class="btn btn-sm btn-<?= $filter==='approved'?'primary':'outline-primary' ?>">Approved</a>
      <a href="?filter=all"      class="btn btn-sm btn-<?= $filter==='all'?'primary':'outline-primary' ?>">All</a>
    </div>
  </div>

  <div class="table-responsive">
    <table class="table table-bordered table-hover align-middle">
      <thead class="table-light">
        <tr>
          <th>Employee</th><th>Date</th><th>Day</th><th>Week</th>
          <th>Login</th><th>Break</th><th>Resume</th><th>Logout</th>
          <th>Hours</th><th>Progress</th><th>Status</th><th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if (mysqli_num_rows($res) === 0): ?>
          <tr><td colspan="12" class="text-center text-muted">No records.</td></tr>
        <?php else: while ($t = mysqli_fetch_assoc($res)):
          $approved = $t['is_approved'] === 'true';
        ?>
          <tr>
            <td><?= htmlspecialchars($t['employee_id']) ?> — <?= htmlspecialchars($t['name']) ?></td>
            <td><?= htmlspecialchars($t['today_date']) ?></td>
            <td><?= htmlspecialchars($t['today_day']) ?></td>
            <td><?= htmlspecialchars($t['week_day']) ?></td>
            <td><?= htmlspecialchars($t['login_time']) ?></td>
            <td><?= htmlspecialchars($t['break_time']) ?></td>
            <td><?= htmlspecialchars($t['resume_time']) ?></td>
            <td><?= htmlspecialchars($t['logout_time']) ?></td>
            <td><strong><?= htmlspecialchars($t['hours_worked']) ?></strong></td>
            <td style="max-width:220px;"><?= nl2br(htmlspecialchars($t['progress'])) ?></td>
            <td>
              <span class="badge bg-<?= $approved ? 'success':'secondary' ?>">
                <?= $approved ? 'Approved' : 'Pending' ?>
              </span>
            </td>
            <td>
              <?php if (!$approved): ?>
                <a href="../../action/timesheet_approve.php?id=<?= (int)$t['id'] ?>"
                   class="btn btn-sm btn-success"
                   onclick="return confirm('Approve this timesheet?')">Approve</a>
              <?php else: ?>
                <span class="text-muted small">—</span>
              <?php endif; ?>
            </td>
          </tr>
        <?php endwhile; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php
mysqli_close($conn);
require_once("../../components/footer.php");
?>