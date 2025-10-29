<?php
$titre_page = "Connexion";
include 'includes/header.php';
session_start();

if (isset($_SESSION['error_message'])) {
    // Afficher le message d'erreur
    echo '<div class="alert alert-danger" role="alert">' . htmlspecialchars($_SESSION['error_message']) . '</div>';
    // Supprimer le message
    unset($_SESSION['error_message']);
}
?>

<div class="row justify-content-center">
  <div class="col-md-6">
    <h2 class="text-center">Connexion</h2>
    <p class="text-center">Accédez à votre espace client ou déménageur.</p>

    <form action="actions/traitement_connexion.php" method="POST">
      
      <div class="mb-3">
       <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" required>
      </div>
      
      <div class="mb-3">
        <label for="password" class="form-label">Mot de passe</label>
        <input type="password" class="form-control" id="password" name="password" required>
      </div>
      
      <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" id="remember" name="remember">
        <label class="form-check-label" for="remember">Se souvenir de moi</label>
      </div>
      
      <button type="submit" class="btn btn-primary w-100">Se connecter</button>
    </form>
    
    <div class="mt-3 text-center">
      <p><a href="#">Mot de passe oublié ?</a></p> 
      <p>Pas encore de compte ? <a href="inscription-client.php">Inscrivez-vous</a>.</p>
    </div>
  </div>
</div>

<?php
include 'includes/footer.php';
?>