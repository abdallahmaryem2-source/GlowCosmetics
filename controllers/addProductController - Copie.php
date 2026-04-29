<?php
// controllers/addProductController.php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['client']) || $_SESSION['client']['role'] !== 'admin') {
    header("Location: ../views/login.php"); exit;
}

require_once "../config/db_connect.php";
require_once "../models/product.php";
require_once "../models/productManager.php";
require_once "../utils/fileUploader.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uploader  = new FileUploader('../public/uploads/products/');
    $imageName = (!empty($_FILES['image_file']['name']))
        ? ($uploader->upload($_FILES['image_file']) ?: 'product.jpg')
        : 'product.jpg';

    $product = new Product(
        trim($_POST['name']),
        trim($_POST['desc'] ?? ''),
        (float)$_POST['price'],
        trim($_POST['category']),
        $imageName,
        (int)$_POST['quantity']
    );

    $manager = new ProductManager($db);
    if ($manager->insert($product)) {
        header("Location: ../views/catalogue.php?success=1");
    } else {
        header("Location: ../views/addProduct.php?error=1");
    }
    exit;
}
header("Location: ../views/addProduct.php");
exit;