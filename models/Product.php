<?php
// models/Product.php

class Product {
    private $db;

    public function __construct($database) {
        $this->db = $database;
    }

    // Récupérer tous les produits
    public function getAllProducts() {
        $sql = "SELECT * FROM products ORDER BY id DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    // Récupérer un produit par son ID (Pour la modification)
    public function getProductById($id) {
        $sql = "SELECT * FROM products WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // Ajouter un produit
    public function addProduct($nom, $description, $prix, $stock, $image) {
        $sql = "INSERT INTO products (nom, description, prix, stock, image) 
                VALUES (:nom, :description, :prix, :stock, :image)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nom' => htmlspecialchars($nom),
            ':description' => htmlspecialchars($description),
            ':prix' => $prix,
            ':stock' => $stock,
            ':image' => $image
        ]);
    }
        /**
     * Modifier un produit existant (prend en compte l'image si elle change)
     */
    public function updateProduct($id, $nom, $description, $prix, $stock, $image = null) {
        if ($image) {
            // Si une nouvelle image a été uploadée, on met tout à jour
            $sql = "UPDATE products 
                    SET nom = :nom, description = :description, prix = :prix, stock = :stock, image = :image 
                    WHERE id = :id";
            $params = [
                ':id' => $id,
                ':nom' => htmlspecialchars($nom),
                ':description' => htmlspecialchars($description),
                ':prix' => $prix,
                ':stock' => $stock,
                ':image' => $image
            ];
        } else {
            // Sinon, on modifie les infos sans toucher à l'image existante
            $sql = "UPDATE products 
                    SET nom = :nom, description = :description, prix = :prix, stock = :stock 
                    WHERE id = :id";
            $params = [
                ':id' => $id,
                ':nom' => htmlspecialchars($nom),
                ':description' => htmlspecialchars($description),
                ':prix' => $prix,
                ':stock' => $stock
            ];
        }
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }
    

    // Supprimer un produit
    public function deleteProduct($id) {
        $sql = "DELETE FROM products WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}