<?php
// controllers/AuthController.php

require_once 'models/User.php';

class AuthController {
    private $userModel;

    // Le constructeur initialise le modèle User avec la connexion PDO
    public function __construct($database) {
        $this->userModel = new User($database);
    }

    /**
     * Gérer l'inscription d'un utilisateur
     */
    public function inscription() {
        $erreur = null;
        $succes = null;

        // Si le formulaire est soumis (Méthode POST)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = trim($_POST['nom']);
            $email = trim($_POST['email']);
            $password = $_POST['password'];

            // Validation simple des données
            if (empty($nom) || empty($email) || empty($password)) {
                $erreur = "Tous les champs sont obligatoires.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $erreur = "Format d'email invalide.";
            } elseif (strlen($password) < 6) {
                $erreur = "Le mot de passe doit contenir au moins 6 caractères.";
            } else {
                // Vérifier si l'email existe déjà
                if ($this->userModel->findByEmail($email)) {
                    $erreur = "Cet email est déjà utilisé par un autre compte.";
                } else {
                    // Tenter l'inscription
                    if ($this->userModel->register($nom, $email, $password)) {
                        $succes = "Votre compte a été créé avec succès ! Vous pouvez vous connecter.";
                    } else {
                        $erreur = "Une erreur est survenue lors de l'inscription.";
                    }
                }
            }
        }

        // Charger la vue de l'inscription et lui passer les variables d'état
        require_once 'views/inscription.php';
    }

    /**
     * Gérer la connexion de l'utilisateur
     */
    public function connexion() {
        $erreur = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email']);
            $password = $_POST['password'];

            if (empty($email) || empty($password)) {
                $erreur = "Veuillez remplir tous les champs.";
            } else {
                // Rechercher l'utilisateur en BDD via le modèle
                $user = $this->userModel->findByEmail($email);

                // Si l'utilisateur existe, on vérifie le mot de passe haché
                if ($user && password_verify($password, $user['password'])) {
                    // Authentification réussie : on remplit la session
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_nom'] = $user['nom'];
                    $_SESSION['user_role'] = $user['role']; // 'client' ou 'admin'

                    // Redirection vers l'accueil ou le tableau de bord de l'admin
                    if ($user['role'] === 'admin') {
                        header('Location: index.php?page=admin_dashboard');
                    } else {
                        header('Location: index.php?page=home');
                    }
                    exit();
                } else {
                    $erreur = "Identifiants incorrects (Email ou mot de passe faux).";
                }
            }
        }

        // Charger la vue de la connexion
        require_once 'views/connexion.php';
    }

    /**
     * Déconnexion de l'utilisateur
     */
    public function deconnexion() {
        session_destroy();
        header('Location: index.php?page=home');
        exit();
    }
}