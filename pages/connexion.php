<?php include_once '../inc/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-body p-5">
                <h2 class="text-center mb-4 text-junia-purple">Connexion</h2>
                
                <?php if(isset($_GET['error'])): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
                <?php endif; ?>
                
                <?php if(isset($_GET['success'])): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></div>
                <?php endif; ?>
                
                <form action="../api/auth.php?action=login" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Adresse Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mot de passe</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-junia-purple w-100 mt-3">Se connecter</button>
                </form>

                <hr class="my-4 text-muted">

                <div class="text-center">
                    <p class="mb-1 text-secondary small">Vous êtes une entreprise et vous n'avez pas encore d'identifiants ?</p>
                    <a href="contact.php" class="text-junia-orange fw-bold text-decoration-none small">
                        👉 Rejoindre la plateforme & demander un accès
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once '../inc/footer.php'; ?>