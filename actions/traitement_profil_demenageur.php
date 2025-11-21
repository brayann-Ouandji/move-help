<?php
session_start();
require_once __DIR__ . '/../includes/db.php';

// SÉCURITÉ : Vérifier que l'utilisateur est connecté et est un déménageur
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'demenageur') {
    $_SESSION['error_message'] = "Accès non autorisé.";
    header('Location: ../connexion.php');
    exit;
}

// Vérifier que la requête est bien en POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error_message'] = "Méthode non autorisée.";
    header('Location: ../demenageur/profil.php');
    exit;
}

// RÉCUPÉRER LES DONNÉES DU FORMULAIRE
$id_utilisateur = $_SESSION['user_id'];
$nom           = trim($_POST['nom']);
$prenom        = trim($_POST['prenom']);
$email         = trim($_POST['email']);
$telephone     = trim($_POST['telephone']);
$adresse       = trim($_POST['adresse']);
$ville         = trim($_POST['ville']);
$code_postal   = trim($_POST['code_postal']);
$vehicule      = isset($_POST['vehicule']) ? $_POST['vehicule'] : null; // Assure que le champ existe

//  VALIDATION : Email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error_message'] = "Format d'email invalide.";
    header('Location: ../demenageur/profil.php');
    exit;
}

// VÉRIFIER L'UNICITÉ DE L'EMAIL
$stmt_check = $mysqli->prepare("SELECT id_utilisateur FROM utilisateur WHERE email = ? AND id_utilisateur != ?");
$stmt_check->bind_param('si', $email, $id_utilisateur);
$stmt_check->execute();
$stmt_check->store_result();

if ($stmt_check->num_rows > 0) {
    $_SESSION['error_message'] = "Cet email est déjà utilisé par un autre compte.";
    $stmt_check->close();
    $mysqli->close();
    header('Location: ../demenageur/profil.php');
    exit;
}
$stmt_check->close();

//  DÉBUT DE TRANSACTION
$mysqli->begin_transaction();

try {
    //  Mise à jour de la table UTILISATEUR
    $sql_user = "UPDATE utilisateur SET nom = ?, prenom = ?, email = ?, telephone = ? WHERE id_utilisateur = ?";
    $stmt_user = $mysqli->prepare($sql_user);
    $stmt_user->bind_param('ssssi', $nom, $prenom, $email, $telephone, $id_utilisateur);
    $stmt_user->execute();
    $stmt_user->close();

    //  Mise à jour de la table DEMENAGEUR
    $sql_demenageur = "UPDATE demenageur SET adress = ?, ville = ?, code_postal = ?, vehicule = ? WHERE id_utilisateur = ?";
    $stmt_demenageur = $mysqli->prepare($sql_demenageur);
    $stmt_demenageur->bind_param('ssssi', $adresse, $ville, $code_postal, $vehicule, $id_utilisateur);
    $stmt_demenageur->execute();
    $stmt_demenageur->close();

    // Valider la transaction
    $mysqli->commit();

    // Mettre à jour la session
    $_SESSION['user_nom'] = $nom;
    $_SESSION['user_prenom'] = $prenom;
    $_SESSION['success_message'] = "Profil mis à jour avec succès.";

} catch (mysqli_sql_exception $e) {
    $mysqli->rollback();
    $_SESSION['error_message'] = "Erreur lors de la mise à jour : " . $e->getMessage();
}

//  Redirection finale
$mysqli->close();
header('Location: ../demenageur/profil.php');
exit;
?>

