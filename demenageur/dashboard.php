<?php
$titre_page = "Tableau de bord";
include 'includes/header_demenageur.php';
?>

<h1 class="mb-4">Bienvenue, <?php echo $_SESSION['user_prenom'] . ' ' . $_SESSION['user_nom']; ?> !</h1>

<div class="row">
    <div class="col-md-3">
        <div class="card text-center text-bg-primary mb-3">
            <div class="card-body">
                <h5 class="card-title">Propositions Envoyées</h5>
                <p class="card-text fs-3 fw-bold">8</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center text-bg-warning mb-3">
            <div class="card-body">
                <h5 class="card-title">En Attente</h5>
                <p class="card-text fs-3 fw-bold">5</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center text-bg-success mb-3">
            <div class="card-body">
                <h5 class="card-title">Acceptées</h5>
                <p class="card-text fs-3 fw-bold">2</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center text-bg-info mb-3">
            <div class="card-body">
                <h5 class="card-title">Note Moyenne</h5>
                <p class="card-text fs-3 fw-bold">4.5/5</p>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Mes prochaines missions</h4>
            </div>
            <div class="card-body">
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Déménagement F3 Paris -> Lyon</strong>
                            <br><small class="text-muted">15/11/2025 à 09:00</small>
                        </div>
                        <span class="badge bg-success">350€</span>
                        <a href="annonce_detail.php?id=1" class="btn btn-sm btn-outline-primary">Voir</a>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Studio Marseille</strong>
                            <br><small class="text-muted">20/11/2025 à 14:00</small>
                        </div>
                        <span class="badge bg-success">180€</span>
                        <a href="annonce_detail.php?id=2" class="btn btn-sm btn-outline-primary">Voir</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Nouvelles annonces disponibles</h4>
            </div>
            <div class="card-body">
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Transport Piano Nice</strong>
                            <br><small class="text-muted">22/11/2025 | 3 propositions</small>
                        </div>
                        <a href="annonce_detail.php?id=5" class="btn btn-sm btn-success">Proposer</a>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Maison Bordeaux -> Toulouse</strong>
                            <br><small class="text-muted">25/11/2025 | 1 proposition</small>
                        </div>
                        <a href="annonce_detail.php?id=6" class="btn btn-sm btn-success">Proposer</a>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Appartement T2 Lille</strong>
                            <br><small class="text-muted">28/11/2025 | 0 proposition</small>
                        </div>
                        <a href="annonce_detail.php?id=7" class="btn btn-sm btn-success">Proposer</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer_demenageur.php'; ?>
