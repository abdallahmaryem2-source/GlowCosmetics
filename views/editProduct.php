<!DOCTYPE html>
<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['client']) || $_SESSION['client']['role'] !== 'admin') {
    header("Location: login.php"); exit;
}
require_once "../config/db_connect.php";
require_once "../models/product.php";
require_once "../models/productManager.php";

$id      = (int)($_GET['id'] ?? 0);
$manager = new ProductManager($db);
$product = $manager->getById($id);
if (!$product) { header("Location: catalogue.php"); exit; }
?>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le produit — Glow Cosmetics</title>
</head>
<body>
<?php include "header.php"; ?>

<main class="container-sm" style="padding-top:48px; padding-bottom:80px;">
    <div class="form-card">
        <h2 class="form-title">Modifier le produit</h2>
        <p class="form-subtitle"><?= htmlspecialchars($product->name) ?></p>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-error">⚠ Une erreur est survenue lors de la modification.</div>
        <?php endif; ?>

        <form action="../controllers/editProductController.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $product->id ?>">

            <div class="form-group">
                <label for="name">Désignation</label>
                <input type="text" name="name" id="name" value="<?= htmlspecialchars($product->name) ?>" required>
            </div>

            <div class="form-group">
                <label for="desc">Description</label>
                <textarea name="desc" id="desc"><?= htmlspecialchars($product->desc) ?></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="price">Prix (TND)</label>
                    <input type="number" step="0.001" min="0" name="price" id="price" value="<?= $product->price ?>" required>
                </div>
                <div class="form-group">
                    <label for="quantity">Quantité en stock</label>
                    <input type="number" min="0" name="quantity" id="quantity" value="<?= $product->quantity ?>" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="promo_price">Prix promo (TND)</label>
                    <input type="number" step="0.001" min="0" name="promo_price" id="promo_price" value="<?= $product->promo_price ?>">
                </div>
                <div class="form-group">
                    <label for="promo_label">Label promo</label>
                    <input type="text" name="promo_label" id="promo_label" value="<?= htmlspecialchars($product->promo_label) ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="category">Catégorie</label>
                <input type="text" name="category" id="category" value="<?= htmlspecialchars($product->category) ?>" required>
            </div>

            <div class="form-group">
                <label>Image actuelle</label>
                <div style="margin-bottom:10px;">
                    <img src="../public/uploads/products/<?= htmlspecialchars($product->image_url) ?>"
                         style="height:80px; object-fit:cover; border:1px solid var(--gold-light); border-radius:2px;"
                         onerror="this.style.display='none'">
                </div>
                <label for="image_file">Changer l'image (optionnel)</label>
                <input type="file" name="image_file" id="image_file" accept="image/*">
                <span class="form-hint">Laissez vide pour conserver l'image actuelle.</span>
            </div>

            <div style="margin-top:32px; display:flex; flex-direction:column; gap:12px;">
                <button type="submit" class="btn btn-primary btn-full">Enregistrer les modifications</button>
                <a href="catalogue.php" class="btn btn-ghost btn-full">Annuler</a>
            </div>
        </form>
    </div>
</main>
</body>
</html>