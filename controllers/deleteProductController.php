<?php
// controllers/deleteProductController.php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['client']) || $_SESSION['client']['role'] !== 'admin') {
    header("Location: ../views/login.php"); exit;
}

require_once "../config/db_connect.php";
require_once "../models/productManager.php";

$id      = (int)($_GET['id'] ?? 0);
$manager = new ProductManager($db);
$manager->delete($id);

header("Location: ../views/catalogue.php");
exit;