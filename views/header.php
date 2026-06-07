<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShopESA - Plateforme E-Commerce</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php?page=home">ShopESA</a>
        <div class="navbar-nav ms-auto">
            <a class="nav-link" href="index.php?page=catalogue">Catalogue</a>
            <a class="nav-link" href="index.php?page=panier">Panier</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <span class="navbar-text text-white me-3">Bonjour, <?= htmlspecialchars($_SESSION['user_nom']) ?></span>
                <a class="btn btn-outline-danger btn-sm" href="index.php?page=deconnexion">Déconnexion</a>
            <?php else: ?>
                <a class="nav-link" href="index.php?page=connexion">Connexion</a>
                <a class="nav-link text-white fw-bold" href="index.php?page=inscription">S'inscrire</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<div class="container">
    /div> <footer class="bg-dark text-white text-center py-3 mt-5">
    <p class="mb-0">&copy; 2026 ShopESA - ESA-AGOE. Tous droits réservés.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>