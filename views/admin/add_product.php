<div class="mb-3">
    <label for="categorie_id" class="form-label fw-bold">Catégorie du produit</label>
    <select class="form-select" id="categorie_id" name="categorie_id" required>
        <option value="" selected disabled>-- Choisir une catégorie --</option>
        
        <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat['id'] ?>">
                <?= htmlspecialchars($cat['nom']) ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>