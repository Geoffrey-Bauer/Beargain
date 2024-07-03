
<div class="container-nav mt-5">
  <div class="d-flex justify-content-around align-items-center">
    <div class="nav-logo">
      <a href="/">
        <img src="/assets/images/logo.svg" alt="Logo Beargain" class="img-fluid">
      </a>
    </div>

    <div class="dropdown">
      <button class="btn btn-light dropdown btnlogin" type="button" data-bs-toggle="dropdown">
        <i class="bi bi-list"></i>
        <i class="bi bi-person-circle"></i>
      </button>

      <ul class="dropdown-menu rounded-4">
        <?php if ($auth::isAuth()) : ?>
          <li>
            <p class="dropdown-item li-custom">Bonjour, <?= \Core\Session\Session::get(\Core\Session\Session::USER)->lastname ?></p>
          </li>
          <li>
            <hr class="dropdown-divider">
          </li>
          <li>
            <a href="/user/info/<?= \Core\Session\Session::get(\Core\Session\Session::USER)->id ?>" class="dropdown-item li-custom">Mon Profil</a>
          </li>
          <li>
            <a href="/user/reservation/<?= \Core\Session\Session::get(\Core\Session\Session::USER)->id ?>" class="dropdown-item li-custom">Mes Réservations</a>
          </li>
          <li>
            <hr class="dropdown-divider">
          </li>
          <li>
            <a href="/user/my-annonce/<?= \Core\Session\Session::get(\Core\Session\Session::USER)->id ?>" class="dropdown-item li-custom">Mode Hôte </a>
          </li>
          <li>
            <a href="/logout" class="dropdown-item li-custom">Déconnexion</a>
          </li>
        <?php else : ?>
          <li>
            <a href="/connexion" class="dropdown-item li-custom">Connexion</a>
          </li>
          <li>
            <a href="/inscription" class="dropdown-item li-custom">Inscription</a>
          </li>
        <?php endif ?>
      </ul>
    </div>
  </div>
</div>