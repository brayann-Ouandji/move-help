<?php
$titre_page = "Détail de l'annonce";
include 'includes/header_demenageur.php';
require_once __DIR__ . '/../includes/db.php';


$id_utilisateur = $_SESSION['user_id'];
$stmt_dem = $mysqli->prepare("SELECT id_demenageur FROM DEMENAGEUR WHERE id_utilisateur = ?");
$stmt_dem->bind_param('i', $id_utilisateur);
$stmt_dem->execute();
$result_dem = $stmt_dem->get_result();
$dem_data = $result_dem->fetch_assoc();
$id_demenageur_connecte = $dem_data['id_demenageur'];
$stmt_dem->close();


if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo '<div class="alert alert-danger">Aucune annonce spécifiée.</div>';
    include 'includes/footer_demenageur.php';
    exit;
}
$id_annonce = (int)$_GET['id'];


$sql_annonce = "SELECT * FROM ANNONCE WHERE id_annonce = ? AND statut = 'publiee'";
$stmt_annonce = $mysqli->prepare($sql_annonce);
$stmt_annonce->bind_param('i', $id_annonce);
$stmt_annonce->execute();
$result_annonce = $stmt_annonce->get_result();

if ($result_annonce->num_rows === 0) {
    echo '<div class="alert alert-danger">Annonce non trouvée ou n\'est plus disponible.</div>';
    include 'includes/footer_demenageur.php';
    $mysqli->close();
    exit;
}
$annonce = $result_annonce->fetch_assoc();
$stmt_annonce->close();


$sql_photos = "SELECT * FROM PHOTO_ANNONCE WHERE id_annonce = ? ORDER BY ordre ASC";
$stmt_photos = $mysqli->prepare($sql_photos);
$stmt_photos->bind_param('i', $id_annonce);
$stmt_photos->execute();
$result_photos = $stmt_photos->get_result();
$photos = $result_photos->fetch_all(MYSQLI_ASSOC);
$stmt_photos->close();


$sql_prop = "SELECT * FROM PROPOSITION WHERE id_annonce = ? AND id_demenageur = ?";
$stmt_prop = $mysqli->prepare($sql_prop);
$stmt_prop->bind_param('ii', $id_annonce, $id_demenageur_connecte);
$stmt_prop->execute();
$result_prop = $stmt_prop->get_result();
$proposition_existante = $result_prop->fetch_assoc(); // Sera NULL ou contiendra la proposition
$stmt_prop->close();
$mysqli->close();


$date_dem = new DateTime($annonce['date_demenagement']);
?>

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

<h1>Détail de l'annonce</h1>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3><?php echo $annonce['titre']; ?></h3>
            </div>
            <div class="card-body">
                <p><strong>Date :</strong> <?php echo $date_dem->format('d/m/Y à H:i'); ?></p>
                <p><strong>Volume :</strong> <?php echo $annonce['volume_m3']; ?> m³</p>
                <p><strong>Déménageurs souhaités :</strong> <?php echo $annonce['nb_demenageur_souhaites']; ?></p>
                <p><strong>Description :</strong><br> <?php echo nl2br($annonce['description']); ?></p>
                
                <hr>
                
                <div class="row">
                    <div class="col-md-6">
                        <h5>Lieu de Départ</h5>
                        <p><i class="bi bi-geo-alt-fill"></i> <?php echo $annonce['ville_depart']; ?> (<?php echo $annonce['code_postal_depart']; ?>)</p>
                        <p><i class="bi bi-building"></i> <?php echo ucfirst($annonce['type_logement_depart']); ?></p>
                        <?php if($annonce['type_logement_depart'] == 'appartement'): ?>
                            <p>Étage : <?php echo $annonce['etage_depart']; ?>, Ascenseur : <?php echo $annonce['ascenseur_depart'] ? 'Oui' : 'Non'; ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6">
                        <h5>Lieu d'Arrivée</h5>
                        <p><i class="bi bi-geo-alt-fill"></i> <?php echo $annonce['ville_arrivee']; ?> (<?php echo $annonce['code_postal_arrivee']; ?>)</p>
                        <p><i class="bi bi-building"></i> <?php echo ucfirst($annonce['type_logement_arrivee']); ?></p>
                    </div>
                </div>
                
                <hr>
                
                <h5>Photos</h5>
                <?php if (empty($photos)): ?>
                    <p class="text-muted">Aucune photo pour cette annonce.</p>
                <?php else: ?>
                    <div class="row g-2">
                        <?php foreach ($photos as $photo): ?>
                            <div class="col-3">
                                <img src="../<?php echo $photo['chemin_fichier']; ?>" alt="Photo annonce" class="img-fluid rounded">
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        
        <?php if ($proposition_existante): // L'utilisateur a déjà proposé ?>
        
            <div class="card">
                <div class="card-header bg-warning">
                    <h4>Votre proposition</h4>
                </div>
                <div class="card-body">
                    <p>Vous avez déjà fait une proposition pour cette annonce :</p>
                    <p><strong>Votre prix : <span class="h4"><?php echo $proposition_existante['prix_propose']; ?> €</span></strong></p>
                    <p><strong>Statut : 
                        <?php if ($proposition_existante['statut'] == 'en_attente'): ?>
                            <span class="badge bg-warning">En attente</span>
                        <?php elseif ($proposition_existante['statut'] == 'acceptee'): ?>
                            <span class="badge bg-success">Acceptée</span>
                        <?php else: ?>
                            <span class="badge bg-danger">Refusée</span>
                        <?php endif; ?>
                    </strong></p>
                    <a href="mes-propositions.php" class="btn btn-primary w-100">Voir toutes mes propositions</a>
                </div>
            </div>

        <?php else: // L'utilisateur N'A PAS encore proposé ?>

            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4>Faire une proposition</h4>
                </div>
                <div class="card-body">
                    <form action="../actions/traitement_prop_demenageur.php" method="POST">
                        <input type="hidden" name="id_annonce" value="<?php echo $id_annonce; ?>">
                        <input type="hidden" name="id_demenageur" value="<?php echo $id_demenageur_connecte; ?>">
                        
                        <div class="mb-3">
                            <label for="prix" class="form-label">Votre prix (€)</label>
                            <input type="number" class="form-control" id="prix" name="prix" min="1" step="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Message au client (optionnel)</label>
                            <textarea class="form-control" id="message" name="message" rows="4" placeholder="Bonjour, je suis disponible et équipé..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-send-fill"></i> Envoyer ma proposition
                        </button>
                    </form>
                </div>
            </div>

        <?php endif; ?>
        
    </div>
</div>

<?php
include 'includes/footer_demenageur.php';
?>