<?php

$titre_page = "Modifier l'annonce";
include 'includes/header_client.php'; // Inclut check_session.php
require_once __DIR__ . '/../includes/db.php'; // Connexion BDD



//récupérer l'ID client (pour la vérification de sécurité)
$id_utilisateur = $_SESSION['user_id'];
$stmt_client = $mysqli->prepare("SELECT id_client FROM client WHERE id_utilisateur = ?");
$stmt_client->bind_param('i', $id_utilisateur);
$stmt_client->execute();
$id_client_connecte = $stmt_client->get_result()->fetch_assoc()['id_client'];
$stmt_client->close();

// Vérifier que l'ID de l'annonce est présent dans l'URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo '<div class="alert alert-danger">Aucune annonce spécifiée.</div>';
    include 'includes/footer_client.php';
    exit;
}
$id_annonce = (int)$_GET['id'];

//  Récupérer l'annonce ET VÉRIFIER QU'ELLE APPARTIENT AU CLIENT
$sql_annonce = "SELECT * FROM annonce WHERE id_annonce = ? AND id_client = ?";
$stmt_annonce = $mysqli->prepare($sql_annonce);
$stmt_annonce->bind_param('ii', $id_annonce, $id_client_connecte);
$stmt_annonce->execute();
$result_annonce = $stmt_annonce->get_result();

if ($result_annonce->num_rows === 0) {
    echo '<div class="alert alert-danger">Annonce non trouvée ou non autorisée.</div>';
    include 'includes/footer_client.php';
    $mysqli->close();
    exit;
}
$annonce = $result_annonce->fetch_assoc();
$stmt_annonce->close();
$mysqli->close();

// Formater la date et l'heure pour les champs 'date' et 'time'
$date_dem = new DateTime($annonce['date_demenagement']);
$date_pour_input = $date_dem->format('Y-m-d');
$heure_pour_input = $date_dem->format('H:i');
?>

<h1>Modifier l'annonce : <?php echo $annonce['titre']; ?></h1>

<?php
if (isset($_SESSION['error_message'])) {
    echo '<div class="alert alert-danger">' . $_SESSION['error_message'] . '</div>';
    unset($_SESSION['error_message']);
}
?>

<form action="../actions/traitement_modifier_annonce.php" method="POST" class="card">
    <div class="card-body">
        
        <input type="hidden" name="id_annonce" value="<?php echo $annonce['id_annonce']; ?>">

        <fieldset>
            <legend>Informations générales</legend>
            <div class="mb-3">
                <label for="titre" class="form-label">Titre de l'annonce</label>
                <input type="text" class="form-control" id="titre" name="titre" required value="<?php echo $annonce['titre']; ?>">
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"><?php echo $annonce['description']; ?></textarea>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="date_dem" class="form-label">Date du déménagement</label>
                    <input type="date" class="form-control" id="date_dem" name="date_dem" required value="<?php echo $date_pour_input; ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="heure_dem" class="form-label">Heure de début</label>
                    <input type="time" class="form-control" id="heure_dem" name="heure_dem" required value="<?php echo $heure_pour_input; ?>">
                </div>
            </div>
        </fieldset>
        
        <hr>

        <fieldset>
            <legend>Lieu de Départ</legend>
            <div class="row">
                <div class="col-md-8 mb-3">
                    <label for="ville_depart" class="form-label">Ville de départ</label>
                    <input type="text" class="form-control" id="ville_depart" name="ville_depart" required value="<?php echo $annonce['ville_depart']; ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="cp_depart" class="form-label">Code Postal</label>
                    <input type="text" class="form-control" id="cp_depart" name="cp_depart" required value="<?php echo $annonce['code_postal_depart']; ?>">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Type de logement (Départ)</label><br>
                <input type="radio" class="btn-check" name="type_logement_depart" id="maison_depart" value="maison" <?php if($annonce['type_logement_depart'] == 'maison') echo 'checked'; ?>>
                <label class="btn btn-outline-secondary" for="maison_depart">Maison</label>
                
                <input type="radio" class="btn-check" name="type_logement_depart" id="appart_depart" value="appartement" <?php if($annonce['type_logement_depart'] == 'appartement') echo 'checked'; ?>>
                <label class="btn btn-outline-secondary" for="appart_depart">Appartement</label>
            </div>
            <div class="row" id="details_appart_depart">
                <div class="col-md-6 mb-3">
                    <label for="etage_depart" class="form-label">Étage</label>
                    <input type="number" class="form-control" id="etage_depart" name="etage_depart" min="0" value="<?php echo $annonce['etage_depart']; ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Ascenseur ?</label><br>
                    <input type="radio" class="btn-check" name="ascenseur_depart" id="asc_oui_depart" value="1" <?php if($annonce['ascenseur_depart'] == 1) echo 'checked'; ?>>
                    <label class="btn btn-outline-success" for="asc_oui_depart">Oui</label>
                    
                    <input type="radio" class="btn-check" name="ascenseur_depart" id="asc_non_depart" value="0" <?php if($annonce['ascenseur_depart'] == 0) echo 'checked'; ?>>
                    <label class="btn btn-outline-danger" for="asc_non_depart">Non</label>
                </div>
            </div>
        </fieldset>

        <hr>
        
        <fieldset>
            <legend>Détails de la prestation</legend>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="volume" class="form-label">Volume estimé (en m³)</label>
                    <input type="number" class="form-control" id="volume" name="volume" min="1" required value="<?php echo $annonce['volume_m3']; ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="nb_demenageurs" class="form-label">Nombre de déménageurs souhaités</label>
                    <input type="number" class="form-control" id="nb_demenageurs" name="nb_demenageurs" min="1" required value="<?php echo $annonce['nb_demenageur_souhaites']; ?>">
                </div>
            </div>
            </fieldset>

    </div>
    <div class="card-footer text-center">
        <button type="submit" class="btn btn-success btn-lg">
            <i class="bi bi-check-lg"></i> Enregistrer les modifications
        </button>
        <a href="detail_annonce.php?id=<?php echo $id_annonce; ?>" class="btn btn-secondary btn-lg">Annuler</a>
    </div>
</form>

<?php
include 'includes/footer_client.php';
?>