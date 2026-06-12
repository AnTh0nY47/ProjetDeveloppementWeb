<?php
// On inclut la connexion à la base de données MAMP
// Comme le script est à la racine, le chemin est 'inc/db.php'
require_once 'inc/db.php';

// On génère des hashs tout propres localement sur VOTRE MAMP
$hash_admin = password_hash('admin123', PASSWORD_BCRYPT);
$hash_entreprise = password_hash('entreprise123', PASSWORD_BCRYPT);

try {
    // 1. Mise à jour ou insertion sécurisée du compte Administrateur
    $stmtAdmin = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = 'admin@junia.com'");
    $stmtAdmin->execute();
    if ($stmtAdmin->fetch()) {
        $stmt = $pdo->prepare("UPDATE utilisateurs SET mot_de_passe = ? WHERE email = 'admin@junia.com'");
        $stmt->execute([$hash_admin]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO utilisateurs (email, mot_de_passe, role) VALUES ('admin@junia.com', ?, 'admin')");
        $stmt->execute([$hash_admin]);
    }

    // 2. Mise à jour ou insertion sécurisée du compte Entreprise
    $stmtEntreprise = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = 'pro@junia.com'");
    $stmtEntreprise->execute();
    $userEntreprise = $stmtEntreprise->fetch();

    if ($userEntreprise) {
        // L'utilisateur existe déjà, on met à jour son mot de passe
        $stmt = $pdo->prepare("UPDATE utilisateurs SET mot_de_passe = ? WHERE email = 'pro@junia.com'");
        $stmt->execute([$hash_entreprise]);
        $userId = $userEntreprise['id'];
    } else {
        // L'utilisateur n'existe pas, on le crée
        $stmt = $pdo->prepare("INSERT INTO utilisateurs (email, mot_de_passe, role) VALUES ('pro@junia.com', ?, 'entreprise')");
        $stmt->execute([$hash_entreprise]);
        $userId = $pdo->lastInsertId();
    }

    // 3. On s'assure que l'entreprise possède bien sa liaison obligatoire dans la table 'entreprises'
    $stmtCheckLink = $pdo->prepare("SELECT id FROM entreprises WHERE user_id = ?");
    $stmtCheckLink->execute([$userId]);
    if (!$stmtCheckLink->fetch()) {
        $stmtLink = $pdo->prepare("INSERT INTO entreprises (user_id, nom_entreprise, contact_nom) VALUES (?, 'Partenaire JUNIA Pro', 'Responsable RH')");
        $stmtLink->execute([$userId]);
    }
    
    echo "<div style='padding: 20px; border: 2px solid green; background-color: #e6ffe6; border-radius: 8px; max-width: 600px; margin: 30px auto;'>";
    echo "<h2 style='color: green; font-family: sans-serif; margin-top: 0;'>✅ Succès ! Les hashs ont été régénérés par votre MAMP.</h2>";
    echo "<p style='font-family: sans-serif;'>Les comptes ont été synchronisés avec succès dans la table <code>utilisateurs</code> :</p>";
    echo "<ul style='font-family: sans-serif;'>";
    echo "<li><strong>Compte Admin :</strong> admin@junia.com / <code>admin123</code></li>";
    echo "<li><strong>Compte Entreprise :</strong> pro@junia.com / <code>entreprise123</code></li>";
    echo "</ul>";
    echo "<p style='font-family: sans-serif; margin-bottom: 0;'><a href='pages/connexion.php' style='display: inline-block; background: #6B2C91; color: white; padding: 10px 15px; text-decoration: none; border-radius: 4px;'>👉 Retourner à la page de connexion</a></p>";
    echo "</div>";

} catch (\PDOException $e) {
    echo "<div style='padding: 20px; border: 2px solid red; background-color: #ffe6e6; border-radius: 8px; max-width: 600px; margin: 30px auto; font-family: sans-serif;'>";
    echo "<h2 style='color: red; margin-top: 0;'>❌ Erreur lors de la mise à jour :</h2>";
    echo "<pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
    echo "</div>";
}
?>