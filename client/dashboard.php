<?php

$titre_page = "Tableau de bord";
include 'includes/header_client.php';
require_once __DIR__ . '/../includes/db.php';


$id_utilisateur=$_SESSION['user_id'];
$sql_client = "SELECT id_client FROM CLIENT WHERE id_utilisateur = ?";
$stmt_client = $mysqli->prepare($sql_client);
$stmt_client->bind_param('i', $id_utilisateur);
$stmt_client->execute();
$result_client = $stmt_client->get_result();
$client_data = $result_client->fetch_assoc();
$id_client_connecte = $client_data['id_client'];

// Annonces Actives (statut 'publiee' ou 'acceptee')
$sql_actives = "SELECT COUNT(*) as total FROM ANNONCE WHERE id_client = ? AND statut IN ('publiee', 'acceptee')";
$stmt_actives = $mysqli->prepare($sql_actives);
$stmt_actives->bind_param('i', $id_client_connecte);
$stmt_actives->execute();
$count_actives = $stmt_actives->get_result()->fetch_assoc()['total'];

//Propositions reçues (sur les annonces 'publiees' du client)
$sql_props = "SELECT COUNT(p.id_proposition) as total 
              FROM PROPOSITION p
              JOIN ANNONCE a ON p.id_annonce = a.id_annonce
              WHERE a.id_client = ? AND a.statut = 'publiee'";
$stmt_props = $mysqli->prepare($sql_props);
$stmt_props->bind_param('i', $id_client_connecte);
$stmt_props->execute();
$count_props = $stmt_props->get_result()->fetch_assoc()['total'];

//Missions Terminées
$sql_terminees = "SELECT COUNT(*) as total FROM ANNONCE WHERE id_client = ? AND statut = 'terminee'";
$stmt_terminees = $mysqli->prepare($sql_terminees);
$stmt_terminees->bind_param('i', $id_client_connecte);
$stmt_terminees->execute();
$count_terminees = $stmt_terminees->get_result()->fetch_assoc()['total'];


?>

<h1 class="mb-4">Bienvenue, <?php echo $_SESSION['user_prenom'] . ' ' . $_SESSION['user_nom']; ?> !</h1>

<div class="row">
    <div class="col-md-4">
        <div class="card text-center text-bg-primary mb-3">
            <div class="card-body">
                <h5 class="card-title">Annonces Actives</h5>
                <p class="card-text fs-3 fw-bold"><?php echo $count_actives;?></p> </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center text-bg-success mb-3">
            <div class="card-body">
                <h5 class="card-title">Propositions Reçues</h5>
                <p class="card-text fs-3 fw-bold"><?php echo $count_props;?></p> </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center text-bg-info mb-3">
            <div class="card-body">
                <h5 class="card-title">Déménagements Terminées</h5>
                <p class="card-text fs-3 fw-bold"><?php echo $count_terminees;?></p> </div>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">
        <h4 class="mb-0">Vos annonces récentes</h4>
    </div>
    <div class="card-body">
        <p class="card-text">Voici un aperçu de vos dernières demandes de déménagement.</p>
        
        <ul class="list-group">
          <?php 
                      // (On récupère les 5 dernières annonces, tous statuts confondus)
                      $sql_list = "SELECT id_annonce, titre, statut, 
                         (SELECT COUNT(*) FROM PROPOSITION p WHERE p.id_annonce = a.id_annonce) as nb_props
                         FROM ANNONCE a
                         WHERE id_client = ?
                         ORDER BY date_creation DESC
                         LIMIT 5";
            
            $stmt_list = $mysqli->prepare($sql_list);
            $stmt_list->bind_param('i', $id_client_connecte);
            $stmt_list->execute();
            $result_list = $stmt_list->get_result();

            if ($result_list->num_rows > 0) {
                while ($annonce = $result_list->fetch_assoc()) {
                    
                    // Définir le style du badge en fonction du statut
                    $badge_class = 'bg-secondary';
                    $badge_text = ucfirst($annonce['statut']); // Met la première lettre en majuscule

                    if ($annonce['statut'] == 'publiee') {
                        $badge_class = 'bg-warning';
                        $badge_text = 'En attente (' . $annonce['nb_props'] . ' prop.)';
                    } elseif ($annonce['statut'] == 'acceptee') {
                        $badge_class = 'bg-success';
                        $badge_text = 'Acceptée';
                    } elseif ($annonce['statut'] == 'terminee') {
                        $badge_class = 'bg-secondary';
                        $badge_text = 'Terminée';
                    }

                    // Afficher l'élément de liste
                    echo '<li class="list-group-item d-flex justify-content-between align-items-center">';
                    echo htmlspecialchars($annonce['titre']);
                    echo '<span class="badge ' . $badge_class . '">' . $badge_text . '</span>';
                    
                    // On crée un lien vers une page qui n'existe pas encore
                    echo '<a href="detail_annonce.php?id=' . $annonce['id_annonce'] . '" class="btn btn-sm btn-outline-primary">Voir</a>';
                    
                    // Pour l'instant, on lie vers mes-annonces.php
                   // echo '<a href="mes-annonces.php" class="btn btn-sm btn-outline-primary">Gérer</a>';
                    
                    echo '</li>';
                }
            } else {
                echo '<li class="list-group-item text-muted">Vous n\'avez pas encore créé d\'annonce.</li>';
            }
            
            // Fermer les statements et la connexion
            $stmt_actives->close();
            $stmt_props->close();
            $stmt_terminees->close();
            $stmt_list->close();
            $mysqli->close();
            ?>

        </ul>
        
        <div class="text-center mt-3">
            <a href="mes_annonces.php" class="btn btn-primary">Voir toutes mes annonces</a>
            <a href="creer_annonce.php" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Créer une nouvelle annonce
            </a>
        </div>
    </div>
</div>

<?php
include 'includes/footer_client.php';
?>