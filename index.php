<?php
// index.php - Point d'entrée unique de l'application ShopESA

// Démarrage de la session pour gérer le panier et la connexion des utilisateurs
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inclusion automatique de la connexion à la base de données
if (file_exists('config/db.php')) {
    require_once 'config/db.php';
} else {
    die("Le fichier de configuration de la base de données est manquant. Veuillez configurer config/db.php.");
}

// Système de routage simple (basé sur le paramètre d'URL 'page')
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Structure de contrôle pour diriger l'utilisateur vers le bon contrôleur
switch ($page) {
    case 'home':
        // Exemple : Appeler le contrôleur de la page d'accueil
        // require_once 'controllers/HomeController.php';
        echo "<h1>Bienvenue sur ShopESA ! (Page d'accueil)</h1><p>Le routeur fonctionne parfaitement.</p>";
        break;

    case 'catalogue':
        // Page de la liste des produits
        echo "<h1>Catalogue des Produits ShopESA</h1>";
        break;

    case 'panier':
        // Page de gestion du panier
        echo "<h1>Votre Panier</h1>";
        break;

    case 'connexion':
        // Page de connexion
        echo "<h1>Connexion Client / Admin</h1>";
        break;

    case 'inscription':
        // Page d'inscription
        echo "<h1>Créer un compte</h1>";
        break;

    default:
        // Gestion propre de l'erreur 404 si la page demandée n'existe pas
        http_response_code(404);
        echo "<h1>Erreur 404 - Page non trouvée</h1><p>Désolé, la page demandée n'existe pas.</p>";
        break;
}