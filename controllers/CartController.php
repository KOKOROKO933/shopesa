<?php
// controllers/CartController.php

require_once 'models/Product.php';

class CartController {
    private $productModel;

    public function __construct($database) {
        // On initialise le panier s'il n'existe pas encore en session
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        $this->productModel = new Product($database);
    }

    // 1. Afficher le contenu du panier
    public function index() {
        $cartItems = [];
        $totalGeneral = 0;

        // On récupère les vraies infos de la BDD pour chaque produit du panier
        foreach ($_SESSION['cart'] as $productId => $quantite) {
            $product = $this->productModel->getProductById($productId);
            if ($product) {
                $sousTotal = $product['prix'] * $quantite;
                $totalGeneral += $sousTotal;
                
                $cartItems[] = [
                    'id' => $product['id'],
                    'nom' => $product['nom'],
                    'prix' => $product['prix'],
                    'image' => $product['image'],
                    'quantite' => $quantite,
                    'sous_total' => $sousTotal
                ];
            }
        }

        require_once 'views/panier.php';
    }

    // 2. Ajouter un produit au panier
    public function add() {
        if (isset($_GET['id'])) {
            $productId = intval($_GET['id']);
            
            // Vérifier si le produit existe et s'il y a du stock
            $product = $this->productModel->getProductById($productId);
            if ($product && $product['stock'] > 0) {
                // Si le produit est déjà dans le panier, on augmente la quantité
                if (isset($_SESSION['cart'][$productId])) {
                    $_SESSION['cart'][$productId]++;
                } else {
                    // Sinon, on l'ajoute avec une quantité de 1
                    $_SESSION['cart'][$productId] = 1;
                }
            }
        }
        // Redirection instantanée vers la page du panier
        header('Location: index.php?page=panier');
        exit();
    }

    // 3. Supprimer un produit du panier
    public function remove() {
        if (isset($_GET['id'])) {
            $productId = intval($_GET['id']);
            if (isset($_SESSION['cart'][$productId])) {
                unset($_SESSION['cart'][$productId]);
            }
        }
        header('Location: index.php?page=panier');
        exit();
    }
}