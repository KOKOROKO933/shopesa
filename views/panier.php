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

    <div class="container flex-grow-1">
        <h2 class="mb-4 fw-bold">Votre Panier d'Achats</h2>

        <?php if (empty($panier)): ?>
            <div class="alert alert-warning text-center py-5">
                <p class="fs-5 mb-3">Votre panier est malheureusement vide...</p>
                <a href="index.php?page=catalogue" class="btn btn-dark">Découvrir nos produits</a>
            </div>
        <?php else: ?>
            <div class="row">
                <div class="col-lg-8 mb-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-0">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Produit</th>
                                        <th>Prix Unitaire</th>
                                        <th>Quantité</th>
                                        <th>Sous-total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($panier as $id => $item): ?>
                                        <tr>
                                            <td class="fw-bold"><?= htmlspecialchars($item['nom']) ?></td>
                                            <td><?= number_format($item['prix'], 0, ',', ' ') ?> F</td>
                                            <td><span class="badge bg-secondary fs-6"><?= $item['quantite'] ?></span></td>
                                            <td class="fw-bold text-primary"><?= number_format($item['prix'] * $item['quantite'], 0, ',', ' ') ?> F CFA</td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-dark text-white fw-bold">Résumé de la commande</div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-3 fs-5">
                                <span>Total à payer :</span>
                                <span class="fw-bold text-success"><?= number_format($total, 0, ',', ' ') ?> F CFA</span>
                            </div>
                            <hr>
                            <button class="btn btn-success w-100 mb-2">Procéder au paiement</button>
                            <a href="index.php?page=vider_panier" class="btn btn-outline-danger btn-sm w-100">Vider le panier</a>
                        </div>
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