<?php include(PATH_ROOT .'views/_templates/_navbar.html.php'); ?>

  <div class="container mt-5 mb-3">
    <div class="d-flex justify-content-center">
      <div class="w-100">
        <form class="rounded-4 p-3 shadow-sm" action="/search-filter" method="POST" style="background-color: white">
          <div class="row g-3 align-items-end">
            <div class="col-md">
              <label for="city" class="form-label">Ville</label>
              <input type="text" name="word" id="city" class="form-control">
            </div>
            <div class="col-md-auto">
              <button type="submit" class="btn btn-primary rounded w-100">
                <i class="bi bi-search"></i>
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>


<?php if (empty($logements)) : ?>
  <div class="container mt-5">
    <div class="alert alert-warning" role="alert">
      Aucun logement trouvé.
      <a href="/" class="alert-link">Revenir à la page d'accueil</a>.
    </div>
  </div>
<?php else: ?>


<div class="container mt-4">
  <h1 class="text-center m-5">Les locations disponibles</h1>
  <div class="row">
    <?php foreach ($logements as $logement) : ?>
      <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-3">
        <div class="card h-100 d-flex flex-column rounded-4">
          <div class="position-relative rounded-4" style="height: 200px; overflow: hidden;">
            <?php if(count($logement->medias) > 0): ?>
              <div id="carousel-<?= $logement->id ?>" class="carousel slide h-100" data-ride="carousel">
                <div class="carousel-inner h-100 rounded-15">
                  <?php foreach($logement->medias as $index => $media): ?>
                    <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                      <img src="/assets/images/<?= $media->image_path ?>" class="d-block w-100 h-100 rounded-3" alt="" style="object-fit: cover;">
                    </div>
                  <?php endforeach ?>
                </div>
                <a class="carousel-control-prev" href="#carousel-<?= $logement->id ?>" role="button" data-slide="prev">
                  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                  <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carousel-<?= $logement->id ?>" role="button" data-slide="next">
                  <span class="carousel-control-next-icon" aria-hidden="true"></span>
                  <span class="sr-only">Next</span>
                </a>
              </div>
              <span class="badge badge-primary position-absolute top-0 start-0 m-2" style="background-color: #fa796c">Superhôte</span>
            <?php endif ?>
          </div>
          <div class="card-body d-flex flex-column justify-content-end text-center p-2">
            <h5 class="card-title mt-2 mb-1"><?= $logement->title ?> <span class="text-primary"></span></h5>
            <p class="card-text mb-1"><small class="text-muted"><?= $logement->nb_room ?> Chambres</small></p>
            <div class="d-flex flex-column justify-content-center align-items-center mt-auto">
              <p class="price font-weight-bold mb-1"><?= $logement->price_per_night ?> € par nuit</p>
              <?php $path = $auth::isAuth() ? "/annonce/$logement->id" : '/connexion' ?>
              <a href="<?= $path ?>" class="btn btn-primary btn-sm w-50">Réserver</a>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach ?>
  </div>
</div>
<?php endif ?>