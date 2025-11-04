<?php
$titre_page = "Mes annonces";
include 'includes/header_client.php';
require_once __DIR__ . '/../includes/db.php';

if (isset($_SESSION['success_message'])) {
    echo '<div class="alert alert-success" role="alert">' . $_SESSION['success_message'] . '</div>';
    unset($_SESSION['success_message']);
}
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Mes Annonces</h1>
    <a href="creer_annonce.php" class="btn btn-success">
        <i class="bi bi-plus-circle"></i> Créer une annonce
    </a>
</div>

<div class="list-group">

    <?php
    // Trouver l'id_client à partir de l'id_utilisateur (stocké dans la session)
    $id_utilisateur = $_SESSION['user_id'];
    $sql_client = "SELECT id_client FROM CLIENT WHERE id_utilisateur = ?";
    $stmt_client = $mysqli->prepare($sql_client);
    $stmt_client->bind_param('i', $id_utilisateur);
    $stmt_client->execute();
    $result_client = $stmt_client->get_result();
    $client_data = $result_client->fetch_assoc();
    $id_client_connecte = $client_data['id_client'];
    $stmt_client->close();
    
    //  REQUÊTE POUR LA LISTE COMPLÈTE DES ANNONCES
    $sql_list = "SELECT 
                    id_annonce, 
                    titre, 
                    statut, 
                    date_demenagement, 
                    ville_depart, 
                    ville_arrivee, 
                    volume_m3,
                    (SELECT COUNT(*) FROM PROPOSITION p WHERE p.id_annonce = a.id_annonce) as nb_props
                 FROM ANNONCE a
                 WHERE id_client = ?
                 ORDER BY a.date_creation DESC";
    
    $stmt_list = $mysqli->prepare($sql_list);
    $stmt_list->bind_param('i', $id_client_connecte);
    $stmt_list->execute();
    $result_list = $stmt_list->get_result();

    if ($result_list->num_rows > 0) {
        
        while ($annonce = $result_list->fetch_assoc()) {
            
            // Formatage de la date
            $date_obj = new DateTime($annonce['date_demenagement']);
            $date_formatee = $date_obj->format('d/m/Y');

            // Définir le style du badge en fonction du statut
            $badge_class = 'bg-secondary';
            $badge_text = ucfirst($annonce['statut']);

            if ($annonce['statut'] == 'publiee') {
                $badge_class = 'bg-warning';
                $badge_text = 'En attente';
            } elseif ($annonce['statut'] == 'acceptee') {
                $badge_class = 'bg-success';
                $badge_text = 'Acceptée';
            } elseif ($annonce['statut'] == 'terminee') {
                $badge_class = 'bg-secondary';
                $badge_text = 'Terminée';
            }

            // Afficher l'élément de liste (maintenant cliquable vers la page de détail)
            // Nous créons le lien vers 'detail_annoncephp' 
            echo '<a href="detail_annonce.php?id=' . $annonce['id_annonce'] . '" class="list-group-item list-group-item-action">';
            echo '  <div class="d-flex w-100 justify-content-between">';
            echo '    <h5 class="mb-1">' . $annonce['titre'] . '</h5>';
            echo '    <small class="text-muted">Pour le ' . $date_formatee . '</small>';
            echo '  </div>';
            echo '  <p class="mb-1">Trajet: ' . $annonce['ville_depart'] . ' → ' . $annonce['ville_arrivee'] . '. Volume: ' . $annonce['volume_m3'] . 'm³.</p>';
            echo '  <small>Statut: <span class="badge ' . $badge_class . '">' . $badge_text . '</span>';
            
            if ($annonce['statut'] == 'publiee') {
                echo ' | Propositions: <span class="badge bg-primary">' . $annonce['nb_props'] . '</span>';
            }
            
            echo '  </small>';
            echo '</a>';
        }
    } else {
        // Aucune annonce trouvée
        echo '<div class="list-group-item text-muted text-center">Vous n\'avez pas encore créé d\'annonce.</div>';
    }
    
    // Fermer les ressources
    $stmt_list->close();
    $mysqli->close();
    ?>
    
</div>

<?php
include 'includes/footer_client.php';
?>