<?php
// models/Order.php

class Order {
    private $db;

    public function __construct($database) {
        $this->db = $database;
    }

    /**
     * Enregistrer une commande et ses lignes associées en BDD (Transaction sécurisée)
     */
    public function createOrder($userId, $totalHT, $montantTVA, $totalTTC, $cartItems) {
        try {
            // On démarre la transaction
            $this->db->beginTransaction();

            // Insérer la commande globale
            $sqlOrder = "INSERT INTO orders (user_id, total_ht, tva, total_ttc, status) 
                         VALUES (:user_id, :total_ht, :tva, :total_ttc, 'En attente')";
            $stmtOrder = $this->db->prepare($sqlOrder);
            $stmtOrder->execute([
                ':user_id'   => $userId,
                ':total_ht'  => $totalHT,
                ':tva'       => $montantTVA,
                ':total_ttc' => $totalTTC
            ]);

            // Récupérer l'ID de la commande qui vient d'être généré
            $orderId = $this->db->lastInsertId();

            // Insérer chaque ligne de produit du panier
            $sqlItem = "INSERT INTO order_items (order_id, product_id, quantity, price) 
                        VALUES (:order_id, :product_id, :quantity, :price)";
            $stmtItem = $this->db->prepare($sqlItem);

            // On prépare aussi la requête pour baisser le stock du produit en BDD !
            $sqlStock = "UPDATE products SET stock = stock - :quantity WHERE id = :product_id";
            $stmtStock = $this->db->prepare($sqlStock);

            foreach ($cartItems as $item) {
                // Insertion dans order_items
                $stmtItem->execute([
                    ':order_id'   => $orderId,
                    ':product_id' => $item['id'],
                    ':quantity'   => $item['quantite'],
                    ':price'      => $item['prix']
                ]);

                // Mise à jour du stock disponible pour le produit
                $stmtStock->execute([
                    ':quantity'   => $item['quantite'],
                    ':product_id' => $item['id']
                ]);
            }

            // Si tout s'est bien passé, on valide définitivement en BDD
            $this->db->commit();
            return $orderId;

        } catch (Exception $e) {
            // En cas d'erreur, on annule TOUT ce qui a été fait au dessus
            $this->db->rollBack();
            return false;
        }
    }
        /**
     * Récupérer toutes les commandes d'un utilisateur spécifique
     */
    public function getOrdersByUserId($userId) {
        $sql = "SELECT * FROM orders WHERE user_id = :user_id ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    /**
     * [Admin] Récupérer toutes les commandes de la boutique avec les infos du client
     */
    public function getAllOrders() {
        $sql = "SELECT orders.*, users.nom AS client_nom, users.email AS client_email 
                FROM orders 
                INNER JOIN users ON orders.user_id = users.id 
                ORDER BY orders.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * [Admin] Modifier le statut d'une commande
     */
    public function updateStatus($orderId, $newStatus) {
        $sql = "UPDATE orders SET status = :status WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':status' => $newStatus,
            ':id' => $orderId
        ]);
    }
    /**
     * [Admin] Récupérer les statistiques globales de la boutique
     */
    public function getAdminStats() {
        // 1. Calcul du Chiffre d'Affaires (uniquement sur les commandes non annulées)
        $sqlCA = "SELECT SUM(total_ttc) as total_ca FROM orders WHERE status != 'Annulée'";
        $stmtCA = $this->db->query($sqlCA);
        $resCA = $stmtCA->fetch(PDO::FETCH_ASSOC);

        // 2. Nombre de commandes en attente
        $sqlWait = "SELECT COUNT(*) as total_attente FROM orders WHERE status = 'En attente'";
        $stmtWait = $this->db->query($sqlWait);
        $resWait = $stmtWait->fetch(PDO::FETCH_ASSOC);

        // 3. Nombre total de commandes passées
        $sqlTotal = "SELECT COUNT(*) as total_ordres FROM orders";
        $stmtTotal = $this->db->query($sqlTotal);
        $resTotal = $stmtTotal->fetch(PDO::FETCH_ASSOC);

        return [
            'ca' => $resCA['total_ca'] ?? 0,
            'en_attente' => $resWait['total_attente'] ?? 0,
            'total_commandes' => $resTotal['total_ordres'] ?? 0
        ];
    }
    /**
     * [Admin & Client] Récupérer les articles d'une commande spécifique
     */
    public function getOrderItems($orderId) {
        // On sélectionne explicitement les champs pour éviter les surprises
        // Remplace 'order_items' par le nom exact de ta table intermédiaire si nécessaire
        $sql = "SELECT oi.*, p.nom, p.image, p.prix 
                FROM order_items oi 
                INNER JOIN products p ON oi.product_id = p.id 
                WHERE oi.order_id = :order_id";
                
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':order_id' => $orderId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    /**
     * Récupérer toutes les commandes passées par un client spécifique
     */
    public function getUserOrders($userId) {
        $sql = "SELECT * FROM orders WHERE user_id = :user_id ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // On inclut aussi les articles pour chaque commande du client
        foreach ($orders as $key => $order) {
            $orders[$key]['items'] = $this->getOrderItems($order['id']);
        }
        return $orders;
    }
    /**
     * [Admin] Récupérer l'évolution du Chiffre d'Affaires par jour
     */
    public function getSalesEvolution() {
        $sql = "SELECT DATE(created_at) as date_vente, SUM(total_ttc) as total_jour 
                FROM orders 
                WHERE status != 'Annulée' 
                GROUP BY DATE(created_at) 
                ORDER BY date_vente ASC 
                LIMIT 10"; // On prend les 10 derniers jours d'activité
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}