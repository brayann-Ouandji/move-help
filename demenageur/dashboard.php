<?php
$titre_page = "Tableau de bord";
include 'includes/header_demenageur.php';
require_once __DIR__ . '/../includes/db.php';

$id_utilisateur = $_SESSION['user_id'];
$stmt_dem = $mysqli->prepare("SELECT id_demenageur, note_moyenne FROM DEMENAGEUR WHERE id_utilisateur = ?");
$stmt_dem->bind_param('i', $id_utilisateur);
$stmt_dem->execute();
$result_dem = $stmt_dem->get_result();
$dem_data = $result_dem->fetch_assoc();
$id_demenageur_connecte = $dem_data['id_demenageur'];
$note_moyenne = $dem_data['note_moyenne'];
$stmt_dem->close();

// Propositions envoyées
$sql_envoyees = "SELECT COUNT(*) as total FROM PROPOSITION WHERE id_demenageur = ?";
$stmt_envoyees = $mysqli->prepare($sql_envoyees);
$stmt_envoyees->bind_param('i', $id_demenageur_connecte);
$stmt_envoyees->execute();
$count_envoyees = $stmt_envoyees->get_result()->fetch_assoc()['total'];
$stmt_envoyees->close();

// Missions acceptées
$sql_acceptees = "SELECT COUNT(*) as total FROM PROPOSITION WHERE id_demenageur = ? AND statut = 'acceptee'";
$stmt_acceptees = $mysqli->prepare($sql_acceptees);
$stmt_acceptees->bind_param('i', $id_demenageur_connecte);
$stmt_acceptees->execute();
$count_acceptees = $stmt_acceptees->get_result()->fetch_assoc()['total'];
$stmt_acceptees->close();
//missions à venir (celles qui sont acceptées ET dans le futur)
$sql_avenir = "SELECT COUNT(p.id_proposition) as total 
               FROM PROPOSITION p
               JOIN ANNONCE a ON p.id_annonce = a.id_annonce
               WHERE p.id_demenageur = ? 
               AND p.statut = 'acceptee' 
               AND a.date_demenagement > NOW()"; // La condition "dans le futur", on verifie que la dtae est plus taradL.

$stmt_avenir = $mysqli->prepare($sql_avenir);
$stmt_avenir->bind_param('i', $id_demenageur_connecte);
$stmt_avenir->execute();
$count_avenir = $stmt_avenir->get_result()->fetch_assoc()['total'];
$stmt_avenir->close();
?>
?>

<h1 class="mb-4">Bienvenue, <?php echo $_SESSION['user_prenom'] . ' ' . $_SESSION['user_nom']; ?> !</h1>

<div class="row">
    <div class="col-md-4">
        <div class="card text-center text-bg-primary mb-3">
            <div class="card-body">
                <h5 class="card-title">proposition envoyées</h5>
                <p class="card-text fs-3 fw-bold"><?php echo $count_envoyees;?></p> </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center text-bg-success mb-3">
            <div class="card-body">
                <h5 class="card-title">missions acceptées</h5>
                <p class="card-text fs-3 fw-bold"><?php echo $count_acceptees;?></p> </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center text-bg-info mb-3">
            <div class="card-body">
                <h5 class="card-title">note moyennes</h5>
                <p class="card-text fs-3 fw-bold"><?php echo number_format($note_moyenne, 1);?></p> </div>
        </div>
    </div>
</div>
    <div class="col-md-4">
        <div class="card text-center text-bg-info mb-3">
            <div class="card-body">
                <h5 class="card-title">Missions a venir</h5>
                <p class="card-text fs-3 fw-bold"><?php echo $count_avenir;?></p> </div>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">
        <h4 class="mb-0">Nouvelles annonces pour vous</h4>
    </div>
    <div class="card-body">
        <p>Voici les dernières annonces "publiées" auxquelles vous n'avez pas encore postulé.</p>
        
        <div class="list-group">
            <?php
            
            // On cherche les annonces publiées
            // où le déménageur n'a PAS fait de proposition
            $sql_list = "SELECT a.id_annonce, a.titre, a.date_demenagement, a.volume_m3
                         FROM ANNONCE a
                         WHERE a.statut = 'publiee'
                         AND a.id_annonce NOT IN (
                             SELECT id_annonce FROM PROPOSITION WHERE id_demenageur = ?
                         )
                         ORDER BY a.date_creation DESC
                         LIMIT 3";
            
            $stmt_list = $mysqli->prepare($sql_list);
            $stmt_list->bind_param('i', $id_demenageur_connecte);
            $stmt_list->execute();
            $result_list = $stmt_list->get_result();

            if ($result_list->num_rows > 0) {
                while ($annonce = $result_list->fetch_assoc()) {
                    $date_obj = new DateTime($annonce['date_demenagement']);
                    $date_formatee = $date_obj->format('d/m/Y');
            ?>
                <a href="detail_annonce.php?id=<?php echo $annonce['id_annonce']; ?>" class="list-group-item list-group-item-action">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1"><?php echo $annonce['titre']; ?></h5>
                        <small class="text-muted">Pour le <?php echo $date_formatee; ?></small>
                    </div>
                    <p class="mb-1">Volume estimé: <?php echo $annonce['volume_m3']; ?>m³.</p>
                    <small class="text-success fw-bold">Nouveau</small>
                </a>
            <?php
                }
            } else {
                echo '<div class="list-group-item text-muted">Aucune nouvelle annonce disponible pour le moment.</div>';
            }
            $stmt_list->close();
            ?>
        </div>
        <div class="text-center mt-3">
            <a href="annonces.php" class="btn btn-primary">Voir toutes les annonces disponibles</a>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">
        <h4 class="mb-0">Vos prochaines missions</h4>
    </div>
    <div class="card-body">
        <ul class="list-group">
            <?php
            // 4. --- REQUÊTE POUR LES MISSIONS À VENIR ---
            $sql_missions = "SELECT a.id_annonce, a.titre, a.date_demenagement
                             FROM PROPOSITION p
                             JOIN ANNONCE a ON p.id_annonce = a.id_annonce
                             WHERE p.id_demenageur = ?
                             AND p.statut = 'acceptee'
                             AND a.date_demenagement > NOW()
                             ORDER BY a.date_demenagement ASC";
            
            $stmt_missions = $mysqli->prepare($sql_missions);
            $stmt_missions->bind_param('i', $id_demenageur_connecte);
            $stmt_missions->execute();
            $result_missions = $stmt_missions->get_result();
            
            if ($result_missions->num_rows > 0) {
                while ($mission = $result_missions->fetch_assoc()) {
                    $date_obj = new DateTime($mission['date_demenagement']);
            ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <?php echo $mission['titre']; ?>
                    <span class="badge bg-success">Le <?php echo $date_obj->format('d/m/Y à H:i'); ?></span>
                    <a href="acceptes.php" class="btn btn-sm btn-outline-primary">Voir détails</a>
                </li>
            <?php
                }
            } else {
                echo '<li class="list-group-item text-muted">Aucune mission à venir.</li>';
            }
            $stmt_missions->close();
            $mysqli->close();
            ?>
        </ul>
    </div>
</div>

<?php include 'includes/footer_demenageur.php';
?>
