<?php
$titre_page = "Mon Profil";
include 'includes/header_client.php';

// On simule la récupération des infos de l'utilisateur
// (Plus tard, on fera une requête SELECT en BDD)
$client_info = array(
    'nom' => $_SESSION['user_nom'],
    'prenom' => $_SESSION['user_prenom'],
    'email' => 'brayann.ouandji@exemple.com',
    'telephone' => '0601020304',
    'adresse' => '12 rue de la Paix, 75002 Paris'
);
?>

<h1>Mon Profil</h1>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5>Mes informations personnelles</h5>
            </div>
            <div class="card-body">
                <form action="../actions/traitement_profil_client.php" method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nom" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="nom" name="nom" value="<?php echo $client_info['nom']; ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="prenom" class="form-label">Prénom</label>
                            <input type="text" class="form-control" id="prenom" name="prenom" value="<?php echo $client_info['prenom']; ?>">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo $client_info['email']; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="telephone" class="form-label">Téléphone</label>
                        <input type="tel" class="form-control" id="telephone" name="telephone" value="<?php echo $client_info['telephone']; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="adresse" class="form-label">Adresse (optionnel)</label>
                        <input type="text" class="form-control" id="adresse" name="adresse" value="<?php echo $client_info['adresse']; ?>">
                    </div>
                    <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                </form>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header">
                <h5>Changer mon mot de passe</h5>
            </div>
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
                <img src="https://via.placeholder.com/200" class="img-fluid rounded-circle mb-3" alt="Photo de profil">
                <form action="../actions/traitement_upload_photo.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="photo_profil" class="form-label">Changer de photo</label>
                        <input class="form-control" type="file" id="photo_profil" name="photo_profil" accept="image/jpeg, image/png">
                    </div>
                    <button type="submit" class="btn btn-primary">Mettre à jour</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
include 'includes/footer_client.php';
?>