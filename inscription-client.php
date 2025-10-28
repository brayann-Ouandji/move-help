<?php
$titre_page = "Inscription Client";
include 'includes/header.php';
session_start();

if (isset($_SESSION['error_message'])) {
    // Afficher le message d'erreur dans une alerte Bootstrap
    echo '<div class="alert alert-danger" role="alert">' . htmlspecialchars($_SESSION['error_message']) . '</div>';
    // Supprimer le message pour ne pas l'afficher à nouveau
    unset($_SESSION['error_message']);
}

?>

<div class="row justify-content-center">
  <div class="col-md-8">
    <h2 class="text-center">Créer un compte Client</h2>
    <p class="text-center">Trouvez l'aide qu'il vous faut pour votre déménagement.</p>

    <form action="actions/traitement_inscription_client.php" method="POST">
      
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

      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" required>
      </div>
      
      <div class="mb-3">
        <label for="telephone" class="form-label">Téléphone</label>
        <input type="tel" class="form-control" id="telephone" name="telephone" required>
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
      
      <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" id="cgu" name="cgu" required>
        <label class="form-check-label" for="cgu">J'accepte les Conditions Générales d'Utilisation</label>
      </div>
      
      <button type="submit" class="btn btn-primary w-100">Créer mon compte</button>
    </form>
    
    <div class="mt-3 text-center">
      <p>Déjà inscrit ? <a href="connexion.php">Se connecter</a>.</p>
    </div>
  </div>
</div>

<?php
include 'includes/footer.php';
?>