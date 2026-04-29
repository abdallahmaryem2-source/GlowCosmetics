<?php
// controllers/updateStatusController.php
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['client']) || $_SESSION['client']['role'] !== 'admin') {
    header('Location: ../views/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../views/admin.php');
    exit;
}

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$status = trim((string)($_POST['status'] ?? ''));
$allowedStatuses = ['pending', 'confirmed', 'shipped', 'cancelled'];

if ($id <= 0 || !in_array($status, $allowedStatuses, true)) {
    header('Location: ../views/admin.php?status_updated=0');
    exit;
}

require_once '../config/db_connect.php';
require_once '../models/ordersManager.php';

$oManager = new OrdersManager($db);
$updated = $oManager->updateStatus($id, $status);

if ($updated) {
    header('Location: ../views/admin.php?status_updated=1');
    exit;
}

header('Location: ../views/admin.php?status_updated=0');
exit;
