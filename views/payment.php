<div class="card p-4 shadow-sm border-0">
    <h4 class="fw-bold text-dark mb-4">Sélectionnez votre mode de paiement</h4>
    
    <div class="row g-3">
        <div class="col-md-4">
            <label class="p-3 border rounded-3 d-block text-center cursor-pointer bg-light">
                <input type="radio" name="payment_method" value="tmoney" class="form-check-input me-2" checked>
                <strong>TMoney</strong> (UTB/TOGOCOM)
            </label>
        </div>
        <div class="col-md-4">
            <label class="p-3 border rounded-3 d-block text-center cursor-pointer bg-light">
                <input type="radio" name="payment_method" value="moov" class="form-check-input me-2">
                <strong>Moov Money</strong> (Flooz)
            </label>
        </div>
        <div class="col-md-4">
            <label class="p-3 border rounded-3 d-block text-center cursor-pointer bg-light">
                <input type="radio" name="payment_method" value="visa" class="form-check-input me-2">
                <strong>Carte VISA</strong>
            </label>
        </div>
    </div>

    <div id="mobile-payment-fields" class="mt-4">
        <label class="form-label">Numéro de téléphone Flooz / Tmoney</label>
        <input type="tel" placeholder="Ex: +228 90 XX XX XX" class="form-control">
    </div>
    <a href="mailto:<?= $order['client_email'] ?>?subject=Assistance ShopCaphy - Commande #<?= $order['id'] ?>&body=Bonjour <?= urlencode($order['client_nom']) ?>, nous avons remarqué une difficulté sur votre commande..." class="btn btn-sm btn-outline-info">
        ✉️ Contacter le client
    </a>

    <div id="visa-payment-fields" class="mt-4 d-none">
        <label class="form-label">Numéro de Carte VISA</label>
        <input type="text" placeholder="4000 1234 5678 9010" class="form-control mb-2">
        <div class="row">
            <div class="col-6"><input type="text" placeholder="MM/AA" class="form-control"></div>
            <div class="col-6"><input type="text" placeholder="CVV" class="form-control"></div>
        </div>
    </div>

    <button class="btn btn-primary w-100 mt-4 py-2" onclick="simulerPaiement()">Confirmer le paiement</button>
</div>

<script>
// Logique d'affichage dynamique des champs
document.querySelectorAll('input[name="payment_method"]').forEach(input => {
    input.addEventListener('change', (e) => {
        if(e.target.value === 'visa') {
            document.getElementById('visa-payment-fields').classList.remove('d-none');
            document.getElementById('mobile-payment-fields').classList.add('d-none');
        } else {
            document.getElementById('visa-payment-fields').classList.add('d-none');
            document.getElementById('mobile-payment-fields').classList.remove('d-none');
        }
    });
});

function simulerPaiement() {
    alert("📡 Connexion à l'opérateur de paiement en cours...\nStatut: Paiement de votre commande validé avec succès !");
    window.location.href = "index.php?page=confirmation_commande";
}
</script>