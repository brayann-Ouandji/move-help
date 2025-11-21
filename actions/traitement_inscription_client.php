<?php

session_start();


require_once __DIR__ . '/../includes/db.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];
    $cgu = isset($_POST['cgu']); // Vérifie si la case CGU est cochée

    

    if (!$cgu) {
        $_SESSION['error_message'] = "Vous devez accepter les CGU.";
        header('Location: ../inscription-client.php');
        exit;
    }

    // Vérifier que les mots de passe correspondent
    if ($password !== $password_confirm) {
        $_SESSION['error_message'] = "Les mots de passe ne correspondent pas.";
        header('Location: ../inscription-client.php');
        exit;
    }


    if (strlen($password) < 8) {
        $_SESSION['error_message'] = "Le mot de passe doit faire au moins 8 caractères.";
        header('Location: ../inscription-client.php');
        exit;
    }

    //  Crypter le mot de passe (TRES IMPORTANT)
   
    // 
    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    

    $sql_check = "SELECT id_utilisateur FROM utilisateur WHERE email = ?";
    $stmt_check = $mysqli->prepare($sql_check);
    $stmt_check->bind_param('s', $email);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        // L'email existe déjà
        $_SESSION['error_message'] = "Cette adresse email est déjà utilisée.";
        header('Location: ../inscription-client.php');
        $stmt_check->close();
        $mysqli->close();
        exit;
    }
    $stmt_check->close();


    $mysqli->begin_transaction();

    try {
        //  Insérer dans la table UTILISATEUR
        $sql_user = "INSERT INTO utilisateur (nom, prenom, email, telephone, mot_de_passe, role) 
                     VALUES (?, ?, ?, ?, ?, 'client')";
        
        $stmt_user = $mysqli->prepare($sql_user);
        // 'sssss' signifie 5 arguments de type String
        $stmt_user->bind_param('sssss', $nom, $prenom, $email, $telephone, $password_hash);
        $stmt_user->execute();

        //  Récupérer l'ID de l'utilisateur qu'on vient de créer
        $id_utilisateur_cree = $mysqli->insert_id;

        //  Insérer dans la table CLIENT
        $sql_client = "INSERT INTO client (id_utilisateur, ville) VALUES (?, ?)";
        // On met une ville vide par défaut, l'utilisateur la remplira dans son profil
        $ville_defaut = ""; 
        
        $stmt_client = $mysqli->prepare($sql_client);
        // 'is' signifie 1 argument Integer, 1 argument String
        $stmt_client->bind_param('is', $id_utilisateur_cree, $ville_defaut);
        $stmt_client->execute();
        
        
        $mysqli->commit();

        
        $_SESSION['user_id'] = $id_utilisateur_cree;
        $_SESSION['user_role'] = 'client';
        $_SESSION['user_prenom'] = $prenom;
        $_SESSION['user_nom'] = $nom;
        
        // Rediriger vers le tableau de bord client avec un message de succès
        $_SESSION['success_message'] = "Bienvenue ! Votre compte a été créé avec succès.";
        header('Location: ../client/dashboard.php');
        exit;

    } catch (mysqli_sql_exception $exception) {
        
        $mysqli->rollback();
        
        $_SESSION['error_message'] = "Erreur lors de la création du compte : " . $exception->getMessage();
        header('Location: ../inscription-client.php');
        exit;
    }

} else {
    // Si quelqu'un accède à ce fichier sans passer par le formulaire
    $_SESSION['error_message'] = "Accès non autorisé.";
    header('Location: ../inscription-client.php');
    exit;
}
?>