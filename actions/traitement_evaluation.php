<?php


session_start();
require_once __DIR__ . '/../includes/db.php';


if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'client') {
    header('Location: ../connexion.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header('Location: ../client/dashboard.php');
    exit;
}

// RÉCUPÉRER LES DONNÉES
$id_annonce = (int)$_POST['id_annonce'];
$id_demenageur = (int)$_POST['id_demenageur'];
$note = (int)$_POST['note'];
$commentaire = $_POST['commentaire'];
$id_utilisateur = $_SESSION['user_id'];


$redirect_url = '../client/evaluation.php?id=' . $id_annonce;

// Récupérer l'ID client
$stmt_client = $mysqli->prepare("SELECT id_client FROM CLIENT WHERE id_utilisateur = ?");
$stmt_client->bind_param('i', $id_utilisateur);
$stmt_client->execute();
$id_client_connecte = $stmt_client->get_result()->fetch_assoc()['id_client'];
$stmt_client->close();



//  Vérifier que l'annonce appartient bien au client ET est terminée
$stmt_check = $mysqli->prepare("SELECT id_client FROM ANNONCE WHERE id_annonce = ? AND id_client = ? AND statut = 'terminee'");
$stmt_check->bind_param('ii', $id_annonce, $id_client_connecte);
$stmt_check->execute();
$result_check = $stmt_check->get_result();
if ($result_check->num_rows === 0) {
    $_SESSION['error_message'] = "Vous n'êtes pas autorisé à évaluer cette mission.";
    header('Location: ' . $redirect_url);
    exit;
}
$stmt_check->close();

//  Vérifier que ce déménageur a bien été 'acceptee' pour cette annonce
$stmt_prop = $mysqli->prepare("SELECT id_proposition FROM PROPOSITION WHERE id_annonce = ? AND id_demenageur = ? AND statut = 'acceptee'");
$stmt_prop->bind_param('ii', $id_annonce, $id_demenageur);
$stmt_prop->execute();
if ($stmt_prop->get_result()->num_rows === 0) {
    $_SESSION['error_message'] = "Ce déménageur n'a pas participé à cette mission.";
    header('Location: ' . $redirect_url);
    exit;
}
$stmt_prop->close();

// Vérifier qu'une évaluation n'existe pas déjà
$stmt_eval = $mysqli->prepare("SELECT id_evaluation FROM EVALUATION WHERE id_annonce = ? AND id_demenageur = ? AND id_client = ?");
$stmt_eval->bind_param('iii', $id_annonce, $id_demenageur, $id_client_connecte);
$stmt_eval->execute();
if ($stmt_eval->get_result()->num_rows > 0) {
    $_SESSION['error_message'] = "Vous avez déjà évalué ce déménageur pour cette mission.";
    header('Location: ' . $redirect_url);
    exit;
}
$stmt_eval->close();



$mysqli->begin_transaction();
try {
    //  Insérer la nouvelle évaluation
    $sql_insert = "INSERT INTO EVALUATION (id_annonce, id_client, id_demenageur, note, commentaire, date_evaluation)
                   VALUES (?, ?, ?, ?, ?, NOW())";
    $stmt_insert = $mysqli->prepare($sql_insert);
    $stmt_insert->bind_param('iiiis', $id_annonce, $id_client_connecte, $id_demenageur, $note, $commentaire);
    $stmt_insert->execute();
    $stmt_insert->close();

    //  Recalculer la note moyenne du déménageur
    
    $sql_avg = "UPDATE DEMENAGEUR d
                SET d.note_moyenne = (
                    SELECT AVG(e.note)
                    FROM EVALUATION e
                    WHERE e.id_demenageur = ?
                )
                WHERE d.id_demenageur = ?";
    $stmt_avg = $mysqli->prepare($sql_avg);
    $stmt_avg->bind_param('ii', $id_demenageur, $id_demenageur);
    $stmt_avg->execute();
    $stmt_avg->close();
    
    
    $mysqli->commit();
    $_SESSION['success_message'] = "Merci ! Votre évaluation a bien été enregistrée.";

} catch (mysqli_sql_exception $exception) {
    $mysqli->rollback();
    $_SESSION['error_message'] = "Erreur BDD : " . $exception->getMessage();
}


$mysqli->close();
header('Location: ' . $redirect_url);
exit;
?>