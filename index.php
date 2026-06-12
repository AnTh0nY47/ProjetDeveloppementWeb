<?php include_once 'inc/header.php'; ?>

<div class="p-5 mb-4 bg-light rounded-3 shadow-sm border-start border-junia border-5">
    <div class="container-fluid py-3">
        <h1 class="display-5 fw-bold text-junia-purple">Centralisation des CV JUNIA</h1>
        <p class="col-md-8 fs-4">Trouvez votre prochain contrat d'apprentissage, stage, mobilité internationale ou premier emploi CDI parmi nos profils qualifiés.</p>
        <?php if(!isset($_SESSION['user_id'])): ?>
            <a href="pages/connexion.php" class="btn btn-junia-purple btn-lg">Espace Connexion</a>
            <a href="pages/inscription.php" class="btn btn-junia-orange btn-lg ms-2">Rejoindre la plateforme</a>
        <?php endif; ?>
    </div>
</div>

<div class="row text-center my-5">
    <div class="col-md-3">
        <div class="card p-3 border-0 shadow-sm">
            <h3 class="text-junia-orange">🚀 Alternance</h3>
            <p class="small text-muted">Contrats de Pro et d'apprentissage en 5e année.</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3 border-0 shadow-sm">
            <h3 class="text-junia-purple">💼 Stages</h3>
            <p class="small text-muted">Immergez nos talents de 1re et 2e année dans vos équipes.</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3 border-0 shadow-sm">
            <h3 class="text-junia-orange">🌍 International</h3>
            <p class="small text-muted">Profils disponibles pour vos besoins en mobilité.</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3 border-0 shadow-sm">
            <h3 class="text-junia-purple">🎓 Emploi CDI</h3>
            <p class="small text-muted font-weight-bold">Recrutez nos futurs diplômés opérationnels.</p>
        </div>
    </div>
</div>

<div class="my-5">
    <h2 class="text-center mb-4 text-junia-purple">Ils nous font confiance</h2>
    <div class="row align-items-center justify-content-center g-4 text-center">
        <div class="col-6 col-md-2 grey-logo font-weight-bold fs-4 text-muted">Alumni JUNIA</div>
        <div class="col-6 col-md-2 grey-logo font-weight-bold fs-4 text-muted">Partner Corp</div>
        <div class="col-6 col-md-2 grey-logo font-weight-bold fs-4 text-muted">Tech Solutions</div>
        <div class="col-6 col-md-2 grey-logo font-weight-bold fs-4 text-muted">Global Industry</div>
    </div>
</div>

<?php include_once 'inc/footer.php'; ?>