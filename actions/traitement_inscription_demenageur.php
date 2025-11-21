<?php

session_start();

// Inclure la connexion BDD
require_once __DIR__ . '/../includes/db.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Récupérer les données du formulaire
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];
    
    // Données spécifiques au déménageur
    $ville = $_POST['ville'];
    $experience = $_POST['experience'];
    $vehicule_dispo = ($_POST['vehicule'] != 'non'); // Vrai (1) si un véhicule est sélectionné, Faux (0) sinon
    $type_vehicule = $vehicule_dispo ? $_POST['vehicule'] : NULL; // Type de véhicule, ou NULL si pas de véhicule
    
    $cgu = isset($_POST['cgu']);

 
    
    if (!$cgu) {
        $_SESSION['error_message'] = "Vous devez accepter les CGU.";
        header('Location: ../inscription-demenageur.php');
        exit;
    }

    if ($password !== $password_confirm) {
        $_SESSION['error_message'] = "Les mots de passe ne correspondent pas.";
        header('Location: ../inscription-demenageur.php');
        exit;
    }

    if (strlen($password) < 8) {
        $_SESSION['error_message'] = "Le mot de passe doit faire au moins 8 caractères.";
        header('Location: ../inscription-demenageur.php');
        exit;
    }

  
    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    // 6. Insérer les données
    
   
    $sql_check = "SELECT id_utilisateur FROM utilisateur WHERE email = ?";
    $stmt_check = $mysqli->prepare($sql_check);
    $stmt_check->bind_param('s', $email);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        $_SESSION['error_message'] = "Cette adresse email est déjà utilisée.";
        header('Location: ../inscription-demenageur.php');
        $stmt_check->close();
        $mysqli->close();
        exit;
    }
    $stmt_check->close();


    $mysqli->begin_transaction();

    try {
     
        $sql_user = "INSERT INTO utilisateur (nom, prenom, email, telephone, mot_de_passe, role) 
                     VALUES (?, ?, ?, ?, ?, 'demenageur')";
        
        $stmt_user = $mysqli->prepare($sql_user);
        $stmt_user->bind_param('sssss', $nom, $prenom, $email, $telephone, $password_hash);
        $stmt_user->execute();

      
        $id_utilisateur_cree = $mysqli->insert_id;


        $sql_dem = "INSERT INTO demenageur (id_utilisateur, ville_de_residence, annees_experience, vehicule_disponible, type_vehicule) 
                    VALUES (?, ?, ?, ?, ?)";
        
        $stmt_dem = $mysqli->prepare($sql_dem);
        // 'isiis' : integer, string, integer, integer (pour boolean), string
        $stmt_dem->bind_param('isiis', $id_utilisateur_cree, $ville, $experience, $vehicule_dispo, $type_vehicule);
        $stmt_dem->execute();
        
        
        $mysqli->commit();

       
        $_SESSION['user_id'] = $id_utilisateur_cree;
        $_SESSION['user_role'] = 'demenageur';
        $_SESSION['user_prenom'] = $prenom;
        $_SESSION['user_nom'] = $nom;
        
      
        $_SESSION['success_message'] = "Bienvenue ! Votre compte déménageur a été créé avec succès.";
        header('Location: ../demenageur/dashboard.php');
        exit;

    } catch (mysqli_sql_exception $exception) {
        
        $mysqli->rollback();
        
        $_SESSION['error_message'] = "Erreur lors de la création du compte : " . $exception->getMessage();
        header('Location: ../inscription-demenageur.php');
        exit;
    }

} else {
    // Accès non autorisé
    $_SESSION['error_message'] = "Accès non autorisé.";
    header('Location: ../inscription-demenageur.php');
    exit;
}
?>