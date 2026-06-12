<?php include_once __DIR__ . '/db.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portail CV - JUNIA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Open+Sans&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Open Sans', sans-serif; background-color: #f8f9fa; }
        h1, h2, h3, .navbar-brand { font-family: 'Montserrat', sans-serif; font-weight: 700; }
        .bg-junia-purple { background-color: #6B2C91 !important; }
        .text-junia-purple { color: #6B2C91 !important; }
        .btn-junia-purple { background-color: #6B2C91; color: white; }
        .btn-junia-purple:hover { background-color: #552274; color: white; }
        .btn-junia-orange { background-color: #F39200; color: white; }
        .btn-junia-orange:hover { background-color: #d67f00; color: white; }
        .border-junia { border-color: #6B2C91 !important; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-junia-purple shadow-sm">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="/projet-cv-junia/index.php">
            <span style="color: #F39200; font-size: 1.5rem; margin-right: 5px;">■</span> JUNIA CV
        </a>
        <button class="navbar-expand-lg navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item"><a class="nav-link text-white" href="/projet-cv-junia/index.php">Accueil</a></li>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <?php if($_SESSION['role'] == 'etudiant'): ?>
                        <li class="nav-item"><a class="nav-link text-white" href="/projet-cv-junia/pages/profil.php">Mon CV</a></li>
                    <?php elseif($_SESSION['role'] == 'entreprise'): ?>
                        <li class="nav-item"><a class="nav-link text-white" href="/projet-cv-junia/pages/catalogue.php">Catalogue</a></li>
                    <?php elseif($_SESSION['role'] == 'admin'): ?>
                        <li class="nav-item"><a class="nav-link text-white" href="/projet-cv-junia/pages/admin/dashboard.php">Administration</a></li>
                    <?php endif; ?>
                    <li class="nav-item ms-lg-3"><a class="btn btn-junia-orange btn-sm" href="/projet-cv-junia/api/auth.php?action=logout">Déconnexion</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link text-white" href="/projet-cv-junia/pages/connexion.php">Connexion</a></li>
                    <li class="nav-item ms-lg-2"><a class="btn btn-junia-orange btn-sm" href="/projet-cv-junia/pages/inscription.php">Inscription Étudiant</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<div class="container my-4" style="min-height: 75vh;">