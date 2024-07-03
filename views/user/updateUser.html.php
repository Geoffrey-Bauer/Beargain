<?php include(PATH_ROOT . 'views/_templates/_navbar2.html.php'); ?>

<div class="container mt-5">
  <div class="card">
    <div class="card-header">
      <h2>Mettre à jour l'adresse de l'utilisateur</h2>
    </div>
    <div class="card-body">
      <?php include(PATH_ROOT . 'views/_templates/_message.html.php'); ?>
      <form class="auth-form form-update" action="/update-user" method="POST" enctype="multipart/form-data">
        <div class="form-group">
          <label for="adress">Adresse</label>
          <input type="text" class="form-control" id="adress" name="adress" value="<?= $information->adress ?>">
        </div>
        <div class="form-group">
          <label for="zip_code">Code Postal</label>
          <input type="text" class="form-control" id="zip_code" name="zip_code" value="<?= $information->zip_code ?>">
        </div>
        <div class="form-group">
          <label for="city">Ville</label>
          <input type="text" class="form-control" id="city" name="city" value="<?= $information->city ?>">
        </div>
        <div class="form-group">
          <label for="country">Pays</label>
          <input type="text" class="form-control" id="country" name="country" value="<?= $information->country ?>">
        </div>
        <div class="form-group">
          <label for="phone">Téléphone</label>
          <input type="text" class="form-control" id="phone" name="phone" value="<?= $information->phone ?>">
        </div>
        <button type="submit" class="btn btn-primary">Mettre à jour</button>
      </form>
    </div>
  </div>
</div>