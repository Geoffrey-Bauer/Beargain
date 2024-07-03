<div class="container">
  <div class="row justify-content-center mt-5">
    <div class="col-lg-8">
      <div class="card shadow-lg">
        <div class="card-body">
          <h1 class="card-title text-center mb-4">Modification du titre</h1>
          <form class="auth-form form-update" action="/update-title" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="logement_id" value="<?= $logement->id ?>">

            <div class="form-group">
              <label for="title" class="h5">Nom du logement</label>
              <input type="text" class="form-control" name="title" value="<?= $logement->title ?>">
            </div>

            <button type="submit" class="btn btn-primary btn-lg btn-block mt-4">Modifier le titre du logement</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
