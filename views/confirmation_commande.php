<div class="container my-5 text-center py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6 bg-white shadow-sm rounded p-5">
            <div class="text-success mb-4">
                <i class="bi bi-check-circle-fill" style="font-size: 5rem;"></i>
            </div>
            
            <h2 class="fw-bold text-dark mb-3">Commande validée avec succès !</h2>
            <p class="text-muted fs-5 mb-4">
                Merci pour votre achat sur <strong>ShopCaphy</strong>. Votre commande a été enregistrée de manière sécurisée dans notre système.
            </p>

            <div class="card bg-light border-0 p-3 mb-4 text-start">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-secondary">Numéro de commande :</span>
                    <span class="fw-bold text-dark">#<?= $orderId ?></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-secondary">Montant Hors Taxe (HT) :</span>
                    <span class="fw-bold"><?= number_format($totalHT, 0, ',', ' ') ?> F CFA</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-secondary">TVA (18%) :</span>
                    <span class="fw-bold"><?= number_format($montantTVA, 0, ',', ' ') ?> F CFA</span>
                </div>
                <hr class="my-2">
                <div class="d-flex justify-content-between fs-5 fw-bold text-primary">
                    <span>Montant Total TTC :</span>
                    <span><?= number_format($totalTTC, 0, ',', ' ') ?> F CFA</span>
                </div>
            </div>

            <div class="d-grid gap-2">
                <a href="index.php?page=catalogue" class="btn btn-dark btn-lg fw-bold rounded-pill">
                    Continuer mes achats
                </a>
            </div>
        </div>
    </div>
</div>