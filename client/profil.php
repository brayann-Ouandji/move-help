<?php
$titre_page = "Mon Profil";
include 'includes/header_client.php';
require_once __DIR__ . '/../includes/db.php';

$id_utilisateur_connecte = $_SESSION['user_id'];

// On fait une jointure pour récupérer les infos des deux tables
$sql = "SELECT u.nom, u.prenom, u.email, u.telephone, u.photo_profil, c.adress, c.code_postal, c.ville
        FROM UTILISATEUR u
        LEFT JOIN CLIENT c ON u.id_utilisateur = c.id_utilisateur
        WHERE u.id_utilisateur = ?";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param('i', $id_utilisateur_connecte);
$stmt->execute();
$result = $stmt->get_result();
$client_info = $result->fetch_assoc();
$stmt->close();
$mysqli->close(); // On ferme la connexion, on n'en a plus besoin pour l'affichage

// Définir le chemin de la photo de profil
$photo_path = '../image/default_profil.png'; // Image par défaut
if (!empty($client_info['photo_profil']) && $client_info['photo_profil'] != 'default_profil.png') {
    
    $photo_path = '../uploads/profil/' . $client_info['photo_profil'];
}

?>

<h1>Mon Profil</h1>

<?php
if (isset($_SESSION['success_message'])) {
    echo '<div class="alert alert-success">' . $_SESSION['success_message'] . '</div>';
    unset($_SESSION['success_message']);
}
if (isset($_SESSION['error_message'])) {
    echo '<div class="alert alert-danger">' . $_SESSION['error_message'] . '</div>';
    unset($_SESSION['error_message']);
}
?>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5>Mes informations personnelles</h5>
            </div>
            <div class="card-body">
                <form action="../actions/traitement_profil_client.php" method="POST"> //fichier traitement_profil_client.phpà créer
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
                        <label for="adresse" class="form-label">Adresse</label>
                        <input type="text" class="form-control" id="adresse" name="adresse" value="<?php echo $client_info['adress'] ?? ''; ?>" placeholder="39 rue Léon gambetta">
                    </div>
                     <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="ville" class="form-label">Ville</label>
                            <input type="text" class="form-control" id="ville" name="ville" value="<?php echo $client_info['ville'] ?? ''; ?>" placeholder="Saint-etienne-du Rouvray">
                        </div>
                         <div class="col-md-4 mb-3">
                            <label for="code_postal" class="form-label">Code Postal</label>
                            <input type="text" class="form-control" id="code_postal" name="code_postal" value="<?php echo $client_info['code_postal'] ?? ''; ?>" placeholder="76800">
                        </div>
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
                <form action="../actions/traitement_change_mdp.php" method="POST">//fiçchier traitement_change_mdp.php à créer
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
                
                <form action="../actions/traitement_upload_photo.php" method="POST" enctype="multipart/form-data">//fichier traitement_upload_photo.php à créer
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