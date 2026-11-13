<?php
session_start();
require_once("../../utils/auth.php");
require_role("Admin");
require_once("../../utils/conn.php");
require_once("../../components/header.php");

$id = (int)($_GET['id'] ?? 0);
$stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE id = ? LIMIT 1");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$u = mysqli_fetch_assoc($res);
mysqli_stmt_close($stmt);

if (!$u) {
    set_flash('error', "User not found.");
    header("Location: index.php"); exit;
}
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
  <h3>Edit User</h3>
  <?php show_flash(); ?>

  <form action="../../action/user_update.php" method="POST" class="card card-body shadow-sm">
    <input type="hidden" name="id" value="<?= (int)$u['id'] ?>">
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Employee ID</label>
        <input name="employee_id" class="form-control" value="<?= htmlspecialchars($u['employee_id']) ?>" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Name</label>
        <input name="name" class="form-control" value="<?= htmlspecialchars($u['name']) ?>" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Designation</label>
        <input name="designation" class="form-control" value="<?= htmlspecialchars($u['designation']) ?>" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Role</label>
        <select name="role" class="form-select" required>
          <option value="Employee" <?= $u['role']==='Employee'?'selected':'' ?>>Employee</option>
          <option value="Admin"    <?= $u['role']==='Admin'?'selected':'' ?>>Admin</option>
        </select>
      </div>
      <div class="col-md-6">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($u['email']) ?>" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Phone</label>
        <input name="phone" class="form-control" value="<?= htmlspecialchars($u['phone']) ?>" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">New Password (leave blank to keep)</label>
        <input type="password" name="password" class="form-control" minlength="6">
      </div>
      <div class="col-md-6">
        <label class="form-label">Joining Date</label>
        <input name="joining_date" class="form-control" value="<?= htmlspecialchars($u['joining_date']) ?>" required>
      </div>
      <div class="col-md-4">
        <label class="form-label">PAN</label>
        <input name="pan_number" class="form-control" maxlength="10" value="<?= htmlspecialchars($u['pan_number']) ?>" required>
      </div>
      <div class="col-md-4">
        <label class="form-label">Bank Account</label>
        <input name="bank_acc_number" class="form-control" value="<?= htmlspecialchars($u['bank_acc_number']) ?>" required>
      </div>
      <div class="col-md-4">
        <label class="form-label">Bank IFSC</label>
        <input name="bank_ifsc" class="form-control" value="<?= htmlspecialchars($u['bank_ifsc']) ?>" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Annual CTC (₹)</label>
        <input name="annual_ctc" type="number" class="form-control" value="<?= htmlspecialchars($u['annual_ctc']) ?>" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Monthly Salary (₹)</label>
        <input name="monthly_salary" type="number" class="form-control" value="<?= htmlspecialchars($u['monthly_salary']) ?>" required>
      </div>
      <div class="col-12">
        <button class="btn btn-primary">Update</button>
        <a href="index.php" class="btn btn-secondary">Cancel</a>
      </div>
    </div>
  </form>
</div>

<?php require_once("../../components/footer.php"); ?>