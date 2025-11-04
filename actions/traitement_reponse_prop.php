<?php
session_start();
require_once__DIR__ . '/../includes/database.php';

 //Vérifier si l'utilisateur est un client connecté
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'client') {
    $_SESSION['error_message'] = "Accès non autorisé.";
    header('Location: ../connexion.php');
    exit;
}

//  Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    $_SESSION['error_message'] = "Méthode non autorisée.";
    header('Location: ../client/dashboard.php');
    exit;
}

// Vérifier que les données nécessaires sont diospo
if (!isset($_POST['id_proposition']) || !isset($_POST['id_annonce']) || !isset($_POST['action'])) {
    $_SESSION['error_message'] = "Données manquantes.";
    header('Location: ../client/dashboard.php');
    exit;
}

// RÉCUP LES DONNÉES 

$id_proposition = (int)$_POST['id_proposition'];
$id_annonce = (int)$_POST['id_annonce'];
$action = $_POST['action']; // 'accepter' ou 'refuser'
$id_utilisateur = $_SESSION['user_id'];

// Définir l'URL de redirection en cas d'erreur ou de succès
$redirect_url = '../client/detail_annonce.php?id=' . $id_annonce;

// VÉRIFIER LES DROITS (TRÈS IMPORTANT)
//
// Récupérer l'ID client
$stmt_client = $mysqli->prepare("SELECT id_client FROM CLIENT WHERE id_utilisateur = ?");
$stmt_client->bind_param('i', $id_utilisateur);
$stmt_client->execute();
$id_client_connecte = $stmt_client->get_result()->fetch_assoc()['id_client'];
$stmt_client->close();

// Vérifier que l'annonce appartient bien à ce client
$stmt_check = $mysqli->prepare("SELECT id_client FROM ANNONCE WHERE id_annonce = ?");
$stmt_check->bind_param('i', $id_annonce);
$stmt_check->execute();
$annonce_owner = $stmt_check->get_result()->fetch_assoc();
$stmt_check->close();

if (!$annonce_owner || $annonce_owner['id_client'] != $id_client_connecte) {
    $_SESSION['error_message'] = "Vous n'êtes pas autorisé à modifier cette annonce.";
    header('Location: ' . $redirect_url);
    exit;
}


$mysqli->begin_transaction();

try {
    if ($action == 'accepter') {
        // ACTION : ACCEPTER 
        
        //  Mettre à jour la proposition à 'acceptee'
        $sql_prop = "UPDATE PROPOSITION SET statut = 'acceptee', date_reponse = NOW() WHERE id_proposition = ?";
        $stmt_prop = $mysqli->prepare($sql_prop);
        $stmt_prop->bind_param('i', $id_proposition);
        $stmt_prop->execute();
        $stmt_prop->close();

        // ettre à jour l'annonce à 'acceptee'
        // Cela verrouille l'annonce, elle n'apparaîtra plus dans les recherches
        $sql_annonce = "UPDATE ANNONCE SET statut = 'acceptee' WHERE id_annonce = ?";
        $stmt_annonce = $mysqli->prepare($sql_annonce);
        $stmt_annonce->bind_param('i', $id_annonce);
        $stmt_annonce->execute();
        $stmt_annonce->close();
        
        // Refuser toutes les autres propositions pour cette annonce(peut etre eccessif? a voir)
        $sql_refus = "UPDATE PROPOSITION SET statut = 'refusee', date_reponse = NOW() 
                      WHERE id_annonce = ? AND id_proposition != ?";
        $stmt_refus = $mysqli->prepare($sql_refus);
        $stmt_refus->bind_param('ii', $id_annonce, $id_proposition);
        $stmt_refus->execute();
        $stmt_refus->close();

        $_SESSION['success_message'] = "Proposition acceptée ! Le déménageur a été confirmé.";

    } elseif ($action == 'refuser') {
        // ACTION : REFUSER 
        
        // Mettre à jour la proposition à 'refusee'
        $sql_prop = "UPDATE PROPOSITION SET statut = 'refusee', date_reponse = NOW() WHERE id_proposition = ?";
        $stmt_prop = $mysqli->prepare($sql_prop);
        $stmt_prop->bind_param('i', $id_proposition);
        $stmt_prop->execute();
        $stmt_prop->close();
        
        $_SESSION['success_message'] = "Proposition refusée.";
    }

   
    $mysqli->commit();

} catch (mysqli_sql_exception $exception) {
    
    $mysqli->rollback();
    $_SESSION['error_message'] = "Erreur lors de l'opération : " . $exception->getMessage();
}

//  Rediriger vers la page de détail
$mysqli->close();
header('Location: ' . $redirect_url);
exit;
?>

