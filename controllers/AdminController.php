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

        if (isset($_GET['action']) && $_GET['action'] === 'modifier' && isset($_GET['id'])) {
            $productToEdit = $this->productModel->getProductById(intval($_GET['id']));
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
            $nom = trim($_POST['nom']);
            $description = trim($_POST['description']);
            $prix = floatval($_POST['prix']);
            $stock = intval($_POST['stock']);

            if (empty($nom) || $prix <= 0 || $stock < 0) {
                $erreur = "Veuillez remplir correctement les champs obligatoires.";
            } else {
                
                // --- SCRIPT DE GESTION DE L'UPLOAD D'IMAGE ---
                $nomImage = null;
                if (isset($_FILES['image_fichier']) && $_FILES['image_fichier']['error'] === 0) {
                    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                    $fileInfo = pathinfo($_FILES['image_fichier']['name']);
                    $extension = strtolower($fileInfo['extension']);

                    // Vérifier si le format est accepté
                    if (in_array($extension, $allowedExtensions)) {
                        // Créer un nom unique pour l'image (ex: 171892012_communique.png) pour éviter d'écraser une autre photo
                        $nomImage = time() . '_' . basename($_FILES['image_fichier']['name']);
                        $targetDir = "public/images/";
                        $targetFilePath = $targetDir . $nomImage;

                        // Déplacer physiquement le fichier dans le dossier public/images/
                        if (!move_uploaded_file($_FILES['image_fichier']['tmp_name'], $targetFilePath)) {
                            $nomImage = null; // En cas d'échec du transfert
                        }
                    }
                }
                // ----------------------------------------------

                // Action : MODIFICATION
                if ($_POST['action'] === 'modifier' && isset($_POST['id'])) {
                    $id = intval($_POST['id']);
                    
                    if ($this->productModel->updateProduct($id, $nom, $description, $prix, $stock, $nomImage)) {
                        $succes = "Produit mis à jour avec succès !";
                        header("Refresh: 2; url=index.php?page=admin_dashboard");
                    } else {
                        $erreur = "Une erreur est survenue lors de la modification.";
                    }
                } 
                // Action : AJOUT
                elseif ($_POST['action'] === 'ajouter') {
                    // Si aucune image n'a été sélectionnée, on applique 'default.jpg'
                    $finalImage = $nomImage ? $nomImage : "default.jpg"; 
                    
                    if ($this->productModel->addProduct($nom, $description, $prix, $stock, $finalImage)) {
                        $succes = "Produit ajouté avec succès !";
                    } else {
                        $erreur = "Une erreur est survenue lors de l'ajout.";
                    }
                }
            }
        }

        if (isset($_GET['action']) && $_GET['action'] === 'supprimer' && isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $this->productModel->deleteProduct($id);
            header('Location: index.php?page=admin_dashboard');
            exit();
        }

        $products = $this->productModel->getAllProducts();
        require_once 'views/admin_dashboard.php';
    }
}