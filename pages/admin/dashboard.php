<?php 
include_once '../../inc/header.php'; 

// Sécurité : Vérification que l'utilisateur est connecté ET qu'il est administrateur
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../connexion.php");
    exit;
}

// 1. Traitement de la création manuelle d'un compte Entreprise par l'Admin
$msg = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_entreprise'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $nom_entreprise = strip_tags($_POST['nom_entreprise']);

    // Vérification de l'unicité de l'adresse email
    $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        $msg = "<div class='alert alert-danger'>Cet email est déjà utilisé.</div>";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $pdo->beginTransaction();
        try {
            // Insertion dans la table générique des utilisateurs
            $stmt = $pdo->prepare("INSERT INTO utilisateurs (email, mot_de_passe, role) VALUES (?, ?, 'entreprise')");
            $stmt->execute([$email, $hashedPassword]);
            $userId = $pdo->lastInsertId();

            // Insertion dans la table spécifique des entreprises
            $stmt = $pdo->prepare("INSERT INTO entreprises (user_id, nom_entreprise) VALUES (?, ?)");
            $stmt->execute([$userId, $nom_entreprise]);

            $pdo->commit();
            $msg = "<div class='alert alert-success'>Compte Entreprise pour <strong>$nom_entreprise</strong> créé avec succès !</div>";
        } catch (Exception $e) {
            $pdo->rollBack();
            $msg = "<div class='alert alert-danger'>Une erreur est survenue lors de la création.</div>";
        }
    }
}

// 2. Récupération des statistiques dynamiques (Bonus valorisé)
$countEtudiants = $pdo->query("SELECT COUNT(*) FROM etudiants")->fetchColumn();
$countEntreprises = $pdo->query("SELECT COUNT(*) FROM entreprises")->fetchColumn();
$countConvocations = $pdo->query("SELECT COUNT(*) FROM convocations")->fetchColumn();

// 3. Récupération de la liste des comptes utilisateurs (Modération)
$utilisateurs = $pdo->query("SELECT id, email, role, date_creation FROM utilisateurs WHERE role != 'admin' ORDER BY date_creation DESC")->fetchAll();

// 4. Récupération des demandes de contact / partenariat reçues
$demandes = $pdo->query("SELECT * FROM demandes_contact ORDER BY date_envoi DESC")->fetchAll();
?>

<h2 class="text-junia-purple mb-4">Tableau de Bord Administrateur (JUNIA)</h2>
<?= $msg ?>

<div class="row mb-5 text-center">
    <div class="col-md-4 mb-3">
        <div class="card p-3 border-start border-junia border-4 shadow-sm">
            <h5 class="text-muted">Étudiants inscrits</h5>
            <h2 class="text-junia-purple fw-bold"><?= $countEtudiants ?></h2>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card p-3 border-start border-warning border-4 shadow-sm">
            <h5 class="text-muted">Entreprises partenaires</h5>
            <h2 class="text-junia-orange fw-bold"><?= $countEntreprises ?></h2>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card p-3 border-start border-success border-4 shadow-sm">
            <h5 class="text-muted">Entretiens planifiés</h5>
            <h2 class="text-success fw-bold"><?= $countConvocations ?></h2>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-5 mb-4">
        <div class="card shadow-sm border-0 p-4">
            <h4 class="text-junia-purple mb-3">Créer un accès Entreprise</h4>
            <form action="" method="POST">
                <input type="hidden" name="create_entreprise" value="1">
                <div class="mb-3">
                    <label class="form-label">Raison Sociale / Nom de l'entreprise</label>
                    <input type="text" name="nom_entreprise" class="form-control" placeholder="Ex: Alumni JUNIA, Microsoft..." required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email de connexion</label>
                    <input type="email" name="email" class="form-control" placeholder="rh@entreprise.com" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Mot de passe provisoire</label>
                    <input type="text" name="password" class="form-control" value="JuniaPartner<?= rand(100,999) ?>" required>
                </div>
                <button type="submit" class="btn btn-junia-orange w-100">Générer les identifiants</button>
            </form>
        </div>
    </div>

    <div class="col-lg-7 mb-4">
        <div class="card shadow-sm border-0 p-4">
            <h4 class="text-junia-purple mb-3">Gestion & Modération des comptes</h4>
            <div class="table-responsive" style="max-height: 335px; overflow-y: auto;">
                <table class="table table-striped align-middle small mb-0">
                    <thead>
                        <tr>
                            <th>Email</th>
                            <th>Rôle</th>
                            <th>Date d'inscription</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($utilisateurs as $user): ?>
                            <tr>
                                <td><?= htmlspecialchars($user['email']) ?></td>
                                <td>
                                    <span class="badge <?= $user['role'] === 'etudiant' ? 'bg-primary' : 'bg-success' ?>">
                                        <?= ucfirst($user['role']) ?>
                                    </span>
                                </td>
                                <td><?= date('d/m/Y H:i', strtotime($user['date_creation'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if(empty($utilisateurs)): ?>
                            <tr>
                                <td colspan="3" class="text-center text-muted">Aucun utilisateur enregistré pour le moment.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-12">
        <div class="card shadow-sm border-0 p-4">
            <h4 class="text-junia-purple mb-3">📩 Demandes de partenariat en attente (contact)</h4>
            <div class="table-responsive">
                <table class="table table-bordered align-middle small mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Entreprise</th>
                            <th>Contact & Coordonnées</th>
                            <th>Message / Présentation des besoins</th>
                            <th>Date de réception</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($demandes as $d): ?>
                            <tr>
                                <td class="fw-bold text-junia-purple"><?= htmlspecialchars($d['nom_entreprise']) ?></td>
                                <td>
                                    <a href="mailto:<?= htmlspecialchars($d['email_contact']) ?>" class="text-decoration-none">
                                        <?= htmlspecialchars($d['email_contact']) ?>
                                    </a>
                                    <?php if(!empty($d['telephone'])): ?>
                                        <br><span class="text-muted text-nowrap">📞 <?= htmlspecialchars($d['telephone']) ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <p class="mb-0 text-secondary" style="white-space: pre-line; max-height: 100px; overflow-y: auto;"><?= htmlspecialchars($d['message']) ?></p>
                                </td>
                                <td class="text-muted text-nowrap">
                                    <?= date('d/m/Y H:i', strtotime($d['date_envoi'])) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if(empty($demandes)): ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted py-3">Aucune demande de partenariat reçue pour le moment.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include_once '../../inc/footer.php'; ?>