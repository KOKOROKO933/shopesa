<?php
// controllers/ProductController.php

require_once 'models/Product.php';

class ProductController {
    private $productModel;
    private $db;

    public function __construct($database) {
        $this->db = $database;
        $this->productModel = new Product($database);
    }

    /**
     * Afficher le catalogue public
     */
    public function catalogue() {
        // 1. Récupération des catégories via la méthode existante du Modèle
        $categories = $this->productModel->getCategories(); 
        
        // 2. Gestion des filtres d'affichage des produits
        if (isset($_GET['categorie_id'])) {
            // Filtrer par catégorie
            $categoryId = intval($_GET['categorie_id']);
            $products = $this->productModel->getProductsByCategory($categoryId);
        } elseif (isset($_GET['action']) && $_GET['action'] === 'all') {
            // Afficher tous les produits si clic sur "Tous les produits"
            $products = $this->productModel->getAllProducts();
        } else {
            // Affichage par défaut : Tous les produits
            $products = $this->productModel->getAllProducts(); 
        }

        // 3. Charger la vue
        require_once 'views/catalogue.php';
    }
}