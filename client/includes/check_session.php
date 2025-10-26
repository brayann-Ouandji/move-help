<?php
// Démarrage de la session (doit être au TOUT début)
session_start();


// ---- POUR Les TESTS ----
// On simule une session utilisateur connectée


$_SESSION['user_id'] = 1;         // ID client simulé
$_SESSION['user_role'] = 'client';  // Rôle client simulé
$_SESSION['user_prenom'] = 'Brayann'; // Prénom simulé
$_SESSION['user_nom'] = 'OUANDJI';   // Nom simulé

// -------------------------


// VERIFICATION DE SECURITE ---

// 1. On vérifie si l'utilisateur n'est PAS connecté
if ( !isset($_SESSION['user_id']) ) {
    // Redirection vers la page de connexion
    header('Location: ../connexion.php');
    exit; // Toujours mettre exit() après une redirection
}

// 2. On vérifie que l'utilisateur a le bon rôle
if ( $_SESSION['user_role'] != 'client' ) {
    // L'utilisateur est connecté mais n'est pas un client
    // On le redirige vers la page d'accueil publique
    header('Location: ../index.php');
    exit;
}

// Si on arrive ici, c'est que tout est bon !
// La variable $user_id sera utile plus tard
$user_id_connecte = $_SESSION['user_id'];
?>