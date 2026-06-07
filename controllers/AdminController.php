<?php
// controllers/AdminController.php

require_once 'models/Product.php';

class AdminController {
    private $productModel;

    public function __construct($database) {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: index.php?page=home');
            exit();
        }
        $this->productModel = new Product($database);
    }

    public function dashboard() {
        $erreur = null;
        $succes = null;
        $productToEdit = null;

        // Détecter si on demande la modification (GET)
        if (isset($_GET['action']) && $_GET['action'] === 'modifier' && isset($_GET['id'])) {
            $productToEdit = $this->productModel->getProductById(intval($_GET['id']));
        }

        // Traitement des formulaires (POST)
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
            $nom = trim($_POST['nom']);
            $description = trim($_POST['description']);
            $prix = floatval($_POST['prix']);
            $stock = intval($_POST['stock']);

            if (empty($nom) || $prix <= 0 || $stock < 0) {
                $erreur = "Veuillez remplir correctement les champs obligatoires.";
            } else {
                // Si c'est une MODIFICATION
                if ($_POST['action'] === 'modifier' && isset($_POST['id'])) {
                    $id = intval($_POST['id']);
                    if ($this->productModel->updateProduct($id, $nom, $description, $prix, $stock)) {
                        $succes = "Produit mis à jour avec succès !";
                        header("Refresh: 2; url=index.php?page=admin_dashboard");
                    } else {
                        $erreur = "Une erreur est survenue lors de la modification.";
                    }
                } 
                // Si c'est un AJOUT
                elseif ($_POST['action'] === 'ajouter') {
                    $image = "default.jpg"; 
                    if ($this->productModel->addProduct($nom, $description, $prix, $stock, $image)) {
                        $succes = "Produit ajouté avec succès !";
                    } else {
                        $erreur = "Une erreur est survenue lors de l'ajout.";
                    }
                }
            }
        }

        // Traitement de la suppression (GET)
        if (isset($_GET['action']) && $_GET['action'] === 'supprimer' && isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $this->productModel->deleteProduct($id);
            header('Location: index.php?page=admin_dashboard');
            exit();
        }

        // Récupérer tous les produits pour le tableau
        $products = $this->productModel->getAllProducts();
        require_once 'views/admin_dashboard.php';
    }
}