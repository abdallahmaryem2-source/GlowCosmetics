<?php
// controllers/orderController.php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['client'])) { header("Location: ../views/login.php"); exit; }

$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) { header("Location: ../views/cart.php"); exit; }

require_once "../config/db_connect.php";
require_once "../models/product.php";
require_once "../models/productManager.php";
require_once "../models/ordersManager.php";

$pManager = new ProductManager($db);
$oManager = new OrdersManager($db);
$userId   = (int)$_SESSION['client']['id'];
$total    = 0.0;
$validCartItems = [];

// Calculate total & validate stock
foreach ($cart as $id => $data) {
    $product = $pManager->getById((int)$id);
    $qty = (int)($data['qty'] ?? 0);

    if (!$product || $qty <= 0 || $product->quantity < $qty) {
        header("Location: ../views/cart.php?order_error=1");
        exit;
    }

    $total += $product->price * $qty;
    $validCartItems[(int)$id] = $qty;
}

if ($total <= 0 || empty($validCartItems)) {
    header("Location: ../views/cart.php?order_error=1");
    exit;
}

// Create order
$orderId = $oManager->createOrder($userId, $total);

if ($orderId) {
    // Decrement stock for each item
    foreach ($validCartItems as $id => $qty) {
        $pManager->decrementStock((int)$id, $qty);
    }
    $_SESSION['cart'] = [];
    header("Location: ../views/cart.php?order_success=1");
} else {
    header("Location: ../views/cart.php?order_error=1");
}
exit;
