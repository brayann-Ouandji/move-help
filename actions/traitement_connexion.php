<?php

session_start();


require_once __DIR__ . '/../includes/db.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    
    $email = $_POST['email'];
    $password_saisi = $_POST['password'];

    // Valider les données (basique)
    if (empty($email) || empty($password_saisi)) {
        $_SESSION['error_message'] = "Veuillez remplir tous les champs.";
        header('Location: ../connexion.php');
        exit;
    }

    
    // On récupère toutes les infos dont on aura besoin pour la session
    $sql = "SELECT id_utilisateur, mot_de_passe, role, nom, prenom, statut
            FROM UTILISATEUR
            WHERE email = ?";
    
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    
    
    $resultat = $stmt->get_result();

    if ($resultat->num_rows == 1) {
        //  Un utilisateur a été trouvé
        $utilisateur = $resultat->fetch_assoc();
        
        //  VÉRIFIER LE MOT DE PASSE (le plus important)
        
        if (password_verify($password_saisi, $utilisateur['mot_de_passe'])) {
            if ($utilisateur['statut'] != 'actif') {
                 // L'utilisateur est trouvé, le mdp est bon, MAIS le compte est désactivé
                $_SESSION['error_message'] = "Votre compte est actuellement désactivé. Veuillez contacter un administrateur.";
                header('Location: ../connexion.php');
                exit;
            }
        
            $_SESSION['user_id'] = $utilisateur['id_utilisateur'];
            $_SESSION['user_role'] = $utilisateur['role'];
            $_SESSION['user_prenom'] = $utilisateur['prenom'];
            $_SESSION['user_nom'] = $utilisateur['nom'];
            

            //  Rediriger l'utilisateur vers son dashboard
            
            if ($utilisateur['role'] == 'client') {
                header('Location: ../client/dashboard.php');
                exit;
            } elseif ($utilisateur['role'] == 'demenageur') {
                header('Location: ../demenageur/dashboard.php');
                exit;
            } elseif ($utilisateur['role'] == 'admin') {
                header('Location: ../admin/dashboard.php');
                exit;
            } else {
                // Rôle inconnu, sécurité
                header('Location: ../index.php');
                exit;
            }

        } else {
            // Mot de passe incorrect
            $_SESSION['error_message'] = "Email ou mot de passe incorrect.";
            header('Location: ../connexion.php');
            exit;
        }

    } else {
        // Aucun utilisateur trouvé avec cet email
        $_SESSION['error_message'] = "Email ou mot de passe incorrect.";
        header('Location: ../connexion.php');
        exit;
    }

} else {
    // Accès non autorisé
    $_SESSION['error_message'] = "Accès non autorisé.";
    header('Location: ../connexion.php');
    exit;
}


?>