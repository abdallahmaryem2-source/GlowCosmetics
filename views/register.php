<!DOCTYPE html>
<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (isset($_SESSION['client'])) { header("Location: catalogue.php"); exit; }
?>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S'inscrire — Glow Cosmetics</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,400&family=Jost:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body class="auth-page">
    <div class="auth-card">
        <div class="auth-logo">GLOW<span>&middot;</span>COSMETICS</div>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-error">⚠ Cet email est déjà utilisé ou une erreur est survenue.</div>
        <?php endif; ?>

        <h2 class="form-title" style="text-align:center">Créer un compte</h2>
        <p class="form-subtitle" style="text-align:center">Rejoignez la communauté Glow</p>

        <form action="../controllers/registerController.php" method="POST">
            <div class="form-group">
                <label for="username">Nom d'utilisateur</label>
                <input type="text" name="username" id="username" placeholder="VotreNom" required autofocus>
            </div>
            <div class="form-group">
                <label for="email">Adresse email</label>
                <input type="email" name="email" id="email" placeholder="vous@exemple.com" required>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" name="password" id="password" placeholder="Min. 6 caractères" minlength="6" required>
            </div>
            <button type="submit" class="btn btn-rose btn-full" style="margin-top:8px;">Créer mon compte</button>
        </form>

        <div class="auth-divider">ou</div>

        <div class="auth-link">
            Déjà un compte ? <a href="login.php">Se connecter</a>
        </div>
    </div>
</body>
</html>