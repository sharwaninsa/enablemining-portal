<?php
session_start();
require_once("../../utils/auth.php");
require_role("Admin");
require_once("../../utils/conn.php");
require_once("../../components/header.php");

$res = mysqli_query($conn, "SELECT id, employee_id, name, designation, role, email, phone FROM users ORDER BY id DESC");
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
    <h3 class="mb-0">Users</h3>
    <a href="create.php" class="btn btn-primary">+ Add User</a>
  </div>

  <div class="table-responsive">
    <table class="table table-bordered table-hover align-middle">
      <thead class="table-light">
        <tr>
          <th>#</th><th>Employee ID</th><th>Name</th><th>Designation</th>
          <th>Role</th><th>Email</th><th>Phone</th><th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (mysqli_num_rows($res) === 0): ?>
          <tr><td colspan="8" class="text-center text-muted">No users yet.</td></tr>
        <?php else: while ($u = mysqli_fetch_assoc($res)): ?>
          <tr>
            <td><?= (int)$u['id'] ?></td>
            <td><?= htmlspecialchars($u['employee_id']) ?></td>
            <td><?= htmlspecialchars($u['name']) ?></td>
            <td><?= htmlspecialchars($u['designation']) ?></td>
            <td>
              <span class="badge bg-<?= $u['role']==='Admin'?'dark':'primary' ?>">
                <?= htmlspecialchars($u['role']) ?>
              </span>
            </td>
            <td><?= htmlspecialchars($u['email']) ?></td>
            <td><?= htmlspecialchars($u['phone']) ?></td>
            <td>
              <a href="edit.php?id=<?= (int)$u['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
              <?php if ($u['employee_id'] !== $_SESSION['employee_id']): ?>
                <a href="../../action/user_delete.php?id=<?= (int)$u['id'] ?>"
                   class="btn btn-sm btn-danger"
                   onclick="return confirm('Delete this user? Their leaves/timesheets will also be removed.')">Delete</a>
              <?php endif; ?>
            </td>
          </tr>
        <?php endwhile; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require_once("../../components/footer.php"); ?>