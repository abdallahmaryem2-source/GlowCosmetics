<?php
require_once "../config/db_connect.php";
require_once "../models/product.php";
require_once "../models/productManager.php";

$manager = new ProductManager($db);
$products = $manager->getAllProducts(); 
