<?php
$titre_page = "Mon Profil Déménageur";
include 'includes/header_demenageur.php';

// On simule la récupération des infos
$dem_info = array(
    'nom' => $_SESSION['user_nom'],
    'prenom' => $_SESSION['user_prenom'],
    'email' => 'irving.sikadi@exemple.com',
    'telephone' => '0780206010',
    'ville' => 'Rouen',
    'experience' => 3,
    'vehicule' => 'oui_utilitaire'
);
?>

<h1>Mon Profil Déménageur</h1>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5>Mes informations</h5>
            </div>
            <div class="card-body">
                <form action="../actions/traitement_profil_dem.php" method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nom" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="nom" name="nom" value="<?php echo $dem_info['nom']; ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="prenom" class="form-label">Prénom</label>
                            <input type="text" class="form-control" id="prenom" name="prenom" value="<?php echo $dem_info['prenom']; ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo $dem_info['email']; ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="telephone" class="form-label">Téléphone</label>
                            <input type="tel" class="form-control" id="telephone" name="telephone" value="<?php echo $dem_info['telephone']; ?>">
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="ville" class="form-label">Ville de résidence</label>
                            <input type="text" class="form-control" id="ville" name="ville" value="<?php echo htmlspecialchars($dem_info['ville']); ?>">
                        </div>
                         <div class="col-md-6 mb-3">
                            <label for="experience" class="form-label">Expérience (années)</label>
                            <input type="number" class="form-control" id="experience" name="experience" min="0" value="<?php echo htmlspecialchars($dem_info['experience']); ?>">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="vehicule" class="form-label">Véhicule disponible</label>
                        <select class="form-select" id="vehicule" name="vehicule">
                            <option value="non" <?php if($dem_info['vehicule'] == 'non') echo 'selected'; ?>>Non</option>
                            <option value="oui_petit" <?php if($dem_info['vehicule'] == 'oui_petit') echo 'selected'; ?>>Oui (Petit utilitaire)</option>
                            <option value="oui_grand" <?php if($dem_info['vehicule'] == 'oui_grand') echo 'selected'; ?>>Oui (Grand utilitaire)</option>
                            <option value="oui_camion" <?php if($dem_info['vehicule'] == 'oui_camion') echo 'selected'; ?>>Oui (Camion)</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                </form>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header"><h5>Changer mon mot de passe</h5></div>
            <div class="card-body">
                <form action="../actions/traitement_change_mdp.php" method="POST">
                    <button type="submit" class="btn btn-warning">Changer le mot de passe</button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        </div>
</div>

<?php
include 'includes/footer_demenageur.php';
?>