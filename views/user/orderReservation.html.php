<?php
use Core\Session\Session;

$user_id = Session::get(Session::USER)->id;
include(PATH_ROOT .'views/_templates/_navbar.html.php');

?>

<div class="container mt-5">
  <h1 class="text-center mb-4">Mes réservations</h1>
  <?php include(PATH_ROOT .'views/_templates/_message.html.php'); ?>
  <?php if (empty($reservations)): ?>
    <div class="d-flex justify-content-center">
      <div class="d-flex flex-row flex-wrap my-3 justify-content-center col-lg-10">
        <div class="alert alert-info" role="alert">
          Vous n'avez pas encore réservé de logement.
        </div>
      </div>
    </div>
  <?php else: ?>
    <div class="row justify-content-center">
      <?php foreach ($reservations as $row) : ?>
        <div class="col-md-6 col-lg-3 mb-4">
          <div class="card shadow-sm h-100 rounded-5">
            <div class="card-body d-flex flex-column">
              <h5 class="card-title">Numéro de commande: <?= $row->order_number ?></h5>
              <h6 class="card-subtitle mb-2 text-muted">
                <?= $row->logement->title ?>
                (<?= isset($row->logement->information->city) ? htmlspecialchars($row->logement->information->city) : 'Ville non disponible' ?>)
              </h6>
              <p class="card-text flex-grow-1">
                <strong>Date début:</strong> <?= $row->date_start ?><br>
                <strong>Date de fin:</strong> <?= $row->date_end ?><br>
                <strong>Prix:</strong> <?= $row->price_total ?> €
              </p>
              <a href="/user/reservation/delete/<?= $row->id ?>" class="btn btn-danger mt-auto">Annuler la réservation</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

