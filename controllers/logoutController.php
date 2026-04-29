<?php
// controllers/logoutController.php
if (session_status() === PHP_SESSION_NONE) session_start();
session_destroy();
header("Location: ../views/login.php?logout=1");
exit;