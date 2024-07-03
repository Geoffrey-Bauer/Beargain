<div class="container">
  <div class="row justify-content-center mt-5">
    <div class="col-lg-8">
      <div class="card shadow-lg">
        <div class="card-body">
          <h1 class="card-title text-center mb-4">Modification des détails</h1>
          <form class="auth-form form-update" action="/update-detail" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="logement_id" value="<?= $logement->id ?>">

            <div class="form-group">
              <label for="price_per_night" class="h5">Prix par nuit</label>
              <div class="input-group">
                <input type="number" name="price_per_night" id="price_per_night" class="form-control" value="<?= $logement->price_per_night ?>">
                <div class="input-group-append">
                  <span class="input-group-text">€</span>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label for="nb_traveler" class="h5">Nombre de personnes max</label>
              <input type="number" name="nb_traveler" id="nb_traveler" class="form-control" value="<?= $logement->nb_traveler ?>">
            </div>

            <div class="form-group">
              <label for="nb_room" class="h5">Nombre de chambres max</label>
              <input type="number" name="nb_room" id="nb_room" class="form-control" value="<?= $logement->nb_room ?>">
            </div>

            <div class="form-group">
              <label for="nb_bed" class="h5">Nombre de lits</label>
              <input type="number" name="nb_bed" id="nb_bed" class="form-control" value="<?= $logement->nb_bed ?>">
            </div>

            <div class="form-group">
              <label for="nb_bath" class="h5">Nombre de salles de bain</label>
              <input type="number" name="nb_bath" id="nb_bath" class="form-control" value="<?= $logement->nb_bath ?>">
            </div>

            <button type="submit" class="btn btn-primary btn-lg btn-block mt-4">Modifier les détails du logement</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
