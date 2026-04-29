<!DOCTYPE html>
<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once "../config/db_connect.php";
require_once "../models/product.php";
require_once "../models/productManager.php";

$manager    = new ProductManager($db);
$search     = trim($_GET['search'] ?? '');
$category   = $_GET['category'] ?? '';
$products   = $manager->getAllProducts($search, $category);
$categories = $manager->getCategories();
$total      = $manager->getTotalCount();
$isAdmin = isset($_SESSION['client']) && $_SESSION['client']['role'] === 'admin';
$favoriteIds = [];
if (isset($_SESSION['client'])) {
    require_once "../models/favoriteManager.php";
    $favManager = new FavoriteManager($db);
    $favoriteIds = $favManager->getFavoriteIdsByUser((int)$_SESSION['client']['id']);
}
?>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boutique — Glow Cosmetics</title>
</head>
<body>
<?php include "header.php"; ?>

<section class="hero">
    <div class="hero-content">
        <h1>Beauté <em>naturelle</em><br>& raffinée</h1>
        <p>Découvrez notre collection de soins et cosmétiques premium</p>
    </div>
</section>

<main class="container">
    <!-- Filter Bar -->
    <form method="GET" class="filter-bar">
        <input type="text" name="search" placeholder="Rechercher un produit…" value="<?= htmlspecialchars($search) ?>">
        <select name="category" onchange="this.form.submit()">
            <option value="">Toutes les catégories</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= htmlspecialchars($cat) ?>" <?= $category===$cat?'selected':'' ?>>
                    <?= htmlspecialchars($cat) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="btn btn-primary">Filtrer</button>
        <?php if ($search || $category): ?>
            <a href="catalogue.php" class="btn btn-ghost">Réinitialiser</a>
        <?php endif; ?>
    </form>

    <div class="page-header">
        <div>
            <h2 class="page-title">Notre collection</h2>
            <p class="page-count"><?= count($products) ?> produit<?= count($products)!==1?'s':'' ?> trouvé<?= count($products)!==1?'s':'' ?></p>
        </div>
        <?php if ($isAdmin): ?>
            <a href="addProduct.php" class="btn btn-rose">+ Ajouter un produit</a>
        <?php endif; ?>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">✓ Produit ajouté avec succès au catalogue.</div>
    <?php endif; ?>
    <?php if (isset($_GET['cart_added'])): ?>
        <div class="alert alert-success">✓ Produit ajouté à votre panier.</div>
    <?php endif; ?>

    <?php if (isset($_GET['cart_error'])): ?>
        <div class="alert alert-error">Ce produit n'est pas disponible pour le moment.</div>
    <?php endif; ?>

    <?php if (empty($products)): ?>
        <div class="empty-state">
            <svg width="64" height="64" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                      d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
            <h3>Aucun produit trouvé</h3>
            <p>Essayez d'autres termes de recherche.</p>
            <a href="catalogue.php" class="btn btn-outline">Voir tout</a>
        </div>
    <?php else: ?>
        <div class="product-grid">
            <?php foreach ($products as $product): ?>
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
                        <?php if (isset($_SESSION['client'])): ?>
                            <form class="favorite-toggle" action="../controllers/favoriteController.php" method="POST">
                                <input type="hidden" name="id" value="<?= $product->id ?>">
                                <input type="hidden" name="action" value="<?= in_array($product->id, $favoriteIds) ? 'remove' : 'add' ?>">
                                <button type="submit" class="btn btn-fav <?= in_array($product->id, $favoriteIds) ? 'active' : '' ?>" aria-label="<?= in_array($product->id, $favoriteIds) ? 'Retirer des favoris' : 'Ajouter aux favoris' ?>">
                                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41 0.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35Z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </button>
                            </form>
                        <?php endif; ?>
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
                            <div>
                                <div class="product-price">
                                    <?= number_format($product->hasPromo() ? $product->promo_price : $product->price, 3) ?> <small>TND</small>
                                </div>
                                <?php if ($product->hasPromo()): ?>
                                    <div style="font-size:.8rem; color:var(--muted); text-decoration:line-through; margin-top:4px;">
                                        <?= number_format($product->price, 3) ?> TND
                                    </div>
                                    <?php if ($product->promo_label): ?>
                                        <div style="font-size:.75rem; color:var(--rose); margin-top:4px;">
                                            <?= htmlspecialchars($product->promo_label) ?>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>

                            <?php if ($product->isInStock()): ?>
                                <?php if (isset($_SESSION['client'])): ?>
                                    <a href="../controllers/cartController.php?action=add&id=<?= $product->id ?>" class="btn-add-cart">
                                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        Ajouter
                                    </a>
                                <?php else: ?>
                                    <a href="login.php" class="btn-add-cart">Connexion</a>
                                <?php endif; ?>
                            <?php else: ?>
                                <button disabled class="btn-add-cart" style="opacity:.5;cursor:not-allowed">Indisponible</button>
                            <?php endif; ?>
                        </div>

                        <?php if ($isAdmin): ?>
                            <div style="margin-top:12px; display:flex; gap:8px;">
                                <a href="editProduct.php?id=<?= $product->id ?>" class="btn btn-ghost" style="padding:7px 14px; font-size:0.7rem; flex:1; text-align:center;">Modifier</a>
                                <a href="../controllers/deleteProductController.php?id=<?= $product->id ?>"
                                   class="btn btn-ghost" style="padding:7px 14px; font-size:0.7rem; color:#c0392b; border-color:#e8b4b0;"
                                   onclick="return confirm('Supprimer ce produit ?')">Supprimer</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<footer>
    <div class="footer-inner">
        <div class="footer-brand">
            <span class="logo">GLOW<span style="color:var(--rose)">&middot;</span>COSMETICS</span>
            <p>Des soins naturels et raffinés pour sublimer votre beauté au quotidien.</p>
        </div>
        <div class="footer-col">
            <h4>Navigation</h4>
            <a href="catalogue.php">Boutique</a>
            <a href="cart.php">Panier</a>
            <a href="order.php">Commandes</a>
        </div>
        <div class="footer-col">
            <h4>Compte</h4>
            <a href="login.php">Connexion</a>
            <a href="register.php">S'inscrire</a>
        </div>
    </div>
    <div class="footer-bottom">
        <span>© <?= date('Y') ?> Glow Cosmetics. Tous droits réservés.</span>
        <span>Fait avec ♡ en Tunisie</span>
    </div>
</footer>
</body>
</html>
