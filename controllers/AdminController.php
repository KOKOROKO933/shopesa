<?php
// controllers/AdminController.php

require_once 'models/Product.php';

class AdminController {
    private $productModel;

    public function __construct($database) {
        // SÉCURITÉ : Vérifier si l'utilisateur est connecté et s'il est ADMIN
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: index.php?page=home');
            exit();
        }
        $this->productModel = new Product($database);
    }

    /**
     * Afficher le tableau de bord avec la liste des produits et le formulaire d'ajout
     */
    public function dashboard() {
        $erreur = null;
        $succes = null;

        // Traitement de l'ajout de produit si le formulaire est soumis
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'ajouter') {
            $nom = trim($_POST['nom']);
            $description = trim($_POST['description']);
            $prix = floatval($_POST['prix']);
            $stock = intval($_POST['stock']);
            
            if (empty($nom) || $prix <= 0 || $stock < 0) {
                $erreur = "Veuillez remplir correctement les champs obligatoires.";
            } else {
                // Gestion simplifiée de l'image (par défaut si vide)
                $image = "default.jpg"; 
                
                if ($this->productModel->addProduct($nom, $description, $prix, $stock, $image, null)) {
                    $succes = "Produit ajouté avec succès au catalogue !";
                } else {
                    $erreur = "Une erreur est survenue lors de l'ajout.";
                }
            }
        }

        // Traitement de la suppression
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'supprimer') {
            $id = intval($_GET['id']);
            if ($this->productModel->deleteProduct($id)) {
                header('Location: index.php?page=admin_dashboard');
                exit();
            }
        }

        // Récupérer tous les produits pour les lister dans le tableau de bord
        $products = $this->productModel->getAllProducts();
        require_once 'views/admin_dashboard.php';
    }
}