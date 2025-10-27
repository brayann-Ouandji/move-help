<?php
$titre_page = "Mes propositions";
include 'includes/header_demenageur.php';
?>

<h1 class="mb-4">Mes propositions</h1>

<!-- Filtres par statut -->
<div class="card mb-4">
    <div class="card-body">
        <div class="btn-group w-100" role="group">
            <a href="?statut=toutes" class="btn btn-outline-primary active">Toutes (8)</a>
            <a href="?statut=en_attente" class="btn btn-outline-warning">En attente (5)</a>
            <a href="?statut=acceptees" class="btn btn-outline-success">Acceptées (2)</a>
            <a href="?statut=refusees" class="btn btn-outline-danger">Refusées (1)</a>
        </div>
    </div>
</div>

<!-- Liste des propositions -->
<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card h-100 border-success">
            <div class="card-header bg-success text-white">
                <i class="bi bi-check-circle"></i> Acceptée
                <span class="badge bg-danger float-end">Nouveau</span>
            </div>
            <div class="card-body">
                <h5 class="card-title">Déménagement F3 Paris -> Lyon</h5>
                <p class="text-muted small">
                    <i class="bi bi-person"></i> Client: Jean Dupont<br>
                    <i class="bi bi-calendar"></i> 15/11/2025 à 09:00<br>
                    <i class="bi bi-geo-alt"></i> Paris → Lyon
                </p>
                <hr>
                <h4 class="text-success">350.00 €</h4>
                <p class="small text-muted">Proposé le 05/11/2025</p>
            </div>
            <div class="card-footer bg-transparent">
                <a href="annonce_detail.php?id=1" class="btn btn-primary btn-sm w-100">Voir l'annonce</a>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card h-100 border-warning">
            <div class="card-header bg-warning">
                <i class="bi bi-clock"></i> En attente
            </div>
            <div class="card-body">
                <h5 class="card-title">Transport Piano Nice</h5>
                <p class="text-muted small">
                    <i class="bi bi-person"></i> Client: Marie Martin<br>
                    <i class="bi bi-calendar"></i> 22/11/2025 à 14:00<br>
                    <i class="bi bi-geo-alt"></i> Nice → Nice
                </p>
                <hr>
                <h4 class="text-success">150.00 €</h4>
                <p class="small text-muted">Proposé le 07/11/2025</p>
            </div>
            <div class="card-footer bg-transparent">
                <a href="annonce_detail.php?id=2" class="btn btn-primary btn-sm w-100">Voir l'annonce</a>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card h-100 border-success">
            <div class="card-header bg-success text-white">
                <i class="bi bi-check-circle"></i> Acceptée
            </div>
            <div class="card-body">
                <h5 class="card-title">Studio Marseille</h5>
                <p class="text-muted small">
                    <i class="bi bi-person"></i> Client: Sophie Dubois<br>
                    <i class="bi bi-calendar"></i> 20/11/2025 à 14:00<br>
                    <i class="bi bi-geo-alt"></i> Marseille → Aix-en-Provence
                </p>
                <hr>
                <h4 class="text-success">180.00 €</h4>
                <p class="small text-muted">Proposé le 06/11/2025</p>
            </div>
            <div class="card-footer bg-transparent">
                <a href="annonce_detail.php?id=3" class="btn btn-primary btn-sm w-100">Voir l'annonce</a>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer_demenageur.php'; ?>
