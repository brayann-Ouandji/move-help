<?php
$titre_page = "Créer une annonce";
include 'includes/header_client.php';
?>

<h1>Publier une nouvelle annonce</h1>
<p>Remplissez ce formulaire pour décrire votre besoin.</p>

<form action="../actions/traitement_creer_annonce.php" method="POST" enctype="multipart/form-data" class="card">
    <div class="card-body">

        <fieldset>
            <legend>Informations générales</legend>
            <div class="mb-3">
                <label for="titre" class="form-label">Titre de l'annonce</label>
                <input type="text" class="form-control" id="titre" name="titre" required placeholder="Ex: Déménagement T3, Transport canapé...">
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3" placeholder="Donnez plus de détails..."></textarea>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="date_dem" class="form-label">Date du déménagement</label>
                    <input type="date" class="form-control" id="date_dem" name="date_dem" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="heure_dem" class="form-label">Heure de début</label>
                    <input type="time" class="form-control" id="heure_dem" name="heure_dem" required>
                </div>
            </div>
        </fieldset>
        
        <hr>

        <fieldset>
            <legend>Lieu de Départ</legend>
            <div class="row">
                <div class="col-md-8 mb-3">
                    <label for="ville_depart" class="form-label">Ville de départ</label>
                    <input type="text" class="form-control" id="ville_depart" name="ville_depart" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="cp_depart" class="form-label">Code Postal</label>
                    <input type="text" class="form-control" id="cp_depart" name="cp_depart" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Type de logement (Départ)</label><br>
                <input type="radio" class="btn-check" name="type_logement_depart" id="maison_depart" value="maison" checked>
                <label class="btn btn-outline-secondary" for="maison_depart">Maison</label>
                
                <input type="radio" class="btn-check" name="type_logement_depart" id="appart_depart" value="appartement">
                <label class="btn btn-outline-secondary" for="appart_depart">Appartement</label>
            </div>
            <div class="row" id="details_appart_depart">
                <div class="col-md-6 mb-3">
                    <label for="etage_depart" class="form-label">Étage</label>
                    <input type="number" class="form-control" id="etage_depart" name="etage_depart" min="0">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Ascenseur ?</label><br>
                    <input type="radio" class="btn-check" name="ascenseur_depart" id="asc_oui_depart" value="1">
                    <label class="btn btn-outline-success" for="asc_oui_depart">Oui</label>
                    
                    <input type="radio" class="btn-check" name="ascenseur_depart" id="asc_non_depart" value="0" checked>
                    <label class="btn btn-outline-danger" for="asc_non_depart">Non</label>
                </div>
            </div>
        </fieldset>

        <hr>

        <fieldset>
            <legend>Lieu d'Arrivée</legend>
            <div class="row">
                <div class="col-md-8 mb-3">
                    <label for="ville_arrivee" class="form-label">Ville d'arrivée</label>
                    <input type="text" class="form-control" id="ville_arrivee" name="ville_arrivee" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="cp_arrivee" class="form-label">Code Postal</label>
                    <input type="text" class="form-control" id="cp_arrivee" name="cp_arrivee" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Type de logement (Arrivée)</label><br>
                <input type="radio" class="btn-check" name="type_logement_arrivee" id="maison_arrivee" value="maison" checked>
                <label class="btn btn-outline-secondary" for="maison_arrivee">Maison</label>
                
                <input type="radio" class="btn-check" name="type_logement_arrivee" id="appart_arrivee" value="appartement">
                <label class="btn btn-outline-secondary" for="appart_arrivee">Appartement</label>
            </div>
        </fieldset>
        
        <hr>

        <fieldset>
            <legend>Détails de la prestation</legend>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="volume" class="form-label">Volume estimé (en m³)</label>
                    <input type="number" class="form-control" id="volume" name="volume" min="1" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="nb_demenageurs" class="form-label">Nombre de déménageurs souhaités</label>
                    <input type="number" class="form-control" id="nb_demenageurs" name="nb_demenageurs" min="1" required>
                </div>
            </div>
            <div class="mb-3">
                <label for="photos" class="form-label">Photos (optionnel, max 5)</label>
                <input class="form-control" type="file" id="photos" name="photos[]" multiple accept="image/jpeg, image/png">
            </div>
        </fieldset>

    </div>
    <div class="card-footer text-center">
        <button type="submit" class="btn btn-success btn-lg">
            <i class="bi bi-send-fill"></i> Publier mon annonce
        </button>
    </div>
</form>

<?php
include 'includes/footer_client.php';
?>