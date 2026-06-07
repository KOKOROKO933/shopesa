<?php
// controllers/CartController.php

require_once 'models/Product.php';

class CartController {
    private $productModel;

    public function __construct($database) {
        // Initialiser le panier en session s'il n'existe pas encore
        if (!isset($_SESSION['panier'])) {
            $_SESSION['panier'] = [];
        }
        $this->productModel = new Product($database);
    }

    /**
     * Ajouter un produit au panier
     */
    public function ajouter() {
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            
            // Vérifier si le produit existe bien en BDD
            $product = $this->productModel->getProductById($id);

            if ($product) {
                // Si le produit est déjà dans le panier, on augmente la quantité
                if (isset($_SESSION['panier'][$id])) {
                    if ($_SESSION['panier'][$id]['quantite'] < $product['stock']) {
                        $_SESSION['panier'][$id]['quantite']++;
                    }
                } else {
                    // Sinon, on l'ajoute pour la première fois
                    $_SESSION['panier'][$id] = [
                        'nom' => $product['nom'],
                        'prix' => $product['prix'],
                        'quantite' => 1
                    ];
                }
            }
        }
        // Redirection instantanée vers le catalogue pour continuer ses achats
        header('Location: index.php?page=catalogue');
        exit();
    }

    /**
     * Afficher le contenu du panier
     */
    public function afficher() {
        $panier = $_SESSION['panier'];
        $total = 0;

        // Calcul du montant total de la commande
        foreach ($panier as $item) {
            $total += $item['prix'] * $item['quantite'];
        }

        require_once 'views/panier.php';
    }

    /**
     * Vider complètement le panier
     */
    public function vider() {
        $_SESSION['panier'] = [];
        header('Location: index.php?page=panier');
        exit();
    }
}