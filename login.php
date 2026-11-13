<?php
session_start();

// If already logged in → go to their dashboard
if (!empty($_SESSION['employee_id'])) {
    if ($_SESSION['role'] === 'Admin') {
        header("Location: admin/dashboard.php");
    } else {
        header("Location: employee/dashboard.php");
    }
    exit;
}

require_once("components/header.php");
?>

<div class="container mt-5" style="max-width:420px;">
  <div class="card shadow-sm">
    <div class="card-body p-4">
      <h4 class="mb-3 text-center">Enablemining Pty Ltd ERP</h4>

      <?php if (!empty($_SESSION['flash_error'])): ?>
        <div class="alert alert-danger py-2">
          <?= htmlspecialchars($_SESSION['flash_error']) ?>
        </div>
        <?php unset($_SESSION['flash_error']); ?>
      <?php endif; ?>

      <form action="action/loginprocess.php" method="POST" autocomplete="off">
        <div class="mb-3">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Password</label>
          <input type="password" name="password" class="form-control" required>
        </div>
        <button class="btn btn-primary w-100">Login</button>
      </form>
    </div>
  </div>
</div>

<?php require_once("components/footer.php"); ?>