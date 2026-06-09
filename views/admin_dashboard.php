<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShopCaphy - Espace Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .admin-banner {
            background: linear-gradient(135deg, #112233 0%, #1a3a5c 100%);
            color: #ffffff;
            padding: 2.5rem 2rem;
            border-radius: 0 0 15px 15px;
        }
        .stat-card {
            border: none;
            border-radius: 12px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.15) !important;
        }
        .card-ca { background-color: #3b71ca !important; }
        .card-attente { background-color: #e4be5b !important; color: #212529 !important; }
        .card-total { background-color: #0b0f19 !important; }
        
        .stat-icon {
            font-size: 2.5rem;
            opacity: 0.8;
        }
    </style>
</head>
<body class="bg-light d-flex flex-column min-vh-100">

    <div class="admin-banner shadow-sm mb-5">
        <div class="container-fluid d-flex flex-column flex-md-row justify-content-between align-items-center gap-4">
            
            <div>
                <h1 class="fw-bold mb-0 tracking-tight" style="font-size: 2.2rem;">
                    ShopCaphy <span class="fw-light opacity-75" style="font-size: 1.6rem;">Dashboard Admin</span>
                </h1>
            </div>

            <div class="d-flex flex-wrap flex-md-nowrap gap-3 justify-content-center align-items-stretch" style="max-width: 900px; flex-grow: 1;">
                
                <div class="card stat-card card-ca text-white p-3 shadow-sm flex-fill" style="min-width: 200px;">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon">🪙</div>
                        <div>
                            <small class="text-uppercase fw-bold opacity-75 d-block" style="font-size: 0.75rem; letter-spacing: 0.5px;">Chiffre d'Affaires</small>
                            <span class="fs-4 fw-bold text-nowrap"><?= number_format($stats['ca'], 0, ',', ' ') ?> F CFA</span>
                        </div>
                    </div>
                </div>

                <div class="card stat-card card-attente p-3 shadow-sm flex-fill" style="min-width: 200px;">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon">⏳</div>
                        <div>
                            <small class="text-uppercase fw-bold opacity-75 d-block" style="font-size: 0.75rem; letter-spacing: 0.5px;">Commandes en attente</small>
                            <span class="fs-4 fw-bold text-nowrap"><?= $stats['en_attente'] ?> commande(s)</span>
                        </div>
                    </div>
                </div>

                <div class="card stat-card card-total text-white p-3 shadow-sm flex-fill" style="min-width: 200px;">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon">📦</div>
                        <div>
                            <small class="text-uppercase fw-bold opacity-75 d-block" style="font-size: 0.75rem; letter-spacing: 0.5px;">Total Commandes</small>
                            <span class="fs-4 fw-bold text-nowrap"><?= $stats['total_commandes'] ?> reçue(s)</span>
                        </div>
                    </div>
                </div>

            </div>

            <div class="d-flex align-items-center gap-2 text-nowrap">
                <a href="index.php?page=home" class="btn btn-sm btn-outline-light px-3 py-2 rounded-pill fw-semibold">
                    👁️ Voir le site public
                </a>
                <a href="index.php?page=deconnexion" class="btn btn-sm btn-danger px-3 py-2 rounded-pill fw-semibold">
                    Déconnexion 🔑
                </a>
            </div>

        </div>
    </div>
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
    <a href="index.php?page=export_sales" class="btn btn-success shadow-sm mb-3">
    📥 Exporter les Ventes (CSV / Excel)
    </a>
    <div class="row g-4 my-4 px-2">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-3 bg-white p-4">
                <h5 class="fw-bold text-dark mb-3">📈 Évolution du Chiffre d'Affaires</h5>
                <div style="position: relative; height:220px;">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 rounded-3 bg-white p-4">
                <h5 class="fw-bold text-dark mb-3">📊 Vue d'ensemble</h5>
                <div style="position: relative; height:300px; display: flex; justify-content: center;">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid px-4 my-5">
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
                            <!-- <th>Articles</th> -->
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
                                <!-- <td>
                                    <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#modalCommande<?= $order['id'] ?>">
                                        👁️ Voir articles (<?= count($order['items']) ?>)
                                    </button>
                                </td> -->
                                <td>
                                    <?php if ($order['status'] === 'En attente'): ?>
                                        <span class="badge bg-warning text-dark px-2 py-2">En attente</span>
                                    <?php elseif ($order['status'] === 'Livrée'): ?>
                                        <span class="badge bg-success px-2 py-2">Livrée</span>
                                    <?php elseif ($order['status'] === 'En cours de livraison'): ?>
                                        <span class="badge bg-info text-dark px-2 py-2">En cours...</span>
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

            <?php foreach ($allOrders as $order): ?>
                <div class="modal fade" id="modalCommande<?= $order['id'] ?>" tabindex="-1" aria-labelledby="labelModal<?= $order['id'] ?>" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content text-start">
                            <div class="modal-header bg-dark text-white">
                                <h5 class="modal-title fw-bold" id="labelModal<?= $order['id'] ?>">Détails de la Commande #<?= $order['id'] ?></h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <strong>👤 Client :</strong> <?= htmlspecialchars($order['client_nom']) ?> (<?= htmlspecialchars($order['client_email']) ?>)<br>
                                    <strong>📅 Date de commande :</strong> <?= date('d/m/Y à H:i', strtotime($order['created_at'])) ?>
                                </div>
                                <hr>
                                <h6 class="fw-bold text-secondary mb-3">📦 Panier commandé :</h6>
                                <div class="table-responsive">
                                    <table class="table align-middle">
                                        <thead>
                                            <tr class="table-light">
                                                <th>Produit</th>
                                                <th class="text-center">Quantité</th>
                                                <th class="text-end">Prix Unitaire</th>
                                                <th class="text-end">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($order['items'] as $item): ?>
                                                <?php 
                                                    // Sécurité : Détection automatique des noms de colonnes (Français ou Anglais)
                                                    $quantite = $item['quantity'] ?? $item['quantite'] ?? 1;
                                                    
                                                    // On prend le prix payé au moment de la commande, sinon le prix actuel du produit
                                                    $prix_unitaire = $item['price'] ?? $item['prix'] ?? 0; 
                                                    
                                                    $total_ligne = $prix_unitaire * $quantite;
                                                    
                                                    // Gestion de l'image par défaut si elle est vide
                                                    $image_produit = !empty($item['image']) ? $item['image'] : 'default.jpg';
                                                ?>
                                                <tr>
                                                    <td>
                                                        <img src="public/images/<?= htmlspecialchars($image_produit) ?>" alt="" style="width: 45px; height: 45px; object-fit: cover;" class="rounded me-2">
                                                        <strong><?= htmlspecialchars($item['nom'] ?? 'Produit inconnu') ?></strong>
                                                    </td>
                                                    <td class="text-center fw-bold">x<?= $quantite ?></td>
                                                    <td class="text-end"><?= number_format($prix_unitaire, 0, ',', ' ') ?> F CFA</td>
                                                    <td class="text-end fw-bold text-primary"><?= number_format($total_ligne, 0, ',', ' ') ?> F CFA</td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="modal-footer bg-light">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Configuration du graphique de l'évolution des ventes (Line Chart)
        const ctxSales = document.getElementById('salesChart').getContext('2d');
        new Chart(ctxSales, {
            type: 'line',
            data: {
                labels: <?= $jsonLabels ?>, // Tes dates
                datasets: [{
                    label: 'Ventes (F CFA)',
                    data: <?= $jsonValues ?>, // Tes montants
                    borderColor: '#3b71ca',
                    backgroundColor: 'rgba(59, 113, 202, 0.08)', // Léger dégradé sous la courbe
                    borderWidth: 3,
                    
                    // 🪄 LES SECRETS DE LA COURBE LISSE :
                    tension: 0.4,                 // Ajuste la rondeur de la courbe (0 = droit, 0.4 = courbe parfaite)
                    cubicInterpolationMode: 'monotone', // Assure une transition douce entre les points
                    
                    fill: true,
                    pointBackgroundColor: '#1a3a5c',
                    pointRadius: 4,               // Taille du point pour qu'il soit bien visible
                    pointHoverRadius: 6           // Effet de survol sympa
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                // 🚀 Animations dynamiques au chargement
                animations: {
                    tension: {
                        duration: 1000,
                        easing: 'linear',
                        from: 1,
                        to: 0.4,
                        loop: false
                    }
                },
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { 
                        beginAtZero: true,
                        grid: { color: 'rgba(0, 0, 0, 0.05)' } // Grilles discrètes
                    },
                    x: {
                        grid: { display: false } // On cache la grille verticale pour un effet plus épuré
                    }
                }
            }
        });

        // Configuration du graphique des statuts de commandes (Doughnut)
        const ctxStatus = document.getElementById('statusChart').getContext('2d');
        new Chart(ctxStatus, {
            type: 'doughnut',
            data: {
                labels: ['En attente', 'Livrées'],
                datasets: [{
                    data: [<?= $stats['en_attente'] ?>, <?= $stats['total_commandes'] - $stats['en_attente'] ?>],
                    backgroundColor: ['#e4be5b', '#198754'],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    </script>

    <footer class="bg-dark text-white text-center py-3 mt-5">
        <p class="mb-0">&copy; 2026 ShopCaphy - Espace Admin.</p>
    </footer>
</body>
</html>