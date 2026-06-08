<?php
// index.php - Point d'entrée unique de l'application ShopESA

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Connexion à la base de données
if (file_exists('config/db.php')) {
    require_once 'config/db.php';
} else {
    die("Le fichier config/db.php est manquant.");
}

// NOTE : Dans ton projet, ta variable de connexion s'appelle $pdo. 
// Nous allons donc l'utiliser pour tous les contrôleurs.

// 2. Chargement de TOUS nos contrôleurs
require_once 'controllers/AuthController.php';
require_once 'controllers/ProductController.php';
require_once 'controllers/AdminController.php';
require_once 'controllers/CartController.php';

// 3. Instanciation des contrôleurs
$authController    = new AuthController($pdo);
$productController = new ProductController($pdo);
$cartController    = new CartController($pdo);

// 4. Récupération de la page demandée (par défaut 'home')
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// 5. Routeur (Aiguillage des requêtes)
switch ($page) {
    case 'panier':
        $cartController->index();
        break;

    case 'ajouter_panier':
        $cartController->add();
        break;

    // NOUVELLE ROUTE POUR LES BOUTONS + ET -
    case 'update_quantite':
        $cartController->updateQuantite();
        break;

    case 'supprimer_panier':
        $cartController->remove();
        break;
        
    case 'home':
        // Par défaut, rediriger vers le catalogue
        header('Location: index.php?page=catalogue');
        exit();

    case 'catalogue':
        $productController->catalogue();
        break;

    case 'admin_dashboard':
        // L'instanciation est faite ici pour déclencher la sécurité du constructeur au bon moment
        $adminController = new AdminController($pdo);
        $adminController->dashboard();
        break;

    case 'connexion':
        $authController->connexion();
        break;

    case 'inscription':
        $authController->inscription();
        break;
        
    case 'deconnexion':
        $authController->deconnexion();
        break;

    // --- MODULE PANIER (Méthodes calées sur ton CartController) ---
    case 'panier':
        $cartController->index(); // Affiche le panier
        break;

    case 'ajouter_panier':
        $cartController->add(); // Ajoute un produit
        break;

    case 'supprimer_panier':
        $cartController->remove(); // Supprime un produit spécifique
        break;
        
    default:
        http_response_code(404);
        echo "<h1>Erreur 404 - Page non trouvée</h1>";
        break;
}