<?php

$titre_page = "Gestion des utilisateurs";
include 'includes/header_admin.php';
require_once __DIR__ . '/../includes/db.php';

$sql = "SELECT id_utilisateur, nom, prenom, email, role, statut
        FROM UTILISATEUR
        ORDER BY date_inscription DESC";
$result = $mysqli->query($sql);
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
        <form class="row g-3">
            <div class="col-md-5">
                <input type="text" class="form-control" placeholder="Rechercher par nom ou email...">
            </div>
            <div class="col-md-4">
                <select class="form-select">
                    <option value="">Tous les rôles</option>
                    <option value="client">Client</option>
                    <option value="demenageur">Déménageur</option>
                    <option value="admin">Admin</option>
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
                    <td><?php echo ($user['prenom'] . ' ' . $user['nom']); ?></td>
                    <td><?php echo ($user['email']); ?></td>
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
                    echo '<tr><td colspan="6" class="text-center">Aucun utilisateur trouvé.</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php
$result->close();
$mysqli->close();
include 'includes/footer_admin.php';
?>