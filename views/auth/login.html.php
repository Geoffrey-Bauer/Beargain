<?php if($auth::isAuth()) $auth::redirect('/') ?>
<main class="container-form">
  <!-- affichage des erreurs s'il y en a -->
  <div class="d-flex justify-content-center align-items-center mt-5 mb-5">
    <div class="wrapper">
      <h2>Connexion</h2>

      <!-- On importe notre template de gestion d'erreur et success -->
      <?php include(PATH_ROOT . 'views/_templates/_message.html.php')?>

      <form action="/login" method="post">
        <div class="input-box">
          <input type="text" placeholder="Votre E-mail" name="email">
        </div>
        <div class="input-box">
          <input type="password" placeholder="Mot de passe" name="password">
        </div>
        <div class="input-box button">
          <input type="Submit" value="Connexion">
        </div>
        <div class="text">
          <h3>Vous n'avez pas de compte ? <a href="/inscription">S'inscrire</a></h3>
        </div>
      </form>
    </div>
  </div>
</main>
