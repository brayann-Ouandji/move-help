<?php
$titre_page = "Trouver des annonces";
include 'includes/header_demenageur.php';
?>

<h1>Annonces disponibles</h1>
<p>Recherchez une mission et faites votre proposition.</p>

<div class="card mb-4">
    <div class="card-body">
        <form class="row g-3">
            <div class="col-md-4">
                <label for="filtre_ville" class="form-label">Ville de départ</label>
                <input type="text" class="form-control" id="filtre_ville" placeholder="Rouen, Paris...">
            </div>
            <div class="col-md-4">
                <label for="filtre_date" class="form-label">Date</label>
                <input type="date" class="form-control" id="filtre_date">
            </div>
            <div class="col-md-2">
                <label for="filtre_volume" class="form-label">Volume (m³)</label>
                <input type="number" class="form-control" id="filtre_volume" min="1">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Filtrer</button>
            </div>
        </form>
    </div>
</div>

<div class="row">

    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Déménagement F3 Paris -> Lyon</h5>
                <h6 class="card-subtitle mb-2 text-muted">30/11/2025</h6>
                <p class="card-text">
                    <strong>Trajet:</strong> Paris (75015) → Lyon (69003)<br>
                    <strong>Volume:</strong> 30m³<br>
                    <strong>Souhaité:</strong> 2 déménageurs
                </p>
                <span class="badge bg-warning">Vous avez déjà proposé</span>
                <a href="annonce-detail.php?id=1" class="btn btn-primary float-end">Voir détails</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Transport Piano Rouen</h5>
                <h6 class="card-subtitle mb-2 text-muted">02/12/2025</h6>
                <p class="card-text">
                    <strong>Trajet:</strong> Rouen → Rouen<br>
                    <strong>Volume:</strong> 3m³<br>
                    <strong>Souhaité:</strong> 1 déménageur
                </p>
                <span class="badge bg-success">Mission acceptée</span>
                <a href="annonce-detail.php?id=2" class="btn btn-primary float-end">Voir détails</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Transport Frigo Américain</h5>
                <h6 class="card-subtitle mb-2 text-muted">28/11/2025</h6>
                <p class="card-text">
                    <strong>Trajet:</strong> Le Havre → Le Havre<br>
                    <strong>Volume:</strong> 2m³<br>
                    <strong>Souhaité:</strong> 1 déménageur
                </p>
                <a href="annonce-detail.php?id=4" class="btn btn-primary float-end">Voir et Proposer</a>
            </div>
        </div>
    </div>

</div>

<?php
include 'includes/footer_demenageur.php';
?>