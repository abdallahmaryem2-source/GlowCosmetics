<?php
// controllers/loginController.php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once "../config/db_connect.php";
require_once "../models/userManager.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $manager = new UserManager($db);
    $user    = $manager->login($email, $password);

    if ($user) {
        $_SESSION['client'] = [
            'id'       => $user['id'],
            'username' => $user['username'],
            'email'    => $user['email'],
            'role'     => $user['role'],
        ];
        header("Location: /projet_mariem/views/catalogue.php");
    } else {
        header("Location: /projet_mariem/views/login.php?error=1");
    }
    exit;
}
header("Location: /projet_mariem/views/login.php");
exit;