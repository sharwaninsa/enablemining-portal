<?php
// components/flash.php
function set_flash($type, $msg) {
    $_SESSION['flash'] = ['type' => $type, 'msg' => $msg];
}
function show_flash() {
    if (empty($_SESSION['flash'])) return;
    $f = $_SESSION['flash'];
    unset($_SESSION['flash']);
    $cls = $f['type'] === 'success' ? 'alert-success'
         : ($f['type'] === 'error' ? 'alert-danger' : 'alert-info');
    echo '<div class="alert ' . $cls . ' alert-dismissible fade show">'
       . htmlspecialchars($f['msg'])
       . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
}