<?php

$titre_page = "Détail de l'annonce";
include 'includes/header_client.php'; 
require_once __DIR__ . '/../includes/db.php'; // Connexion BDD

$id_utilisateur = $_SESSION['user_id'];
$stmt_client = $mysqli->prepare("SELECT id_client FROM CLIENT WHERE id_utilisateur = ?");
$stmt_client->bind_param('i', $id_utilisateur);
$stmt_client->execute();
$result_client = $stmt_client->get_result();
$client_data = $result_client->fetch_assoc();
$id_client_connecte = $client_data['id_client'];
$stmt_client->close();

//  Vérifier que l'ID de l'annonce est présent dans l'URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo '<div class="alert alert-danger">Aucune annonce spécifiée.</div>';
    include 'includes/footer_client.php';
    exit;
}
$id_annonce = (int)$_GET['id'];

$sql_annonce = "SELECT * FROM ANNONCE WHERE id_annonce = ? AND id_client = ?";
$stmt_annonce = $mysqli->prepare($sql_annonce);
$stmt_annonce->bind_param('ii', $id_annonce, $id_client_connecte);
$stmt_annonce->execute();
$result_annonce = $stmt_annonce->get_result();

if ($result_annonce->num_rows === 0) {
    // Soit l'annonce n'existe pas, soit elle n'appartient pas à ce client
    echo '<div class="alert alert-danger">Annonce non trouvée ou non autorisée.</div>';
    include 'includes/footer_client.php';
    $mysqli->close();
    exit;
}
$annonce = $result_annonce->fetch_assoc();
$stmt_annonce->close();
$date_dem = new DateTime($annonce['date_demenagement']);
$date_maintenant = new DateTime();

//  Récupérer les photos de l'annonce
$sql_photos = "SELECT * FROM PHOTO_ANNONCE WHERE id_annonce = ? ORDER BY ordre ASC";
$stmt_photos = $mysqli->prepare($sql_photos);
$stmt_photos->bind_param('i', $id_annonce);
$stmt_photos->execute();
$result_photos = $stmt_photos->get_result();
$photos = $result_photos->fetch_all(MYSQLI_ASSOC);
$stmt_photos->close();

// Récupérer les propositions pour cette annonce
$sql_props = "SELECT p.*, u.nom, u.prenom, u.photo_profil, d.note_moyenne
              FROM PROPOSITION p
              JOIN DEMENAGEUR d ON p.id_demenageur = d.id_demenageur
              JOIN UTILISATEUR u ON d.id_utilisateur = u.id_utilisateur
              WHERE p.id_annonce = ?
              ORDER BY p.date_proposition DESC";
$stmt_props = $mysqli->prepare($sql_props);
$stmt_props->bind_param('i', $id_annonce);
$stmt_props->execute();
$result_props = $stmt_props->get_result();
$propositions = $result_props->fetch_all(MYSQLI_ASSOC);
$stmt_props->close();
$mysqli->close();


