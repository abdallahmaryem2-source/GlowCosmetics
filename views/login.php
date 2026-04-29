<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['client'])) {
    header("Location: catalogue.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — Glow Cosmetics</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,400&family=Jost:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body class="auth-page">
    <div class="auth-card">
        <div class="auth-logo">GLOW<span>&middot;</span>COSMETICS</div>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-error">⚠ Email ou mot de passe incorrect.</div>
        <?php endif; ?>
        <?php if (isset($_GET['registered'])): ?>
            <div class="alert alert-success">✓ Compte créé ! Vous pouvez vous connecter.</div>
        <?php endif; ?>
        <?php if (isset($_GET['logout'])): ?>
            <div class="alert alert-info">Vous avez été déconnecté(e).</div>
        <?php endif; ?>

        <h2 class="form-title" style="text-align:center">Connexion</h2>
        <p class="form-subtitle" style="text-align:center">Accédez à votre espace client</p>

        <form action="../controllers/loginController.php" method="POST">
            <div class="form-group">
                <label for="email">Adresse email</label>
                <input type="email" name="email" id="email" placeholder="vous@exemple.com" required autofocus>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" name="password" id="password" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn btn-rose btn-full" style="margin-top:8px;">Se connecter</button>
        </form>

        <div class="auth-divider">ou</div>

        <div class="auth-link">
            Pas encore de compte ? <a href="register.php">Créer un compte</a>
        </div>
    </div>
</body>
</html>