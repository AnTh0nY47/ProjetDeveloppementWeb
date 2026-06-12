<?php
include_once __DIR__ . '/../inc/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'entreprise') {
    exit('Accès interdit');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $etudiantId = intval($_POST['etudiant_id']);
    $dateEntretien = $_POST['date_entretien'];

    // Récupérer l'ID entreprise correspondant à la session connectée
    $stmt = $pdo->prepare("SELECT id, nom_entreprise FROM entreprises WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $entreprise = $stmt->fetch();
    $entrepriseId = $entreprise['id'];

    // Enregistrer l'historique en base de données
    $stmtIns = $pdo->prepare("INSERT INTO convocations (entreprise_id, etudiant_id, date_entretien) VALUES (?, ?, ?)");
    $stmtIns->execute([$entrepriseId, $etudiantId, $dateEntretien]);

    // Récupération de l'email de l'étudiant pour la simulation d'envoi
    $stmtEtudiant = $pdo->prepare("SELECT u.email FROM etudiants e JOIN utilisateurs u ON e.user_id = u.id WHERE e.id = ?");
    $stmtEtudiant->execute([$etudiantId]);
    $emailEtudiant = $stmtEtudiant->fetchColumn();

    /* 
       Simulation de l'envoi d'email automatique demandé au cahier des charges.
       Dans un environnement réel (PHPMailer + SMTP comme valorisé au Bonus), le code ressembler lierait :
       mail($emailEtudiant, "Convocation Entretien JUNIA", "L'entreprise ".$entreprise['nom_entreprise']." vous convoque le ".$dateEntretien);
    */

    header("Location: ../pages/catalogue.php?success=1");
    exit;
}
?>