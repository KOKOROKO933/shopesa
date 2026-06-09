<?php
// index.php - Point d'entrée unique de l'application ShopESA

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Connexion à la base de données
if (file_exists('config/db.php')) {
    require_once 'config/db.php';
} else {
    die("Le fichier config/db.php est manquant.");
}

// NOTE : Dans ton projet, ta variable de connexion s'appelle $pdo. 
// Nous allons donc l'utiliser pour tous les contrôleurs.

// Chargement de TOUS nos contrôleurs
require_once 'controllers/OrderController.php';
require_once 'controllers/AuthController.php';
require_once 'controllers/ProductController.php';
require_once 'controllers/AdminController.php';
require_once 'controllers/CartController.php';

// Instanciation des contrôleurs
$authController    = new AuthController($pdo);
$productController = new ProductController($pdo);
$cartController    = new CartController($pdo);
$orderController = new OrderController($pdo);
$adminController = new AdminController($pdo); // ou ($db) selon le nom de ton objet PDO principal

// Récupération de la page demandée (par défaut 'home')
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// CODE TEMPORAIRE DE TEST
if (isset($_GET['test_admin'])) {
    $testOrder = new Order($pdo);
    echo "<pre>Méthodes trouvées dans le Modèle Order :<br>";
    print_r(get_class_methods($testOrder));
    echo "</pre>";
    exit();
}

// Routeur (Aiguillage des requêtes)
switch ($page) {
    case 'commander':
        $orderController->checkout();
        break;

    case 'confirmation':
        $orderId = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $totalHT = isset($_GET['ht']) ? floatval($_GET['ht']) : 0;
        $montantTVA = isset($_GET['tva']) ? floatval($_GET['tva']) : 0;
        $totalTTC = isset($_GET['ttc']) ? floatval($_GET['ttc']) : 0;

        require_once 'views/header.php';
        require_once 'views/confirmation_commande.php';
        require_once 'views/footer.php';
        break;

    case 'mes_commandes':
        $orders = $orderController->mesCommandes();

        require_once 'views/header.php';
        require_once 'views/mes_commandes.php';
        require_once 'views/footer.php';
        break;

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
    
    case 'admin_dashboard':
        // On appelle la méthode du contrôleur qui va récupérer $allOrders et charger la vue
        $adminController->dashboard();
        break;

    case 'admin_modifier_statut':
        // On appelle la méthode qui traite le formulaire POST de mise à jour du statut
        $adminController->modifierStatutCommande();
        break;

    case 'profile':
        require_once 'controllers/ProfileController.php';
        $controller = new ProfileController($db);
        $controller->index();
        break;

    case 'profile_update':
        require_once 'controllers/ProfileController.php';
        $controller = new ProfileController($db);
        $controller->update();
        break; 

    // ... tes autres cas (home, admin_dashboard, connexion...) ...

    case 'admin_dashboard':
        require_once 'controllers/AdminController.php';
        $controller = new AdminController($db);
        $controller->dashboard();
        break;

    // 📥 AJOUT : Routage pour l'exportation CSV/Excel des ventes
    case 'export_sales':
        require_once 'controllers/AdminController.php';
        // 📁 Remplacer $db par la vraie variable globale de connexion (ex: $pdo)
        $controller = new AdminController($pdo); 
        $controller->exportSales();
        break;
    case 'modifier_statut_commande':
        require_once 'controllers/AdminController.php';
        $controller = new AdminController($db);
        $controller->modifierStatutCommande();
        break;

    // ...
    default:
        require_once 'controllers/HomeController.php';
        $controller = new HomeController($db);
        $controller->index();
        http_response_code(404);
        echo "<h1>Erreur 404 - Page non trouvée</h1>";
        break;
}