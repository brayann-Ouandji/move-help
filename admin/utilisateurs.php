<?php

$titre_page = "Gestion des utilisateurs";
include 'includes/header_admin.php';

// Données simulées
$utilisateurs = [
    ['id' => 1, 'nom' => 'OUANDJI Brayann', 'email' => 'brayann.o@exemple.com', 'role' => 'client', 'statut' => 'Actif'],
    ['id' => 2, 'nom' => 'SIKADI Irving', 'email' => 'irving.s@exemple.com', 'role' => 'demenageur', 'statut' => 'Actif'],
    ['id' => 3, 'nom' => 'Admin User', 'email' => 'admin@move-help.fr', 'role' => 'admin', 'statut' => 'Actif'],
    ['id' => 4, 'nom' => 'User Spam', 'email' => 'spam@bot.com', 'role' => 'client', 'statut' => 'Désactivé'],
];
?>

<h1 class="mb-4">Gestion des utilisateurs</h1>

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
                <?php foreach ($utilisateurs as $user): ?>
                <tr>
                    <td><?php echo $user['id']; ?></td>
                    <td><?php echo $user['nom']; ?></td>
                    <td><?php echo $user['email']; ?></td>
                    <td>
                        <span class="badge 
                            <?php if($user['role'] == 'client') echo 'bg-primary';
                                  elseif($user['role'] == 'demenageur') echo 'bg-info';
                                  else echo 'bg-danger'; ?>">
                            <?php echo $user['role']; ?>
                        </span>
                    </td>
                    <td>
                        <span class="badge <?php echo $user['statut'] == 'Actif' ? 'bg-success' : 'bg-secondary'; ?>">
                            <?php echo $user['statut']; ?>
                        </span>
                    </td>
                    <td>
                        <?php if ($user['role'] != 'admin'): ?>
                            
                            <?php if ($user['statut'] == 'Actif'): ?>
                            <a href="#" class="btn btn-sm btn-warning" title="Désactiver le compte">
                                <i class="bi bi-person-x-fill"></i> Désactiver
                            </a>
                            <?php else: ?>
                            <a href="#" class="btn btn-sm btn-success" title="Activer le compte">
                                <i class="bi bi-person-check-fill"></i> Activer
                            </a>
                            <?php endif; ?>

                            <a href="#" class="btn btn-sm btn-danger" title="Supprimer le compte"
                               onclick="return confirm('Voulez-vous vraiment SUPPRIMER cet utilisateur ?');">
                                <i class="bi bi-trash-fill"></i>
                            </a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
include 'includes/footer_admin.php';
?>