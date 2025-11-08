<?php
$titre_page = "Trouver des annonces";
include 'includes/header_demenageur.php'; // Inclut check_session.php
require_once __DIR__ . '/../includes/db.php'; // Connexion BDD


$id_utilisateur = $_SESSION['user_id'];
$stmt_dem = $mysqli->prepare("SELECT id_demenageur FROM DEMENAGEUR WHERE id_utilisateur = ?");
$stmt_dem->bind_param('i', $id_utilisateur);
$stmt_dem->execute();
$result_dem = $stmt_dem->get_result();
$dem_data = $result_dem->fetch_assoc();
$id_demenageur_connecte = $dem_data['id_demenageur'];
$stmt_dem->close();

?>

<h1>Annonces disponibles</h1>
<p>Recherchez une mission et faites votre proposition.</p>

<div class="card mb-4">
    <div class="card-body">
        <form class="row g-3" method="GET"> <div class="col-md-4">
                <label for="filtre_ville" class="form-label">Ville de départ</label>
                <input type="text" class="form-control" id="filtre_ville" name="ville" placeholder="Rouen, Paris...">
            </div>
            <div class="col-md-4">
                <label for="filtre_date" class="form-label">Date</label>
                <input type="date" class="form-control" id="filtre_date" name="date">
            </div>
            <div class="col-md-2">
                <label for="filtre_volume" class="form-label">Volume (m³)</label>
                <input type="number" class="form-control" id="filtre_volume" name="volume" min="1">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Filtrer</button>
            </div>
        </form>
    </div>
</div>

<div class="row">

    <?php
  
    
    // On sélectionne les annonces "publiées"
    $sql = "SELECT 
                a.id_annonce, a.titre, a.date_demenagement, a.ville_depart, 
                a.ville_arrivee, a.volume_m3, a.nb_demenageur_souhaites,
                p.id_proposition,  -- Sera NULL si le déménageur n'a pas proposé
                p.statut as statut_proposition
            FROM ANNONCE a
            LEFT JOIN PROPOSITION p ON a.id_annonce = p.id_annonce AND p.id_demenageur = ?
            WHERE a.statut = 'publiee'";
    
    $params = [$id_demenageur_connecte];
    $types = 'i';

    // Ajout des filtres (simples)
    if (!empty($_GET['ville'])) {
        $sql .= " AND a.ville_depart LIKE ?";
        $params[] = '%' . $_GET['ville'] . '%';
        $types .= 's';
    }
    if (!empty($_GET['volume'])) {
        $sql .= " AND a.volume_m3 >= ?";
        $params[] = $_GET['volume'];
        $types .= 'd';
    }
    
    $sql .= " ORDER BY a.date_demenagement ASC";

    
    $stmt_list = $mysqli->prepare($sql);
    if ($stmt_list === false) {
        die("Erreur de préparation : " . $mysqli->error);
    }
    
    // On lie les paramètres dynamiquement
    $stmt_list->bind_param($types, ...$params);
    
    $stmt_list->execute();
    $result_list = $stmt_list->get_result();

    if ($result_list->num_rows > 0) {
        while ($annonce = $result_list->fetch_assoc()) {
            
            $date_obj = new DateTime($annonce['date_demenagement']);
            $date_formatee = $date_obj->format('d/m/Y');
    ?>
    
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><?php echo $annonce['titre']; ?></h5>
                <h6 class="card-subtitle mb-2 text-muted"><?php echo $date_formatee; ?></h6>
                <p class="card-text">
                    <strong>Trajet:</strong> <?php echo $annonce['ville_depart']; ?> → <?php echo $annonce['ville_arrivee']; ?><br>
                    <strong>Volume:</strong> <?php echo $annonce['volume_m3']; ?> m³<br>
                    <strong>Souhaité:</strong> <?php echo $annonce['nb_demenageur_souhaites']; ?> déménageur(s)
                </p>
                
                <?php
                
                $lien_detail = 'detail_annonce.php?id=' . $annonce['id_annonce'];
                
                if ($annonce['id_proposition'] !== NULL) {
                    // Le déménageur a déjà fait une proposition
                    if ($annonce['statut_proposition'] == 'en_attente') {
                        echo '<span class="badge bg-warning">Proposition envoyée</span>';
                    } elseif ($annonce['statut_proposition'] == 'acceptee') {
                        echo '<span class="badge bg-success">Mission acceptée</span>';
                    } elseif ($annonce['statut_proposition'] == 'refusee') {
                        echo '<span class="badge bg-danger">Proposition refusée</span>';
                    }
                    echo '<a href="' . $lien_detail . '" class="btn btn-secondary float-end">Voir détails</a>';

                } else {
                    // Aucune proposition faite, on affiche le bouton
                    echo '<a href="' . $lien_detail . '" class="btn btn-primary float-end">Voir et Proposer</a>';
                }
                ?>
            </div>
        </div>
    </div>

    <?php
        }
    } else {
        echo '<div class="col-12"><p class="text-center text-muted">Aucune annonce ne correspond à vos critères.</p></div>';
    }
    
    
    $stmt_list->close();
    $mysqli->close();
    ?>

</div>

<?php
include 'includes/footer_demenageur.php';
?>