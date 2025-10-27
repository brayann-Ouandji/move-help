<?php
$titre_page = "Tableau de bord";
include 'includes/header_demenageur.php';
?>

<h1 class="mb-4">Bienvenue, <?php echo $_SESSION['user_prenom'] . ' ' . $_SESSION['user_nom']; ?> !</h1>

<div class="row">
    <div class="col-md-4">
        <div class="card text-center text-bg-primary mb-3">
            <div class="card-body">
                <h5 class="card-title">proposition envoyées</h5>
                <p class="card-text fs-3 fw-bold">2</p> </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center text-bg-success mb-3">
            <div class="card-body">
                <h5 class="card-title">missions acceptées</h5>
                <p class="card-text fs-3 fw-bold">5</p> </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center text-bg-info mb-3">
            <div class="card-body">
                <h5 class="card-title">note moyennes</h5>
                <p class="card-text fs-3 fw-bold">6/10</p> </div>
        </div>
    </div>
</div>
    <div class="col-md-4">
        <div class="card text-center text-bg-info mb-3">
            <div class="card-body">
                <h5 class="card-title">Missions a venir</h5>
                <p class="card-text fs-3 fw-bold">1</p> </div>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">
        <h4 class="mb-0">nouveles annonces disponible</h4>
    </div>
    <div class="card-body">
        <p class="card-text">nouvelles annonces</p>
        
        <ul class="list-group">
            <li class="list-group-item d-flex justify-content-between align-items-center">
                Déménagement F3 Paris -> Lyon
                <span class="badge bg-warning">En attente (3 propositions)</span>
                <a href="acceptes.php?id=1" class="btn btn-sm btn-outline-primary">Voir</a>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                Transport Piano Rouen
                <span class="badge bg-success">Acceptée (1 déménageur)</span>
                <a href="acceptes.php?id=2" class="btn btn-sm btn-outline-primary">Voir</a>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                Studio Étudiant
                <span class="badge bg-secondary">Terminée</span>
                <a href="acceptes.php?id=3" class="btn btn-sm btn-outline-primary">Voir</a>
            </li>
        </ul>
        
        

<?php
include 'includes/footer_demenageur.php';
?>