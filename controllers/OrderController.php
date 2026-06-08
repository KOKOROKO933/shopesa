<?php
// controllers/OrderController.php

require_once 'models/Product.php';
require_once 'models/Order.php';

class OrderController {
    private $productModel;
    private $orderModel;

    public function __construct($database) {
        $this->productModel = new Product($database);
        $this->orderModel = new Order($database);
    }

    public function checkout() {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['erreur_commande'] = "Vous devez être connecté pour valider votre commande.";
            header('Location: index.php?page=connexion');
            exit();
        }

        if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
            header('Location: index.php?page=catalogue');
            exit();
        }

        $cartItems = [];
        $totalHT = 0;

        foreach ($_SESSION['cart'] as $productId => $quantite) {
            $product = $this->productModel->getProductById($productId);
            if ($product) {
                if ($product['stock'] < $quantite) {
                    $_SESSION['erreur_stock'] = "Le stock pour le produit " . $product['nom'] . " est insuffisant.";
                    header('Location: index.php?page=panier');
                    exit();
                }

                $sousTotal = $product['prix'] * $quantite;
                $totalHT += $sousTotal;

                $cartItems[] = [
                    'id' => $product['id'],
                    'prix' => $product['prix'],
                    'quantite' => $quantite
                ];
            }
        }

        $tauxTVA = 0.18;
        $montantTVA = $totalHT * $tauxTVA;
        $totalTTC = $totalHT + $montantTVA;
        $userId = $_SESSION['user_id'];

        $orderId = $this->orderModel->createOrder($userId, $totalHT, $montantTVA, $totalTTC, $cartItems);

        if ($orderId) {
            $_SESSION['cart'] = [];
            header('Location: index.php?page=confirmation&id=' . $orderId . '&ht=' . $totalHT . '&tva=' . $montantTVA . '&ttc=' . $totalTTC);
            exit();
        } else {
            die("Une erreur technique est survenue.");
        }
    }

        /**
     * Afficher l'historique des commandes de l'utilisateur connecté
     */
    public function mesCommandes() {
        // Sécurité : si pas connecté, redirection
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=connexion');
            exit();
        }

        $userId = $_SESSION['user_id'];
        
        // Récupération des commandes
        $orders = $this->orderModel->getOrdersByUserId($userId);

        // Chargement de la vue avec la structure globale (header/footer)
        require_once 'views/includes/header.php';
        require_once 'views/mes_commandes.php';

    }
}