// $date_dem = new DateTime($annonce['date_demenagement']);
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
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Détail : <?php echo htmlspecialchars($annonce['titre']); ?></h1>
    <div>
        <?php
        // On affiche les boutons en fonction du statut de l'annonce
        
        if ($annonce['statut'] == 'publiee'):
            // Si l'annonce est 'publiée', on peut la modifier ou la supprimer
        ?>
            <a href="modifier-annonce.php?id=<?php echo $id_annonce; ?>" class="btn btn-outline-primary"><i class="bi bi-pencil"></i> Modifier</a>
            <a href="../actions/traitement_supprimer_annonce.php?id=<?php echo $id_annonce; ?>" class="btn btn-outline-danger" onclick="return confirm('Voulez-vous vraiment supprimer cette annonce ?');">
                <i class="bi bi-trash"></i> Supprimer
            </a>

        <?php 
        elseif ($annonce['statut'] == 'acceptee'): 
            // Si elle est 'acceptée', on vérifie la date
            
            if ($date_dem < $date_maintenant):
                // La date est PASSÉE, on affiche le bouton "Terminer"
        ?>
                <a href="../actions/traitement_annonce_terminee.php?id=<?php echo $id_annonce; ?>" class="btn btn-info" onclick="return confirm('Confirmez-vous que ce déménagement est terminé ?');">
                    <i class="bi bi-check-all"></i> Marquer comme terminée
                </a>
        <?php 
            else:
                // La date est DANS LE FUTUR, on affiche un badge
        ?>
                <span class="badge bg-success fs-6">Déménagement confirmé (à venir)</span>
        <?php
            endif; // Fin de la vérification de la date

        elseif ($annonce['statut'] == 'terminee'): 
            // Si elle est 'terminée', on affiche le bouton pour Évaluer
        ?>
            <a href="evaluation.php?id=<?php echo $id_annonce; ?>" class="btn btn-warning">
                <i class="bi bi-star-fill"></i> Évaluer les déménageurs
            </a>
        <?php endif; // Fin de la vérification du statut ?>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">
        <h5><i class="bi bi-info-circle-fill"></i> Informations sur l'annonce</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-8">
                <p><strong>Date :</strong> <?php echo $date_dem->format('d/m/Y à H:i'); ?></p>
                <p><strong>Description :</strong> <?php echo nl2br($annonce['description']); ?></p>
                <p><strong>Volume :</strong> <?php echo $annonce['volume_m3']; ?> m³</p>
                <p><strong>Déménageurs souhaités :</strong> <?php echo $annonce['nb_demenageur_souhaites']; ?></p>

                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <strong><i class="bi bi-box-arrow-up"></i> Départ :</strong><br>
                        <?php echo $annonce['ville_depart']; ?> (<?php echo $annonce['code_postal_depart']; ?>)<br>
                        Type : <?php echo ucfirst($annonce['type_logement_depart']); ?><br>
                        <?php if($annonce['type_logement_depart'] == 'appartement'): ?>
                            Étage : <?php echo $annonce['etage_depart']; ?>, Ascenseur : <?php echo $annonce['ascenseur_depart'] ? 'Oui' : 'Non'; ?>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6">
                        <strong><i class="bi bi-box-arrow-in-down"></i> Arrivée :</strong><br>
                        <?php echo $annonce['ville_arrivee']; ?> (<?php echo $annonce['code_postal_arrivee']; ?>)<br>
                        Type : <?php echo ucfirst($annonce['type_logement_arrivee']); ?><br>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 border-start">
                <h5><i class="bi bi-images"></i> Photos</h5>
                <?php if (empty($photos)): ?>
                    <p class="text-muted">Aucune photo pour cette annonce.</p>
                <?php else: ?>
                    <div class="row g-2">
                        <?php foreach ($photos as $photo): ?>
                            <div class="col-6">
                                <img src="../<?php echo $photo['chemin_fichier']; ?>" alt="Photo annonce" class="img-fluid rounded">
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<h3><i class="bi bi-cash-coin"></i> Propositions reçues (<?php echo count($propositions); ?>)</h3>

<?php if (empty($propositions)): ?>
    <div class="alert alert-info">Vous n'avez pas encore reçu de proposition pour cette annonce.</div>
<?php else: ?>
    <?php foreach ($propositions as $prop): ?>
        <div class="card mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 text-center">
                        <img src="../image/default_profil.png" class="img-fluid rounded-circle mb-2" style="width: 80px;" alt="profil">
                        <strong><?php echo $prop['prenom'] . ' ' . $prop['nom']; ?></strong><br>
                        <small>Note: <?php echo $prop['note_moyenne'] ?? 'N/A'; ?>/5</small>
                    </div>
                    
                    <div class="col-md-6 border-start">
                        <p><strong>Prix proposé : <span class="text-success h4"><?php echo $prop['prix_propose']; ?> €</span></strong></p>
                        <p class="text-muted">Proposé le <?php echo (new DateTime($prop['date_proposition']))->format('d/m/Y'); ?></p>
                        <?php if (!empty($prop['message'])): ?>
                            <p><strong>Message :</strong> "<?php echo nl2br($prop['message']); ?>"</p>
                        <?php endif; ?>
                    </div>
                    
                    <div class="col-md-3 text-center d-flex flex-column justify-content-center border-start">
                        <?php if ($annonce['statut'] == 'publiee'): // On ne peut accepter que si l'annonce est encore "publiée" ?>
                            <form action="../actions/traitement_reponse_prop.php" method="POST" class="d-grid gap-2">
                                <input type="hidden" name="id_proposition" value="<?php echo $prop['id_proposition']; ?>">
                                <input type="hidden" name="id_annonce" value="<?php echo $id_annonce; ?>">
                                
                                <button type="submit" name="action" value="accepter" class="btn btn-success">
                                    <i class="bi bi-check-lg"></i> Accepter
                                </button>
                                <button type="submit" name="action" value="refuser" class="btn btn-danger">
                                    <i class="bi bi-x-lg"></i> Refuser
                                </button>
                            </form>
                        <?php elseif ($annonce['statut'] == 'acceptee' && $prop['statut'] == 'acceptee'): ?>
                            <div class="alert alert-success">
                                <i class="bi bi-check-circle-fill"></i> <strong>Accepté !</strong><br>
                                Ce déménageur est confirmé.
                            </div>
                        <?php else: // Annonce terminée ou proposition refusée ?>
                            <div class="alert alert-secondary">
                                Proposition <?php echo $prop['statut']; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php
include 'includes/footer_client.php';
?>

