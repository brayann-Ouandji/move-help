<?php
session_start();
require_once __DIR__ . '/../includes/db.php';

// Vérifier que l'utilisateur est un déménageur connecté
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'demenageur') {
    $_SESSION['error_message'] = "Accès non autorisé.";
    header('Location: ../connexion.php');
    exit;
}


//  Vérifier que le formulaire a été soumis en POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error_message'] = "Méthode non autorisée.";
    header('Location: ../demenageur/annonce.php');
    exit;
}

//  Vérifier que les données nécessaires sont disponibles
if (!isset($_POST['id_annonce']) || !isset($_POST['id_demenageur']) || !isset( $_POST['prix'])) {
    $_SESSION['error_message'] = "Données manquantes.";
    header('Location: ../demenageur/annonce.php');
    exit;
}

// Récupérer les données
$id_annonce = (int)$_POST['id_annonce'];
$id_demenageur     = (int)$_POST['id_demenageur'];
$prix         =(float) $_POST['prix']; 
$message = $_POST['message'];
$redirect_url   = '../demenageur/detail_annonce.php?id=' . $id_annonce;

//  Vérifier les droits
$id_utilisateur = $_SESSION ['user_id'];
$stmt_demenageur = $mysqli->prepare("SELECT id_demenageur FROM DEMENAGEUR WHERE id_utilisateur = ?");
$stmt_demenageur->bind_param('i', $id_utilisateur);
$stmt_demenageur->execute();
$result_demenageur = $stmt_demenageur->get_result();
$id_demenageur_connecte = $result_demenageur->fetch_assoc()['id_demenageur'] ?? null;
$stmt_demenageur->close();

if ($id_demenageur!=$id_demenageur_connecte) {
    $_SESSION['error_message'] = "Profil déménageur introuvable.";
    header('Location: ' . $redirect_url);
    exit;
}
// verifié si la proposition a deja été faites
$sql_prop = "SELECT id_proposition FROM PROPOSITION WHERE id_annonce  = ? and id_demenageur = ?";
$stmt_prop = $mysqli->prepare($sql_prop);
$stmt_prop->bind_param('ii',$id_annonce,$id_demenageur_connecte);
$stmt_prop->execute();
$stmt_prop->store_result();


if ($stmt_prop->num_rows>0) {
    $_SESSION['error_message'] = "Vous avez deja fait une proposition sur cette annonce.";
    header('Location: ' . $redirect_url);
    $stmt_prop->close();
    $mysqli->close();
    exit;
}
$stmt_prop->close();


try {
    $sql_insert = "INSERT INTO PROPOSITION (id_annonce, id_demenageur, prix_propose, message) VALUES (?, ?, ?, ?)";
        $stmt_insert = $mysqli->prepare($sql_insert);
        $stmt_insert-> bind_param('iids',$id_annonce,$id_demenageur_connecte,$prix,$message);
        if($stmt_insert->execute())
        {
            $_SESSION ['success_message']="proposition envoyée";
        }else{
            $_SESSION ['error_message']="erreur de l'envoie";
        }
        $stmt_insert->close();
        $mysqli->close();
        header('Location: ' . $redirect_url);
        exit;
    
    }catch (mysqli_sql_exception $exception) {
    $_SESSION['error_message'] = "Erreur lors de l'opération : " . $exception->getMessage();
    header('Location: ' . $redirect_url);
    exit;
}


?>
