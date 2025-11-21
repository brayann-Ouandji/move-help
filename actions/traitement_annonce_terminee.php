<?php


session_start();
require_once __DIR__ . '/../includes/db.php';

// SÉCURITÉ
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'client') {
    header('Location: ../connexion.php');
    exit;
}
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: ../client/dashboard.php');
    exit;
}

// RÉCUPÉRER LES DONNÉES 
$id_annonce = (int)$_GET['id'];
$id_utilisateur = $_SESSION['user_id'];
$redirect_url = '../client/detail_annonce.php?id=' . $id_annonce;

// Récupérer l'ID client
$stmt_client = $mysqli->prepare("SELECT id_client FROM client WHERE id_utilisateur = ?");
$stmt_client->bind_param('i', $id_utilisateur);
$stmt_client->execute();
$id_client_connecte = $stmt_client->get_result()->fetch_assoc()['id_client'];
$stmt_client->close();



//  Vérifier que l'annonce appartient au client ET qu'elle est "acceptee"
$stmt_check = $mysqli->prepare("SELECT id_client, date_demenagement FROM annonce WHERE id_annonce = ? AND id_client = ? AND statut = 'acceptee'");
$stmt_check->bind_param('ii', $id_annonce, $id_client_connecte);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows === 0) {
    $_SESSION['error_message'] = "Impossible de trouver cette annonce.";
    header('Location: ' . $redirect_url);
    exit;
}
$annonce = $result_check->fetch_assoc();
$stmt_check->close();

// Vérifier (côté serveur) que la date est bien passée
$date_dem = new DateTime($annonce['date_demenagement']);
if ($date_dem > new DateTime()) {
    $_SESSION['error_message'] = "Vous ne pouvez pas marquer cette mission comme 'terminée' avant sa date de fin.";
    header('Location: ' . $redirect_url);
    exit;
}

//  LA MISE À JOUR
try {
    $sql_update = "UPDATE annonce SET statut = 'terminee', date_modification = NOW() WHERE id_annonce = ?";
    $stmt_update = $mysqli->prepare($sql_update);
    $stmt_update->bind_param('i', $id_annonce);
    
    if ($stmt_update->execute()) {
        $_SESSION['success_message'] = "Annonce marquée comme 'Terminée'. Vous pouvez maintenant l'évaluer.";
    } else {
        $_SESSION['error_message'] = "Erreur lors de la mise à jour.";
    }

} catch (mysqli_sql_exception $exception) {
    $_SESSION['error_message'] = "Erreur BDD : " . $exception->getMessage();
}

$mysqli->close();
header('Location: ' . $redirect_url);
exit;
?>