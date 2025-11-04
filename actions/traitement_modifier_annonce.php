<?php
session_start();
require_once __DIR__ . '/../includes/db.php';


if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'client') {
    header('Location: ../connexion.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header('Location: ../client/dashboard.php');
    exit;
}
if (!isset($_POST['id_annonce']) || !is_numeric($_POST['id_annonce'])) {
    header('Location: ../client/dashboard.php');
    exit;
}


$id_annonce = (int)$_POST['id_annonce'];
$id_utilisateur = $_SESSION['user_id'];


$stmt_client = $mysqli->prepare("SELECT id_client FROM CLIENT WHERE id_utilisateur = ?");
$stmt_client->bind_param('i', $id_utilisateur);
$stmt_client->execute();
$id_client_connecte = $stmt_client->get_result()->fetch_assoc()['id_client'];
$stmt_client->close();

// URL de redirection
$redirect_url = '../client/modifier_annonces.php?id=' . $id_annonce;


$stmt_check = $mysqli->prepare("SELECT id_client FROM ANNONCE WHERE id_annonce = ?");
$stmt_check->bind_param('i', $id_annonce);
$stmt_check->execute();
$annonce_owner = $stmt_check->get_result()->fetch_assoc();
$stmt_check->close();

if (!$annonce_owner || $annonce_owner['id_client'] != $id_client_connecte) {
    $_SESSION['error_message'] = "Vous n'êtes pas autorisé à modifier cette annonce.";
    header('Location: ../client/mes_annonces.php');
    exit;
}


$titre = $_POST['titre'];
$description = $_POST['description'];
$date_dem = $_POST['date_dem'];
$heure_dem = $_POST['heure_dem'];
$date_demenagement = $date_dem . ' ' . $heure_dem . ':00';
$ville_depart = $_POST['ville_depart'];
$cp_depart = $_POST['cp_depart'];
$type_logement_depart = $_POST['type_logement_depart'];
$etage_depart = $type_logement_depart == 'appartement' ? $_POST['etage_depart'] : NULL;
$ascenseur_depart = $type_logement_depart == 'appartement' ? $_POST['ascenseur_depart'] : 0;
// ... (ajouter les champs _arrivee ici si vous les avez mis dans le form)
$volume = $_POST['volume'];
$nb_demenageurs = $_POST['nb_demenageurs'];

//EXÉCUTER UPDATE
try {
    $sql_update = "UPDATE ANNONCE SET 
                        titre = ?, description = ?, date_demenagement = ?,
                        ville_depart = ?, code_postal_depart = ?, type_logement_depart = ?, etage_depart = ?, ascenseur_depart = ?,
                        volume_m3 = ?, nb_demenageur_souhaites = ?, date_modification = NOW()
                    WHERE id_annonce = ? AND id_client = ?";
    
    $stmt_update = $mysqli->prepare($sql_update);
    // Les types : ssssssiidi ii (12 variables)
    $stmt_update->bind_param('ssssssiidiii',
        $titre, $description, $date_demenagement,
        $ville_depart, $cp_depart, $type_logement_depart, $etage_depart, $ascenseur_depart,
        $volume, $nb_demenageurs,
        $id_annonce, $id_client_connecte // pour le WHERE
    );
    
    $stmt_update->execute();
    $stmt_update->close();
    $mysqli->close();

    $_SESSION['success_message'] = "Annonce modifiée avec succès.";
    // Rediriger vers la page de détail
    header('Location: ../client/detail_annonce.php?id=' . $id_annonce);
    exit;

} catch (mysqli_sql_exception $exception) {
    $_SESSION['error_message'] = "Erreur lors de la mise à jour : " . $exception->getMessage();
    header('Location: ' . $redirect_url);
    exit;
}
?>