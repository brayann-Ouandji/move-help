<?php
// Démarrage de la session (doit être au TOUT début)
session_start();





// VERIFICATION DE SECURITE ---

// On vérifie si l'utilisateur n'est PAS connecté
if ( !isset($_SESSION['user_id']) ) {
    // Redirection vers la page de connexion
    header('Location: ../connexion.php');
    exit; // Toujours mettre exit() après une redirection
}

//  On vérifie que l'utilisateur a le bon rôle
if ( $_SESSION['user_role'] != 'client' ) {
    // L'utilisateur est connecté mais n'est pas un client
    // On le redirige vers la page d'accueil publique
    header('Location: ../index.php');
    exit;
}


$user_id_connecte = $_SESSION['user_id'];
?>