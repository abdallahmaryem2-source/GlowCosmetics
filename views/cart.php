<!DOCTYPE html>
<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['client'])) { header("Location: login.php"); exit; }

require_once "../config/db_connect.php";
require_once "../models/product.php";
require_once "../models/productManager.php";

$manager = new ProductManager($db);
$cart    = $_SESSION['cart'] ?? [];
$items   = [];
$total   = 0.0;

foreach ($cart as $id => $data) {
    $product = $manager->getById((int)$id);
    if ($product) {
        $qty      = (int)($data['qty'] ?? 0);
        $subtotal = $product->price * $qty;
        $total   += $subtotal;
        $items[]  = ['product' => $product, 'qty' => $qty, 'subtotal' => $subtotal];
    }
}
?>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panier - Glow Cosmetics</title>
</head>
<body>
<?php include "header.php"; ?>

<main class="container" style="padding-top:48px; padding-bottom:80px;">
    <div class="page-header">
        <div>
            <h1 class="page-title">Mon panier</h1>
            <p class="page-count"><?= count($items) ?> article<?= count($items)!==1?'s':'' ?></p>
        </div>
        <a href="catalogue.php" class="btn btn-ghost">&larr; Continuer mes achats</a>
    </div>

    <?php if (isset($_GET['order_success'])): ?>
        <div class="alert alert-success" style="margin-top:20px;">
            Votre commande a ete passee avec succes ! Merci pour votre achat.
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['order_error'])): ?>
        <div class="alert alert-error" style="margin-top:20px;">
            Une erreur est survenue lors de la commande. Veuillez reessayer.
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['stock_error'])): ?>
        <div class="alert alert-error" style="margin-top:20px;">
            La quantite demandee depasse le stock disponible.
        </div>
    <?php endif; ?>

    <?php if (empty($items)): ?>
        <div class="empty-state" style="margin-top:60px;">
            <svg width="64" height="64" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                      d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            <h3>Votre panier est vide</h3>
            <p>Decouvrez nos produits et ajoutez-en a votre panier.</p>
            <a href="catalogue.php" class="btn btn-rose">Decouvrir la boutique</a>
        </div>
    <?php else: ?>
        <div style="display:grid; grid-template-columns:1fr 320px; gap:32px; margin-top:32px; align-items:start;">
            <div>
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Produit</th>
                            <th>Prix unitaire</th>
                            <th>Quantite</th>
                            <th>Sous-total</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                            <tr>
                                <td>
                                    <div style="display:flex; align-items:center; gap:14px;">
                                        <img src="../public/uploads/products/<?= htmlspecialchars($item['product']->image_url) ?>"
                                             alt="<?= htmlspecialchars($item['product']->name) ?>"
                                             style="width:52px; height:52px; object-fit:cover; border:1px solid var(--gold-light);"
                                             onerror="this.style.display='none'">
                                        <div>
                                            <strong style="font-family:'Cormorant Garamond',serif; font-size:1rem;">
                                                <?= htmlspecialchars($item['product']->name) ?>
                                            </strong>
                                            <div style="font-size:0.75rem; color:var(--muted);">
                                                <?= htmlspecialchars($item['product']->category) ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td><?= number_format($item['product']->price, 3) ?> TND</td>
                                <td>
                                    <form action="../controllers/cartController.php" method="GET" style="display:flex;align-items:center;gap:6px;">
                                        <input type="hidden" name="action" value="update">
                                        <input type="hidden" name="id" value="<?= $item['product']->id ?>">
                                        <input type="number" name="qty" value="<?= $item['qty'] ?>"
                                               min="1" max="<?= $item['product']->quantity ?>"
                                               class="qty-input" onchange="this.form.submit()">
                                    </form>
                                </td>
                                <td><strong><?= number_format($item['subtotal'], 3) ?> TND</strong></td>
                                <td>
                                    <a href="../controllers/cartController.php?action=remove&id=<?= $item['product']->id ?>"
                                       style="color:var(--error); font-size:0.75rem; text-decoration:none;"
                                       onclick="return confirm('Retirer cet article ?')">x</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <tr class="cart-total-row">
                            <td colspan="3" style="text-align:right; padding-right:20px;">Total</td>
                            <td colspan="2"><?= number_format($total, 3) ?> TND</td>
                        </tr>
                    </tbody>
                </table>

                <div style="margin-top:16px;">
                    <a href="../controllers/cartController.php?action=clear"
                       class="btn btn-ghost" style="font-size:0.72rem;"
                       onclick="return confirm('Vider le panier ?')">Vider le panier</a>
                </div>
            </div>

            <div class="form-card" style="padding:32px;">
                <h3 style="font-family:'Cormorant Garamond',serif; font-size:1.4rem; font-weight:300; margin-bottom:20px;">
                    Recapitulatif
                </h3>
                <div style="display:flex; justify-content:space-between; margin-bottom:10px; font-size:0.88rem;">
                    <span style="color:var(--muted)">Sous-total</span>
                    <span><?= number_format($total, 3) ?> TND</span>
                </div>
                <div style="display:flex; justify-content:space-between; margin-bottom:10px; font-size:0.88rem;">
                    <span style="color:var(--muted)">Livraison</span>
                    <span style="color:var(--success)">Gratuite</span>
                </div>
                <div class="divider" style="margin:16px 0;"></div>
                <div style="display:flex; justify-content:space-between; font-family:'Cormorant Garamond',serif; font-size:1.3rem; font-weight:600; margin-bottom:24px;">
                    <span>Total</span>
                    <span><?= number_format($total, 3) ?> TND</span>
                </div>
                <a href="../controllers/orderController.php" class="btn btn-rose btn-full">
                    Passer la commande
                </a>
                <p style="font-size:0.72rem; color:var(--muted); text-align:center; margin-top:12px;">
                    Paiement securise a la livraison
                </p>
            </div>
        </div>
    <?php endif; ?>
</main>
</body>
</html>
