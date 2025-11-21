<?php


session_start();
require_once __DIR__ . '/../includes/db.php';

//SÉCURITÉ DE BASE
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'client') {
    $_SESSION['error_message'] = "Accès non autorisé.";
    header('Location: ../connexion.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] != 'POST' || !isset($_POST['id_proposition']) || !isset($_POST['id_annonce']) || !isset($_POST['action'])) {
    $_SESSION['error_message'] = "Données manquantes.";
    header('Location: ../client/dashboard.php');
    exit;
}

// RÉCUPÉRER LES DONNÉES
$id_proposition = (int)$_POST['id_proposition'];
$id_annonce = (int)$_POST['id_annonce'];
$action = $_POST['action']; // 'accepter' ou 'refuser'
$id_utilisateur = $_SESSION['user_id'];
$redirect_url = '../client/detail_annonce.php?id=' . $id_annonce;

// VÉRIFIER LES DROITS (TRÈS IMPORTANT)
$stmt_client = $mysqli->prepare("SELECT id_client FROM client WHERE id_utilisateur = ?");
$stmt_client->bind_param('i', $id_utilisateur);
$stmt_client->execute();
$id_client_connecte = $stmt_client->get_result()->fetch_assoc()['id_client'];
$stmt_client->close();

// Vérifier que l'annonce appartient bien à ce client ET récupérer le nombre de déménageurs requis
$stmt_check = $mysqli->prepare("SELECT id_client, nb_demenageur_souhaites FROM annonce WHERE id_annonce = ?");
$stmt_check->bind_param('i', $id_annonce);
$stmt_check->execute();
$result_check = $stmt_check->get_result();
$annonce_data = $result_check->fetch_assoc();
$stmt_check->close();

if (!$annonce_data || $annonce_data['id_client'] != $id_client_connecte) {
    $_SESSION['error_message'] = "Vous n'êtes pas autorisé à modifier cette annonce.";
    header('Location: ' . $redirect_url);
    exit;
}
$nb_demenageurs_requis = (int)$annonce_data['nb_demenageur_souhaites'];


$mysqli->begin_transaction();

try {
    if ($action == 'accepter') {
        //ACCEPTER 
        
        // Mettre  la proposition à 'acceptee'
        $sql_prop = "UPDATE proposition SET statut = 'acceptee', date_reponse = NOW() WHERE id_proposition = ?";
        $stmt_prop = $mysqli->prepare($sql_prop);
        $stmt_prop->bind_param('i', $id_proposition);
        $stmt_prop->execute();
        $stmt_prop->close();

        //  combien de déménageurs sont maintenant acceptés
        $stmt_count = $mysqli->prepare("SELECT COUNT(*) as total_acceptees FROM proposition WHERE id_annonce = ? AND statut = 'acceptee'");
        $stmt_count->bind_param('i', $id_annonce);
        $stmt_count->execute();
        $total_acceptees_maintenant = (int)$stmt_count->get_result()->fetch_assoc()['total_acceptees'];
        $stmt_count->close();

        //  Vérifier si le quota est atteint
        if ($total_acceptees_maintenant >= $nb_demenageurs_requis) {
            
            // QUOTA ATTEINT : On verrouille l'annonce et on refuse les autres
            
            // Mettre l'annonce à 'acceptee'
            $sql_annonce = "UPDATE annonce SET statut = 'acceptee' WHERE id_annonce = ?";
            $stmt_annonce = $mysqli->prepare($sql_annonce);
            $stmt_annonce->bind_param('i', $id_annonce);
            $stmt_annonce->execute();
            $stmt_annonce->close();
            
            //  Refuser toutes les autres propositions qui sont encore 'en_attente'
            $sql_refus = "UPDATE proposition SET statut = 'refusee', date_reponse = NOW() 
                          WHERE id_annonce = ? AND statut = 'en_attente'";
            $stmt_refus = $mysqli->prepare($sql_refus);
            $stmt_refus->bind_param('i', $id_annonce);
            $stmt_refus->execute();
            $stmt_refus->close();

            $_SESSION['success_message'] = "Déménageur accepté ! Le quota est atteint, l'annonce est maintenant verrouillée.";
            
        } else {
            // QUOTA NON ATTEINT : On informe le client
            $restants = $nb_demenageurs_requis - $total_acceptees_maintenant;
            $_SESSION['success_message'] = "Déménageur accepté ! Il vous reste " . $restants . " place(s) à valider.";
        }

    } elseif ($action == 'refuser') {
        //REFUSER
        
        $sql_prop = "UPDATE proposition SET statut = 'refusee', date_reponse = NOW() WHERE id_proposition = ?";
        $stmt_prop = $mysqli->prepare($sql_prop);
        $stmt_prop->bind_param('i', $id_proposition);
        $stmt_prop->execute();
        $stmt_prop->close();
        
        $_SESSION['success_message'] = "Proposition refusée.";
    }

    // Valider la transaction
    $mysqli->commit();

} catch (mysqli_sql_exception $exception) {
    // erreur
    $mysqli->rollback();
    $_SESSION['error_message'] = "Erreur lors de l'opération : " . $exception->getMessage();
}

$mysqli->close();
header('Location: ' . $redirect_url);
exit;
?>