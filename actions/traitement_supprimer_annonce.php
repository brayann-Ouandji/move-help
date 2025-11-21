<?php
session_start();
require_once __DIR__ . '/../includes/db.php';

// SÉCURITÉ
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'client') {
    $_SESSION['error_message'] = "Accès non autorisé.";
    header('Location: ../connexion.php');
    exit;
}

// Vérifier que l'ID de l'annonce est passé
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error_message'] = "Requête invalide.";
    header('Location: ../client/mes_annonces.php');
    exit;
}
$id_annonce_a_supprimer = (int)$_GET['id'];
$id_utilisateur = $_SESSION['user_id'];

// Récupérer l'ID client
$stmt_client = $mysqli->prepare("SELECT id_client FROM client WHERE id_utilisateur = ?");
$stmt_client->bind_param('i', $id_utilisateur);
$stmt_client->execute();
$id_client_connecte = $stmt_client->get_result()->fetch_assoc()['id_client'];
$stmt_client->close();

// VÉRIFIER QUE L'ANNONCE APPARTIENT AU CLIENT (TRÈS IMPORTANT)
$stmt_check = $mysqli->prepare("SELECT id_client FROM annonce WHERE id_annonce = ?");
$stmt_check->bind_param('i', $id_annonce_a_supprimer);
$stmt_check->execute();
$result_check = $stmt_check->get_result();
if ($result_check->num_rows === 0) {
    $_SESSION['error_message'] = "Annonce non trouvée.";
    header('Location: ../client/mes_annonces.php');
    exit;
}
$annonce_owner = $result_check->fetch_assoc();
$stmt_check->close();

if ($annonce_owner['id_client'] != $id_client_connecte) {
    $_SESSION['error_message'] = "Vous n'êtes pas autorisé à supprimer cette annonce.";
    header('Location: ../client/mes_annonces.php');
    exit;
}

// SUPPRIMER LES PHOTOS ASSOCIÉES (fichiers physiques)
$stmt_photos = $mysqli->prepare("SELECT chemin_fichier FROM photo_annonce WHERE id_annonce = ?");
$stmt_photos->bind_param('i', $id_annonce_a_supprimer);
$stmt_photos->execute();
$result_photos = $stmt_photos->get_result();
while ($photo = $result_photos->fetch_assoc()) {
    $chemin_fichier_physique = __DIR__ . '/../' . $photo['chemin_fichier'];
    if (file_exists($chemin_fichier_physique)) {
        unlink($chemin_fichier_physique); // Supprime le fichier du serveur
    }
}
$stmt_photos->close();

$stmt_delete = $mysqli->prepare("DELETE FROM annonce WHERE id_annonce = ?");
$stmt_delete->bind_param('i', $id_annonce_a_supprimer);

if ($stmt_delete->execute()) {
    $_SESSION['success_message'] = "Annonce supprimée avec succès.";
} else {
    $_SESSION['error_message'] = "Erreur lors de la suppression de l'annonce.";
}

$stmt_delete->close();
$mysqli->close();

// Rediriger vers la liste des annonces
header('Location: ../client/mes_annonces.php');
exit;
?>