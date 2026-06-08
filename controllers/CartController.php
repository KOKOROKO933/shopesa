<?php
// controllers/CartController.php

require_once 'models/Product.php';

class CartController {
    private $productModel;

    public function __construct($database) {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        $this->productModel = new Product($database);
    }

    // Afficher le contenu du panier avec calcul HT, TVA et TTC
    public function index() {
        $cartItems = [];
        $totalHT = 0;

        foreach ($_SESSION['cart'] as $productId => $quantite) {
            $product = $this->productModel->getProductById($productId);
            if ($product) {
                $sousTotal = $product['prix'] * $quantite;
                $totalHT += $sousTotal;
                
                $cartItems[] = [
                    'id' => $product['id'],
                    'nom' => $product['nom'],
                    'prix' => $product['prix'],
                    'image' => $product['image'],
                    'quantite' => $quantite,
                    'sous_total' => $sousTotal,
                    'stock_dispo' => $product['stock']
                ];
            }
        }

        // Calculs réglementaires demandés par le sujet
        $tauxTVA = 0.18; // 18%
        $montantTVA = $totalHT * $tauxTVA;
        $totalTTC = $totalHT + $montantTVA;

        require_once 'views/panier.php';
    }

    // Ajouter un produit au panier
    public function add() {
        if (isset($_GET['id'])) {
            $productId = intval($_GET['id']);
            $product = $this->productModel->getProductById($productId);
            
            if ($product && $product['stock'] > 0) {
                if (isset($_SESSION['cart'][$productId])) {
                    // On ne dépasse pas le stock disponible
                    if ($_SESSION['cart'][$productId] < $product['stock']) {
                        $_SESSION['cart'][$productId]++;
                    }
                } else {
                    $_SESSION['cart'][$productId] = 1;
                }
            }
        }
        header('Location: index.php?page=panier');
        exit();
    }

    // Modifier la quantité (+ ou -) depuis le panier
    public function updateQuantite() {
        if (isset($_GET['id']) && isset($_GET['action'])) {
            $productId = intval($_GET['id']);
            $action = $_GET['action'];

            if (isset($_SESSION['cart'][$productId])) {
                if ($action === 'plus') {
                    $product = $this->productModel->getProductById($productId);
                    if ($_SESSION['cart'][$productId] < $product['stock']) {
                        $_SESSION['cart'][$productId]++;
                    }
                } elseif ($action === 'moins') {
                    $_SESSION['cart'][$productId]--;
                    if ($_SESSION['cart'][$productId] <= 0) {
                        unset($_SESSION['cart'][$productId]);
                    }
                }
            }
        }
        header('Location: index.php?page=panier');
        exit();
    }

    // Supprimer un produit du panier
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