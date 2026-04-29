<?php
// controllers/favoriteController.php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['client'])) {
    header('Location: ../views/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../views/catalogue.php');
    exit;
}

require_once '../config/db_connect.php';
require_once '../models/favoriteManager.php';

$userId    = (int)$_SESSION['client']['id'];
$productId = (int)($_POST['id'] ?? 0);
$action    = trim((string)($_POST['action'] ?? ''));

$manager = new FavoriteManager($db);
if ($productId <= 0) {
    $redirect = $_SERVER['HTTP_REFERER'] ?? '../views/catalogue.php';
    header("Location: $redirect");
    exit;
}

if ($action === 'remove') {
    $manager->removeFavorite($userId, $productId);
} else {
    $manager->addFavorite($userId, $productId);
}

$redirect = $_SERVER['HTTP_REFERER'] ?? '../views/catalogue.php';
header("Location: $redirect");
exit;
