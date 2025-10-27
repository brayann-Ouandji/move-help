<?php
$titre_page = "Publier une nouvelle annonce";
include 'includes/header_client.php';
?>

<div class="container py-4">
    <h1 class="mb-4 text-center text-primary"><i class="bi bi-pencil-square"></i> Publier une nouvelle annonce</h1>
    <p class="text-muted text-center mb-5">Remplissez ce formulaire pour décrire votre besoin.</p>

    <form method="POST" action="traitement_annonce.php">
        <!-- Informations générales -->
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="bi bi-info-circle"></i> Informations générales</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Titre de l'annonce</label>
                    <input type="text" name="titre" class="form-control" placeholder="Ex: Déménagement T3, Transport canapé..." required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="4" placeholder="Donnez plus de détails..." required></textarea>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Date du déménagement</label>
                        <input type="date" name="date" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Heure de début</label>
                        <select name="heure" class="form-select" required>
                            <option value="">Sélectionnez une heure</option>
                            <?php
                            for ($h = 6; $h <= 20; $h++) {
                                $heure = sprintf("%02d:00", $h);
                                echo "<option value=\"$heure\">$heure</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lieu de départ -->
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="bi bi-geo-alt"></i> Lieu de départ</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Adresse</label>
                        <input type="text" name="adresse_depart" class="form-control" placeholder="Numéro et rue" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Code postal</label>
                        <input type="text" name="cp_depart" class="form-control" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Ville</label>
                        <input type="text" name="ville_depart" class="form-control" required>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bouton de soumission -->
        <div class="text-center">
            <button type="submit" class="btn btn-success px-5">
                <i class="bi bi-send"></i> Publier l'annonce
            </button>
        </div>
    </form>
</div>

<?php include 'includes/footer_client.php'; ?>
