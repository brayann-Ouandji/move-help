<?php
session_start();
require_once __DIR__ . '/../includes/db.php';

//  Vérifier que l'utilisateur est un déménageur connecté
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'demenageur') {
    $_SESSION['error_message'] = "Accès non autorisé.";
    header('Location: ../connexion.php');
    exit;
}

$id_utilisateur = $_SESSION['user_id'];

//  Récupérer l'ID du déménageur
$stmt_demenageur = $mysqli->prepare("SELECT id_demenageur FROM DEMENAGEUR WHERE id_utilisateur = ?");
$stmt_demenageur->bind_param('i', $id_utilisateur);
$stmt_demenageur->execute();
$result_demenageur = $stmt_demenageur->get_result();
$id_demenageur = $result_demenageur->fetch_assoc()['id_demenageur'] ?? null;
$stmt_demenageur->close();

if (!$id_demenageur) {
    $_SESSION['error_message'] = "Profil déménageur introuvable.";
    header('Location: ../demenageur/dashboard.php');
    exit;
}

// Récupérer les missions (annonces acceptées ou terminées)
$sql = "SELECT 
            id_annonce,
            titre,
            statut,
            date_creation,
            date_execution
        FROM ANNONCE
        WHERE id_demenageur = ? AND statut IN ('acceptee', 'terminee')
        ORDER BY date_creation DESC";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param('i', $id_demenageur);
$stmt->execute();
$result = $stmt->get_result();
?>

<h2> Suivi de vos missions</h2>

<?php if ($result->num_rows > 0): ?>
    <ul class="list-group">
        <?php while ($mission = $result->fetch_assoc()): ?>
            <li class="list-group-item">
                <strong>Titre :</strong> <?= htmlspecialchars($mission['titre']) ?><br>
                <strong>Date de création :</strong> <?= htmlspecialchars($mission['date_creation']) ?><br>
                <?php if (!empty($mission['date_execution'])): ?>
                    <strong>Date d'exécution :</strong> <?= htmlspecialchars($mission['date_execution']) ?><br>
                <?php endif; ?>
                <strong>Statut :</strong> 
                <span class="badge 
                    <?= $mission['statut'] === 'terminee' ? 'bg-success' : 'bg-warning' ?>">
                    <?= ucfirst($mission['statut']) ?>
                </span><br>
                <a href="detail_annonce.php?id=<?= $mission['id_annonce'] ?>" class="btn btn-sm btn-outline-primary mt-2">Voir les détails</a>
            </li>
        <?php endwhile; ?>
    </ul>
<?php else: ?>
    <p class="text-muted">Vous n'avez encore aucune mission en cours ou terminée.</p>
<?php endif; ?>

<?php
$stmt->close();
$mysqli->close();
?>
