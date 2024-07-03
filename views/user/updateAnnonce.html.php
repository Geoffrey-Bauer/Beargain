<?php include(PATH_ROOT . 'views/_templates/_navbar2.html.php'); ?>

<div class="custom-container">
  <div class="row justify-content-center mt-5">
    <div class="col-lg-10">
      <div class="card shadow-lg">
        <div class="card-body">
          <h1 class="card-title text-center mb-4">Modification de votre Annonce</h1>

          <div class="row">
            <!-- Colonne pour les médias et le titre -->
            <div class="col-md-6">
              <!-- Inclusion pour les médias -->
              <?php require PATH_ROOT . 'views/user/updateMedia.html.php'; ?>

              <!-- Espacement entre médias et titre -->
              <div class="my-4"></div>

              <?php require PATH_ROOT . 'views/user/updateDetail.html.php'; ?>
            </div>

            <!-- Colonne pour la description, les détails, et l'adresse -->
            <div class="col-md-6">
              <!-- Inclusion pour le titre -->
              <?php require PATH_ROOT . 'views/user/updateTitle.html.php'; ?>

              <!-- Espacement entre description et détails -->
              <div class="my-4"></div>

              <!-- Inclusion pour la description -->
              <?php require PATH_ROOT . 'views/user/updateDescription.html.php'; ?>

              <!-- Espacement entre description et détails -->
              <div class="my-4"></div>

              <!-- Espacement entre détails et adresse -->
              <div class="my-4"></div>

              <!-- Inclusion pour l'adresse -->
              <?php require PATH_ROOT . 'views/user/updateAdress.html.php'; ?>
            </div>
          </div>

          <div class="row mt-4">
            <div class="col">
              <!-- Inclusion pour les équipements -->
              <?php require PATH_ROOT . 'views/user/updateEquipement.html.php'; ?>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>
