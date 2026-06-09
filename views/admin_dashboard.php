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
                <a class="nav-link text-white fw-bold" href="index.php?page=catalogue">Voir le site public</a>
                <a class="btn btn-sm btn-outline-light ms-3" href="index.php?page=deconnexion">Déconnexion</a>
            </div>
        </div>
    </nav>

    <div class="container flex-grow-1">
        <div class="row">
            
            <div class="col-lg-4 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header <?= $productToEdit ? 'bg-primary' : 'bg-dark' ?> text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><?= $productToEdit ? 'Modifier le Produit' : 'Ajouter un Produit' ?></h5>
                        <?php if ($productToEdit): ?>
                            <a href="index.php?page=admin_dashboard" class="btn btn-sm btn-light text-primary fw-bold">Annuler</a>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <?php if ($erreur): ?><div class="alert alert-danger py-2"><?= $erreur ?></div><?php endif; ?>
                        <?php if ($succes): ?><div class="alert alert-success py-2"><?= $succes ?></div><?php endif; ?>

                        <form action="index.php?page=admin_dashboard" method="POST" enctype="multipart/form-data">
    
                            <input type="hidden" name="action" value="<?= $productToEdit ? 'modifier' : 'ajouter' ?>">
                            <?php if ($productToEdit): ?>
                                <input type="hidden" name="id" value="<?= $productToEdit['id'] ?>">
                            <?php endif; ?>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Nom du produit *</label>
                                <input type="text" name="nom" class="form-control" required value="<?= $productToEdit ? htmlspecialchars($productToEdit['nom']) : '' ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Description</label>
                                <textarea name="description" class="form-control" rows="3"><?= $productToEdit ? htmlspecialchars($productToEdit['description']) : '' ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Prix (F CFA) *</label>
                                <input type="number" step="0.01" name="prix" class="form-control" required value="<?= $productToEdit ? $productToEdit['prix'] : '' ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Stock *</label>
                                <input type="number" name="stock" class="form-control" required value="<?= $productToEdit ? $productToEdit['stock'] : '10' ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Image du produit</label>
                                <input type="file" name="image_fichier" class="form-control" accept="image/*">
                                <?php if ($productToEdit && !empty($productToEdit['image'])): ?>
                                    <div class="form-text text-muted">Image actuelle : <?= htmlspecialchars($productToEdit['image']) ?></div>
                                <?php endif; ?>
                            </div>
                            
                            <button type="submit" class="btn <?= $productToEdit ? 'btn-primary' : 'btn-danger' ?> w-100 fw-bold">
                                <?= $productToEdit ? 'Enregistrer les modifications' : 'Enregistrer le produit' ?>
                            </button>
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
                                        <th class="text-center" style="width: 180px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(empty($products)): ?>
                                        <tr><td colspan="5" class="text-center py-4 text-muted">Aucun produit en stock.</td></tr>
                                    <?php else: ?>
                                        <?php foreach($products as $product): ?>
                                            <tr>
                                                <td>#<?= $product['id'] ?></td>
                                                <td class="fw-bold text-secondary"><?= htmlspecialchars($product['nom']) ?></td>
                                                <td class="fw-bold"><?= number_format($product['prix'], 0, ',', ' ') ?> F</td>
                                                <td>
                                                    <span class="badge bg-<?= $product['stock'] > 0 ? 'success' : 'danger' ?>">
                                                        <?= $product['stock'] ?>
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group" role="group">
                                                        <a href="index.php?page=admin_dashboard&action=modifier&id=<?= $product['id'] ?>" 
                                                           class="btn btn-sm btn-outline-primary">
                                                            Modifier
                                                        </a>
                                                        <a href="index.php?page=admin_dashboard&action=supprimer&id=<?= $product['id'] ?>" 
                                                           class="btn btn-sm btn-outline-danger" 
                                                           onclick="return confirm('Supprimer définitivement ce produit ?')">
                                                            Supprimer
                                                        </a>
                                                    </div>
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
    <div class="container my-5">
    <hr class="my-5">
    <h3 class="fw-bold text-dark mb-4">🗂️ Gestion des Commandes Clients</h3>

        <?php if (empty($allOrders)): ?>
            <div class="alert alert-secondary text-center">
                Aucune commande n'a encore été passée sur la boutique.
            </div>
        <?php else: ?>
            <div class="table-responsive bg-white shadow-sm rounded p-3">
                <table class="table align-middle table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>N°</th>
                            <th>Client</th>
                            <th>Date</th>
                            <th>Montant TTC</th>
                            <th>Statut Actuel</th>
                            <th class="text-center">Changer le Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($allOrders as $order): ?>
                            <tr>
                                <td class="fw-bold">#<?= $order['id'] ?></td>
                                <td>
                                    <strong><?= htmlspecialchars($order['client_nom']) ?></strong><br>
                                    <small class="text-muted"><?= htmlspecialchars($order['client_email']) ?></small>
                                </td>
                                <td><?= date('d/m/Y à H:i', strtotime($order['created_at'])) ?></td>
                                <td class="fw-bold text-danger"><?= number_format($order['total_ttc'], 0, ',', ' ') ?> F CFA</td>
                                <td>
                                    <?php if ($order['status'] === 'En attente'): ?>
                                        <span class="badge bg-warning text-dark px-2 py-2">En attente</span>
                                    <?php elseif ($order['status'] === 'Livrée'): ?>
                                        <span class="badge bg-success px-2 py-2">Livrée</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary px-2 py-2"><?= htmlspecialchars($order['status']) ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <form action="index.php?page=admin_modifier_statut" method="POST" class="d-flex gap-2 justify-content-center">
                                        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                        <select name="status" class="form-select form-select-sm" style="width: 140px;">
                                            <option value="En attente" <?= $order['status'] === 'En attente' ? 'selected' : '' ?>>En attente</option>
                                            <option value="En cours de livraison" <?= $order['status'] === 'En cours de livraison' ? 'selected' : '' ?>>En cours...</option>
                                            <option value="Livrée" <?= $order['status'] === 'Livrée' ? 'selected' : '' ?>>Livrée</option>
                                            <option value="Annulée" <?= $order['status'] === 'Annulée' ? 'selected' : '' ?>>Annulée</option>
                                        </select>
                                        <button type="submit" class="btn btn-sm btn-primary">Mettre à jour</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <footer class="bg-dark text-white text-center py-3 mt-5">
        <p class="mb-0">&copy; 2026 ShopCaphy - Espace Admin.</p>
    </footer>
</body>
</html>