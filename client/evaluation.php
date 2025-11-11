<?php
$titre_page = "Évaluer les déménageurs";
include 'includes/header_client.php';
require_once __DIR__ . '/../includes/db.php'; 




$id_utilisateur = $_SESSION['user_id'];
$stmt_client = $mysqli->prepare("SELECT id_client FROM CLIENT WHERE id_utilisateur = ?");
$stmt_client->bind_param('i', $id_utilisateur);
$stmt_client->execute();
$id_client_connecte = $stmt_client->get_result()->fetch_assoc()['id_client'];
$stmt_client->close();


if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo '<div class="alert alert-danger">Annonce non spécifiée.</div>';
    include 'includes/footer_client.php';
    exit;
}
$id_annonce = (int)$_GET['id'];

// Vérifier que l'annonce appartient au client ET qu'elle est "terminee"
$sql_annonce = "SELECT * FROM ANNONCE WHERE id_annonce = ? AND id_client = ? AND statut = 'terminee'";
$stmt_annonce = $mysqli->prepare($sql_annonce);
$stmt_annonce->bind_param('ii', $id_annonce, $id_client_connecte);
$stmt_annonce->execute();
$result_annonce = $stmt_annonce->get_result();

if ($result_annonce->num_rows === 0) {
    echo '<div class="alert alert-danger">Cette annonce ne peut pas être évaluée.</div>';
    include 'includes/footer_client.php';
    $mysqli->close();
    exit;
}
$annonce = $result_annonce->fetch_assoc();
$stmt_annonce->close();


// On cherche les déménageurs qui ont été 'acceptee' pour cette annonce
$sql_dem = "SELECT 
                d.id_demenageur, u.nom, u.prenom, u.photo_profil,
                (SELECT id_evaluation FROM EVALUATION e WHERE e.id_annonce = ? AND e.id_demenageur = d.id_demenageur) as evaluation_existante
            FROM PROPOSITION p
            JOIN DEMENAGEUR d ON p.id_demenageur = d.id_demenageur
            JOIN UTILISATEUR u ON d.id_utilisateur = u.id_utilisateur
            WHERE p.id_annonce = ?
            AND p.statut = 'acceptee'";

$stmt_dem = $mysqli->prepare($sql_dem);
$stmt_dem->bind_param('ii', $id_annonce, $id_annonce);
$stmt_dem->execute();
$result_dem = $stmt_dem->get_result();
$demenageurs = $result_dem->fetch_all(MYSQLI_ASSOC);
$stmt_dem->close();
$mysqli->close();

?>

<h1>Évaluer la mission : <?php echo ($annonce['titre']); ?></h1>
<p>Donnez une note aux déménageurs qui vous ont aidé.</p>

<?php
if (isset($_SESSION['success_message'])) {
    echo '<div class="alert alert-success">' . ($_SESSION['success_message']) . '</div>';
    unset($_SESSION['success_message']);
}
if (isset($_SESSION['error_message'])) {
    echo '<div class="alert alert-danger">' . ($_SESSION['error_message']) . '</div>';
    unset($_SESSION['error_message']);
}
?>

<div class="row">
    <?php foreach ($demenageurs as $dem): ?>
    <div class="col-md-6 mb-3">
        <div class="card">
            <div class="card-body text-center">
                <img src="../img/default_profil.png" class="img-fluid rounded-circle mb-2" style="width: 80px;" alt="profil">
                <h5 class="card-title"><?php echo ($dem['prenom'] . ' ' . $dem['nom']); ?></h5>
                
                <?php if ($dem['evaluation_existante']): // Si le client a DÉJÀ évalué ce déménageur ?>
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle-fill"></i> Merci, vous avez déjà évalué ce déménageur.
                    </div>
                <?php else: // Afficher le formulaire d'évaluation ?>
                    <form action="../actions/traitement_evaluation.php" method="POST">
                        <input type="hidden" name="id_annonce" value="<?php echo $id_annonce; ?>">
                        <input type="hidden" name="id_demenageur" value="<?php echo $dem['id_demenageur']; ?>">
                        
                        <div class="mb-3">
                            <label for="note-<?php echo $dem['id_demenageur']; ?>" class="form-label">Votre note (sur 5)</label>
                            <select class="form-select" id="note-<?php echo $dem['id_demenageur']; ?>" name="note" required>
                                <option value="">Choisir...</option>
                                <option value="5">5 - Excellent</option>
                                <option value="4">4 - Très bien</option>
                                <option value="3">3 - Moyen</option>
                                <option value="2">2 - Insuffisant</option>
                                <option value="1">1 - Très mauvais</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="commentaire-<?php echo $dem['id_demenageur']; ?>" class="form-label">Commentaire (optionnel)</label>
                            <textarea class="form-control" name="commentaire" id="commentaire-<?php echo $dem['id_demenageur']; ?>" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Envoyer l'évaluation</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<a href="mes_annonces.php" class="btn btn-secondary mt-3">Retour à mes annonces</a>

<?php
include 'includes/footer_client.php';
?>