<?php


session_start();
require_once __DIR__ . '/../includes/database.php';

//  SÉCURITÉ
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_message'] = "Accès non autorisé.";
    header('Location: ../connexion.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    $_SESSION['error_message'] = "Méthode non autorisée.";
    header('Location: ../index.php');
    exit;
}

// Définir la page de redirection
$redirect_page = '../' . $_SESSION['user_role'] . '/profil.php';
$id_utilisateur_connecte = $_SESSION['user_id'];

// VÉRIFIER LE FICHIER ENVOYÉ 
if (isset($_FILES['photo_profil']) && $_FILES['photo_profil']['error'] === UPLOAD_ERR_OK) {

    $file = $_FILES['photo_profil'];
    $file_tmp_name = $file['tmp_name'];
    $file_name = $file['name'];
    $file_size = $file['size'];
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    //  Définir le dossier d'upload (différent de celui des annonces)
    $upload_dir = __DIR__ . '/../uploads/profiL/';
   
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    // Valider l'extension et la taille
    $allowed_exts = ['jpg', 'jpeg', 'png'];
    if (!in_array($file_ext, $allowed_exts)) {
        $_SESSION['error_message'] = "Format de fichier non autorisé (uniquement jpg, jpeg, png).";
        header('Location: ' . $redirect_page);
        exit;
    }
    if ($file_size > 5000000) { // 5 Mo
        $_SESSION['error_message'] = "Le fichier est trop volumineux (max 5 Mo).";
        header('Location: ' . $redirect_page);
        exit;
    }

    // TRAITER L'UPLOAD 
    
    // Générer un nom unique
    $new_file_name = 'profil_' . $id_utilisateur_connecte . '_' . uniqid() . '.' . $file_ext;
    $destination = $upload_dir . $new_file_name;
    $db_path = 'uploads/profil/' . $new_file_name; // Chemin pour la BDD

    if (move_uploaded_file($file_tmp_name, $destination)) {
        
        // Mettre à jour la BDD
        $sql = "UPDATE UTILISATEUR SET photo_profil = ? WHERE id_utilisateur = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param('si', $db_path, $id_utilisateur_connecte);
        
        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Photo de profil mise à jour.";
        } else {
            $_SESSION['error_message'] = "Erreur lors de la mise à jour de la base de données.";
        }
        $stmt->close();
    } else {
        $_SESSION['error_message'] = "Erreur lors du déplacement du fichier.";
    }

} else {
    $_SESSION['error_message'] = "Aucun fichier n'a été envoyé ou une erreur est survenue.";
}

// REDIRIGER
$mysqli->close();
header('Location: ' . $redirect_page);
exit;
?>
