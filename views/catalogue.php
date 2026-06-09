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
    <div class="container mb-4">
        <div class="d-flex flex-wrap gap-2 justify-content-center">
            <a href="index.php?page=catalogue" 
                class="btn <?= empty($catIdSelectionne) ? 'btn-dark' : 'btn-outline-dark' ?> rounded-pill fw-bold">
                    Tous les produits
            </a>

            <?php foreach ($categories as $cat): ?>
                <a href="index.php?page=catalogue&cat_id=<?= $cat['id'] ?>" 
                    class="btn <?= ($catIdSelectionne == $cat['id']) ? 'btn-danger' : 'btn-outline-danger' ?> rounded-pill fw-bold">
                    <?= htmlspecialchars($cat['nom']) ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>  
        <?php if (empty($products)): ?>
            <div class="alert alert-info text-center">Aucun produit n'est disponible pour le moment.</div>
        <?php else: ?>
           <div class="row">
            
                <?php foreach ($products as $product): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm border-0">
                          
                            <div class="card-img-top-wrapper bg-light" style="height: 400px; overflow: hidden;">
                                <?php 
                                $imageName = !empty($product['image']) ? $product['image'] : 'default.jpg';
                                $imagePath = 'public/images/' . $imageName;
                                ?>
                                <img src="<?= $imagePath ?>" 
                                    alt="<?= htmlspecialchars($product['nom']) ?>" 
                                    class="img-fluid w-100 h-100" 
                                    style="object-fit: cover;">
                            </div>

                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title fw-bold"><?= htmlspecialchars($product['nom']) ?></h5>
                                <p class="card-text text-muted flex-grow-1">
                                    <?= htmlspecialchars($product['description'] ?? '') ?>
                                </p>
                                
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="fs-5 fw-bold text-primary">
                                        <?= number_format($product['prix'], 0, ',', ' ') ?> F CFA
                                    </span>
                                    <span class="badge bg-<?= $product['stock'] > 0 ? 'success' : 'danger' ?>">
                                        <?= $product['stock'] > 0 ? 'En stock (' . $product['stock'] . ')' : 'Rupture' ?>
                                    </span>
                                </div>

                                <a href="index.php?page=ajouter_panier&id=<?= $product['id'] ?>" 
                                class="btn btn-dark w-100 <?= $product['stock'] <= 0 ? 'disabled' : '' ?>">
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