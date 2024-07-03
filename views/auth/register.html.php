<?php if($auth::isAuth()) $auth::redirect('/') ?>
<main class="container-form">
  <div class="d-flex justify-content-center align-items-center mt-5 mb-5">
    <div class="wrapper">
      <h2>Inscription</h2>

      <!-- On importe notre template de gestion d'erreur et success -->
      <?php include(PATH_ROOT . 'views/_templates/_message.html.php')?>

      <form action="/register" method="post">
        <div class="input-box">
          <input type="text" placeholder="Nom" name="lastname">
        </div>
        <div class="input-box">
          <input type="text" placeholder="Prénom" name="firstname">
        </div>
        <div class="input-box">
          <input type="text" placeholder="Votre E-mail" name="email">
        </div>
        <div class="input-box">
          <input type="password" placeholder="Mot de passe" name="password">
        </div>
        <div class="policy">
          <input type="checkbox" required>
          <h3>J'accepte tous les termes et conditions</h3>
        </div>
        <div class="input-box button">
          <input type="Submit" value="S'inscrire">
        </div>
        <div class="text">
          <h3>Vous avez déjà un compte ? <a href="/connexion">Se connecter</a></h3>
        </div>
      </form>
    </div>
  </div>
</main>




