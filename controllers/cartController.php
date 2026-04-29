<?php
// controllers/cartController.php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['client'])) { header("Location: ../views/login.php"); exit; }

require_once "../config/db_connect.php";
require_once "../models/productManager.php";

$action = $_GET['action'] ?? '';
$id     = (int)($_GET['id'] ?? 0);
$productManager = new ProductManager($db);

if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

switch ($action) {
    case 'add':
        if ($id > 0) {
            $product = $productManager->getById($id);
            if (!$product || !$product->isInStock()) {
                header("Location: ../views/catalogue.php?cart_error=1");
                exit;
            }

            $currentQty = (int)($_SESSION['cart'][$id]['qty'] ?? 0);
            if ($currentQty >= $product->quantity) {
                header("Location: ../views/cart.php?stock_error=1");
                exit;
            }

            if (isset($_SESSION['cart'][$id])) {
                $_SESSION['cart'][$id]['qty']++;
            } else {
                $_SESSION['cart'][$id] = ['qty' => 1];
            }
            header("Location: ../views/catalogue.php?cart_added=1");
        }
        break;

    case 'remove':
        unset($_SESSION['cart'][$id]);
        header("Location: ../views/cart.php");
        break;

    case 'update':
        $qty = (int)($_GET['qty'] ?? 1);
        $product = $productManager->getById($id);
        if ($qty > 0 && $product && isset($_SESSION['cart'][$id])) {
            $qty = min($qty, $product->quantity);
            $_SESSION['cart'][$id]['qty'] = $qty;
        }
        header("Location: ../views/cart.php");
        break;

    case 'clear':
        $_SESSION['cart'] = [];
        header("Location: ../views/cart.php");
        break;

    default:
        header("Location: ../views/cart.php");
}
exit;
