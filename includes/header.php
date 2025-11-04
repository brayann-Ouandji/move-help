<?php
session_start();


?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <title><?php echo isset($titre_page) ? $titre_page . ' - MOVE&HELP' : 'MOVE&HELP'; ?></title>
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  
  <link href="css/style.css" rel="stylesheet">
</head>
<body>

<header>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
      
      <a class="navbar-brand" href="index.php">
        <img src="image/logo.png" alt="MOVE&HELP Accueil" height="40" class="d-inline-block align-text-top">
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      
      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="index.php">Accueil</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#comment-ca-marche">Comment ca marche ?</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="connexion.php">Connexion</a>
          </li>
        </ul>
      <a href="inscription-client.php" class="btn btn-primary d-none d-lg-block">Inscription</a>
      </div>
    </div>
  </nav>
</header>

<main>