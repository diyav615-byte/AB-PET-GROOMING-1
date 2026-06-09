<?php
require_once '../includes/bootstrap.php';

// Verify CSRF token (no regen on failure)
if (!CsrfProtection::verifyFromPostNoRegen($_POST)) {
    die('Invalid CSRF token');
}

session_destroy();
header("Location: login.php");
exit;
?>
