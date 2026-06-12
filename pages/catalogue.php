<?php 
include_once '../inc/header.php'; 
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'entreprise') {
    header("Location: connexion.php");
    exit;
}

// Filtres de recherche reçus par l'URL
$filtreDomaine = $_GET['domaine'] ?? '';
$filtreSearch = $_GET['search'] ?? '';

// Construction SQL dynamique et sécurisée
$sql = "SELECT DISTINCT e.* FROM etudiants e 
        LEFT JOIN domaines_recherche d ON e.id = d.etudiant_id 
        WHERE 1=1";
$params = [];

if ($filtreDomaine) {
    $sql .= " AND d.domaine = ?";
    $params[] = $filtreDomaine;
}
if ($filtreSearch) {
    $sql .= " AND (e.competences LIKE ? OR e.nom LIKE ? OR e.prenom LIKE ?)";
    $params[] = "%$filtreSearch%";
    $params[] = "%$filtreSearch%";
    $params[] = "%$filtreSearch%";
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$etudiants = $stmt->fetchAll();
?>

<h2 class="text-junia-purple mb-4">Catalogue des Profils Étudiants</h2>

<!-- Zone de Filtres -->
<div class="card p-3 mb-4 bg-light border-0 shadow-sm">
    <form method="GET" class="row g-3">
        <div class="col-md-5">
            <input type="text" name="search" class="form-control" placeholder="Rechercher par compétence, nom..." value="<?= htmlspecialchars($filtreSearch) ?>">
        </div>
        <div class="col-md-4">
            <select name="domaine" class="form-select">
                <option value="">Tous les domaines de recherche</option>
                <option value="Stage 1A" <?= $filtreDomaine == 'Stage 1A' ? 'selected' : '' ?>>Stage 1A</option>
                <option value="Stage 2A" <?= $filtreDomaine == 'Stage 2A' ? 'selected' : '' ?>>Stage 2A</option>
                <option value="Apprentissage" <?= $filtreDomaine == 'Apprentissage' ? 'selected' : '' ?>>Apprentissage</option>
                <option value="Contrat Pro" <?= $filtreDomaine == 'Contrat Pro' ? 'selected' : '' ?>>Contrat Pro</option>
                <option value="Mobilité Internationale" <?= $filtreDomaine == 'Mobilité Internationale' ? 'selected' : '' ?>>Mobilité Internationale</option>
                <option value="CDI" <?= $filtreDomaine == 'CDI' ? 'selected' : '' ?>>CDI</option>
            </select>
        </div>
        <div class="col-md-3 d-grid">
            <button type="submit" class="btn btn-junia-purple">Filtrer</button>
        </div>
    </form>
</div>

<!-- Résultats -->
<?php if(isset($_GET['success'])): ?><div class="alert alert-success">Convocation envoyée avec succès par courriel à l'étudiant !</div><?php endif; ?>

<div class="row">
    <?php foreach($etudiants as $e): 
        // Récupérer les domaines de l'étudiant pour affichage badge
        $s = $pdo->prepare("SELECT domaine FROM domaines_recherche WHERE etudiant_id = ?");
        $s->execute([$e['id']]);
        $doms = $s->fetchAll(PDO::FETCH_COLUMN);
    ?>
        <div class="col-md-6 mb-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <img src="../uploads/<?= htmlspecialchars($e['photo']) ?>" class="rounded-circle me-3" style="width:70px; height:70px; object-fit:cover;" alt="Photo">
                        <div>
                            <h5 class="mb-0 text-junia-purple"><?= htmlspecialchars($e['prenom'] . ' ' . $e['nom']) ?></h5>
                            <small class="text-muted"><?= htmlspecialchars($e['ecole_promo']) ?></small>
                        </div>
                    </div>
                    
                    <p class="small text-secondary mb-2"><strong>Parcours :</strong> <?= htmlspecialchars($e['parcours_academique']) ?></p>
                    <p class="small text-secondary mb-2"><strong>Compétences :</strong> <?= htmlspecialchars($e['competences']) ?></p>
                    
                    <div class="mb-3">
                        <?php foreach($doms as $d): ?>
                            <span class="badge bg-junia-orange text-white me-1"><?= htmlspecialchars($d) ?></span>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- Bouton Convoquer déclenchant une modale Bootstrap -->
                    <button type="button" class="btn btn-junia-purple btn-sm w-100" data-bs-toggle="modal" data-bs-target="#modal_<?= $e['id'] ?>">
                        Convoquer à un entretien
                    </button>
                </div>
            </div>
        </div>

        <!-- Modale de Convocation pour cet étudiant -->
        <div class="modal fade" id="modal_<?= $e['id'] ?>" isset-tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form action="../api/convoquer.php" method="POST" class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Planifier un entretien avec <?= htmlspecialchars($e['prenom']) ?></h5>
                        <button type="button" class="btn-close" data-bs-shadow="none" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="etudiant_id" value="<?= $e['id'] ?>">
                        <div class="mb-3">
                            <label class="form-label">Date et Heure de la convocation</label>
                            <input type="datetime-local" name="date_entretien" class="form-control" required>
                        </div>
                        <p class="small text-muted">Un email automatique de notification récapitulant vos coordonnées sera envoyé à l'étudiant à la validation.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-junia-orange btn-sm">Confirmer & Envoyer l'email</button>
                    </div>
                </form>
            </div>
        </div>
    <?php endforeach; if(empty($etudiants)) echo "<p class='text-muted text-center'>Aucun profil ne correspond à vos critères.</p>"; ?>
</div>
<?php include_once '../inc/footer.php'; ?>