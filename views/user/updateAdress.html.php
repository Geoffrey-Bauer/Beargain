<div class="container mt-4">
  <div class="row justify-content-center">
    <div class="col-lg-6">
      <div class="card shadow">
        <div class="card-body">
          <h5 class="card-title text-center mb-4">Modifier l'adresse du logement</h5>

          <!-- formulaire pour changer l'adresse du logement -->
          <form class="auth-form form-update" action="/update-adress" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="logement_id" value="<?= $logement->id ?>">

            <div class="form-group">
              <label for="adress" class="h5">Adresse</label>
              <input type="text" name="adress" class="form-control" value="<?= htmlspecialchars($logement->information->adress) ?>">
            </div>

            <div class="form-group">
              <label for="city" class="h5">Ville</label>
              <input type="text" name="city" class="form-control" value="<?= htmlspecialchars($logement->information->city) ?>">
            </div>

            <div class="form-group">
              <label for="zip_code" class="h5">Code Postal</label>
              <input type="number" name="zip_code" class="form-control" value="<?= $logement->information->zip_code ?>">
            </div>

            <div class="form-group">
              <label for="country" class="h5">Pays</label>
              <input type="text" name="country" class="form-control" value="<?= htmlspecialchars($logement->information->country) ?>">
            </div>

            <div class="form-group">
              <label for="phone" class="h5">Téléphone</label>
              <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($logement->information->phone) ?>">
            </div>

            <div class="text-center mt-4">
              <button class="btn btn-primary btn-lg" type="submit">Mettre à jour l'adresse</button>
            </div>
          </form>
          <!-- fin du formulaire -->

        </div>
      </div>
    </div>
  </div>
</div>
