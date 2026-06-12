<?php 
// Inclusion du header global (vérifiez bien le chemin d'accès selon votre structure)
include_once '../inc/header.php'; 

$msg = "";

// Traitement du formulaire à la soumission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom_entreprise = strip_tags(trim($_POST['nom_entreprise']));
    $email_contact = filter_var($_POST['email_contact'], FILTER_SANITIZE_EMAIL);
    $telephone = strip_tags(trim($_POST['telephone']));
    $message = strip_tags(trim($_POST['message']));

    if (!empty($nom_entreprise) && !empty($email_contact) && !empty($message)) {
        if (filter_var($email_contact, FILTER_VALIDATE_EMAIL)) {
            try {
                $stmt = $pdo->prepare("INSERT INTO demandes_contact (nom_entreprise, email_contact, telephone, message) VALUES (?, ?, ?, ?)");
                $stmt->execute([$nom_entreprise, $email_contact, $telephone, $message]);
                
                $msg = "<div class='alert alert-success'><strong>Merci !</strong> Votre demande de partenariat a bien été transmise à l'administration de JUNIA. Un email contenant vos accès vous sera envoyé après validation.</div>";
            } catch (Exception $e) {
                $msg = "<div class='alert alert-danger'>Une erreur technique est survenue. Veuillez réessayer plus tard.</div>";
            }
        } else {
            $msg = "<div class='alert alert-danger'>L'adresse email saisie n'est pas valide.</div>";
        }
    } else {
        $msg = "<div class='alert alert-danger'>Veuillez remplir tous les champs obligatoires (*).</div>";
    }
}
?>

<div class="row justify-content-center mt-4">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 p-4 p-md-5">
            <div class="text-center mb-4">
                <h2 class="text-junia-purple fw-bold">Devenir Entreprise Partenaire</h2>
                <p class="text-muted">Vous souhaitez accéder au catalogue de CV de nos étudiants ingénieurs HEI, ISEN et ISA ? Remplissez ce formulaire pour demander vos identifiants de connexion.</p>
            </div>

            <?= $msg ?>

            <form action="" method="POST" class="needs-validation" novalidate>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Nom de l'entreprise *</label>
                        <input type="text" name="nom_entreprise" class="form-control" placeholder="Ex: Tech Company" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Email professionnel de contact *</label>
                        <input type="email" name="email_contact" class="form-control" placeholder="Ex: rh@entreprise.com" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Téléphone de la ligne directe</label>
                    <input type="tel" name="telephone" class="form-control" placeholder="Ex: 03 20 ...">
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Présentation de vos besoins en recrutement *</label>
                    <textarea name="message" class="form-control" rows="5" placeholder="Indiquez brièvement les profils recherchés (Stages, Alternances, CDI...) ainsi que les spécialités (Informatique, BTP, Agroalimentaire...)" required></textarea>
                </div>

                <div class="text-end">
                    <a href="connexion.php" class="btn btn-outline-secondary me-2">Retour à la connexion</a>
                    <button type="submit" class="btn btn-junia-orange px-4">Envoyer ma demande</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once '../inc/footer.php'; ?>