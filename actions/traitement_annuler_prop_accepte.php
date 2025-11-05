<?php
session_start();
require_once __DIR__ . '/../includes/db.php';

//  Vérifier que l'utilisateur est un déménageur connecté
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'demenageur') {
    $_SESSION['error_message'] = "Accès non autorisé.";
    header('Location: ../connexion.php');
    exit;
}

//  Vérifier que la requête est bien en POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error_message'] = "Méthode non autorisée.";
    header('Location: ../demenageur/dashboard.php');
    exit;
}

//  Vérifier que les données nécessaires sont présentes
if (!isset($_POST['id_proposition'], $_POST['id_annonce'])) {
    $_SESSION['error_message'] = "Données manquantes.";
    header('Location: ../demenageur/dashboard.php');
    exit;
}

// Récupérer les données
$id_proposition = (int)$_POST['id_proposition'];
$id_annonce     = (int)$_POST['id_annonce'];
$id_utilisateur = $_SESSION['user_id'];
$redirect_url   = '../demenageur/detail_annonce.php?id=' . $id_annonce;

//  Vérifier les droits du déménageur
$stmt_demenageur = $mysqli->prepare("SELECT id_demenageur FROM DEMENAGEUR WHERE id_utilisateur = ?");
$stmt_demenageur->bind_param('i', $id_utilisateur);
$stmt_demenageur->execute();
$result_demenageur = $stmt_demenageur->get_result();
$id_demenageur_connecte = $result_demenageur->fetch_assoc()['id_demenageur'] ?? null;
$stmt_demenageur->close();

if (!$id_demenageur_connecte) {
    $_SESSION['error_message'] = "Profil déménageur introuvable.";
    header('Location: ' . $redirect_url);
    exit;
}

// Vérifier que l'annonce appartient bien à ce déménageur
$stmt_check = $mysqli->prepare("SELECT id_demenageur FROM ANNONCE WHERE id_annonce = ?");
$stmt_check->bind_param('i', $id_annonce);
$stmt_check->execute();
$annonce_owner = $stmt_check->get_result()->fetch_assoc();
$stmt_check->close();

if (!$annonce_owner || $annonce_owner['id_demenageur'] != $id_demenageur_connecte) {
    $_SESSION['error_message'] = "Vous n'êtes pas autorisé à modifier cette annonce.";
    header('Location: ' . $redirect_url);
    exit;
}

//  Annuler la proposition acceptée
$mysqli->begin_transaction();

try {
    //  Remettre la proposition à 'en attente' (ou supprimer le statut)
    $stmt_prop = $mysqli->prepare("UPDATE PROPOSITION SET statut = 'en_attente', date_reponse = NULL WHERE id_proposition = ?");
    $stmt_prop->bind_param('i', $id_proposition);
    $stmt_prop->execute();
    $stmt_prop->close();

    // Remettre l'annonce à 'publiee'
    $stmt_annonce = $mysqli->prepare("UPDATE ANNONCE SET statut = 'publiee' WHERE id_annonce = ?");
    $stmt_annonce->bind_param('i', $id_annonce);
    $stmt_annonce->execute();
    $stmt_annonce->close();

    $_SESSION['success_message'] = "Proposition annulée et annonce réactivée.";

    $mysqli->commit();

} catch (mysqli_sql_exception $e) {
    $mysqli->rollback();
    $_SESSION['error_message'] = "Erreur lors de l'annulation : " . $e->getMessage();
}

// Redirection
$mysqli->close();
header('Location: ' . $redirect_url);
exit;
?>
