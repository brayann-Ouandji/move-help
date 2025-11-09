<?php


session_start();
require_once __DIR__ . '/../includes/db.php';

//  Vérifier si l'utilisateur est un admin connecté
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    $_SESSION['error_message'] = "Accès non autorisé.";
    header('Location: ../connexion.php');
    exit;
}


if (!isset($_GET['id']) || !is_numeric($_GET['id']) || !isset($_GET['action'])) {
    $_SESSION['error_message'] = "Requête invalide.";
    header('Location: ../admin/utilisateurs.php');
    exit;
}

$id_user_a_modifier = (int)$_GET['id'];
$action = $_GET['action']; // 'actif' ou 'desactive'

//  l'admin ne peut pas se désactiver lui-même
if ($id_user_a_modifier == $_SESSION['user_id']) {
    $_SESSION['error_message'] = "Vous ne pouvez pas modifier votre propre statut.";
    header('Location: ../admin/utilisateurs.php');
    exit;
}


if ($action != 'actif' && $action != 'desactive') {
    $_SESSION['error_message'] = "Action non valide.";
    header('Location: ../admin/utilisateurs.php');
    exit;
}

//  METTRE À JOUR LE STATUT DE L'UTILISATEUR 
try {
    $sql_update = "UPDATE UTILISATEUR SET statut = ? WHERE id_utilisateur = ?";
    $stmt_update = $mysqli->prepare($sql_update);
    $stmt_update->bind_param('si', $action, $id_user_a_modifier);
    
    if ($stmt_update->execute()) {
        $_SESSION['success_message'] = "Le statut de l'utilisateur (ID: $id_user_a_modifier) a été mis à jour.";
    } else {
        $_SESSION['error_message'] = "Erreur lors de la mise à jour du statut.";
    }
    
    $stmt_update->close();
    $mysqli->close();

} catch (mysqli_sql_exception $exception) {
    $_SESSION['error_message'] = "Erreur BDD : " . $exception->getMessage();
}


header('Location: ../admin/utilisateurs.php');
exit;
?>