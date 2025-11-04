<?phps
session_start();
require_once __DIR__ . '/../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'client') {
    $_SESSION['error_message'] = "Vous devez être connecté en tant que client pour poster une annonce.";
    header('Location: ../connexion.php');
    exit;
}
//vérifier que le formulaire a été envoyé via POST
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    $_SESSION['error_message'] = "Accès non autorisé.";
    header('Location: ../client/creer_annonce.php');
    exit;
}
//récupérer l'id du client connecté (diff de l' id_utilisateur)
$id_utilisateur = $_SESSION['user_id'];
$stmt = $mysqli->prepare("SELECT id_client FROM CLIENT WHERE id_utilisateur = ?");
$stmt->bind_param('i', $id_utilisateur);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    $_SESSION['error_message'] = "Erreur : Compte client non trouvé.";
    header('Location: ../client/creer_annonce.php');
    exit;
}
$id_client_connecte = $result->fetch_assoc()['id_client'];
$stmt->close();


$titre = $_POST['titre'];
$description = $_POST['description'];
$date_dem = $_POST['date_dem'];
$heure_dem = $_POST['heure_dem'];
$date_demenagement = $date_dem . ' ' . $heure_dem . ':00'; // Combine en format DATETIME

// Adresses
$ville_depart = $_POST['ville_depart'];
$cp_depart = $_POST['cp_depart'];
$type_logement_depart = $_POST['type_logement_depart'];
$etage_depart = $type_logement_depart == 'appartement' ? $_POST['etage_depart'] : NULL;
$ascenseur_depart = $type_logement_depart == 'appartement' ? $_POST['ascenseur_depart'] : 0;

$ville_arrivee = $_POST['ville_arrivee'];
$cp_arrivee = $_POST['cp_arrivee'];
$type_logement_arrivee = $_POST['type_logement_arrivee'];
// On suppose les mêmes champs pour l'arrivée (à ajouter au formulaire HTML si besoin)
$etage_arrivee = NULL; 
$ascenseur_arrivee = 0;

// Détails
$volume = $_POST['volume'];
$nb_demenageurs = $_POST['nb_demenageurs'];

// Démarrer la transaction (pour Annonce + Photos)
$mysqli->begin_transaction();

try {
    // Insérer l'annonce dans la table ANNONCE
    $sql_annonce = "INSERT INTO ANNONCE (id_client, titre, description, date_demenagement, 
                        ville_depart, code_postal_depart, type_logement_depart, etage_depart, ascenseur_depart,
                        ville_arrivee, code_postal_arrivee, type_logement_arrivee, etage_arrivee, ascenseur_arrivee,
                        volume_m3, nb_demenageur_souhaites, statut)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'publiee')";
    
    $stmt_annonce = $mysqli->prepare($sql_annonce);
    $stmt_annonce->bind_param('isssssisiisisiidi',
        $id_client_connecte, $titre, $description, $date_demenagement,
        $ville_depart, $cp_depart, $type_logement_depart, $etage_depart, $ascenseur_depart,
        $ville_arrivee, $cp_arrivee, $type_logement_arrivee, $etage_arrivee, $ascenseur_arrivee,
        $volume, $nb_demenageurs
    );
    $stmt_annonce->execute();

    // Récupérer l'ID de l'annonce qu'on vient de créer
    $id_annonce_creee = $mysqli->insert_id;
    $stmt_annonce->close();

//TRAITEMENT DES PHOTOS 
$upload_dir = __DIR__ . '/../uploads/';


    if (isset($_FILES['photos']) && !empty($_FILES['photos']['name'][0])) {
        $photos = $_FILES['photos'];
        $ordre = 1;

        // Boucler sur chaque fichier envoyé
        foreach ($photos['name'] as $index => $nom_fichier) {
            
            // Vérifier les erreurs d'upload
            if ($photos['error'][$index] === UPLOAD_ERR_OK) {
                
                $tmp_name = $photos['tmp_name'][$index];
                
                // Générer un nom de fichier unique
                $extension = strtolower(pathinfo($nom_fichier, PATHINFO_EXTENSION));
                $nom_unique = 'annonce_' . $id_annonce_creee . '_' . uniqid() . '.' . $extension;
                $chemin_destination = $upload_dir . $nom_unique;
                $chemin_bdd = 'uploads/' . $nom_unique; // Chemin à stocker en BDD

                // Déplacer le fichier
                if (move_uploaded_file($tmp_name, $chemin_destination)) {
                    
                    // 9. Insérer la photo dans la table PHOTO_ANNONCE
                    $sql_photo = "INSERT INTO PHOTO_ANNONCE (id_annonce, nom_fichier, chemin_fichier, ordre)
                                  VALUES (?, ?, ?, ?)";
                    $stmt_photo = $mysqli->prepare($sql_photo);
                    $stmt_photo->bind_param('issi', $id_annonce_creee, $nom_unique, $chemin_bdd, $ordre);
                    $stmt_photo->execute();
                    $stmt_photo->close();
                    $ordre++;
                }
            }
        }
    }

    // Valider
    $mysqli->commit();
    
    $_SESSION['success_message'] = "Votre annonce a été publiée avec succès !";
    header('Location: ../client/mes-annonces.php');
    exit;

} catch (mysqli_sql_exception $exception) {
    //Annuler en cas d'erreur
    $mysqli->rollback();
    
    $_SESSION['error_message'] = "Erreur lors de la création de l'annonce : " . $exception->getMessage();
    header('Location: ../client/creer-annonce.php');
    exit;
}

?>