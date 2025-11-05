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

// Récupérer les missions terminées
$sql = "SELECT 
            a.id_annonce,
            a.titre,
            a.date_creation,
            a.date_execution,
            u.nom AS client_nom,
            u.prenom AS client_prenom
        FROM ANNONCE a
        JOIN CLIENT c ON a.id_client = c.id_client
        JOIN UTILISATEUR u ON c.id_utilisateur = u.id_utilisateur
        WHERE a.id_demenageur = ? AND a.statut = 'terminee'
        ORDER BY a.date_execution DESC";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param('i', $id_demenageur);
$stmt->execute();
$result = $stmt->get_result();
?>

<h2> Historique de vos missions terminées </h2>

<?php if ($result->num_rows > 0): ?>
    <ul class="list-group">
        <?php while ($mission = $result->fetch_assoc()): ?>
            <li class="list-group-item">
                <strong>Titre :</strong> <?= htmlspecialchars($mission['titre']) ?><br>
                <strong>Client :</strong> <?= htmlspecialchars($mission['client_prenom'] . ' ' . $mission['client_nom']) ?><br>
                <strong>Date de création :</strong> <?= htmlspecialchars($mission['date_creation']) ?><br>
                <strong>Date d'exécution :</strong> <?= htmlspecialchars($mission['date_execution']) ?><br>
                <span class="badge bg-success">Terminée</span><br>
                <a href="detail_annonce.php?id=<?= $mission['id_annonce'] ?>" class="btn btn-sm btn-outline-secondary mt-2">Voir les détails</a>
            </li>
        <?php endwhile; ?>
    </ul>
<?php else: ?>
    <p class="text-muted">Aucune mission terminée pour le moment.</p>
<?php endif; ?>

<?php
$stmt->close();
$mysqli->close();
?>
