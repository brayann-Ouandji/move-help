<?php
// Définition du titre de la page
$titre_page = "Accueil";

// Inclusion de l'en-tête
include 'includes/header.php';
include 'includes/db.php';

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
      
      // On récupère aussi 1 photo par annonce (la première)
      $sql = "SELECT
                a.titre,
                a.ville_depart,
                a.ville_arrivee,
                a.volume_m3,
                a.nb_demenageur_souhaites,
                a.date_demenagement,
                (SELECT p.nom_fichier FROM PHOTO_ANNONCE p WHERE p.id_annonce = a.id_annonce ORDER BY p.ordre ASC LIMIT 1) as photo_principale
              FROM ANNONCE a
              WHERE a.statut = 'publiee'
              ORDER BY a.date_creation DESC
              LIMIT 3";

     
      $resultat = $mysqli->query($sql);

      // Vérifier s'il y a des résultats
      if ($resultat && $resultat->num_rows > 0) {
        
      
          while ($annonce = $resultat->fetch_assoc()) {
            
            
            $date_obj = new DateTime($annonce['date_demenagement']);
            $date_formatee = $date_obj->format('d M Y'); // Ex: 15 Nov 2025

            // Définir une image par défaut si aucune n'est trouvée
            $image_path = 'image/default_annonce.jpg'; 
            if (!empty($annonce['photo_principale'])) {
                // Plus tard, on stockera le chemin complet dans la BDD
                
                $image_path = 'image/' . $annonce['photo_principale'];
            }

            
            echo '<div class="col-md-4 mb-3">';
            echo '  <div class="card h-100">';
            echo '    <img src="' . $image_path . '" class="card-img-top" alt="' . $annonce['titre'] . '">';
            echo '    <div class="card-body d-flex flex-column">';
            echo '      <h5 class="card-title fw-bold">' . $annonce['titre'] . '</h5>';
            echo '      <h6 class="card-subtitle mb-2 text-muted">' . $annonce['ville_depart'] . ' → ' . $annonce['ville_arrivee']. '</h6>';
            echo '      <p class="card-text mb-1">Volume : ' . $annonce['volume_m3'] . ' m³</p>';
            echo '      <p class="card-text">' . $annonce['nb_demenageur_souhaites'] . ' déménageur(s) recherché(s)</p>';
            echo '      <div class="mt-auto text-center">';
            echo '        <span class="date-badge">' . $date_formatee . '</span>';
            echo '      </div>';
            echo '    </div>';
            echo '  </div>';
            echo '</div>';
          }
      } else {
          
          echo '<div class="col-12"><p class="text-center text-muted">Aucune annonce récente disponible pour le moment.</p></div>';
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