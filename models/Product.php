<?php
// models/Product.php

class Product {
    private $db;

    public function __construct($database) {
        $this->db = $database;
    }

    // --- CÔTÉ CATALOGUE ---
    /**
     * Récupérer tous les produits pour les afficher sur le site
     */
    public function getAllProducts() {
        $sql = "SELECT p.*, c.nom as categorie_nom 
                FROM products p 
                LEFT JOIN categories c ON p.cat_id = c.id 
                ORDER BY p.id DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Récupérer un produit spécifique par son ID
     */
    public function getProductById($id) {
        $sql = "SELECT * FROM products WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // --- CÔTÉ ADMINISTRATION (CRUD) ---
    /**
     * Ajouter un nouveau produit
     */
    public function addProduct($nom, $description, $prix, $stock, $image, $cat_id) {
        $sql = "INSERT INTO products (nom, description, prix, stock, image, cat_id) 
                VALUES (:nom, :description, :prix, :stock, :image, :cat_id)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nom' => htmlspecialchars($nom),
            ':description' => htmlspecialchars($description),
            ':prix' => $prix,
            ':stock' => $stock,
            ':image' => $image,
            ':cat_id' => $cat_id ?: null
        ]);
    }

    /**
     * Supprimer un produit
     */
    public function deleteProduct($id) {
        $sql = "DELETE FROM products WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}