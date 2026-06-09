<?php
// controllers/AdminController.php

require_once 'models/Product.php';
require_once 'models/Order.php';

class AdminController {
    private $productModel;
    private $db;

    // Le constructeur doit UNIQUEMENT stocker la connexion, sans rediriger !
    public function __construct($database) {
        $this->productModel = new Product($database);
        $this->db = $database;
    }

    /**
     * Afficher le tableau de bord Admin (Produits + Commandes)
     */
    public function dashboard() {
        // La sécurité s'applique UNIQUEMENT ici
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: index.php?page=connexion');
            exit();
        }

        $erreur = null;
        $succes = null;
        $productToEdit = null;

        // Gestion de la modification d'un produit (récupération des données)
        if (isset($_GET['action']) && $_GET['action'] === 'modifier' && isset($_GET['id'])) {
            $productToEdit = $this->productModel->getProductById(intval($_GET['id']));
        }

        // Traitement du formulaire POST (Ajout / Modification de produit)
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

                    if (in_array($extension, $allowedExtensions)) {
                        $nomImage = time() . '_' . basename($_FILES['image_fichier']['name']);
                        $targetDir = "public/images/";
                        $targetFilePath = $targetDir . $nomImage;

                        if (!move_uploaded_file($_FILES['image_fichier']['tmp_name'], $targetFilePath)) {
                            $nomImage = null;
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
                    $finalImage = $nomImage ? $nomImage : "default.jpg"; 
                    
                    if ($this->productModel->addProduct($nom, $description, $prix, $stock, $finalImage)) {
                        $succes = "Produit ajouté avec succès !";
                    } else {
                        $erreur = "Une erreur est survenue lors de l'ajout.";
                    }
                }
            }
        }

        // Action : SUPPRESSION
        if (isset($_GET['action']) && $_GET['action'] === 'supprimer' && isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $this->productModel->deleteProduct($id);
            header('Location: index.php?page=admin_dashboard');
            exit();
        }

        // Récupération des données pour la vue (Produits + Commandes)
        $products = $this->productModel->getAllProducts();
        
        $orderModel = new Order($this->db);
        $allOrders = $orderModel->getAllOrders();

        // Un seul et unique chargement de la vue à la toute fin !
        // require_once 'views/header.php';
        require_once 'views/admin_dashboard.php';
    }

    /**
     * Modifier le statut d'une commande via un formulaire
     */
    public function modifierStatutCommande() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: index.php?page=connexion');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $orderId = intval($_POST['order_id']);
            $newStatus = $_POST['status'];

            $orderModel = new Order($this->db);
            $orderModel->updateStatus($orderId, $newStatus);
        }

        header('Location: index.php?page=admin_dashboard');
        exit();
    }
}