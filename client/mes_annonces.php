<?php
$titre_page = "Mes annonces";
include 'includes/header_client.php';
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Mes Annonces</h1>
    <a href="creer_annonce.php" class="btn btn-success">
        <i class="bi bi-plus-circle"></i> Créer une annonce
    </a>
</div>

<ul class="nav nav-tabs mb-3">
    <li class="nav-item">
        <a class="nav-link active" aria-current="page" href="#">Toutes</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#">En attente</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#">Acceptées</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#">Terminées</a>
    </li>
</ul>

<div class="list-group">
    
    <a href="annonce_detail.php?id=1" class="list-group-item list-group-item-action">
        <div class="d-flex w-100 justify-content-between">
            <h5 class="mb-1">Déménagement F3 Paris -> Lyon</h5>
            <small class="text-muted">Pour le 30/11/2025</small>
        </div>
        <p class="mb-1">Trajet: Paris (75015) → Lyon (69003). Volume: 30m³.</p>
        <small>Statut: <span class="badge bg-warning">En attente</span> | Propositions: <span class="badge bg-primary">3</span></small>
    </a>
    
    <a href="annonce_detail.php?id=2" class="list-group-item list-group-item-action">
        <div class="d-flex w-100 justify-content-between">
            <h5 class="mb-1">Transport Piano Rouen</h5>
            <small class="text-muted">Pour le 02/12/2025</small>
        </div>
        <p class="mb-1">Trajet: Rouen → Rouen. Volume: 3m³.</p>
        <small>Statut: <span class="badge bg-success">Acceptée</span> | Déménageur: <span class="badge bg-primary">1</span></small>
    </a>
    
    <a href="annonce_detail.php?id=3" class="list-group-item list-group-item-action">
        <div class="d-flex w-100 justify-content-between">
            <h5 class="mb-1">Studio Étudiant</h5>
            <small class="text-muted">Passée (20/10/2025)</small>
        </div>
        <p class="mb-1">Trajet: Le Havre → Caen. Volume: 12m³.</p>
        <small>Statut: <span class="badge bg-secondary">Terminée</span></small>
    </a>

</div>

<?php
include 'includes/footer_client.php';
?>