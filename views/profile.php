<div class="container my-5">
    <div class="row g-4">
        
        <div class="col-md-4">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-dark text-white p-3">
                    <h5 class="mb-0 fw-bold">👤 Mon Profil</h5>
                </div>
                <div class="card-body p-4">
                    <?php if (isset($success)): ?>
                        <div class="alert alert-success py-2 fs-6"><?= $success ?></div>
                    <?php endif; ?>

                    <form action="index.php?page=profile_update" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary">Nom complet</label>
                            <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($_SESSION['user']['nom']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary">Adresse Email</label>
                            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($_SESSION['user']['email']) ?>" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 fw-semibold">Enregistrer les modifications</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-secondary text-white p-3">
                    <h5 class="mb-0 fw-bold">📦 Mon Historique de Commandes</h5>
                </div>
                <div class="card-body p-4">
                    <?php if (empty($userOrders)): ?>
                        <div class="text-center text-muted py-4">
                            <p class="fs-4 mb-1">🛒 Pas encore d'achats ?</p>
                            <a href="index.php?page=home" class="btn btn-sm btn-outline-primary">Découvrir nos produits</a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table align-middle table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Commande</th>
                                        <th>Date</th>
                                        <th>Total TTC</th>
                                        <th>Statut</th>
                                        <th>Articles</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($userOrders as $order): ?>
                                        <tr>
                                            <td class="fw-bold">#<?= $order['id'] ?></td>
                                            <td><?= date('d/m/Y à H:i', strtotime($order['created_at'])) ?></td>
                                            <td class="fw-bold text-danger"><?= number_format($order['total_ttc'], 0, ',', ' ') ?> F CFA</td>
                                            <td>
                                                <?php if ($order['status'] === 'En attente'): ?>
                                                    <span class="badge bg-warning text-dark">En attente</span>
                                                <?php elseif ($order['status'] === 'Livrée'): ?>
                                                    <span class="badge bg-success">Livrée</span>
                                                <?php else: ?>
                                                    <span class="badge bg-info text-dark">En cours...</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalClientOrder<?= $order['id'] ?>">
                                                    👁️ Voir (<?= count($order['items']) ?>)
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>
</div>

<?php foreach ($userOrders as $order): ?>
    <div class="modal fade" id="modalClientOrder<?= $order['id'] ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title fw-bold">Ma Commande #<?= $order['id'] ?></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table align-middle">
                        <thead>
                            <tr class="table-light">
                                <th>Article</th>
                                <th class="text-center">Qté</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($order['items'] as $item): ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($item['nom']) ?></strong></td>
                                    <td class="text-center">x<?= $item['quantity'] ?? $item['quantite'] ?? 1 ?></td>
                                    <td class="text-end fw-bold"><?= number_format(($item['price'] ?? $item['prix']) * ($item['quantity'] ?? $item['quantite'] ?? 1), 0, ',', ' ') ?> F CFA</td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>