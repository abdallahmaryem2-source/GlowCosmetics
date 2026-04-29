<!DOCTYPE html>
<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['client']) || $_SESSION['client']['role'] !== 'admin') {
    header("Location: login.php"); exit;
}
?>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un produit — Glow Cosmetics</title>
</head>
<body>
<?php include "header.php"; ?>

<main class="container-sm" style="padding-top:48px; padding-bottom:80px;">
    <div class="form-card">
        <h2 class="form-title">Nouveau produit</h2>
        <p class="form-subtitle">Remplissez les informations du produit à ajouter</p>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-error">⚠ Une erreur est survenue lors de l'ajout.</div>
        <?php endif; ?>

        <form action="../controllers/addProductController.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Désignation du produit</label>
                <input type="text" name="name" id="name" placeholder="ex: Crème hydratante visage" required>
            </div>

            <div class="form-group">
                <label for="desc">Description</label>
                <textarea name="desc" id="desc" placeholder="Décrivez le produit, ses bienfaits…"></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="price">Prix (TND)</label>
                    <input type="number" step="0.001" min="0" name="price" id="price" placeholder="0.000" required>
                </div>
                <div class="form-group">
                    <label for="quantity">Quantité en stock</label>
                    <input type="number" min="0" name="quantity" id="quantity" placeholder="0" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="promo_price">Prix promo (TND)</label>
                    <input type="number" step="0.001" min="0" name="promo_price" id="promo_price" placeholder="0.000">
                </div>
                <div class="form-group">
                    <label for="promo_label">Label promo</label>
                    <input type="text" name="promo_label" id="promo_label" placeholder="ex: Offre spéciale">
                </div>
            </div>

            <div class="form-group">
                <label for="category">Catégorie</label>
                <input type="text" name="category" id="category" placeholder="ex: Soin visage, Parfum…" required>
            </div>

            <div class="form-group">
                <label for="image_file">Image du produit</label>
                <input type="file" name="image_file" id="image_file" accept="image/*">
                <span class="form-hint">JPG, PNG, GIF ou WebP — max 2 Mo. Laissez vide pour utiliser l'image par défaut.</span>
            </div>

            <div style="margin-top:32px; display:flex; flex-direction:column; gap:12px;">
                <button type="submit" class="btn btn-rose btn-full">Confirmer l'ajout</button>
                <a href="catalogue.php" class="btn btn-ghost btn-full">Retour au catalogue</a>
            </div>
        </form>
    </div>
</main>
</body>
</html>