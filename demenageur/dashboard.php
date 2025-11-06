<?php
  
  $titre_page = "Tableau de bord";
include 'includes/header_demenageur.php';
require_once __DIR__ . '/../includes/db.php';

$id_utilisateur=$_SESSION['user_id'];
$sql_demenageur = "SELECT id_demenageur FROM DEMENAGEUR WHERE id_utilisateur = ?";
$stmt_demenageur = $mysqli->prepare($sql_demenageur);
$stmt_demenageur->bind_param('i', $id_utilisateur);
$stmt_demenageur->execute();
$result_demenageur = $stmt_demenageur->get_result();
$demenageur_data = $result_demenageur->fetch_assoc();
$id_demenageur_connecte = $demenageur_data['id_demenageur'];

// Propositions envoyées
$sql_envoyees = "SELECT COUNT(*) as total FROM PROPOSITION WHERE id_demenageur = ?";
$stmt_envoyees = $mysqli->prepare($sql_envoyees);
$stmt_envoyees->bind_param('i', $id_demenageur_connecte);
$stmt_envoyees->execute();
$count_envoyees = $stmt_envoyees->get_result()->fetch_assoc()['total'];

// Missions acceptées
$sql_acceptees = "SELECT COUNT(*) as total 
                  FROM ANNONCE a
                  JOIN PROPOSITION p ON a.id_annonce = p.id_annonce
                  WHERE p.id_demenageur = ? AND a.statut = 'acceptee'";
$stmt_acceptees = $mysqli->prepare($sql_acceptees);
$stmt_acceptees->bind_param('i', $id_demenageur_connecte);
$stmt_acceptees->execute();
$count_acceptees = $stmt_acceptees->get_result()->fetch_assoc()['total'];

 // Note moyenne
$sql_note = "SELECT ROUND(AVG(note), 2) as moyenne FROM EVALUATION WHERE id_demenageur = ?";
$stmt_note = $mysqli->prepare($sql_note);
$stmt_note->bind_param('i', $id_demenageur);
$stmt_note->execute();
$note_moyenne = $stmt_note->get_result()->fetch_assoc()['moyenne'] ?? 'N/A';


// Missions à venir (acceptées avec date future)
$sql_avenir = "SELECT COUNT(*) as total
               FROM ANNONCE a
               JOIN PROPOSITION p ON a.id_annonce = p.id_annonce
               WHERE p.id_demenageur = ? AND a.statut = 'acceptee' AND a.date_demenagement > NOW()";
$stmt_avenir = $mysqli->prepare($sql_avenir);
$stmt_avenir->bind_param('i', $id_demenageur);
$stmt_avenir->execute();
$count_avenir = $stmt_avenir->get_result()->fetch_assoc()['total'];
?>

<h1 class="mb-4">Bienvenue, <?php echo $_SESSION['user_prenom'] . ' ' . $_SESSION['user_nom']; ?> !</h1>

<div class="row">
    <div class="col-md-4">
        <div class="card text-center text-bg-primary mb-3">
            <div class="card-body">
                <h5 class="card-title">proposition envoyées</h5>
                <p class="card-text fs-3 fw-bold">2</p> </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center text-bg-success mb-3">
            <div class="card-body">
                <h5 class="card-title">missions acceptées</h5>
                <p class="card-text fs-3 fw-bold">5</p> </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center text-bg-info mb-3">
            <div class="card-body">
                <h5 class="card-title">note moyennes</h5>
                <p class="card-text fs-3 fw-bold">6/10</p> </div>
        </div>
    </div>
</div>
    <div class="col-md-4">
        <div class="card text-center text-bg-info mb-3">
            <div class="card-body">
                <h5 class="card-title">Missions a venir</h5>
                <p class="card-text fs-3 fw-bold">1</p> </div>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">
        <h4 class="mb-0">Vos missions récentes</h4>
    </div>
    <div class="card-body">
        <p class="card-text">Voici un aperçu de vos derniers déménagements acceptés ou terminés.</p>
        
        <ul class="list-group">
        <?php
        // Requête : les 5 dernières annonces liées à ce déménageur via une proposition acceptée
        $sql_missions = "SELECT a.id_annonce, a.titre, a.statut, a.date_demenagement
                         FROM ANNONCE a
                         JOIN PROPOSITION p ON a.id_annonce = p.id_annonce
                         WHERE p.id_demenageur = ? AND a.statut IN ('acceptee', 'terminee')
                         ORDER BY a.date_demenagement DESC
                         LIMIT 5";

        $stmt_missions = $mysqli->prepare($sql_missions);
        $stmt_missions->bind_param('i', $id_demenageur);
        $stmt_missions->execute();
        $result_missions = $stmt_missions->get_result();

        if ($result_missions->num_rows > 0) {
            while ($mission = $result_missions->fetch_assoc()) {
                // Badge selon le statut
                $badge_class = ($mission['statut'] == 'acceptee') ? 'bg-success' : 'bg-secondary';
                $badge_text = ($mission['statut'] == 'acceptee') ? 'Acceptée' : 'Terminée';

                echo '<li class="list-group-item d-flex justify-content-between align-items-center">';
                echo htmlspecialchars($mission['titre']) . ' - ' . date('d/m/Y', strtotime($mission['date_demenagement']));
                echo '<span class="badge ' . $badge_class . '">' . $badge_text . '</span>';
                echo '<a href="detail_annonce.php?id=' . $mission['id_annonce'] . '" class="btn btn-sm btn-outline-primary">Voir</a>';
                echo '</li>';
            }
        } else {
            echo '<li class="list-group-item text-muted">Vous n\'avez pas encore effectué de mission.</li>';
        }

        $stmt_missions->close();
        ?>
        </ul>

        <div class="text-center mt-3">
            <a href="mes_missions.php" class="btn btn-primary">Voir toutes mes missions</a>
        </div>
    </div>
</div>

<?php include 'includes/footer_demenageur.php';
 ?>
