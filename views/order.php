<!DOCTYPE html>
<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['client'])) { header("Location: login.php"); exit; }

require_once "../config/db_connect.php";
require_once "../models/ordersManager.php";

$manager = new OrdersManager($db);
$orders  = $manager->getOrdersByUser((int)$_SESSION['client']['id']);
?>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes commandes — Glow Cosmetics</title>
</head>
<body>
<?php include "header.php"; ?>

<main class="container" style="padding-top:48px; padding-bottom:80px;">
    <div class="page-header">
        <div>
            <h1 class="page-title">Mes commandes</h1>
            <p class="page-count"><?= count($orders) ?> commande<?= count($orders)!==1?'s':'' ?></p>
        </div>
        <a href="catalogue.php" class="btn btn-rose">Continuer mes achats</a>
    </div>

    <?php if (empty($orders)): ?>
        <div class="empty-state" style="margin-top:60px;">
            <svg width="64" height="64" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <h3>Aucune commande</h3>
            <p>Vous n'avez pas encore passé de commande.</p>
            <a href="catalogue.php" class="btn btn-rose">Découvrir nos produits</a>
        </div>
    <?php else: ?>
        <table class="orders-table" style="margin-top:32px;">
            <thead>
                <tr>
                    <th># Commande</th>
                    <th>Date</th>
                    <th>Montant total</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><strong>#<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?></strong></td>
                        <td><?= date('d/m/Y à H:i', strtotime($order['order_date'])) ?></td>
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
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</main>
</body>
</html>