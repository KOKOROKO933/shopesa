<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShopCaphy - Espace Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex flex-column min-vh-100">

    <nav class="navbar navbar-expand-lg navbar-dark bg-danger mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php?page=home">ShopCaphy Dashboard Admin</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link text-white" href="index.php?page=catalogue">Voir le site public</a>
                <a class="btn btn-sm btn-outline-light ms-3" href="index.php?page=deconnexion">Déconnexion</a>
            </div>
        </div>
    </nav>

    <div class="container flex-grow-1">
        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0">Ajouter un Produit</h5>
                    </div>
                    <div class="card-body">
                        <?php if ($erreur): ?><div class="alert alert-danger"><?= $erreur ?></div><?php endif; ?>
                        <?php if ($succes): ?><div class="alert alert-success"><?= $succes ?></div><?php endif; ?>

                        <form action="index.php?page=admin_dashboard" method="POST">
                            <input type="hidden" name="action" value="ajouter">
                            <div class="mb-3">
                                <label class="form-label">Nom du produit *</label>
                                <input type="text" name="nom" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="3"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Prix (F CFA) *</label>
                                <input type="number" step="0.01" name="prix" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Stock initial *</label>
                                <input type="number" name="stock" class="form-control" required value="10">
                            </div>
                            <button type="submit" class="btn btn-danger w-100">Enregistrer le produit</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0">Gestion du Stock Actuel</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nom</th>
                                        <th>Prix</th>
                                        <th>Stock</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(empty($products)): ?>
                                        <tr><td colspan="5" class="text-center py-4 text-muted">Aucun produit en stock.</td></tr>
                                    <?php else: ?>
                                        <?php foreach($products as $product): ?>
                                            <tr>
                                                <td>#<?= $product['id'] ?></td>
                                                <td class="fw-bold"><?= htmlspecialchars($product['nom']) ?></td>
                                                <td><?= number_format($product['prix'], 0, ',', ' ') ?> F</td>
                                                <td><span class="badge bg-<?= $product['stock'] > 0 ? 'secondary' : 'danger' ?>"><?= $product['stock'] ?></span></td>
                                                <td class="text-center">
                                                    <a href="index.php?page=admin_dashboard&action=supprimer&id=<?= $product['id'] ?>" 
                                                       class="btn btn-sm btn-outline-danger" 
                                                       onclick="return confirm('Supprimer ce produit ?')">Supprimer</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-dark text-white text-center py-3 mt-5">
        <p class="mb-0">&copy; 2026 ShopCaphy - Espace Admin.</p>
    </footer>
</body>
</html>