<?php include(PATH_ROOT .'views/_templates/_navbar2.html.php'); ?>

<div class="admin-container text-center">
  <h1 class="title title-detail">Mes Annonces</h1>
  <a class="call-action btn btn-primary" href="/user/create-annonce/<?= \Core\Session\Session::get(\Core\Session\Session::USER)->id ?>">
    Je créer une annonce
  </a>
	<?php include(PATH_ROOT . '/views/_templates/_message.html.php') ?>
</div>

<?php if(empty($logements)): ?>
  <div class="d-flex justify-content-center">
    <div class="d-flex flex-row flex-wrap my-3 justify-content-center col-lg-10">
      <div class="alert alert-info" role="alert">
        Vous n'avez pas encore créé d'annonce.
      </div>
    </div>
  </div>
<?php else: ?>
  <div class="d-flex justify-content-center">
    <div class="d-flex flex-row flex-wrap my-3 justify-content-center col-lg-10" >
      <?php foreach ($logements as $logement) : ?>
        <a href="/annonce/<?= $logement->id ?>">
          <div class="card m-2 rounded-top-5" style="width: 18rem;">
            <div id="carousel-<?= $logement->id ?>" class="carousel slide" data-ride="carousel">
              <ol class="carousel-indicators">
                <?php foreach ($logement->medias as $index => $media): ?>
                  <li data-target="#carousel-<?= $logement->id ?>" data-slide-to="<?= $index ?>" class="<?= $index === 0 ? 'active' : '' ?>"></li>
                <?php endforeach; ?>
              </ol>
              <div class="carousel-inner rounded-5" style="height: 200px;">
                <?php foreach ($logement->medias as $index => $media): ?>
                  <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                    <img src="/assets/images/<?= $media->image_path ?>" class="d-block w-100 img-fluid img-thumbnail rounded-5" alt="" style="height: 200px; object-fit: cover;">
                  </div>
                <?php endforeach; ?>
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
            <div class="card-body">
              <h3 class="card-title sub-title text-center"><?= $logement->title ?></h3>
              <div class="d-flex justify-content-center">
                <div>
                  <a onclick="return confirm('Voulez-vous vraiment supprimer votre annonce ?')" href="/user/my-annonce/delete/<?= $logement->id ?>" class="btn btn-danger">
                    <i class="bi bi-trash"></i>
                  </a>
                  <a onclick="return confirm('Voulez-vous vraiment modifier votre annonce ?')" href="/user/my-annonce/update/<?= $logement->id ?>" class="btn btn-warning">
                    <i class="bi bi-pencil"></i>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
<?php endif; ?>
