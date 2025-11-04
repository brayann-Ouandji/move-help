<?php


session_start();
require_once __DIR__ . '/../includes/database.php';

// SÉCURITÉ
if (!isset($_SESSION['user_id'])) { // Accessible par tous les rôles
    $_SESSION['error_message'] = "Accès non autorisé.";
    header('Location: ../connexion.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    $_SESSION['error_message'] = "Méthode non autorisée.";
    // Rediriger vers la page de profil de l'utilisateur (on doit connaître son rôle)
    $redirect_page = $_SESSION['user_role'] == 'client' ? 'client' : 'demenageur';
    header('Location: ../' . $redirect_page . '/profil.php');
    exit;
}

// RÉCUPÉRER LES DONNÉES
$id_utilisateur_connecte = $_SESSION['user_id'];
$old_pass = $_POST['old_pass'];
$new_pass = $_POST['new_pass'];
$new_pass_confirm = $_POST['new_pass_confirm'];

// Définir la page de redirection
$redirect_page = '../' . $_SESSION['user_role'] . '/profil.php';

// VALIDATION
if ($new_pass !== $new_pass_confirm) {
    $_SESSION['error_message'] = "Les nouveaux mots de passe ne correspondent pas.";
    header('Location: ' . $redirect_page);
    exit;
}
if (strlen($new_pass) < 8) {
    $_SESSION['error_message'] = "Le nouveau mot de passe doit faire au moins 8 caractères.";
    header('Location: ' . $redirect_page);
    exit;
}

// VÉRIFIER L'ANCIEN MOT DE PASSE 
$stmt = $mysqli->prepare("SELECT mot_de_passe FROM UTILISATEUR WHERE id_utilisateur = ?");
$stmt->bind_param('i', $id_utilisateur_connecte);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user || !password_verify($old_pass, $user['mot_de_passe'])) {
    $_SESSION['error_message'] = "L'ancien mot de passe est incorrect.";
    header('Location: ' . $redirect_page);
    $mysqli->close();
    exit;
}

// METTRE À JOUR LE MOT DE PASSE
$new_pass_hash = password_hash($new_pass, PASSWORD_BCRYPT);

$sql_update = "UPDATE UTILISATEUR SET mot_de_passe = ? WHERE id_utilisateur = ?";
$stmt_update = $mysqli->prepare($sql_update);
$stmt_update->bind_param('si', $new_pass_hash, $id_utilisateur_connecte);

if ($stmt_update->execute()) {
    $_SESSION['success_message'] = "Mot de passe mis à jour avec succès.";
} else {
    $_SESSION['error_message'] = "Erreur lors de la mise à jour du mot de passe.";
}

//REDIRIGER
$stmt_update->close();
$mysqli->close();
header('Location: ' . $redirect_page);
exit;
?>
