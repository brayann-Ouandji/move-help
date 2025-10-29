<?php
$titre_page = "Inscription Déménageur";
include 'includes/header.php';
session_start(); 

if (isset($_SESSION['error_message'])) {
    // Afficher le message d'erreur
    echo '<div class="alert alert-danger" role="alert">' . $_SESSION['error_message'] . '</div>';
    // Supprimer le message
    unset($_SESSION['error_message']);
}
?>

<div class="row justify-content-center">
  <div class="col-md-8">
    <h2 class="text-center">Devenir Déménageur</h2>
    <p class="text-center">Proposez vos services et gagnez un revenu complémentaire.</p>

    <form action="actions/traitement_inscription_demenageur.php" method="POST">
      
      <div class="row">
        <div class="col-md-6 mb-3">
          <label for="nom" class="form-label">Nom</label>
          <input type="text" class="form-control" id="nom" name="nom" required>
        </div>
        <div class="col-md-6 mb-3">
          <label for="prenom" class="form-label">Prénom</label>
          <input type="text" class="form-control" id="prenom" name="prenom" required>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label for="email" class="form-label">Email</label>
          <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="col-md-6 mb-3">
          <label for="telephone" class="form-label">Téléphone</label>
          <input type="tel" class="form-control" id="telephone" name="telephone" required>
        </div>
      </div>
      
      <div class="row">
        <div class="col-md-6 mb-3">
          <label for="password" class="form-label">Mot de passe</label>
          <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="col-md-6 mb-3">
          <label for="password_confirm" class="form-label">Confirmer le mot de passe</label>
          <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
        </div>
      </div>
      
      <div class="mb-3">
        <label for="ville" class="form-label">Ville de résidence</label> 
        <input type="text" class="form-control" id="ville" name="ville" required>
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label for="experience" class="form-label">Expérience (années)</label> 
          <input type="number" class="form-control" id="experience" name="experience" min="0" required> 
        </div>
        <div class="col-md-6 mb-3">
          <label for="vehicule" class="form-label">Véhicule disponible</label> 
          <select class="form-select" id="vehicule" name="vehicule">
            <option value="non">Non</option>
            <option value="oui_petit">Oui (Petit utilitaire)</option>
            <option value="oui_grand">Oui (Grand utilitaire)</option>
            <option value="oui_camion">Oui (Camion)</option>
          </select>
        </div>
      </div>
      
      <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" id="cgu" name="cgu" required>
        <label class="form-check-label" for="cgu">J'accepte les Conditions Générales d'Utilisation</label>
      </div>
      
      <button type="submit" class="btn btn-primary w-100">Créer mon compte Déménageur</button>
    </form>
    
    <div class="mt-3 text-center">
      <p>Déjà inscrit ? <a href="connexion.php">Se connecter</a>.</p>
    </div>
  </div>
</div>

<?php
include 'includes/footer.php';
?>