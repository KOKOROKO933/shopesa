<?php
// views/connexion.php
// Ce fichier contient également son propre en-tête, son formulaire et son pied de page fussionnés.
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShopESA - Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex flex-column min-vh-100">

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

    <div class="container flex-grow-1">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h3 class="card-title mb-0">Connexion Client / Admin</h3>
                    </div>
                    <div class="card-body">
                        
                        <?php if (!empty($erreur)): ?>
                            <div class="alert alert-danger"><?= $erreur; ?></div>
                        <?php endif; ?>

                        <form action="index.php?page=connexion" method="POST">
                            <div class="mb-3">
                                <label for="email" class="form-label">Adresse Email</label>
                                <input type="email" name="email" id="email" class="form-control" required value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Mot de passe</label>
                                <input type="password" name="password" id="password" class="form-control" required>
                            </div>

                            <button type="submit" class="btn btn-success w-100">Se connecter</button>
                        </form>

                    </div>
                    <div class="card-footer text-center text-muted">
                        Nouveau sur ShopESA ? <a href="index.php?page=inscription">Créez un compte ici</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-dark text-white text-center py-3 mt-5">
        <p class="mb-0">&copy; 2026 ShopESA - ESA-AGOE. Tous droits réservés.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>