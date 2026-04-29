<?php
// controllers/editProductController.php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['client']) || $_SESSION['client']['role'] !== 'admin') {
    header("Location: ../views/login.php"); exit;
}

require_once "../config/db_connect.php";
require_once "../models/product.php";
require_once "../models/productManager.php";
require_once "../utils/fileUploader.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id      = (int)$_POST['id'];
    $manager = new ProductManager($db);
    $existing = $manager->getById($id);
    if (!$existing) { header("Location: ../views/catalogue.php"); exit; }

    $imageName = $existing->image_url;
    if (!empty($_FILES['image_file']['name'])) {
        $uploader = new FileUploader('../public/uploads/products/');
        $uploaded = $uploader->upload($_FILES['image_file']);
        if ($uploaded) $imageName = $uploaded;
    }

    $existing->name       = trim($_POST['name']);
    $existing->desc       = trim($_POST['desc'] ?? '');
    $existing->price      = (float)$_POST['price'];
    $existing->category   = trim($_POST['category']);
    $existing->quantity   = (int)$_POST['quantity'];
    $existing->image_url  = $imageName;
    $existing->promo_price = max(0.0, (float)($_POST['promo_price'] ?? 0.0));
    $existing->promo_label = trim($_POST['promo_label'] ?? '');

    if ($manager->update($existing)) {
        header("Location: ../views/catalogue.php?success=1");
    } else {
        header("Location: ../views/editProduct.php?id=$id&error=1");
    }
    exit;
}
header("Location: ../views/catalogue.php");
exit;