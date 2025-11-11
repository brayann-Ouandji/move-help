<?php

$titre_page = "missions acceptées";
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
?>
<h1>Missions Acceptéess</h1>
<p>Détail de vos prochains déménagements confirmés.</p>
<?php
$sql = "SELECT 
            p.prix_propose,
            a.id_annonce, a.titre, a.date_demenagement, a.statut as statut_annonce,
            a.adress_depart, a.ville_depart, a.code_postal_depart,
            a.adress_arrivee, a.ville_arrivee, a.code_postal_arrivee,
            u.nom as client_nom, u.prenom as client_prenom, u.telephone as client_telephone
        FROM PROPOSITION p
        JOIN ANNONCE a ON p.id_annonce = a.id_annonce
        JOIN CLIENT c ON a.id_client = c.id_client
        JOIN UTILISATEUR u ON c.id_utilisateur = u.id_utilisateur
        WHERE p.id_demenageur = ?
        AND p.statut = 'acceptee'
        ORDER BY a.date_demenagement ASC"; // Trier par la mission la plus proche

$stmt_list = $mysqli->prepare($sql);
$stmt_list->bind_param('i', $id_demenageur_connecte);
$stmt_list->execute();
$result_list = $stmt_list->get_result();

if ($result_list->num_rows > 0) {
    while ($mission = $result_list->fetch_assoc()) {
        
        $date_obj = new DateTime($mission['date_demenagement']);
        $date_formatee = $date_obj->format('d/m/Y à H:i');
        
        // Déterminer le statut (À venir ou Terminée)
        $badge_class = 'bg-success';
        $badge_text = 'À venir';
        if ($date_obj < new DateTime()) {
            $badge_class = 'bg-secondary';
            $badge_text = 'Terminée';
        }
        //On intègre goolge MAPS
        $adresse_depart_complete = ($mission['adress_depart'] ?? '') . ', ' . ($mission['code_postal_depart'] ?? '') . ' ' . ($mission['ville_depart'] ?? '');
        $adresse_arrivee_complete = ($mission['adress_arrivee'] ?? '') . ', ' . ($mission['code_postal_arrivee'] ?? '') . ' ' . ($mission['ville_arrivee'] ?? '');
        //Encoder les adresses pour l'URL (très important)
        $adresse_depart_encoded = urlencode($adresse_depart_complete);
        $adresse_arrivee_encoded = urlencode($adresse_arrivee_complete);
        //Créer le lien final
        // Origine = Ma Position (position GPS du déménageur)
        // Waypoint = Adresse de départ (chez le client)
        // Destination = Adresse d'arrivée
        $google_maps_link = "https://www.google.com/maps/dir/?api=1&origin=Ma+Position&destination=" . $adresse_arrivee_encoded . "&waypoints=" . $adresse_depart_encoded;
?>

<div class="card mb-3">
    <div class="card-header <?php echo $badge_class; ?> text-white d-flex justify-content-between">
        <h5 class="mb-0"><?php echo $mission['titre']; ?></h5>
        <span class="fs-6 fw-bold"><?php echo $badge_text; ?></span>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-8">
                <p><strong>Date et Heure :</strong> <?php echo $date_formatee; ?></p>
                <p><strong>Prix convenu :</strong> <?php echo $mission['prix_propose']; ?> €</p>
                <hr>
                <div class="row">
                    <div class="col-6">
                        <strong><i class="bi bi-box-arrow-up"></i> Départ :</strong><br>
                        <?php echo $adresse_depart_complete; ?>
                    </div>
                    <div class="col-6">
                        <strong><i class="bi bi-box-arrow-in-down"></i> Arrivée :</strong><br>
                        <?php echo $adresse_arrivee_complete; ?>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 border-start">
                <h5>Contact Client</h5>
                <p>
                    <i class="bi bi-person-fill"></i> 
                    <?php echo $mission['client_prenom'] . ' ' . $mission['client_nom']; ?>
                </p>
                <p>
                    <i class="bi bi-telephone-fill"></i> 
                    <a href="tel:<?php echo $mission['client_telephone']; ?>">
                        <?php echo $mission['client_telephone']; ?>
                    </a>
                </D>
                
                <a href="detail_annonce.php?id=<?php echo $mission['id_annonce']; ?>" class="btn btn-primary btn-sm d-block mb-2">Revoir l'annonce complète</a>
                
                <a href="<?php echo $google_maps_link; ?>" target="_blank" class="btn btn-info btn-sm d-block">
                    <i class="bi bi-map-fill"></i> Voir l'itinéraire (Maps)
                </a>
                
            </div>
        </div>
    </div>
</div>

<?php
    }
} else {
    echo '<div class="alert alert-info text-center">Vous n\'avez aucune mission acceptée pour le moment.</div>';
}


$stmt_list->close();
$mysqli->close();
?>
<?php
include 'includes/footer_demenageur.php';
?>