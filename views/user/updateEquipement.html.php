<div class="container">
  <div class="row justify-content-center mt-5">
    <div class="col-lg-8">
      <div class="card shadow-lg">
        <div class="card-body">
          <h1 class="card-title text-center mb-4">Modification des équipements</h1>

          <form class="auth-form form-update" action="/update-equipements" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="logement_id" value="<?= $logement->id ?>">

            <div class="row mb-4">
              <div class="col">
                <h5 class="mb-3">Les équipements</h5>
              </div>
            </div>

            <?php
            // Regrouper les équipements par catégorie
            use App\AppRepoManager;

            $equipements_par_categorie = [];
            foreach (AppRepoManager::getRm()->getEquipementsRepository()->getAllEquipements() as $equipement) {
              $equipements_par_categorie[$equipement->category][] = $equipement;
            }

            foreach ($equipements_par_categorie as $category => $equipements) : ?>
              <div class="row mb-4">
                <div class="col">
                  <h6><?= $category ?></h6>
                  <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                    <?php foreach ($equipements as $equipement) :
                      $est_coche = in_array($equipement, $logement->equipements) ? 'checked' : '';
                      ?>
                      <div class="col mb-3">
                        <div class="card h-100">
                          <div class="card-body text-center">
                            <img class="icon-equipement mb-2" src="/assets/icons/<?= $equipement->image_path ?>" alt="<?= $equipement->label ?>" height="25" width="25">
                            <div class="d-flex flex-column align-items-center">
                              <div class="form-check form-switch mt-2">
                                <input class="form-check-input inputbg" type="checkbox" name="equipements[]" value="<?= $equipement->id ?>" id="equipement_<?= $equipement->id ?>" <?= $est_coche ?>>
                              </div>
                              <label><?= $equipement->label ?></label>
                            </div>
                          </div>
                        </div>
                      </div>
                    <?php endforeach ?>
                  </div>
                </div>
              </div>
            <?php endforeach ?>


            <div class="row">
              <div class="col">
                <button type="submit" class="btn btn-primary btn-lg btn-block">Modifier les équipements</button>
              </div>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>
</div>
