<?php

$titre_page = "Gestion des annonces";
include 'includes/header_admin.php';

// Données simulées
$annonces = [
    ['id' => 1, 'titre' => 'Déménagement F3 Paris -> Lyon', 'client' => 'Brayann O.', 'date' => '30/11/2025', 'statut' => 'Publiée'],
    ['id' => 2, 'titre' => 'Transport Piano Rouen', 'client' => 'Mme Durand', 'date' => '02/12/2025', 'statut' => 'Acceptée'],
    ['id' => 3, 'titre' => 'Studio Étudiant', 'client' => 'Mr Martin', 'date' => '20/10/2025', 'statut' => 'Terminée'],
    ['id' => 68, 'titre' => 'Transport suspect', 'client' => 'User Spam', 'date' => '01/12/2025', 'statut' => 'Publiée (Signalée)'],
];
?>

<h1 class="mb-4">Gestion des annonces</h1>

<div class="card mb-3">
    <div class="card-body">
        <form class="row g-3">
            <div class="col-md-5">
                <input type="text" class="form-control" placeholder="Rechercher par titre ou client...">
            </div>
            <div class="col-md-4">
                <select class="form-select">
                    <option value="">Tous les statuts</option>
                    <option value="publiee">Publiée</option>
                    <option value="acceptee">Acceptée</option>
                    <option value="terminee">Terminée</option>
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
                    <th>Titre</th>
                    <th>Client</th>
                    <th>Date Dém.</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($annonces as $annonce): ?>
                <tr>
                    <td><?php echo $annonce['id']; ?></td>
                    <td><?php echo $annonce['titre']; ?></td>
                    <td><?php echo $annonce['client']; ?></td>
                    <td><?php echo $annonce['date']; ?></td>
                    <td>
                        <span class="badge 
                            <?php if($annonce['statut'] == 'Publiée') echo 'bg-warning';
                                  elseif($annonce['statut'] == 'Acceptée') echo 'bg-success';
                                  elseif($annonce['statut'] == 'Terminée') echo 'bg-secondary';
                                  else echo 'bg-danger'; ?>">
                            <?php echo $annonce['statut']; ?>
                        </span>
                    </td>
                    <td>
                        <a href="#" class="btn btn-sm btn-info" title="Voir l'annonce">
                            <i class="bi bi-eye-fill"></i>
                        </a>
                        <a href="#" class="btn btn-sm btn-danger" title="Supprimer l'annonce" 
                           onclick="return confirm('Voulez-vous vraiment supprimer cette annonce ?');">
                            <i class="bi bi-trash-fill"></i>
                        </a>
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