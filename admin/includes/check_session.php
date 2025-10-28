<?php
// Démarrage de la session
session_start();


// ---- POUR TESTS ----

// une connexion d'ADMINISTRATEUR.

$_SESSION['user_id'] = 3; // ID admin simulé
$_SESSION['user_role'] = 'admin'; // Rôle admin simulé
$_SESSION['user_prenom'] = 'Admin'; // Prénom simulé
$_SESSION['user_nom'] = 'User';   // Nom simulé

// -------------------------


// --- VERIFICATION DE SECURITE ---

// 1. On vérifie si l'utilisateur n'est PAS connecté
if ( !isset($_SESSION['user_id']) ) {
    // Redirection vers la page de connexion
    // (Notez le ../ car on est dans un sous-dossier)
    header('Location: ../connexion.php');
    exit;
}

// 2. On vérifie que l'utilisateur a le bon rôle
if ( $_SESSION['user_role'] != 'admin' ) {
    // L'utilisateur est connecté mais n'est pas un admin
    // On le renvoie à l'accueil
    header('Location: ../index.php');
    exit;
}

// Si on arrive ici, tout est bon.
$user_id_connecte = $_SESSION['user_id'];
?>