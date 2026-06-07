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
        // Récupération de la liste des produits via le modèle
        $products = $this->productModel->getAllProducts();
        
        // Chargement de la vue
        require_once 'views/catalogue.php';
    }
}