<?php
// views/inscription.php
// Ce fichier contient son propre en-tête, son formulaire et son pied de page fussionnés.
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShopCaphy - Inscription</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex flex-column min-vh-100">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php?page=home">ShopCaphy</a>
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
                    <div class="card-header bg-primary text-white">
                        <h3 class="card-title mb-0">Créer un compte</h3>
                    </div>
                    <div class="card-body">
                        
                        <?php if (!empty($erreur)): ?>
                            <div class="alert alert-danger"><?= $erreur; ?></div>
                        <?php endif; ?>

                        <?php if (!empty($succes)): ?>
                            <div class="alert alert-success"><?= $succes; ?></div>
                        <?php endif; ?>

                        <form action="index.php?page=inscription" method="POST">
                            <div class="mb-3">
                                <label for="nom" class="form-label">Nom complet</label>
                                <input type="text" name="nom" id="nom" class="form-control" required value="<?= isset($_POST['nom']) ? htmlspecialchars($_POST['nom']) : ''; ?>">
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Adresse Email</label>
                                <input type="email" name="email" id="email" class="form-control" required value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Mot de passe</label>
                                <input type="password" name="password" id="password" class="form-control" required placeholder="6 caractères minimum">
                            </div>

                            <button type="submit" class="btn btn-primary w-100">S'inscrire</button>
                        </form>

                    </div>
                    <div class="card-footer text-center text-muted">
                        Déjà membre ? <a href="index.php?page=connexion">Connectez-vous ici</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-dark text-white text-center py-3 mt-5">
        <p class="mb-0">&copy; 2026 ShopCaphy. Tous droits réservés.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>