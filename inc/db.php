<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Configuration ultra-précise pour MAMP sur Windows
$host = '127.0.0.1'; // On utilise l'IP locale pour éviter les bugs de résolution
$port = '3306';      // C'est le port MySQL écrit sur ta page d'accueil MAMP !
$db   = 'junia_cv1';
$user = 'root';
$pass = 'root';      // Par défaut sous MAMP le mot de passe est 'root'
$charset = 'utf8mb4';

// On intègre le port directement dans la chaîne de connexion
$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     // Si la connexion échoue, cette ligne va ENFIN afficher la vraie erreur SQL sur ton écran !
     die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>