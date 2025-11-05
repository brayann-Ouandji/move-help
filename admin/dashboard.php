<?php

$titre_page = "Dashboard Admin";
include 'includes/header_admin.php';
?>

<h1 class="mb-4">Dashboard Administrateur</h1>

<div class="row">
    <div class="col-md-4">
        <div class="card text-center text-bg-primary mb-3">
            <div class="card-body">
                <h5 class="card-title">Utilisateurs Inscrits</h5>
                <p class="card-text fs-3 fw-bold">150</p> </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center text-bg-success mb-3">
            <div class="card-body">
                <h5 class="card-title">Annonces Publiées</h5>
                <p class="card-text fs-3 fw-bold">75</p> </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center text-bg-warning mb-3">
            <div class="card-body">
                <h5 class="card-title">Missions Réalisées</h5>
                <p class="card-text fs-3 fw-bold">42</p> </div>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">
        <h4 class="mb-0">Activité récente</h4>
    </div>
    <div class="card-body">
        <ul class="list-group">
            <li class="list-group-item">
                <i class="bi bi-person-plus-fill text-success"></i> 
                Nouveau déménageur : <strong>Irving S.</strong> s'est inscrit.
            </li>
            <li class="list-group-item">
                <i class="bi bi-person-plus-fill text-primary"></i> 
                Nouveau client : <strong>Brayann O.</strong> s'est inscrit.
            </li>
            <li class="list-group-item">
                <i class="bi bi-file-earmark-plus-fill text-info"></i> 
                Nouvelle annonce : "Déménagement T3 Paris -> Lyon" par Brayann O.
            </li>
            <li class="list-group-item">
                <i class="bi bi-exclamation-triangle-fill text-danger"></i> 
                Signalement : Annonce "Transport suspect" (ID: 68).
            </li>
        </ul>
    </div>
</div>

<?php
include 'includes/footer_admin.php';
?>