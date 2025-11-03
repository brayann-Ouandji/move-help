<?php
$titre_page = "Tableau de bord";
include 'includes/header_client.php';
require_once __DIR__ . '/../includes/database.php';
// On récupère l'ID du client connecté (stocké dans la session lors de la connexion)
// Note: $user_id_connecte est déjà défini dans check_session.php, mais on
// va chercher l'ID CLIENT spécifique (qui est différent de l'ID UTILISATEUR)
$id_utilisateur=$SESSION['user_id'];
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
                <h5 class="card-title">Missions Terminées</h5>
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
            <li class="list-group-item d-flex justify-content-between align-items-center">
                Déménagement F3 Paris -> Lyon
                <span class="badge bg-warning">En attente (3 propositions)</span>
                <a href="annonce_detail.php?id=1" class="btn btn-sm btn-outline-primary">Voir</a>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                Transport Piano Rouen
                <span class="badge bg-success">Acceptée (1 déménageur)</span>
                <a href="annonce_detail.php?id=2" class="btn btn-sm btn-outline-primary">Voir</a>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                Studio Étudiant
                <span class="badge bg-secondary">Terminée</span>
                <a href="annonce_detail.php?id=3" class="btn btn-sm btn-outline-primary">Voir</a>
            </li>
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