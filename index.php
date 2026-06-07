<?php
// index.php - Point d'entrée unique de l'application ShopESA

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (file_exists('config/db.php')) {
    require_once 'config/db.php';
} else {
    die("Le fichier config/db.php est manquant.");
}

// Chargement de TOUS nos contrôleurs
require_once 'controllers/AuthController.php';
require_once 'controllers/ProductController.php';
require_once 'controllers/AdminController.php';
require_once 'controllers/CartController.php';

$authController = new AuthController($pdo);
$productController = new ProductController($pdo);
$cartController = new CartController($pdo);


$page = isset($_GET['page']) ? $_GET['page'] : 'home';

switch ($page) {
    // Ajoute cette ligne tout en haut avec les autres inclusions de contrôleurs :
    // Ajoute cette ligne tout en haut avec les autres inclusions de contrôleurs :

    // Instancie le contrôleur juste en dessous des autres :

    // Ajoute ces trois nouveaux cas (cases) dans ton switch ($page) :
    case 'panier':
        $cartController->afficher();
        break;

    case 'ajouter_panier':
        $cartController->ajouter();
        break;

    case 'vider_panier':
        $cartController->vider();
        break;
    // Instancie le contrôleur juste en dessous des autres :
    $cartController = new CartController($pdo);

    // Ajoute ces trois nouveaux cas (cases) dans ton switch ($page) :
    case 'panier':
        $cartController->afficher();
        break;

    case 'ajouter_panier':
        $cartController->ajouter();
        break;

    case 'vider_panier':
    $cartController->vider();
    break;
    case 'home':
        // Par défaut, rediriger vers le catalogue pour donner de la vie au site
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

    case 'panier':
        echo "<h1>Votre Panier (Brique en cours de dev)</h1><a href='index.php'>Retour</a>";
        break;

    default:
        http_response_code(404);
        echo "<h1>Erreur 404 - Page non trouvée</h1>";
        break;
}