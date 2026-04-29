<!DOCTYPE html>
<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once "../config/db_connect.php";
require_once "../models/productManager.php";

$manager = new ProductManager($db);
$product = $manager->getById((int)($_GET['id'] ?? 0));
$favoriteIds = [];
if (isset($_SESSION['client'])) {
    require_once "../models/favoriteManager.php";
    $favManager = new FavoriteManager($db);
    $favoriteIds = $favManager->getFavoriteIdsByUser((int)$_SESSION['client']['id']);
}

if (!$product) {
    header("Location: catalogue.php");
    exit;
}

$isAdmin = isset($_SESSION['client']) && $_SESSION['client']['role'] === 'admin';
?>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($product->name) ?> - Glow Cosmetics</title>
</head>
<body>
<?php include "header.php"; ?>

<main class="container" style="padding-top:48px; padding-bottom:80px;">
    <a href="catalogue.php" class="btn btn-ghost" style="margin-bottom:28px;">&larr; Retour au catalogue</a>

    <div style="display:grid; grid-template-columns:minmax(260px, 440px) 1fr; gap:48px; align-items:start;">
        <div class="product-img-wrap" style="aspect-ratio:1 / 1; height:auto;">
            <img src="../public/uploads/products/<?= htmlspecialchars($product->image_url) ?>"
                 alt="<?= htmlspecialchars($product->name) ?>"
                 onerror="this.src='../public/uploads/products/product.jpg'">
        </div>

        <section>
            <p class="product-category" style="margin-bottom:12px;"><?= htmlspecialchars($product->category) ?></p>
            <h1 class="page-title" style="margin-bottom:18px;"><?= htmlspecialchars($product->name) ?></h1>

            <div style="display:flex; align-items:flex-end; gap:14px; margin-bottom:24px;">
                <div class="product-price" style="font-size:2rem;">
                    <?= number_format($product->hasPromo() ? $product->promo_price : $product->price, 3) ?> <small>TND</small>
                </div>
                <?php if ($product->hasPromo()): ?>
                    <div style="font-size:1rem; color:var(--muted); text-decoration:line-through;">
                        <?= number_format($product->price, 3) ?> TND
                    </div>
                <?php endif; ?>
            </div>

            <?php if ($product->hasPromo() && $product->promo_label): ?>
                <div style="font-size:0.9rem; color:var(--rose); margin-bottom:18px;">
                    <?= htmlspecialchars($product->promo_label) ?>
                </div>
            <?php endif; ?>

            <p style="line-height:1.8; color:var(--charcoal); margin-bottom:28px;">
                <?= nl2br(htmlspecialchars($product->desc)) ?>
            </p>

            <p style="font-size:0.85rem; color:var(--muted); margin-bottom:24px;">
                Stock disponible : <?= $product->quantity ?>
            </p>

            <div style="display:flex; gap:12px; flex-wrap:wrap; align-items:center;">
                <?php if ($product->isInStock()): ?>
                    <?php if (isset($_SESSION['client'])): ?>
                        <a href="../controllers/cartController.php?action=add&id=<?= $product->id ?>" class="btn btn-rose">
                            Ajouter au panier
                        </a>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-rose">Connexion pour acheter</a>
                    <?php endif; ?>
                <?php else: ?>
                    <button disabled class="btn btn-ghost" style="opacity:.5;cursor:not-allowed">Indisponible</button>
                <?php endif; ?>

                <?php if (isset($_SESSION['client'])): ?>
                    <form action="../controllers/favoriteController.php" method="POST" style="margin:0;">
                        <input type="hidden" name="id" value="<?= $product->id ?>">
                        <input type="hidden" name="action" value="<?= in_array($product->id, $favoriteIds) ? 'remove' : 'add' ?>">
                        <button type="submit" class="btn btn-fav <?= in_array($product->id, $favoriteIds) ? 'active' : '' ?>" aria-label="<?= in_array($product->id, $favoriteIds) ? 'Retirer des favoris' : 'Ajouter aux favoris' ?>">
                            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41 0.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35Z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                    </form>
                <?php endif; ?>

                <?php if ($isAdmin): ?>
                    <a href="editProduct.php?id=<?= $product->id ?>" class="btn btn-ghost">Modifier</a>
                <?php endif; ?>
            </div>
        </section>
    </div>
</main>
</body>
</html>
