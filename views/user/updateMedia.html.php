<div class="container">
  <div class="row justify-content-center mt-5">
    <div class="col-lg-8">
      <div class="card shadow-lg">
        <div class="card-body">
          <h1 class="card-title text-center mb-4">Modification de votre image</h1>
          <?php foreach ($logement->medias as $media): ?>
          <form class="auth-form form-update" action="/update-media" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="logement_id" value="<?= $logement->id ?>">

            <div class="form-group">
              <label for="image_path" class="h5">Charger une image</label>
              <input type="file" class="form-control-file" name="image_path">
            </div>

            <div class="form-group">
              <label class="h5">Image actuelle</label>
              <div class="text-center">

                <img class="img-thumbnail admin-img-pizza-thumb" src="/assets/images/<?= $media->image_path ?>" alt="Image actuelle">

              </div>
            </div>

            <button type="submit" class="btn btn-primary btn-lg btn-block mt-4">Modifier l'image</button>
          </form>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</div>
