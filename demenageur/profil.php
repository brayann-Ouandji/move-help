<?php
$titre_page = "Mon Profil Déménageur";
include 'includes/header_demenageur.php'; 
require_once __DIR__ . '/../includes/db.php';


$id_utilisateur_connecte = $_SESSION['user_id'];


$sql = "SELECT 
            u.nom, u.prenom, u.email, u.telephone, u.photo_profil,
            d.ville_de_residence, d.annees_experience, d.vehicule_disponible, d.type_vehicule, d.note_moyenne
        FROM UTILISATEUR u
        JOIN DEMENAGEUR d ON u.id_utilisateur = d.id_utilisateur
        WHERE u.id_utilisateur = ?";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param('i', $id_utilisateur_connecte);
$stmt->execute();
$result = $stmt->get_result();
$dem_info = $result->fetch_assoc();
$stmt->close();
$mysqli->close();


$photo_path = '../image/default_profil.png';
if (!empty($dem_info['photo_profil']) && $dem_info['photo_profil'] != 'default.png') {
    
    $photo_path = '../' . ($dem_info['photo_profil']);
}
?>

<h1>Mon Profil Déménageur</h1>

<?php
if (isset($_SESSION['success_message'])) {
    echo '<div class="alert alert-success">' . ($_SESSION['success_message']) . '</div>';
    unset($_SESSION['success_message']);
}
if (isset($_SESSION['error_message'])) {
    echo '<div class="alert alert-danger">' . ($_SESSION['error_message']) . '</div>';
    unset($_SESSION['error_message']);
}
?>

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
                            <input type="text" class="form-control" id="nom" name="nom" value="<?php echo ($dem_info['nom']); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="prenom" class="form-label">Prénom</label>
                            <input type="text" class="form-control" id="prenom" name="prenom" value="<?php echo ($dem_info['prenom']); ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo ($dem_info['email']); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="telephone" class="form-label">Téléphone</label>
                            <input type="tel" class="form-control" id="telephone" name="telephone" value="<?php echo ($dem_info['telephone']); ?>">
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="ville" class="form-label">Ville de résidence</label>
                            <input type="text" class="form-control" id="ville" name="ville" value="<?php echo ($dem_info['ville_de_residence']); ?>">
                        </div>
                         <div class="col-md-6 mb-3">
                            <label for="experience" class="form-label">Expérience (années)</label>
                            <input type="number" class="form-control" id="experience" name="experience" min="0" value="<?php echo ($dem_info['annees_experience']); ?>">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="vehicule" class="form-label">Véhicule disponible</label>
                        <select class="form-select" id="vehicule" name="vehicule">
                            <option value="non" <?php if(empty($dem_info['vehicule_disponible'])) echo 'selected'; ?>>Non</option>
                            <option value="oui_petit" <?php if($dem_info['type_vehicule'] == 'oui_petit') echo 'selected'; ?>>Oui (Petit utilitaire)</option>
                            <option value="oui_grand" <?php if($dem_info['type_vehicule'] == 'oui_grand') echo 'selected'; ?>>Oui (Grand utilitaire)</option>
                            <option value="oui_camion" <?php if($dem_info['type_vehicule'] == 'oui_camion') echo 'selected'; ?>>Oui (Camion)</option>
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
                    <div class="mb-3">
                        <label for="old_pass" class="form-label">Mot de passe actuel</label>
                        <input type="password" class="form-control" id="old_pass" name="old_pass" required>
                    </div>
                    <div class="mb-3">
                        <label for="new_pass" class="form-label">Nouveau mot de passe</label>
                        <input type="password" class="form-control" id="new_pass" name="new_pass" required>
                    </div>
                    <div class="mb-3">
                        <label for="new_pass_confirm" class="form-label">Confirmer le nouveau mot de passe</label>
                        <input type="password" class="form-control" id="new_pass_confirm" name="new_pass_confirm" required>
                    </div>
                    <button type="submit" class="btn btn-warning">Changer le mot de passe</button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5>Photo de profil</h5>
            </div>
            <div class="card-body text-center">
                <img src="<?php echo $photo_path; ?>" class="img-fluid rounded-circle mb-3" alt="Photo de profil" style="width: 200px; height: 200px; object-fit: cover;">
                
                <form action="../actions/traitement_upload_photo.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="photo_profil" class="form-label">Changer de photo</label>
                        <input class="form-control" type="file" id="photo_profil" name="photo_profil" accept="image/jpeg, image/png">
                    </div>
                    <button type="submit" class="btn btn-primary">Mettre à jour</button>
                </form>
            </div>
        </div>
        <div class="card mt-4 text-center">
             <div class="card-header">
                <h5>Votre note moyenne</h5>
            </div>
            <div class="card-body">
                <h2 class="display-4"><?php echo number_format($dem_info['note_moyenne'], 1); ?> / 5</h2>
                </div>
        </div>
    </div>
</div>

<?php
include 'includes/footer_demenageur.php';
?>