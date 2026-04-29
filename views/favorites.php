<!DOCTYPE html>
<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['client'])) {
    header('Location: login.php');
    exit;
}

require_once '../config/db_connect.php';
require_once '../models/favoriteManager.php';
require_once '../models/product.php';
require_once '../models/productManager.php';

$favoriteManager = new FavoriteManager($db);
$favoriteIds = $favoriteManager->getFavoriteIdsByUser((int)$_SESSION['client']['id']);
$productManager = new ProductManager($db);
$favorites = [];

if (!empty($favoriteIds)) {
    foreach ($favoriteIds as $productId) {
        $product = $productManager->getById((int)$productId);
        if ($product) {
            $favorites[] = $product;
        }
    }
}
?>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Favoris — Glow Cosmetics</title>
</head>
<body>
<?php include "header.php"; ?>

<main class="container" style="padding-top:48px; padding-bottom:80px;">
    <div class="page-header">
        <div>
            <h1 class="page-title">Vos favoris</h1>
            <p class="page-count"><?= count($favorites) ?> article<?= count($favorites)!==1 ? 's' : '' ?> enregistré<?= count($favorites)!==1 ? 's' : '' ?></p>
        </div>
        <a href="catalogue.php" class="btn btn-ghost">Continuer vos achats</a>
    </div>

    <?php if (empty($favorites)): ?>
        <div class="empty-state">
            <h3>Vous n’avez aucun favori pour l’instant</h3>
            <p>Ajoutez des produits à votre liste pour les retrouver plus tard.</p>
            <a href="catalogue.php" class="btn btn-primary">Voir la boutique</a>
        </div>
    <?php else: ?>
        <div class="product-grid">
            <?php foreach ($favorites as $product): ?>
                <div class="product-card">
                    <?php if (!$product->isInStock()): ?>
                        <span class="badge badge-out">Rupture</span>
                    <?php endif; ?>

                    <div class="product-img-wrap">
                        <a href="productDetails.php?id=<?= $product->id ?>">
                            <img src="../public/uploads/products/<?= htmlspecialchars($product->image_url) ?>"
                                 alt="<?= htmlspecialchars($product->name) ?>"
                                 onerror="this.src='../public/uploads/products/product.jpg'">
                        </a>
                    </div>

                    <div class="product-info">
                        <p class="product-category"><?= htmlspecialchars($product->category) ?></p>
                        <h3 class="product-name">
                            <a href="productDetails.php?id=<?= $product->id ?>" style="color:inherit; text-decoration:none;">
                                <?= htmlspecialchars($product->name) ?>
                            </a>
                        </h3>
                        <p class="product-desc"><?= htmlspecialchars($product->desc) ?></p>

                        <div class="product-footer">
                            <div class="product-price">
                                <?= number_format($product->hasPromo() ? $product->promo_price : $product->price, 3) ?> <small>TND</small>
                            </div>
                            <?php if ($product->hasPromo()): ?>
                                <div style="font-size:0.75rem; color:var(--muted); text-decoration:line-through; margin-left:10px;">
                                    <?= number_format($product->price, 3) ?> TND
                                </div>
                            <?php endif; ?>
                        </div>

                        <form action="../controllers/favoriteController.php" method="POST" style="margin-top:16px;">
                            <input type="hidden" name="id" value="<?= $product->id ?>">
                            <input type="hidden" name="action" value="remove">
                            <button type="submit" class="btn btn-fav active" aria-label="Retirer des favoris">
                                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41 0.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35Z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>
</body>
</html>
