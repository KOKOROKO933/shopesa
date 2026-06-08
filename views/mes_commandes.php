<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold m-0">Mon Historique de Commandes</h2>
        <a href="index.php?page=catalogue" class="btn btn-outline-dark btn-sm">Retour au catalogue</a>
    </div>

    <?php if (empty($orders)): ?>
        <div class="alert alert-info text-center py-4">
            <p class="mb-0">Vous n'avez pas encore passé de commande sur ShopESA.</p>
        </div>
    <?php else: ?>
        <div class="table-responsive bg-white shadow-sm rounded p-3">
            <table class="table align-middle table-hover">
                <thead class="table-light">
                    <tr>
                        <th>N° Commande</th>
                        <th>Date</th>
                        <th>Montant HT</th>
                        <th>TVA (18%)</th>
                        <th>Total TTC</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td class="fw-bold">#<?= $order['id'] ?></td>
                            <td><?= date('d/m/Y à H:i', strtotime($order['created_at'])) ?></td>
                            <td><?= number_format($order['total_ht'], 0, ',', ' ') ?> F CFA</td>
                            <td class="text-muted"><?= number_format($order['tva'], 0, ',', ' ') ?> F CFA</td>
                            <td class="fw-bold text-primary"><?= number_format($order['total_ttc'], 0, ',', ' ') ?> F CFA</td>
                            <td>
                                <?php if ($order['status'] === 'En attente'): ?>
                                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">En attente</span>
                                <?php elseif ($order['status'] === 'Livrée'): ?>
                                    <span class="badge bg-success px-3 py-2 rounded-pill">Livrée</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary px-3 py-2 rounded-pill"><?= htmlspecialchars($order['status']) ?></span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>