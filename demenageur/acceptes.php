<?php
$titre_page = "missions acceptées";
include 'includes/header_demenageur.php';
?>
<h1>Missions Acceptéess</h1>
<p>Détail de vos prochains déménagements confirmés.</p>
<div class="card mb-3">
    <div class="card-header bg-success text-white">
        <h5 class="mb-0">Transport Piano Rouen</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-8">
                <p><strong>Date et Heure:</strong> 02 Décembre 2025 à 14:00</p>
                <p><strong>Adresses:</strong> 12 Rue du Gros Horloge (Départ) → 55 Rue Jeanne d'Arc (Arrivée), Rouen</p>
                <p><strong>Prix convenu:</strong> 100,00 €</p>
                <p><strong>Autres participants:</strong> Vous êtes le seul déménageur sur cette mission.</p>
            </div>
            <div class="col-md-4 border-start">
                <h5>Contact Client</h5>
                <p>
                    <i class="bi bi-person-fill"></i> Mme SIKADI
                </p>
                <p>
                    <i class="bi bi-telephone-fill"></i> 
                    <a href="tel:0612345678">06 12 34 56 78</a>
                </p>
                <a href="annonce-detail.php?id=2" class="btn btn-primary btn-sm">Revoir l'annonce complète</a>
            </div>
        </div>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header bg-secondary text-white">
        <h5 class="mb-0">Studio Étudiant (Terminée)</h5>
    </div>
    <div class="card-body">
        <p><strong>Date:</strong> 20 Octobre 2025</p>
        <p><strong>Trajet:</strong> Le Havre → Caen</p>
        <p><strong>Statut:</strong> <span class="badge bg-secondary">Terminée</span></p>
        <p>Une évaluation a été laissée (5/5).</p>
    </div>
</div>

<?php
include 'includes/footer_demenageur.php';
?>