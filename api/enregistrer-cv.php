<?php
include_once __DIR__ . '/../inc/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'etudiant') {
    exit('Accès interdit');
}

$action = $_GET['action'] ?? '';

// Récupération de l'ID étudiant lié
$stmt = $pdo->prepare("SELECT id, photo FROM etudiants WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$etudiant = $stmt->fetch();
$etudiantId = $etudiant['id'];

if ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = strip_tags($_POST['nom']);
    $prenom = strip_tags($_POST['prenom']);
    $date_naissance = $_POST['date_naissance'];
    $telephone = strip_tags($_POST['telephone']);
    $ecole_promo = strip_tags($_POST['ecole_promo']);
    $biographie = strip_tags($_POST['biographie']);
    $parcours_academique = strip_tags($_POST['parcours_academique']);
    $experiences_pro = strip_tags($_POST['experiences_pro']);
    $competences = strip_tags($_POST['competences']);
    $langues = strip_tags($_POST['langues']);

    // Gestion du fichier Photo
    $nomPhoto = $etudiant['photo'];
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['photo']['tmp_name'];
        $fileName = $_FILES['photo']['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        $extensionsAutorisees = ['jpg', 'jpeg', 'png'];
        if (in_array($fileExtension, $extensionsAutorisees)) {
            $nomPhoto = md5(time() . $fileName) . '.' . $fileExtension;
            $uploadFileDir = __DIR__ . '/../uploads/';
            if(!is_dir($uploadFileDir)) mkdir($uploadFileDir, 0777, true);
            move_uploaded_file($fileTmpPath, $uploadFileDir . $nomPhoto);
        }
    }

    // Update table principale
    $stmt = $pdo->prepare("UPDATE etudiants SET nom=?, prenom=?, date_naissance=?, telephone=?, ecole_promo=?, biographie=?, parcours_academique=?, experiences_pro=?, competences=?, langues=?, photo=? WHERE id=?");
    $stmt->execute([$nom, $prenom, $date_naissance, $telephone, $ecole_promo, $biographie, $parcours_academique, $experiences_pro, $competences, $langues, $nomPhoto, $etudiantId]);

    // Update des domaines de recherche (Reset puis réinsertion)
    $stmt = $pdo->prepare("DELETE FROM domaines_recherche WHERE etudiant_id = ?");
    $stmt->execute([$etudiantId]);

    if (isset($_POST['domaines']) && is_array($_POST['domaines'])) {
        $stmtIns = $pdo->prepare("INSERT INTO domaines_recherche (etudiant_id, domaine) VALUES (?, ?)");
        foreach ($_POST['domaines'] as $domaine) {
            $stmtIns->execute([$etudiantId, $domaine]);
        }
    }

    header("Location: ../pages/profil.php?success=1");
    exit;
}

if ($action === 'delete_account' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // RGPD : Cascade supprime automatiquement l'étudiant et ses liaisons
    $stmt = $pdo->prepare("DELETE FROM utilisateurs WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    session_destroy();
    header("Location: ../index.php");
    exit;
}
?>