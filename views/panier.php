<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShopCaphy - Votre Panier</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex flex-column min-vh-100">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php?page=home">ShopCaphy</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="index.php?page=catalogue">Catalogue</a>
                <a class="nav-link active" href="index.php?page=panier">Panier</a>
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

    <div class="container my-5">
    <h2 class="fw-bold mb-4">Votre Panier d'Achat</h2>

    <?php if (empty($cartItems)): ?>
        <div class="alert alert-warning text-center py-4">
            <p class="mb-3">Votre panier est actuellement vide.</p>
            <a href="index.php?page=catalogue" class="btn btn-dark fw-bold">Découvrir nos produits</a>
        </div>
    <?php else: ?>
        <div class="row">
            <div class="col-lg-8">
                <div class="table-responsive bg-white shadow-sm rounded p-3">
                    <table class="table align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Produit</th>
                                <th>Prix</th>
                                <th>Quantité</th>
                                <th>Sous-total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cartItems as $item): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="public/images/<?= htmlspecialchars($item['image'] ?? 'default.jpg') ?>" 
                                                 alt="<?= htmlspecialchars($item['nom']) ?>" 
                                                 style="width: 60px; height: 60px; object-fit: cover;" 
                                                 class="rounded me-3">
                                            <span class="fw-bold"><?= htmlspecialchars($item['nom']) ?></span>
                                        </div>
                                    </td>
                                    <td><?= number_format($item['prix'], 0, ',', ' ') ?> F CFA</td>
                                    <td>
                                        <span class="badge bg-dark px-3 py-2 fs-6"><?= $item['quantite'] ?></span>
                                    </td>
                                    <td class="fw-bold text-primary"><?= number_format($item['sous_total'], 0, ',', ' ') ?> F CFA</td>
                                    <td>
                                        <a href="index.php?page=supprimer_panier&id=<?= $item['id'] ?>" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i> Retirer
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

                <div class="col-lg-4">
                    <div class="card shadow-sm border-0 p-4 bg-white">
                    <h4 class="fw-bold mb-4">Résumé de la commande</h4>
                    <div class="d-flex justify-content-between mb-3 fs-5">
                        <span>Total articles :</span>
                        <span class="fw-bold text-primary"><?= number_format($totalGeneral, 0, ',', ' ') ?> F CFA</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-4 fs-4 fw-bold">
                        <span>Net à payer :</span>
                        <span><?= number_format($totalGeneral, 0, ',', ' ') ?> F CFA</span>
                    </div>
                    <a href="index.php?page=commander" class="btn btn-danger w-100 py-2 fw-bold disabled">
                        Passer à la caisse (Bientôt disponible)
                    </a>
                    <a href="index.php?page=catalogue" class="btn btn-outline-dark w-100 mt-2 py-2">
                        Continuer mes achats
                    </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <footer class="bg-dark text-white text-center py-3 mt-5">
        <p class="mb-0">&copy; 2026 ShopCaphy - Votre panier d'achat.</p>
    </footer>
</body>
</html>