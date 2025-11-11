<?php

$titre_page = "Gestion des annonces";
include 'includes/header_admin.php';
require_once __DIR__ . '/../includes/db.php';

$sql = "SELECT 
            a.id_annonce, a.titre, a.date_demenagement, a.statut,
            u.nom, u.prenom
        FROM ANNONCE a
        JOIN CLIENT c ON a.id_client = c.id_client
        JOIN UTILISATEUR u ON c.id_utilisateur = u.id_utilisateur
        ORDER BY a.date_creation DESC";
$result = $mysqli->query($sql);

$search_term = $_GET['search'] ?? ''; // Le terme de recherche (titre, client)
$statut_filtre = $_GET['statut'] ?? ''; // Le statut (publiee, acceptee, ...)

?>

<h1 class="mb-4">Gestion des annonces</h1>

<div class="card mb-3">
    <div class="card-body">
        <form class="row g-3" method="GET" action="annonce.php">
            <div class="col-md-5">
                <input type="text" class="form-control" id="search" name="search"  placeholder="Rechercher par titre ou client...">
            </div>
            <div class="col-md-4">
                <select class="form-select"id="statut" name="statut">
                    <option value="">Tous les statuts</option>
                    <option value="publiee"<?php if ($statut_filtre == 'publiee') echo 'selected'; ?>>Publiée</option>
                    <option value="acceptee" <?php if ($statut_filtre == 'acceptee') echo 'selected'; ?>>Acceptée</option>
                    <option value="terminee"<?php if ($statut_filtre == 'terminee') echo 'selected'; ?>>Terminée</option>
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
                <?php
                  // Base de la requête avec les jointures
                $sql = "SELECT 
                            a.id_annonce, a.titre, a.date_demenagement, a.statut,
                            u.nom, u.prenom
                        FROM ANNONCE a
                        JOIN CLIENT c ON a.id_client = c.id_client
                        JOIN UTILISATEUR u ON c.id_utilisateur = u.id_utilisateur
                        WHERE 1=1"; // technique pour faciliter l'ajout de filtres
                
                $params = []; // Tableau pour les paramètres
                $types = '';  // Chaîne pour les types

                // filtre de recherche
                if (!empty($search_term)) {
                    $sql .= " AND (a.titre LIKE ? OR u.nom LIKE ? OR u.prenom LIKE ?)";
                    $search_like = '%' . $search_term . '%';
                    $params[] = $search_like;
                    $params[] = $search_like;
                    $params[] = $search_like;
                    $types .= 'sss';
                }

                // filtre de statut
                if (!empty($statut_filtre)) {
                    $sql .= " AND a.statut = ?";
                    $params[] = $statut_filtre;
                    $types .= 's';
                }

                $sql .= " ORDER BY a.date_creation DESC";

                // 3. Prépareation de la requête
                $stmt = $mysqli->prepare($sql);
                
                if (!empty($types)) {
                    $stmt->bind_param($types, ...$params);
                }
                
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result && $result->num_rows > 0) {
                    while ($annonce = $result->fetch_assoc()) {
                        $date_obj = new DateTime($annonce['date_demenagement']);
                        $date_formatee = $date_obj->format('d/m/Y');

                         // Badge pour le statut
                        $badge_class = 'bg-secondary';
                        if ($annonce['statut'] == 'publiee') $badge_class = 'bg-warning';
                        if ($annonce['statut'] == 'acceptee') $badge_class = 'bg-success';
                        if ($annonce['statut'] == 'terminee') $badge_class = 'bg-secondary';
                ?>
                <tr>
                    <td><?php echo $annonce['id_annonce']; ?></td>
                    <td><?php echo ($annonce['titre']); ?></td>
                    <td><?php echo ($annonce['prenom'] . ' ' . $annonce['nom']); ?></td>
                    <td><?php echo $date_formatee; ?></td>
                    <td>
                        <span class="badge <?php echo $badge_class; ?>">
                            <?php echo ucfirst($annonce['statut']); ?>
                        </span>
                    </td>
                    <td>
                        <a href="../actions/traitement_admin_supprimer_annonce.php?id=<?php echo $annonce['id_annonce']; ?>" 
                           class="btn btn-sm btn-danger" title="Supprimer l'annonce" 
                           onclick="return confirm('Voulez-vous vraiment supprimer cette annonce ?');">
                            <i class="bi bi-trash-fill"></i>
                        </a>
                    </td>
                </tr>
                <?php
                    } // Fin du while
                } else {
                    echo '<tr><td colspan="6" class="text-center">Aucune annonce trouvée.</td></tr>';
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