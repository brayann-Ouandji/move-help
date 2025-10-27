<?php
$titre_page = "Mes Propositions";
include 'includes/header_demenageur.php';
?>

<h1>Mes Propositions</h1>
<p>Suivez le statut des offres que vous avez envoyées.</p>

<ul class="nav nav-tabs mb-3">
    <li class="nav-item">
        <a class="nav-link active" aria-current="page" href="mes_propositions.php">Toutes</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#">En attente</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="acceptes.php">Acceptées</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#">Refusées</a>
    </li>
</ul>

<div class="list-group">
    
    <a href="detail_annonce.php?id=2" class="list-group-item list-group-item-action">
        <div class="d-flex w-100 justify-content-between">
            <div>
                <h5 class="mb-1">Transport Piano Rouen</h5>
                <small>Annonce pour le 02/12/2025</small>
            </div>
            <span class="badge bg-success fs-6">Acceptée</span>
        </div>
        <p class="mb-1 mt-2">Votre proposition : <strong>80,00 €</strong></p>
    </a>
    
    <a href="detail_annonce.php?id=1" class="list-group-item list-group-item-action">
        <div class="d-flex w-100 justify-content-between">
            <div>
                <h5 class="mb-1">Déménagement F3 Paris -> Lyon</h5>
                <small>Annonce pour le 30/11/2025</small>
            </div>
            <span class="badge bg-warning fs-6">En attente</span>
        </div>
        <p class="mb-1 mt-2">Votre proposition : <strong>450,00 €</strong></p>
    </a>
    
    <a href="detail_annonce.php?id=3" class="list-group-item list-group-item-action">
        <div class="d-flex w-100 justify-content-between">
            <div>
                <h5 class="mb-1">Studio Étudiant</h5>
                <small>Annonce du 20/10/2025</small>
            </div>
            <span class="badge bg-danger fs-6">Refusée</span>
        </div>
        <p class="mb-1 mt-2">Votre proposition : <strong>120,00 €</strong></p>
    </a>

</div>

<?php
include 'includes/footer_demenageur.php';
?>