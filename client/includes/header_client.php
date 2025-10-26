<?php
// On inclut le script de sécurité avant TOUT
include 'check_session.php';
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <title><?php echo isset($titre_page) ? $titre_page . ' - Espace Client' : 'Espace Client - MOVE&HELP'; ?></title>
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  
  <link href="../css/style.css" rel="stylesheet">
</head>
<body class="bg-light"> <header>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      
      <a class="navbar-brand" href="dashboard.php">
        <img src="../image/logo.png" alt="MOVE&HELP Accueil" height="40">
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navClient" aria-controls="navClient" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      
      <div class="collapse navbar-collapse" id="navClient">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link" href="dashboard.php">Tableau de bord</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="mes_annonces.php">Mes Annonces</a>
          </li>
          <li class="nav-item">
            <a class="nav-link btn btn-primary text-white" href="creer_annonce.php">
              <i class="bi bi-plus-circle-fill"></i> Créer une annonce
            </a>
          </li>
        </ul>
        
        <ul class="navbar-nav">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownUser" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="bi bi-person-circle"></i> 
              Bonjour, <?php echo $_SESSION['user_prenom']; ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownUser">
              <li><a class="dropdown-item" href="profil.php">Mon Profil</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="../deconnexion.php">Déconnexion</a></li>
            </ul>
          </li>
        </ul>
      </div>
      
    </div>
  </nav>
</header>

<main class="container my-4">