<?php
// controllers/registerController.php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once "../config/db_connect.php";
require_once "../models/userManager.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$username || !$email || strlen($password) < 6) {
        header("Location: ../views/register.php?error=1");
        exit;
    }

    $manager = new UserManager($db);
    if ($manager->register($username, $email, $password)) {
        header("Location: ../views/login.php?registered=1");
    } else {
        header("Location: ../views/register.php?error=1");
    }
    exit;
}
header("Location: ../views/register.php");
exit;