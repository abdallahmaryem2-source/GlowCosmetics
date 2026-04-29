<?php
// views/header.php
if (session_status() === PHP_SESSION_NONE) session_start();
$cartCount = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) $cartCount += $item['qty'];
}
$isAdmin = isset($_SESSION['client']) && $_SESSION['client']['role'] === 'admin';
$favCount = 0;
if (isset($_SESSION['client'])) {
    require_once __DIR__ . '/../config/db_connect.php';
    require_once __DIR__ . '/../models/favoriteManager.php';
    $favManager = new FavoriteManager($db);
    $favCount = $favManager->countByUser((int)$_SESSION['client']['id']);
}
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=Jost:wght@300;400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../assets/style.css">

<header class="site-header">
    <div class="header-inner">
        <a href="catalogue.php" class="logo">GLOW<span>&middot;</span>COSMETICS</a>
        <nav class="nav">
            <a href="catalogue.php" class="<?= $currentPage==='catalogue.php'?'active':'' ?>">Boutique</a>

            <?php if (isset($_SESSION['client'])): ?>
                <a href="order.php" class="<?= $currentPage==='order.php'?'active':'' ?>">Mes commandes</a>
                <a href="favorites.php" class="<?= $currentPage==='favorites.php'?'active':'' ?>">Favoris<?php if($favCount > 0): ?><span class="nav-badge"><?= $favCount ?></span><?php endif; ?></a>
                <a href="cart.php" class="<?= $currentPage==='cart.php'?'active':'' ?>">
                    Panier<?php if($cartCount > 0): ?><span class="nav-badge"><?= $cartCount ?></span><?php endif; ?>
                </a>
                <?php if($isAdmin): ?>
                    <a href="addProduct.php" class="<?= $currentPage==='addProduct.php'?'active':'' ?>">+ Produit</a>
                    <a href="admin.php" class="<?= $currentPage==='admin.php'?'active':'' ?>">Admin</a>
                <?php endif; ?>
                <a href="../controllers/logoutController.php">
                    Déconnexion <small style="opacity:.6">(<?= htmlspecialchars($_SESSION['client']['username']) ?>)</small>
                </a>
            <?php else: ?>
                <a href="login.php" class="<?= $currentPage==='login.php'?'active':'' ?>">Connexion</a>
                <a href="register.php" class="<?= $currentPage==='register.php'?'active':'' ?>">S'inscrire</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
