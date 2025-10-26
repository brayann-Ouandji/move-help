<?php
$titre_page = "Tableau de bord";
include 'includes/header_client.php';
?>

<h1 class="mb-4">Bienvenue, <?php echo $_SESSION['user_prenom'] . ' ' . $_SESSION['user_nom']; ?> !</h1>

<div class="row">
    <div class="col-md-4">
        <div class="card text-center text-bg-primary mb-3">
            <div class="card-body">
                <h5 class="card-title">Annonces Actives</h5>
                <p class="card-text fs-3 fw-bold">2</p> </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center text-bg-success mb-3">
            <div class="card-body">
                <h5 class="card-title">Propositions Reçues</h5>
                <p class="card-text fs-3 fw-bold">5</p> </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center text-bg-info mb-3">
            <div class="card-body">
                <h5 class="card-title">Missions Terminées</h5>
                <p class="card-text fs-3 fw-bold">1</p> </div>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">
        <h4 class="mb-0">Vos annonces récentes</h4>
    </div>
    <div class="card-body">
        <p class="card-text">Voici un aperçu de vos dernières demandes de déménagement.</p>
        
        <ul class="list-group">
            <li class="list-group-item d-flex justify-content-between align-items-center">
                Déménagement F3 Paris -> Lyon
                <span class="badge bg-warning">En attente (3 propositions)</span>
                <a href="annonce_detail.php?id=1" class="btn btn-sm btn-outline-primary">Voir</a>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                Transport Piano Rouen
                <span class="badge bg-success">Acceptée (1 déménageur)</span>
                <a href="annonce_detail.php?id=2" class="btn btn-sm btn-outline-primary">Voir</a>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                Studio Étudiant
                <span class="badge bg-secondary">Terminée</span>
                <a href="annonce_detail.php?id=3" class="btn btn-sm btn-outline-primary">Voir</a>
            </li>
        </ul>
        
        <div class="text-center mt-3">
            <a href="mes_annonces.php" class="btn btn-primary">Voir toutes mes annonces</a>
            <a href="creer_annonce.php" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Créer une nouvelle annonce
            </a>
        </div>
    </div>
</div>

<?php
include 'includes/footer_client.php';
?>