<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShopCaphy - Plateforme E-Commerce</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js">
    <style>
        #preloader {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background-color: #1a3a5c; /* Ton bleu nuit */
            display: flex; justify-content: center; align-items: center;
            z-index: 9999;
            transition: opacity 0.5s ease;
        }
        .coin {
            width: 60px; height: 60px;
            background-color: #e4be5b; /* Ton Or */
            border-radius: 50%;
            position: relative;
            border: 4px dashed #fff;
            box-shadow: 0 0 15px rgba(228, 190, 91, 0.6);
            animation: spin3d 1.5s infinite linear;
        }
        @keyframes spin3d {
            0% { transform: rotateY(0deg); }
            100% { transform: rotateY(360deg); }
        }
        .fade-out { opacity: 0; pointer-events: none; }
        </style>

        <div id="preloader">
            <div class="coin"></div>
        </div>

        <script>
        window.addEventListener('load', () => {
            const preloader = document.getElementById('preloader');
            setTimeout(() => {
                preloader.classList.add('fade-out');
            }, 800); // Laisse l'animation tourner un court instant
        });
    </script>
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php?page=home">ShopCaphy</a>
        <div class="navbar-nav ms-auto">
            <a class="nav-link" href="index.php?page=catalogue">Catalogue</a>
            <a class="nav-link" href="index.php?page=panier">Panier</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <span class="navbar-text text-white me-3">Bonjour, <?= htmlspecialchars($_SESSION['user_nom']) ?></span>
                <a class="btn btn-outline-danger btn-sm" href="index.php?page=deconnexion">Déconnexion</a>
            <?php else: ?>
                <a class="nav-link" href="index.php?page=connexion">Connexion</a>
                <a class="nav-link text-white fw-bold" href="index.php?page=inscription">S'inscrire</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<!-- <div class="container">
    <footer class="bg-dark text-white text-center py-3 mt-5">
    <p class="mb-0">&copy; 2026 ShopCaphy. Tous droits réservés.</p>
</footer> -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>