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

    /**
     * Traiter la validation de la commande
     */
    public function checkout() {
        // Sécurité : Si l'utilisateur n'est pas connecté, on le redirige vers la page de connexion
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['erreur_commande'] = "Vous devez être connecté pour valider votre commande.";
            header('Location: index.php?page=connexion');
            exit();
        }

        // Sécurité : Si le panier est vide, impossible de commander
        if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
            header('Location: index.php?page=catalogue');
            exit();
        }

        // Reconstitution des éléments du panier et calculs
        $cartItems = [];
        $totalHT = 0;

        foreach ($_SESSION['cart'] as $productId => $quantite) {
            $product = $this->productModel->getProductById($productId);
            if ($product) {
                // Vérification de sécurité de dernière minute sur le stock disponible
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

        $tauxTVA = 0.18; // 18%
        $montantTVA = $totalHT * $tauxTVA;
        $totalTTC = $totalHT + $montantTVA;
        $userId = $_SESSION['user_id'];

        // Enregistrement effectif en Base de Données
        $orderId = $this->orderModel->createOrder($userId, $totalHT, $montantTVA, $totalTTC, $cartItems);

        if ($orderId) {
            // Succès : On vide le panier de la session utilisateur
            $_SESSION['cart'] = [];
            
            // On charge une vue de confirmation de commande réussie
            require_once 'views/confirmation_commande.php';
        } else {
            die("Une erreur technique est survenue lors de la validation de votre commande.");
        }
    }
}