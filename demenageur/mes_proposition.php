<?php
$titre_page = "Mes Propositions";
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

// Par défaut, on affiche 'toutes' les propositions
$statut_filtre = 'toutes';
$filtres_autorises = ['en_attente', 'acceptee', 'refusee'];

if (isset($_GET['statut']) && in_array($_GET['statut'], $filtres_autorises)) {
    $statut_filtre = $_GET['statut'];
}

?>

<h1>Mes Propositions</h1>
<p>Suivez le statut des offres que vous avez envoyées.</p>

<ul class="nav nav-tabs mb-3">
    <li class="nav-item">
        <a class="nav-link active" aria-current="page" href="mes_proposition.php">Toutes</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="mes_proposition.php?statut=en_attente">En attente</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="mes_proposition.php?statut=acceptee">Acceptées</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="mes_proposition.php?statut=refusee">Refusées</a>
    </li>
</ul>

<div class="list-group">
    
    <?php
    
    $sql = "SELECT 
                p.prix_propose, 
                p.statut,
                a.id_annonce,
                a.titre,
                a.date_demenagement
            FROM PROPOSITION p
            JOIN ANNONCE a ON p.id_annonce = a.id_annonce
            WHERE p.id_demenageur = ?";
    
    $params = [$id_demenageur_connecte];
    $types = 'i'; 

    // On ajoute le filtre à la requête si ce n'est pas 'toutes'
    if ($statut_filtre != 'toutes') {
        $sql .= " AND p.statut = ?"; //condition
        $params[] = $statut_filtre;
        $types .= 's';
    }

    $sql .= " ORDER BY p.date_proposition DESC";
            
    $stmt_list = $mysqli->prepare($sql);
    
    
    $stmt_list->bind_param($types, ...$params); 
    
    $stmt_list->execute();
    $result_list = $stmt_list->get_result();

    if ($result_list->num_rows > 0) {
        while ($prop = $result_list->fetch_assoc()) {
            
            
            $date_obj = new DateTime($prop['date_demenagement']);
            $date_formatee = $date_obj->format('d/m/Y');

            // Définir le style du badge en fonction du statut
            $badge_class = '';
            $badge_text = '';
            if ($prop['statut'] == 'en_attente') {
                $badge_class = 'bg-warning';
                $badge_text = 'En attente';
            } elseif ($prop['statut'] == 'acceptee') {
                $badge_class = 'bg-success';
                $badge_text = 'Acceptée';
            } elseif ($prop['statut'] == 'refusee') {
                $badge_class = 'bg-danger';
                $badge_text = 'Refusée';
            }
        
    
    ?>
    
    <a href="detail_annonce.php?id=<?php echo $prop['id_annonce']; ?>" class="list-group-item list-group-item-action">
        <div class="d-flex w-100 justify-content-between">
            <div>
                <h5 class="mb-1"><?php echo $prop['titre'];?></h5>
                <small>Annonce pour le <?php echo $date_formatee; ?></small>
            </div>
            <span class="badge <?php echo $badge_class;?> fs-6"><?php echo $badge_text; ?></span>
        </div>
        <p class="mb-1 mt-2">Votre proposition : <strong><?php echo $prop['prix_propose']; ?> € </strong></p>
    </a>
    <?php 
        }
    } else {
        echo'<div class="list-group-item text-muted text-center"> Vous n\'avez aucune propotions dans cette catégorie.</div>';
    }
    $stmt_list->close();
    $mysqli->close();
    ?>
    
</div>

<?php
include 'includes/footer_demenageur.php';
?>