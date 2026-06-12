<?php 
include_once '../inc/header.php'; 
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'etudiant') {
    header("Location: connexion.php");
    exit;
}

// Récupération des données actuelles
$stmt = $pdo->prepare("SELECT * FROM etudiants WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$etudiant = $stmt->fetch();

// Récupération des domaines de recherche cochés
$stmtDom = $pdo->prepare("SELECT domaine FROM domaines_recherche WHERE etudiant_id = ?");
$stmtDom->execute([$etudiant['id']]);
$domainesCoches = $stmtDom->fetchAll(PDO::FETCH_COLUMN);

$tousDomaines = ['Stage 1A', 'Stage 2A', 'Apprentissage', 'Contrat Pro', 'Mobilité Internationale', 'CDI'];
?>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-0 text-center p-4">
            <img src="../uploads/<?= htmlspecialchars($etudiant['photo'] ?? 'default.png') ?>" class="rounded-circle mx-auto img-thumbnail" style="width: 150px; hieght: 150px; object-fit: cover;" alt="Photo">
            <h4 class="mt-3"><?= htmlspecialchars($etudiant['prenom'] . ' ' . $etudiant['nom']) ?></h4>
            <p class="text-muted small"><?= htmlspecialchars($etudiant['ecole_promo'] ?? 'Non renseigné') ?></p>
            <hr>
            <form action="../api/enregistrer-cv.php?action=delete_account" method="POST" onsubmit="return confirm('ATTENTION : Voulez-vous vraiment supprimer définitivement votre compte et l\'intégralité de vos données de CV ?');">
                <button type="submit" class="btn btn-danger btn-sm w-100">Supprimer mon compte (RGPD)</button>
            </form>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card shadow-sm border-0 p-4">
            <h2 class="mb-4 text-junia-purple">Édition de votre CV Standardisé</h2>
            <?php if(isset($_GET['success'])): ?><div class="alert alert-success">Profil mis à jour !</div><?php endif; ?>
            
            <form action="../api/enregistrer-cv.php?action=update" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6 mb-3"><label class="form-label">Nom</label><input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($etudiant['nom']) ?>" required></div>
                    <div class="col-md-6 mb-3"><label class="form-label">Prénom</label><input type="text" name="prenom" class="form-control" value="<?= htmlspecialchars($etudiant['prenom']) ?>" required></div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3"><label class="form-label">Date de naissance</label><input type="date" name="date_naissance" class="form-control" value="<?= $etudiant['date_naissance'] ?>" required></div>
                    <div class="col-md-6 mb-3"><label class="form-label">Téléphone</label><input type="text" name="telephone" class="form-control" value="<?= htmlspecialchars($etudiant['telephone'] ?? '') ?>"></div>
                </div>
                <div class="mb-3"><label class="form-label">École & Promotion (ex: HEI 2027, ISEN...)</label><input type="text" name="ecole_promo" class="form-control" value="<?= htmlspecialchars($etudiant['ecole_promo'] ?? '') ?>"></div>
                
                <div class="mb-3"><label class="form-label">Changer la Photo de Profil</label><input type="file" name="photo" class="form-control"></div>
                
                <div class="mb-3"><label class="form-label">Biographie / Lettre d'accroche</label><textarea name="biographie" class="form-control" rows="3"><?= htmlspecialchars($etudiant['biographie'] ?? '') ?></textarea></div>
                <div class="mb-3"><label class="form-label">Parcours Académique</label><textarea name="parcours_academique" class="form-control" rows="3" placeholder="Diplômes, formations..."><?= htmlspecialchars($etudiant['parcours_academique'] ?? '') ?></textarea></div>
                <div class="mb-3"><label class="form-label">Expériences Professionnelles</label><textarea name="experiences_pro" class="form-control" rows="3" placeholder="Stages, jobs d'été..."><?= htmlspecialchars($etudiant['experiences_pro'] ?? '') ?></textarea></div>
                
                <div class="row">
                    <div class="col-md-6 mb-3"><label class="form-label">Compétences Techniques</label><input type="text" name="competences" class="form-control" value="<?= htmlspecialchars($etudiant['competences'] ?? '') ?>" placeholder="PHP, Git, Anglais, ..."></div>
                    <div class="col-md-6 mb-3"><label class="form-label">Langues</label><input type="text" name="langues" class="form-control" value="<?= htmlspecialchars($etudiant['langues'] ?? '') ?>" placeholder="Français, Anglais (TOEIC 850)..."></div>
                </div>

                <div class="mb-3">
                    <label class="form-label d-block"><strong>Domaines de recherche cibles :</strong></label>
                    <?php foreach($tousDomaines as $dom): ?>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="domaines[]" value="<?= $dom ?>" id="dom_<?= md5($dom) ?>" <?= in_array($dom, $domainesCoches) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="dom_<?= md5($dom) ?>"><?= $dom ?></label>
                        </div>
                    <?php endforeach; ?>
                </div>

                <button type="submit" class="btn btn-junia-purple w-100 mt-3">Enregistrer et publier mon CV</button>
            </form>
        </div>
    </div>
</div>
<?php include_once '../inc/footer.php'; ?>