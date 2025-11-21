<?php

session_start();
require_once __DIR__ . '/../includes/db.php';

// SÉCURITÉ 
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'client') {
    $_SESSION['error_message'] = "Accès non autorisé.";
    header('Location: ../connexion.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    $_SESSION['error_message'] = "Méthode non autorisée.";
    header('Location: ../client/profil.php');
    exit;
}

// RÉCUPÉRER LES DONNÉES
$id_utilisateur_connecte = $_SESSION['user_id'];
$nom = $_POST['nom'];
$prenom = $_POST['prenom'];
$email = $_POST['email'];
$telephone = $_POST['telephone'];
$adresse = $_POST['adresse'];
$ville = $_POST['ville'];
$code_postal = $_POST['code_postal'];

// VALIDATION (Exemple : email)
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error_message'] = "Format d'email invalide.";
    header('Location: ../client/profil.php');
    exit;
}

// VÉRIFIER L'UNICITÉ DE L'EMAIL (s'il a changé) 
$stmt_check = $mysqli->prepare("SELECT id_utilisateur FROM utilisateur WHERE email = ? AND id_utilisateur != ?");
$stmt_check->bind_param('si', $email, $id_utilisateur_connecte);
$stmt_check->execute();
$stmt_check->store_result();
if ($stmt_check->num_rows > 0) {
    $_SESSION['error_message'] = "Cet email est déjà utilisé par un autre compte.";
    header('Location: ../client/profil.php');
    $stmt_check->close();
    $mysqli->close();
    exit;
}
$stmt_check->close();


$mysqli->begin_transaction();
try {
    //Mettre à jour la table UTILISATEUR
    $sql_user = "UPDATE utilisateur SET nom = ?, prenom = ?, email = ?, telephone = ? WHERE id_utilisateur = ?";
    $stmt_user = $mysqli->prepare($sql_user);
    $stmt_user->bind_param('ssssi', $nom, $prenom, $email, $telephone, $id_utilisateur_connecte);
    $stmt_user->execute();
    $stmt_user->close();

    //Mettre à jour la table CLIENT
    $sql_client = "UPDATE client SET adress = ?, ville = ?, code_postal = ? WHERE id_utilisateur = ?";
    $stmt_client = $mysqli->prepare($sql_client);
    $stmt_client->bind_param('sssi', $adresse, $ville, $code_postal, $id_utilisateur_connecte);
    $stmt_client->execute();
    $stmt_client->close();
    
    // Valider 
    $mysqli->commit();

    //Mettre à jour la session (pour que le "Bonjour Prénom" soit à jour)
    $_SESSION['user_nom'] = $nom;
    $_SESSION['user_prenom'] = $prenom;

    $_SESSION['success_message'] = "Profil mis à jour avec succès.";

} catch (mysqli_sql_exception $exception) {
    $mysqli->rollback();
    $_SESSION['error_message'] = "Erreur lors de la mise à jour : " . $exception->getMessage();
}

// REDIRIGER
$mysqli->close();
header('Location: ../client/profil.php');
exit;
?>
