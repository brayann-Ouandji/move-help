<?php
$titre_page = "Dashboard Admin";
include 'includes/header_admin.php';
require_once __DIR__ . '/../includes/db.php'; 



// Total Utilisateurs
$result_users = $mysqli->query("SELECT COUNT(*) as total FROM UTILISATEUR");
$count_users = $result_users->fetch_assoc()['total'];

// Total Annonces
$result_annonces = $mysqli->query("SELECT COUNT(*) as total FROM ANNONCE");
$count_annonces = $result_annonces->fetch_assoc()['total'];

// Missions Terminées
$result_done = $mysqli->query("SELECT COUNT(*) as total FROM ANNONCE WHERE statut = 'terminee'");
$count_done = $result_done->fetch_assoc()['total'];

?>

<h1 class="mb-4">Dashboard Administrateur</h1>

<div class="row">
    <div class="col-md-4">
        <div class="card text-center text-bg-primary mb-3">
            <div class="card-body">
                <h5 class="card-title">Utilisateurs Inscrits</h5>
                <p class="card-text fs-3 fw-bold"><?php echo $count_users; ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center text-bg-success mb-3">
            <div class="card-body">
                <h5 class="card-title">Annonces Publiées</h5>
                <p class="card-text fs-3 fw-bold"><?php echo $count_annonces; ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center text-bg-warning mb-3">
            <div class="card-body">
                <h5 class="card-title">Missions Réalisées</h5>
                <p class="card-text fs-3 fw-bold"><?php echo $count_done; ?></p>
            </div>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">
        <h4 class="mb-0">Derniers utilisateurs inscrits</h4>
    </div>
    <div class="card-body">
        <ul class="list-group">
            <?php
            $sql_recent = "SELECT nom, prenom, role, date_inscription 
                           FROM UTILISATEUR 
                           ORDER BY date_inscription DESC 
                           LIMIT 5";
            $result_recent = $mysqli->query($sql_recent);
            
            if ($result_recent->num_rows > 0) {
                while ($user = $result_recent->fetch_assoc()) {
                    $role_icon = $user['role'] == 'client' ? 'bi-person-fill' : 'bi-truck';
                    if($user['role'] == 'admin') {
                        $role_icon = 'bi-shield-lock-fill';
                    }
                    echo '<li class="list-group-item">';
                    echo '<i class="bi ' . $role_icon . '"></i> ';
                    echo 'Nouveau ' . $user['role'] . ' : <strong>' . ($user['prenom'] . ' ' . $user['nom']) . '</strong>';
                    echo '<small class="text-muted float-end">' . (new DateTime($user['date_inscription']))->format('d/m/Y') . '</small>';
                    echo '</li>';
                }
            } else {
                echo '<li class="list-group-item text-muted">Aucune activité récente.</li>';
            }
            $mysqli->close();
            ?>
        </ul>
    </div>
</div>

<?php
include 'includes/footer_admin.php';
?>