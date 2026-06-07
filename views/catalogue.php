<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShopCaphy - Catalogue</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex flex-column min-vh-100">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php?page=home">ShopCaphy</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link active" href="index.php?page=catalogue">Catalogue</a>
                <a class="nav-link" href="index.php?page=panier">Panier</a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php if ($_SESSION['user_role'] === 'admin'): ?>
                        <a class="nav-link text-warning fw-bold" href="index.php?page=admin_dashboard">Admin</a>
                    <?php endif; ?>
                    <span class="navbar-text text-white ms-2 me-3">Bonjour, <?= htmlspecialchars($_SESSION['user_nom']) ?></span>
                    <a class="btn btn-outline-danger btn-sm" href="index.php?page=deconnexion">Déconnexion</a>
                <?php else: ?>
                    <a class="nav-link" href="index.php?page=connexion">Connexion</a>
                    <a class="nav-link" href="index.php?page=inscription">S'inscrire</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="container flex-grow-1">
        <h2 class="mb-4 text-center fw-bold">Notre Catalogue de Produits</h2>

        <?php if (empty($products)): ?>
            <div class="alert alert-info text-center">Aucun produit n'est disponible pour le moment.</div>
        <?php else: ?>
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <?php foreach ($products as $product): ?>
                    <div class="col">
                        <div class="card h-100 shadow-sm">
                            <div class="bg-secondary text-white text-center py-5 rounded-top" style="font-size: 14px;">
                                Image Produit
                            </div>
                            <div class="card-body">
                                <h5 class="card-title fw-bold"><?= htmlspecialchars($product['nom']) ?></h5>
                                <p class="card-text text-muted text-truncate"><?= htmlspecialchars($product['description']) ?></p>
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <span class="fs-4 fw-bold text-primary"><?= number_format($product['prix'], 2, ',', ' ') ?> F CFA</span>
                                    <span class="badge bg-<?= $product['stock'] > 0 ? 'success' : 'danger' ?>">
                                        <?= $product['stock'] > 0 ? 'En stock ('.$product['stock'].')' : 'Rupture' ?>
                                    </span>
                                </div>
                            </div>
                            <div class="card-footer bg-white border-top-0">
                                <!-- <button class="btn btn-dark w-100 <?= $product['stock'] <= 0 ? 'disabled' : '' ?>">
                                    Ajouter au panier
                                </button> -->
                                <a href="index.php?page=ajouter_panier&id=<?= $product['id'] ?>" class="btn btn-dark w-100 <?= $product['stock'] <= 0 ? 'disabled' : '' ?>">
                                    Ajouter au panier
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <footer class="bg-dark text-white text-center py-3 mt-5">
        <p class="mb-0">&copy; 2026 ShopCaphy . Tous droits réservés.</p>
    </footer>
</body>
</html>