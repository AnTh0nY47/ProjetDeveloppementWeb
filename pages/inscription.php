<?php include_once '../inc/header.php'; ?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-body p-5">
                <h2 class="text-center mb-4 text-junia-purple">Inscription Étudiant</h2>
                <?php if(isset($_GET['error'])): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
                <?php endif; ?>
                
                <form action="../api/auth.php?action=register_etudiant" method="POST" id="registerForm">
                    <div class="mb-3">
                        <label class="form-label">Email Académique (@junia.com)</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="nom.prenom@junia.com" required>
                        <div class="invalid-feedback">L'adresse doit obligatoirement se terminer par @junia.com</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mot de passe</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="consent" id="consent" required>
                        <label class="form-check-label small text-muted" for="consent">
                            J'accepte que JUNIA collecte et traite mes données de profil afin de les diffuser auprès des entreprises partenaires conformément à la politique RGPD.
                        </label>
                    </div>
                    <button type="submit" class="btn btn-junia-orange w-100">Créer mon compte</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('registerForm').addEventListener('submit', function(e) {
    const emailInput = document.getElementById('email');
    if (!emailInput.value.endsWith('@junia.com')) {
        e.preventDefault();
        emailInput.classList.add('is-invalid');
    } else {
        emailInput.classList.remove('is-invalid');
    }
});
</script>
<?php include_once '../inc/footer.php'; ?>