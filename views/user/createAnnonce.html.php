<?php

use Core\Session\Session;
use App\AppRepoManager;

include(PATH_ROOT .'views/_templates/_navbar2.html.php');
?>

<main class="container mt-5 mb-5">
  <div class="card shadow-sm rounded-5">
    <div class="card-body">
      <div class="text-center mb-4">
        <h1 class="display-4 text-danger mb-5">Ajouter une annonce</h1>
      </div>

      <form action="/add-annonce-form" method="POST" enctype="multipart/form-data">
        <?php include(PATH_ROOT . 'views/_templates/_message.html.php') ?>

        <input type="hidden" name="user_id" value="<?= Session::get(Session::USER)->id ?>">

        <div class="form-group">
          <label for="image_path" class="h5">Images</label>
          <input type="file" class="form-control" id="image_path" name="image_path[]" multiple required>
        </div>

        <div class="form-group">
          <label for="title" class="h5">Le nom du logement</label>
          <input type="text" name="title" id="title" class="form-control">
        </div>

        <div class="form-group">
          <label class="h5">Type de logement</label>
          <?php foreach (AppRepoManager::getRm()->getTypeLogementRepository()->getAllTypeLogement() as $type) : ?>
            <div class="form-check">
              <input type="radio" name="type_logement_id" value="<?= $type->id ?>" class="form-check-input" id="type_<?= $type->id ?>">
              <label class="form-check-label" for="type_<?= $type->id ?>"><?= $type->label ?></label>
            </div>
          <?php endforeach ?>
        </div>

        <div class="form-group">
          <label class="h5">Les équipements</label>
          <div class="container-fluid">
            <?php
            // Regrouper les équipements par catégorie
            $equipements_par_categorie = [];
            foreach (AppRepoManager::getRm()->getEquipementsRepository()->getAllEquipements() as $equipement) {
              $equipements_par_categorie[$equipement->category][] = $equipement;
            }

            foreach ($equipements_par_categorie as $category => $equipements) :
              ?>
              <div class="row mt-3">
                <div class="col-12">
                  <h5><?= $category ?></h5>
                </div>
                <?php foreach ($equipements as $equipement) : ?>
                  <div class="col-md-3 col-sm-4 col-6">
                    <div class="form-check form-switch">
                      <input class="form-check-input inputbg" type="checkbox" name="equipements[]" value="<?= $equipement->id ?>" id="equipement_<?= $equipement->id ?>" style="">
                      <label class="form-check-label" for="equipement_<?= $equipement->id ?>">
                        <img class="icon-equipement" src="/assets/icons/<?= $equipement->image_path ?>" alt="icones<?= $equipement->label ?>" height="40" width="40">
                        <?= $equipement->label ?>
                      </label>
                    </div>
                  </div>
                <?php endforeach ?>
              </div>
            <?php endforeach ?>
          </div>
        </div>

        <div class="form-group">
          <label for="description" class="h5">Description :</label>
          <textarea name="description" id="description" class="form-control" rows="4"></textarea>
        </div>

        <div class="form-group">
          <label for="price_per_night" class="h5">Prix par nuit :</label>
          <div class="input-group">
            <input type="number" name="price_per_night" id="price_per_night" class="form-control">
            <div class="input-group-append">
              <span class="input-group-text">€</span>
            </div>
          </div>
        </div>

        <div class="form-group">
          <label for="nb_traveler" class="h5">Nombre de personnes :</label>
          <input type="number" name="nb_traveler" id="nb_traveler" class="form-control" value="1" min="1">
        </div>

        <div class="form-group">
          <label for="nb_room" class="h5">Nombre de chambres :</label>
          <input type="number" name="nb_room" id="nb_room" class="form-control" value="1" min="1">
        </div>

        <div class="form-group">
          <label for="nb_bed" class="h5">Nombre de lits :</label>
          <input type="number" name="nb_bed" id="nb_bed" class="form-control" value="1" min="1">
        </div>

        <div class="form-group">
          <label for="nb_bath" class="h5">Nombre de salles de bain :</label>
          <input type="number" name="nb_bath" id="nb_bath" class="form-control" value="1" min="1">
        </div>

        <div class="form-group">
          <label for="address" class="h5">Adresse :</label>
          <input type="text" name="address" id="address" class="form-control">
        </div>

        <div class="form-group">
          <label for="city" class="h5">Ville du logement :</label>
          <input type="text" name="city" id="city" class="form-control">
        </div>

        <div class="form-group">
          <label for="zipcode" class="h5">Code postal :</label>
          <input type="number" name="zipcode" id="zipcode" class="form-control">
        </div>

        <div class="form-group">
          <label for="country" class="h5">Pays :</label>
          <input type="text" name="country" id="country" class="form-control">
        </div>

        <div class="form-group">
          <label for="phone" class="h5">Téléphone :</label>
          <input type="text" name="phone" id="phone" class="form-control">
        </div>

        <div class="text-center mt-4">
          <button class="btn btn-danger btn-lg" type="submit">Je crée mon annonce</button>
        </div>
      </form>
    </div>
  </div>
</main>