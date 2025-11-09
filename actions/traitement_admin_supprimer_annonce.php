<?php


session_start();
require_once __DIR__ . '/../includes/db.php';


//  Vérifier si l'utilisateur est un admin connecté
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    $_SESSION['error_message'] = "Accès non autorisé.";
    header('Location: ../connexion.php');
    exit;
}

//  Vérifier que l'ID de l'annonce est passé via GET
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error_message'] = "Requête invalide.";
    header('Location: ../admin/annonce.php');
    exit;
}
$id_annonce_a_supprimer = (int)$_GET['id'];


//Récupérer les chemins de toutes les photos de cette annonce
$stmt_photos = $mysqli->prepare("SELECT chemin_fichier FROM PHOTO_ANNONCE WHERE id_annonce = ?");
$stmt_photos->bind_param('i', $id_annonce_a_supprimer);
$stmt_photos->execute();
$result_photos = $stmt_photos->get_result();

//  Boucler et supprimer chaque fichier photo
while ($photo = $result_photos->fetch_assoc()) {
    $chemin_fichier_physique = __DIR__ . '/../' . $photo['chemin_fichier'];
    if (file_exists($chemin_fichier_physique)) {
        unlink($chemin_fichier_physique); // Supprime le fichier du serveur
    }
}
$stmt_photos->close();


// "ON DELETE CASCADE" et "ON DELETE SET NULL"
// la suppression de l'annonce va automatiquement nettoyer
// les entrées dans PHOTO_ANNONCE, MESSAGE, PROPOSITION, et EVALUATION.
$stmt_delete = $mysqli->prepare("DELETE FROM ANNONCE WHERE id_annonce = ?");
$stmt_delete->bind_param('i', $id_annonce_a_supprimer);

if ($stmt_delete->execute()) {
    $_SESSION['success_message'] = "Annonce (ID: $id_annonce_a_supprimer) et photos associées supprimées.";
} else {
    $_SESSION['error_message'] = "Erreur lors de la suppression de l'annonce.";
}

$stmt_delete->close();
$mysqli->close();


header('Location: ../admin/annonce.php');
exit;
?>