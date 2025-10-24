<?php
// Définition du titre de la page
$titre_page = "Accueil";

// Inclusion de l'en-tête
include 'includes/header.php';
?>

<div class="container-fluid bg-light p-5 mb-5">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-md-6">
        <h1 class="display-4 fw-bold">TROUVEZ DES DEMENAGEURS PRES DE CHEZ VOUS</h1>
        <p class="fs-5 my-4">Publiez votre annonce gratuitement et recevez des propositions de déménageurs disponibles. Simple, rapide et économique !</p>
        <a href="inscription-client.php" class="btn btn-outline-dark btn-lg me-2">Je suis client</a>
        <a href="inscription-demenageur.php" class="btn btn-outline-dark btn-lg">Je suis déménageur</a>
      </div>
      <div class="col-md-6 text-center">
        <img src="image/demenagement.jpg" class="img-fluid rounded" alt="Illustration de déménagement">
      </div>
    </div>
  </div>
</div>

<div class="container"> <section id="comment-ca-marche" class="my-5 py-5 text-center">
    <h2 class="display-5 mb-5">Comment ça marche ?</h2>
    <div class="row">
      
      <div class="col-md-4">
        <i class="bi bi-pencil-square" style="font-size: 3rem; color: #ffc107;"></i>
        <h3 class="mt-3">ETAPE 1</h3>
        <p>Décrivez votre déménagement, date, lieux, volume et téléchargez des photos.</p>
      </div>
      
      <div class="col-md-4">
        <i class="bi bi-cash-coin" style="font-size: 3rem; color: #0dcaf0;"></i>
        <h3 class="mt-3">ETAPE 2</h3>
        <p>Les déménageurs intéressés vous proposent leurs tarifs et disponibilités.</p>
      </div>
      
      <div class="col-md-4">
        <i class="bi bi-patch-check" style="font-size: 3rem; color: #dc3545;"></i>
        <h3 class="mt-3">ETAPE 3</h3>
        <p>Sélectionnez les meilleurs déménageurs et organisez votre déménagement.</p>
      </div>
    </div>
  </section>

  <section class="recent-listings my-5 py-5 bg-light rounded">
    <h2 class="text-center display-5 mb-5">Déménagements récents</h2>
    <div class="row justify-content-center">
      <?php
      // SIMULATION de données (MISES A JOUR avec vos maquettes)
      $annonces_recentes = array(
        array(
          'titre' => 'Déménagement F3 avec meubles', 
          'trajet' => 'Paris 15ème → Versailles', 
          'volume' => '25 m³', 
          'nb_demenageurs' => '2 déménageurs recherchés',
          'date' => '15 Novembre 2025'
        ),
        array(
          'titre' => 'STUDIO ETUDIANT SANS ASCENSEUR', 
          'trajet' => 'Lyon 3ème → Villeurbanne', 
          'volume' => '12 m³', 
          'nb_demenageurs' => '1 déménageur recherché',
          'date' => '18 Novembre 2025'
        ),
        array(
          'titre' => 'MAISON DE 4 CHAMBRE AVEC GARAGE', 
          'trajet' => 'Marseille → Aix-en-Provence', 
          'volume' => '45 m³', 
          'nb_demenageurs' => '3 déménageurs recherchés',
          'date' => '30 Novembre 2025'
        ),
      );

      foreach ($annonces_recentes as $annonce) {
        echo '<div class="col-md-4 mb-3">';
        echo '  <div class="card h-100">'; // h-100 pour que les cartes aient la même hauteur
        echo '    <img src="image/principale.jpg" class="card-img-top" alt="Image déménagement">';
        echo '    <div class="card-body d-flex flex-column">';
        echo '      <h5 class="card-title fw-bold">' . htmlspecialchars($annonce['titre']) . '</h5>';
        echo '      <h6 class="card-subtitle mb-2 text-muted">' . htmlspecialchars($annonce['trajet']) . '</h6>';
        echo '      <p class="card-text mb-1">Volume : ' . htmlspecialchars($annonce['volume']) . '</p>';
        echo '      <p class="card-text">' . htmlspecialchars($annonce['nb_demenageurs']) . '</p>';
        
        // La date (sera stylisée en orange via CSS)
        // mt-auto pousse la date en bas de la carte
        echo '      <div class="mt-auto text-center">';
        echo '        <span class="date-badge">' . htmlspecialchars($annonce['date']) . '</span>';
        echo '      </div>';
        
        echo '    </div>';
        echo '  </div>';
        echo '</div>';
      }
      ?>
    </div>
  </section>
  
  <section class="why-choose-us my-5 py-5">
    <h2 class="text-center display-5 mb-5">Pourquoi choisir notre plateforme ?</h2>
    <div class="row">
      
      <div class="col-md-6 mb-4">
        <div class="benefit-box client-box p-4 rounded h-100">
          <h3>Pour les clients</h3>
          <ul class="list-unstyled mt-3">
            <li class="mb-2"><i class="bi bi-check-lg"></i> Publiez gratuitement vos annonces</li>
            <li class="mb-2"><i class="bi bi-check-lg"></i> Comparez plusieurs propositions</li>
            <li class="mb-2"><i class="bi bi-check-lg"></i> Choisissez les meilleurs prix</li>
            <li class="mb-2"><i class="bi bi-check-lg"></i> Échangez directement avec les déménageurs</li>
            <li class="mb-2"><i class="bi bi-check-lg"></i> Évaluez la qualité du service</li>
          </ul>
        </div>
      </div>
      
      <div class="col-md-6 mb-4">
        <div class="benefit-box mover-box p-4 rounded h-100">
          <h3>Pour les déménageurs</h3>
          <ul class="list-unstyled mt-3">
            <li class="mb-2"><i class="bi bi-check-lg"></i> Trouvez des missions près de chez vous</li>
            <li class="mb-2"><i class="bi bi-check-lg"></i> Proposez vos tarifs librement</li>
            <li class="mb-2"><i class="bi bi-check-lg"></i> Gérez votre planning facilement</li>
            <li class="mb-2"><i class="bi bi-check-lg"></i> Développez votre activité</li>
            <li class="mb-2"><i class="bi bi-check-lg"></i> Construisez votre réputation</li>
          </ul>
        </div>
      </div>
    </div>
  </section>

</div> <?php
// Inclusion du pied de page
include 'includes/footer.php';
?>