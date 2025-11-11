<?php

$titre_page = "Gestion des utilisateurs";
include 'includes/header_admin.php';
require_once __DIR__ . '/../includes/db.php';


$sql = "SELECT id_utilisateur, nom, prenom, email, role, statut
        FROM UTILISATEUR
        ORDER BY date_inscription DESC";
$result = $mysqli->query($sql);
$search_term = $_GET['search'] ?? ''; // Le terme de recherche (nom, email)
$role_filtre = $_GET['role'] ?? '';   // Le rôle (client, demenageur, admin)

?>

<h1 class="mb-4">Gestion des utilisateurs</h1>
<?php
if (isset($_SESSION['success_message'])) {
    echo '<div class="alert alert-success">' . ($_SESSION['success_message']) . '</div>';
    unset($_SESSION['success_message']);
}
?>
<div class="card mb-3">
    <div class="card-body">
        <form class="row g-3" method="GET" action="utilisateurs.php">
            <div class="col-md-5">
                <input type="text" class="form-control" id="search" name="search"  placeholder="Rechercher...">
            </div>
            <div class="col-md-4">
                <select class="form-select" id="role" name="role">
                    <option value="">Tous les rôles</option>
                    <option value="client" <?php if ($role_filtre == 'client') echo 'selected'; ?>>Client </option>
                    <option value="demenageur"<?php if($role_filtre =='demenageur') echo 'selected'; ?>> Déménageur</option>
                    <option value="admin"<?php if($role_filtre =='admin') echo 'selected';?>>Admin</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">Filtrer</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-striped table-hover mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom Complet</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT id_utilisateur, nom, prenom, email, role, statut
                    FROM UTILISATEUR
                    WHERE 1=1"; // technique pour faciliter l'ajout de filtres
                
                $params = []; // Tableau pour les paramètres de bind_param
                $types = '';  

                //   filtre de recherche
                if (!empty($search_term)) {
                    $sql .= " AND (nom LIKE ? OR prenom LIKE ? OR email LIKE ?)";
                    $search_like = '%' . $search_term . '%';
                    $params[] = $search_like;
                    $params[] = $search_like;
                    $params[] = $search_like;
                    $types .= 'sss';
                }

                // filtre de rôle 
                if (!empty($role_filtre)) {
                    $sql .= " AND role = ?";
                    $params[] = $role_filtre;
                    $types .= 's';
                }

                $sql .= " ORDER BY date_inscription DESC";

                //  Préparation de la requête
                $stmt = $mysqli->prepare($sql);
                
                // les paramètres laiison
                if (!empty($types)) {
                    $stmt->bind_param($types, ...$params);
                }
                
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result && $result->num_rows > 0) {
                    while ($user = $result->fetch_assoc()) {
                        
                        // Badge Rôle
                        $role_badge = 'bg-secondary';
                        if ($user['role'] == 'client') $role_badge = 'bg-primary';
                        if ($user['role'] == 'demenageur') $role_badge = 'bg-info';
                        if ($user['role'] == 'admin') $role_badge = 'bg-danger';

                        // Badge Statut
                        $statut_badge = $user['statut'] == 'actif' ? 'bg-success' : 'bg-secondary';
                ?>
                <tr>
                    <td><?php echo $user['id_utilisateur']; ?></td>
                    <td><?php echo $user['prenom'] . ' ' . $user['nom']; ?></td>
                    <td><?php echo $user['email']; ?></td>
                    <td>
                        <span class="badge <?php echo $role_badge; ?>">
                            <?php echo $user['role']; ?>
                        </span>
                    </td>
                    <td>
                        <span class="badge <?php echo $statut_badge; ?>">
                            <?php echo $user['statut']; ?>
                        </span>
                    </td>
                    <td>
                        <?php if ($user['role'] != 'admin'): ?>
                            
                            <?php if ($user['statut'] == 'actif'): ?>
                            <a href="../actions/traitement_admin_utilisateurs.php?id=<?php echo $user['id_utilisateur']; ?>&action=desactive" 
                               class="btn btn-sm btn-warning" title="Désactiver le compte">
                                <i class="bi bi-person-x-fill"></i> Désactiver
                            </a>
                            <?php else: ?>
                            <a href="../actions/traitement_admin_utilisateurs.php?id=<?php echo $user['id_utilisateur']; ?>&action=actif" 
                               class="btn btn-sm btn-success" title="Activer le compte">
                                <i class="bi bi-person-check-fill"></i> Activer
                            </a>
                            <?php endif; ?>
                            
                        <?php endif; ?>
                    </td>
                </tr>
                <?php
                    } // Fin du while
                } else {
                    echo '<tr><td colspan="6" class="text-center">Aucun utilisateur ne correspond à vos filtres.</td></tr>';
                }
                
                $stmt->close();
                $mysqli->close();
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php
include 'includes/footer_admin.php';
?>
