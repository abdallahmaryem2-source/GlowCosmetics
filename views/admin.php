<!DOCTYPE html>
<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['client']) || $_SESSION['client']['role'] !== 'admin') {
    header("Location: login.php"); exit;
}
require_once "../config/db_connect.php";
require_once "../models/ordersManager.php";
require_once "../models/productManager.php";
require_once "../models/userManager.php";

$oManager = new OrdersManager($db);
$pManager = new ProductManager($db);
$uManager = new UserManager($db);

$orders        = $oManager->getAllOrders();
$totalRevenue  = $oManager->getTotalRevenue();
$totalOrders   = $oManager->getTotalOrders();
$totalProducts = $pManager->getTotalCount();
$totalUsers    = $uManager->getTotalUsers();
?>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration — Glow Cosmetics</title>
</head>
<body>
<?php include "header.php"; ?>

<main class="container" style="padding-top:48px; padding-bottom:80px;">
    <div class="page-header">
        <div>
            <h1 class="page-title">Tableau de bord</h1>
            <p class="page-count">Vue d'ensemble de la boutique</p>
        </div>
        <a href="addProduct.php" class="btn btn-rose">+ Nouveau produit</a>
    </div>

    <!-- Stats -->
    <div class="stats-grid" style="margin-top:32px;">
        <div class="stat-card">
            <p class="stat-label">Chiffre d'affaires</p>
            <p class="stat-value"><?= number_format($totalRevenue, 3) ?> <small style="font-size:1rem">TND</small></p>
        </div>
        <div class="stat-card" style="border-left-color: var(--gold);">
            <p class="stat-label">Commandes totales</p>
            <p class="stat-value"><?= $totalOrders ?></p>
        </div>
        <div class="stat-card" style="border-left-color: var(--charcoal);">
            <p class="stat-label">Produits en catalogue</p>
            <p class="stat-value"><?= $totalProducts ?></p>
        </div>
        <div class="stat-card" style="border-left-color: var(--blush);">
            <p class="stat-label">Clients inscrits</p>
            <p class="stat-value"><?= $totalUsers ?></p>
        </div>
    </div>

    <!-- Orders Table -->
    <div style="margin-top:48px;">
        <h2 class="page-title" style="font-size:1.5rem; margin-bottom:24px;">Toutes les commandes</h2>

        <?php if (isset($_GET['status_updated'])): ?>
            <div class="alert alert-success mb-24">✓ Statut de la commande mis à jour.</div>
        <?php endif; ?>

        <?php if (empty($orders)): ?>
            <div class="alert alert-info">Aucune commande pour le moment.</div>
        <?php else: ?>
            <table class="orders-table">
                <thead>
                    <tr>
                        <th># Commande</th>
                        <th>Client</th>
                        <th>Email</th>
                        <th>Date</th>
                        <th>Montant</th>
                        <th>Statut</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><strong>#<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?></strong></td>
                            <td><?= htmlspecialchars($order['username'] ?? 'N/A') ?></td>
                            <td style="font-size:0.8rem; color:var(--muted);"><?= htmlspecialchars($order['email'] ?? '') ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($order['order_date'])) ?></td>
                            <td><strong style="font-family:'Cormorant Garamond',serif;"><?= number_format($order['total_amount'], 3) ?> TND</strong></td>
                            <td>
                                <?php
                                $statusMap = [
                                    'pending'   => ['label' => 'En attente',  'class' => 'status-pending'],
                                    'confirmed' => ['label' => 'Confirmée',   'class' => 'status-confirmed'],
                                    'shipped'   => ['label' => 'Expédiée',    'class' => 'status-shipped'],
                                    'cancelled' => ['label' => 'Annulée',     'class' => 'status-cancelled'],
                                ];
                                $s = $statusMap[$order['status']] ?? ['label' => $order['status'], 'class' => 'status-pending'];
                                ?>
                                <span class="status-badge <?= $s['class'] ?>"><?= $s['label'] ?></span>
                            </td>
                            <td>
                                <form action="../controllers/updateStatusController.php" method="POST" style="display:flex; gap:6px; align-items:center;">
                                    <input type="hidden" name="id" value="<?= $order['id'] ?>">
                                    <select name="status" class="filter-bar" style="padding:6px 10px; font-size:0.75rem; margin:0;">
                                        <option value="pending"   <?= $order['status']==='pending'?'selected':''   ?>>En attente</option>
                                        <option value="confirmed" <?= $order['status']==='confirmed'?'selected':'' ?>>Confirmée</option>
                                        <option value="shipped"   <?= $order['status']==='shipped'?'selected':''   ?>>Expédiée</option>
                                        <option value="cancelled" <?= $order['status']==='cancelled'?'selected':'' ?>>Annulée</option>
                                    </select>
                                    <button type="submit" class="btn btn-primary" style="padding:6px 12px; font-size:0.7rem;">OK</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</main>
</body>
</html>