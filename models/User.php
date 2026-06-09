<?php
// models/User.php

class User {
    private $db;

    // Le constructeur reçoit la connexion PDO globale
    public function __construct($database) {
        $this->db = $database;
    }

    /**
     * Enregistrer un nouvel utilisateur (Inscription)
     */
    public function register($nom, $email, $password) {
        // Sécurisation du mot de passe avec l'algorithme pro BCRYPT
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        
        $sql = "INSERT INTO users (nom, email, password, role) VALUES (:nom, :email, :password, 'client')";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([
            ':nom' => htmlspecialchars($nom), // Protection contre les failles XSS
            ':email' => filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : null,
            ':password' => $hashed_password
        ]);
    }

    /**
     * Trouver un utilisateur par son email (Connexion et vérification)
     */
    public function findByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);
        
        return $stmt->fetch(); // Retourne les données de l'utilisateur ou false s'il n'existe pas
    }
    /**
     * Mettre à jour les informations de base du profil client
     */
    public function updateProfile($id, $nom, $email) {
        $sql = "UPDATE users SET nom = :nom, email = :email WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nom' => $nom,
            ':email' => $email,
            ':id' => $id
        ]);
    }

    /**
     * Mettre à jour le mot de passe de manière hachée et sécurisée
     */
    public function updatePassword($id, $newPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        $sql = "UPDATE users SET password = :password WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':password' => $hashedPassword,
            ':id' => $id
        ]);
    }
}