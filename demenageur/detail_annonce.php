<?php
$titre_page = "Détail de l'annonce";
include 'includes/header_demenageur.php';

// On simule la récupération de l'annonce
$annonce_id = isset($_GET['id']) ? $_GET['id'] : 1;
$annonce = [
    'titre' => 'Déménagement F3 Paris -> Lyon',
    'date_dem' => '30 Novembre 2025 à 09:00',
    'description' => 'Bonjour, je cherche 2 personnes pour m\'aider à déménager. Les gros meubles sont : 1 canapé, 1 machine à laver, 1 frigo, 2 lits.',
    'ville_depart' => 'Paris (75015)',
    'logement_depart' => 'Appartement, 3ème étage, AVEC ascenseur',
    'ville_arrivee' => 'Lyon (69003)',
    'logement_arrivee' => 'Maison, plain-pied',
    'volume' => 30,
    'nb_demenageurs' => 2
];
?>

<h1>Détail de l'annonce</h1>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3><?php echo $annonce['titre']; ?></h3>
            </div>
            <div class="card-body">
                <p><strong>Date:</strong> <?php echo $annonce['date_dem']; ?></p>
                <p><strong>Volume:</strong> <?php echo $annonce['volume']; ?> m³</p>
                <p><strong>Déménageurs souhaités:</strong> <?php echo $annonce['nb_demenageurs']; ?></p>
                <p><strong>Description:</strong><br> <?php echo $annonce['description']; ?></p>
                
                <hr>
                
                <div class="row">
                    <div class="col-md-6">
                        <h5>Lieu de Départ</h5>
                        <p><i class="bi bi-geo-alt-fill"></i> <?php echo $annonce['ville_depart']; ?></p>
                        <p><i class="bi bi-building"></i> <?php echo $annonce['logement_depart']; ?></p>
                    </div>
                    <div class="col-md-6">
                        <h5>Lieu d'Arrivée</h5>
                        <p><i class="bi bi-geo-alt-fill"></i> <?php echo $annonce['ville_arrivee']; ?></p>
                        <p><i class="bi bi-building"></i> <?php echo $annonce['logement_arrivee']; ?></p>
                    </div>
                </div>
                
                <hr>
                
                <h5>Photos</h5>
                <div class="row">
                    <div class="col-3"><img src="https://via.placeholder.com/150" class="img-fluid rounded" alt="photo 1"></div>
                    <div class="col-3"><img src="https://via.placeholder.com/150" class="img-fluid rounded" alt="photo 2"></div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4>Faire une proposition</h4>
            </div>
            <div class="card-body">
                <form action="../actions/traitement_proposition.php" method="POST">
                    <input type="hidden" name="id_annonce" value="<?php echo $annonce_id; ?>">
                    
                    <div class="mb-3">
                        <label for="prix" class="form-label">Votre prix (€)</label>
                        <input type="number" class="form-control" id="prix" name="prix" min="1" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Message au client (optionnel)</label>
                        <textarea class="form-control" id="message" name="message" rows="4" placeholder="Bonjour, je suis disponible et équipé..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-success w-100">
                        <i class="bi bi-send-fill"></i> Envoyer ma proposition
                    </button>
                </form>
                
                <hr>
                
                <div class="text-center">
                    <a href="#" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-question-circle"></i> Poser une question au client (Bonus)
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include 'includes/footer_demenageur.php';
?>
