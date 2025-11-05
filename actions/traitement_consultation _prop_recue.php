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

//  Récupérer les propositions reçues sur les annonces du déménageur
$sql = "SELECT 
            p.id_proposition,
            p.date_proposition,
            p.statut,
            p.message,
            u.nom AS client_nom,
            u.prenom AS client_prenom,
            a.titre AS annonce_titre,
            a.id_annonce
        FROM PROPOSITION p
        JOIN ANNONCE a ON p.id_annonce = a.id_annonce
        JOIN CLIENT c ON a.id_client = c.id_client
        JOIN UTILISATEUR u ON c.id_utilisateur = u.id_utilisateur
        WHERE a.id_demenageur = ?
        ORDER BY p.date_proposition DESC";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param('i', $id_demenageur);
$stmt->execute();
$result = $stmt->get_result();
?>

<h2> Propositions reçues </h2>

<?php if ($result->num_rows > 0): ?>
    <ul class="list-group">
        <?php while ($row = $result->fetch_assoc()): ?>
            <li class="list-group-item">
                <strong>Annonce :</strong> <?= htmlspecialchars($row['annonce_titre']) ?><br>
                <strong>Client :</strong> <?= htmlspecialchars($row['client_prenom'] . ' ' . $row['client_nom']) ?><br>
                <strong>Date :</strong> <?= htmlspecialchars($row['date_proposition']) ?><br>
                <strong>Message :</strong> <?= nl2br(htmlspecialchars($row['message'])) ?><br>
                <strong>Statut :</strong> 
                <span class="badge 
                    <?= $row['statut'] === 'acceptee' ? 'bg-success' : ($row['statut'] === 'refusee' ? 'bg-danger' : 'bg-warning') ?>">
                    <?= ucfirst($row['statut']) ?>
                </span><br>
                <a href="detail_annonce.php?id=<?= $row['id_annonce'] ?>" class="btn btn-sm btn-outline-primary mt-2">Voir l'annonce</a>
            </li>
        <?php endwhile; ?>
    </ul>
<?php else: ?>
    <p class="text-muted">Aucune proposition reçue pour le moment.</p>
<?php endif; ?>

<?php
$stmt->close();
$mysqli->close();
?>
