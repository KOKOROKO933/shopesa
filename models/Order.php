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
}