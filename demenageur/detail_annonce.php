<?php
$titre_page = "Détail de l'annonce";
include 'includes/header_demenageur.php';
?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="dashboard.php">Accueil</a></li>
        <li class="breadcrumb-item"><a href="annonces.php">Annonces</a></li>
        <li class="breadcrumb-item active">Détail</li>
    </ol>
</nav>

<div class="row">
    <!-- Détails de l'annonce -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-body">
                <h2 class="card-title">Déménagement F3 Paris -> Lyon</h2>
                <p class="text-muted">
                    <i class="bi bi-person"></i> Publié par Jean Dupont | 
                    <span class="badge bg-success">Ouverte</span>
                </p>
                <hr>

                <h5><i class="bi bi-info-circle"></i> Description</h5>
                <p>Bonjour, je recherche des déménageurs pour un déménagement de Paris vers Lyon. 
                   Appartement F3 au 3ème étage sans ascenseur. Environ 25m³ de meubles et cartons.</p>

                <hr>

                <h5><i class="bi bi-calendar-event"></i> Informations du déménagement</h5>
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <strong>Date et heure :</strong><br>
                        15/11/2025 à 09:00
                    </div>
                    <div class="col-md-6 mb-2">
                        <strong>Déménageurs recherchés :</strong><br>
                        2 personne(s)
                    </div>
                    <div class="col-md-6 mb-2">
                        <strong>Volume estimé :</strong><br>
                        25 m³
                    </div>
                </div>

                <hr>

                <h5><i class="bi bi-geo-alt"></i> Lieu de départ</h5>
                <p>
                    <strong>Ville :</strong> Paris (75015)<br>
                    <strong>Type :</strong> Appartement<br>
                    <strong>Étage :</strong> 3ème - ✗ Sans ascenseur
                </p>

                <h5><i class="bi bi-geo-alt-fill"></i> Lieu d'arrivée</h5>
                <p>
                    <strong>Ville :</strong> Lyon (69003)<br>
                    <strong>Type :</strong> Appartement<br>
                    <strong>Étage :</strong> 2ème - ✓ Avec ascenseur
                </p>

                <hr>

                <h5><i class="bi bi-images"></i> Photos</h5>
                <div class="row">
                    <div class="col-md-4 mb-2">
                        <img src="https://via.placeholder.com/300x200" class="img-fluid rounded" alt="Photo 1">
                    </div>
                    <div class="col-md-4 mb-2">
                        <img src="https://via.placeholder.com/300x200" class="img-fluid rounded" alt="Photo 2">
                    </div>
                    <div class="col-md-4 mb-2">
                        <img src="https://via.placeholder.com/300x200" class="img-fluid rounded" alt="Photo 3">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulaire de proposition -->
    <div class="col-lg-4">
        <div class="card sticky-top" style="top: 20px;" id="proposer">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-cash-stack"></i> Faire une proposition</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    <div class="mb-3">
                        <label class="form-label">Votre prix (€) *</label>
                        <input type="number" name="prix" class="form-control form-control-lg" 
                               step="0.01" min="1" placeholder="Ex: 350.00" required>
                        <small class="text-muted">Prix total pour ce déménagement</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Message pour le client (optionnel)</label>
                        <textarea name="message" class="form-control" rows="4" 
                                  placeholder="Présentez-vous, décrivez votre expérience..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-success btn-lg w-100">
                        <i class="bi bi-send"></i> Envoyer la proposition
                    </button>
                </form>

                <hr>

                <a href="question.php?annonce_id=1" class="btn btn-outline-primary w-100">
                    <i class="bi bi-question-circle"></i> Poser une question
                </a>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer_demenageur.php'; ?>