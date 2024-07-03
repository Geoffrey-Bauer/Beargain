<?php
use Core\Session\Session;
include(PATH_ROOT . 'views/_templates/_navbar.html.php');
include(PATH_ROOT . 'views/_templates/_script.html.php');

if (!$auth::isAuth()) $auth::redirect('/login');
?>

<div class="container mt-5 rounded-5">
  <div class="card p-4 rounded-5">
    <div class="container mt-5">
      <div class="card p-4 rounded-5">
        <!-- Galerie d'images -->
        <div class="row rounded-5">
          <div class="col-md-8 mb-2 rounded-5 mt-2">
            <?php if (!empty($logement->medias)): ?>
              <img src="/assets/images/<?= $logement->medias[0]->image_path ?>" alt="" class="d-block w-100 rounded-5 shadow-lg" data-bs-toggle="modal" data-bs-target="#imageModal">
            <?php endif; ?>
          </div>
          <div class="col-md-4">
            <div class="row">
              <?php foreach ($logement->medias as $index => $media): ?>
                <?php if ($index !== 0): ?>
                  <div class="col-6 mb-1 mt-2">
                    <img src="/assets/images/<?= $media->image_path ?>" alt="" class="img-fluid rounded-5 shadow-lg thumbnail-img" data-bs-toggle="modal" data-bs-target="#imageModal">
                  </div>
                <?php endif; ?>
              <?php endforeach ?>
            </div>
          </div>
        </div>
        <!-- Bouton pour afficher toutes les photos -->
        <div class="row mt-3">
          <div class="col text-center">
            <button type="button" class="btn btn-outline-primary btn-lg" data-bs-toggle="modal" data-bs-target="#imageModal">
              Afficher toutes les photos
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Titre, prix, hôte et réservation -->
    <hr class="my-4" style="background-color: #e74c3c">
    <div class="row mt-4">
      <div class="col-md-8">
        <h1 class="property-title"><?= $logement->title ?></h1>
        <p class="price-per-night"><?= $logement->price_per_night ?> € par nuit</p>
        <p>
          <?= $logement->nb_traveler ?> Voyageurs <span> · </span>
          <?= $logement->nb_room ?> Chambres<span> · </span>
          <?= $logement->nb_bed ?> Lits<span> · </span>
          <?= $logement->nb_bath ?> Salle de bain
        </p>
        <hr class="my-4" style="background-color: #e74c3c">

        <h6 class="mb-3">Hôte : <span class="font-weight-light"><?= $user->lastname . ' ' . $user->firstname ?></span></h6>
        <hr class="my-4" style="background-color: #e74c3c">

        <h2>Description</h2>
        <p class="description"><?= $logement->description ?></p>
        <hr class="my-4" style="background-color: #e74c3c">

        <?php
        // Regrouper les équipements par catégorie
        $equipements_par_categorie = [];
        foreach ($logement->equipements as $equipement) {
          $equipements_par_categorie[$equipement->category][] = $equipement;
        }
        $total_categories = count($equipements_par_categorie);
        ?>

        <?php
        // Compteur pour suivre le nombre de catégories affichées
        $category_count = 0;

        foreach ($equipements_par_categorie as $category => $equipements) : ?>
          <div id="<?= strtolower(str_replace(' ', '_', $category)) ?>" class="mb-4">
            <h2 class="mb-3"><?= $category ?></h2>
            <ul class="list-unstyled">
              <?php
              $count = 0;
              foreach ($equipements as $equipement):
                if ($count < 5): // Limite à 5 labels
                  ?>
                  <li class="mb-2">
                    <img src="/assets/icons/<?= $equipement->image_path ?>" alt="<?= $equipement->label ?>" class="img-fluid rounded-circle me-2" style="width: 32px; height: 32px;">
                    <?= $equipement->label ?>
                  </li>
                  <?php
                  $count++;
                endif;
              endforeach;
              ?>
            </ul>
          </div>
          <?php
          // Incrémenter le compteur de catégories affichées
          $category_count++;

          // Limiter l'affichage à deux catégories directement dans l'annonce
          if ($category_count >= 2) {
            break;
          }
        endforeach; ?>

        <?php if ($total_categories > 2) : ?>
          <div>
            <button type="button" class="btn btn-link" data-bs-toggle="modal" data-bs-target="#allCategoriesModal" style="text-decoration: underline; color: #008489; font-size: 14px;">
              Voir toutes les catégories
            </button>
          </div>
        <?php endif; ?>
      </div>

      <div class="col-md-4">
        <div class="card shadow-4 rounded-5" style="background-color: #fff8f8">
          <div class="card-body">
            <p class="price-par-night" style="font-weight: bold;color: #e74c3c">
              <span id="nightPrice"><?= $logement->price_per_night ?></span> € / nuit
            </p>
            <form action="/order/reservation" method="POST" enctype="multipart/form-data" id="formreservation">
              <input type="hidden" name="logement_id" value="<?= htmlspecialchars($logement->id) ?>">
              <input type="hidden" name="user_logement" value="<?= htmlspecialchars($logement->user_id) ?>">
              <input type="hidden" name="user_id" value="<?= htmlspecialchars(Session::get(Session::USER)->id) ?>">
              <input type="hidden" name="price_per_night" value="<?= htmlspecialchars($logement->price_per_night) ?>">
              <input type="hidden" name="nb_traveler" value="<?= htmlspecialchars($logement->nb_traveler) ?>">

              <div class="mb-3">
                <label for="start_date" class="form-label">Date d'arrivée</label>
                <input type="date" min="<?= date("Y-m-d") ?>" name="date_start" class="form-control" id="start_date">
              </div>

              <div class="mb-3">
                <label for="end_date" class="form-label">Date de départ</label>
                <input type="date" min="<?= date("Y-m-d") ?>" name="date_end" class="form-control" id="end_date">
              </div>

              <div class="mb-3">
                <label for="nb_child" class="form-label">Nombre d'enfants</label>
                <input type="number" name="nb_child" class="form-control" min="0" id="nb_child">
              </div>

              <div class="mb-3">
                <label for="nb_adult" class="form-label">Nombre d'adultes</label>
                <input type="number" name="nb_adult" class="form-control" min="0" id="nb_adult">
              </div>

              <div class="mb-4">
                <p>Prix total : <span id="totalPrice"></span> €</p>
              </div>

              <div>
                <?php include(PATH_ROOT . 'views/_templates/_message.html.php') ?>
              </div>

              <button type="submit" class="btn btn-primary btn-block">Réserver</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal pour les images avec carousel -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content rounded-4">
      <div class="modal-header">
        <h5 class="modal-title" id="imageModalLabel">Photos du logement</h5>
        <button type="button" data-bs-dismiss="modal" aria-label="Close" class="btn text-danger">
          <i class="bi bi-x-circle-fill"></i>
        </button>
      </div>
      <div class="modal-body">
        <div id="imageCarousel" class="carousel slide" data-bs-ride="carousel">
          <div class="carousel-inner">
            <?php foreach ($logement->medias as $index => $media): ?>
              <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                <img src="/assets/images/<?= $media->image_path ?>" alt="" class="d-block w-100 rounded-5">
              </div>
            <?php endforeach ?>
          </div>
          <a class="carousel-control-prev" href="#imageCarousel" role="button" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Précédent</span>
          </a>
          <a class="carousel-control-next" href="#imageCarousel" role="button" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Suivant</span>
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal pour afficher toutes les catégories et leurs équipements -->
<div class="modal fade" id="allCategoriesModal" tabindex="-1" aria-labelledby="allCategoriesModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content rounded-5">
      <div class="modal-header">
        <h5 class="modal-title" id="allCategoriesModalLabel">Toutes les catégories et leurs équipements</h5>
        <button type="button" data-bs-dismiss="modal" aria-label="Close" class="btn text-danger">
          <i class="bi bi-x-circle-fill"></i>
        </button>
      </div>
      <div class="modal-body">
        <?php foreach ($equipements_par_categorie as $category => $equipements) : ?>
          <h2><?= $category ?></h2>
          <ul class="list-unstyled">
            <?php foreach ($equipements as $equipement) : ?>
              <li class="mb-2">
                <img src="/assets/icons/<?= $equipement->image_path ?>" alt="<?= $equipement->label ?>" class="img-fluid rounded-circle me-2" style="width: 32px; height: 32px;">
                <?= $equipement->label ?>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php endforeach; ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
      </div>
    </div>
  </div>
</div>
