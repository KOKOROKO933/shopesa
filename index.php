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

// Remplace le bloc switch existant dans ton index.php par celui-ci :

// Inclusion du contrôleur d'authentification
require_once 'controllers/AuthController.php';
$authController = new AuthController($pdo); // On lui passe la connexion PDO $pdo de config/db.php

switch ($page) {
    case 'home':
        echo "<h1>Bienvenue chez Nous ! (Page d'accueil)</h1><p>Le routeur fonctionne parfaitement.</p>";
        break;

    case 'catalogue':
        echo "<h1>Catalogue des Produits ShopESA</h1>";
        break;

    case 'panier':
        echo "<h1>Votre Panier</h1>";
        break;

    case 'connexion':
        // On délègue le travail au contrôleur
        $authController->connexion();
        break;

    case 'inscription':
        // On délègue le travail au contrôleur
        $authController->inscription();
        break;
        
    case 'deconnexion':
        $authController->deconnexion();
        break;

    default:
        // Gestion propre de l'erreur 404 si la page demandée n'existe pas
        http_response_code(404);
        echo "<h1>Erreur 404 - Page non trouvée</h1><p>Désolé, la page demandée n'existe pas.</p>";
        break;
}