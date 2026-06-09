<?php
require_once 'models/User.php';
require_once 'models/Order.php';

class ProfileController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
        // Sécurité : Si l'utilisateur n'est pas connecté, on le redirige vers la connexion
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?page=connexion');
            exit();
        }
    }

    public function index() {
        $orderModel = new Order($this->db);
        // On récupère les commandes de l'utilisateur connecté en session
        $userOrders = $orderModel->getUserOrders($_SESSION['user']['id']);

        // Messages d'alerte s'il y en a
        $success = $_SESSION['success'] ?? null;
        $error = $_SESSION['error'] ?? null;
        unset($_SESSION['success'], $_SESSION['error']);

        require_once 'views/header.php';
        require_once 'views/profile.php';
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = trim($_htmlentities($_POST['nom'] ?? ''));
            $email = trim($_POST['email'] ?? '');
            $userId = $_SESSION['user']['id'];

            $userModel = new User($this->db);
            
            if ($userModel->updateProfile($userId, $nom, $email)) {
                // On met à jour la session pour que le changement soit visible immédiatement
                $_SESSION['user']['nom'] = $nom;
                $_SESSION['user']['email'] = $email;
                $_SESSION['success'] = "Profil mis à jour avec succès !";
            } else {
                $_SESSION['error'] = "Impossible de mettre à jour le profil.";
            }
        }
        header('Location: index.php?page=profile');
        exit();
    }
}