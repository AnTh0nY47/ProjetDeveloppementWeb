<?php
include_once __DIR__ . '/../inc/db.php';

$action = $_GET['action'] ?? '';

if ($action === 'register_etudiant' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $consent = isset($_POST['consent']) ? 1 : 0;

    // Validation stricte du domaine JUNIA
    if (!str_ends_with($email, '@junia.com')) {
        header("Location: ../pages/inscription.php?error=L'adresse email doit être une adresse @junia.com");
        exit;
    }
    if (!$consent) {
        header("Location: ../pages/inscription.php?error=Vous devez accepter la politique RGPD.");
        exit;
    }

    // Vérification unicité
    $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        header("Location: ../pages/inscription.php?error=Cet email est déjà enregistré.");
        exit;
    }

    // Insertion
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    $pdo->beginTransaction();
    try {
        $stmt = $pdo->prepare("INSERT INTO utilisateurs (email, mot_de_passe, role) VALUES (?, ?, 'etudiant')");
        $stmt->execute([$email, $hashedPassword]);
        $userId = $pdo->lastInsertId();

        $stmt = $pdo->prepare("INSERT INTO etudiants (user_id, nom, prenom, date_naissance, consentement_rgpd) VALUES (?, '', '', CURDATE(), ?)");
        $stmt->execute([$userId, $consent]);

        $pdo->commit();
        header("Location: ../pages/connexion.php?success=Compte créé ! Connectez-vous et remplissez votre profil.");
    } catch (Exception $e) {
        $pdo->rollBack();
        header("Location: ../pages/inscription.php?error=Une erreur est survenue.");
    }
    exit;
}

if ($action === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['mot_de_passe'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['email'] = $user['email'];

        if ($user['role'] === 'etudiant') {
            header("Location: ../pages/profil.php");
        } elseif ($user['role'] === 'entreprise') {
            header("Location: ../pages/catalogue.php");
        } else {
            header("Location: ../pages/admin/dashboard.php");
        }
    } else {
        header("Location: ../pages/connexion.php?error=Identifiants incorrects.");
    }
    exit;
}

if ($action === 'logout') {
    session_destroy();
    header("Location: ../index.php");
    exit;
}
?>