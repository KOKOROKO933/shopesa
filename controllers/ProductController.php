<?php
// controllers/ProductController.php

require_once 'models/Product.php';

class ProductController {
    private $productModel;

    public function __construct($database) {
        $this->productModel = new Product($database);
    }

    /**
     * Afficher le catalogue public
     */
    public function catalogue() {
    // Récupérer toutes les catégories (contient désormais l'id et le nom)
    $categories = $this->productModel->getCategories();

    // Vérifier si un ID de catégorie est passé dans l'URL (ex: &cat_id=2)
    $catIdSelectionne = isset($_GET['cat_id']) ? intval($_GET['cat_id']) : null;

    if ($catIdSelectionne) {
        // Si un ID est sélectionné, on filtre par cat_id
        $products = $this->productModel->getProductsByCategory($catIdSelectionne);
    } else {
        // Sinon, on affiche tout
        $products = $this->productModel->getAllProducts();
    }

    // Charger la vue
    require_once 'views/catalogue.php';
    }

}