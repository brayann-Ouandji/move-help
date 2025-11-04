<?php

// session_start();
// On inclut les paramètres
require_once __DIR__ . '/../config.php';

$mysqli = new mysqli($host, $login, $password, $dbname);

// Vérification de la connexion
if ($mysqli->connect_errno) {
    
    echo "Échec de la connexion à MySQL : " . $mysqli->connect_error . " (" . $mysqli->connect_errno.")";
    
    exit();
}


if (!$mysqli->set_charset("utf8")) {
    printf("Erreur lors du chargement du jeu de caractères utf8 : %s\n", $mysqli->error);
    exit();
}


?>