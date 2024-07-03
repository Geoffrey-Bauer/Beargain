<?php

namespace App;

use App\Controller\AuthController;
use App\Controller\FilterController;
use App\Controller\HomeController;
use App\Controller\AnnonceController;
use App\Controller\OrderController;
use App\Controller\PizzaController;
use App\Controller\ReservationController;
use App\Controller\UserController;
use Core\Database\DatabaseConfigInterface;
use MiladRahimi\PhpRouter\Exceptions\InvalidCallableException;
use MiladRahimi\PhpRouter\Exceptions\RouteNotFoundException;
use MiladRahimi\PhpRouter\Router;

class App implements DatabaseConfigInterface
{
  private static ?self $instance = null;
  //on crée une méthode public appelé au demarrage de l'appli dans index.php
  public static function getApp(): self
  {
    if (is_null(self::$instance)) {
      self::$instance = new self();
    }
    return self::$instance;
  }

  //on crée une propriété privée pour stocker le routeur
  private Router $router;
  //méthode qui récupère les infos du routeur
  public function getRouter()
  {
    return $this->router;
  }

  private function __construct()
  {
    //on crée une instance de Router
    $this->router = Router::create();
  }

  //on a 3 méthodes a définir
  // 1. méthode start pour activer le router
  public function start(): void
  {
    //on ouvre l'accès aux sessions
    session_start();
    //enregistrements des routes
    $this->registerRoutes();
    //démarrage du router
    $this->startRouter();
  }

  //2. méthode qui enregistre les routes
  private function registerRoutes(): void
  {
    //on va définir des patterns pour les routes
    $this->router->pattern('id', '[0-9]\d*'); //n'autorise que les chiffres
    $this->router->pattern('order_id', '[0-9]\d*'); //n'autorise que les chiffres

    $this->router->get('/', [HomeController::class, 'home'] );


    // PARTIE AUTH:
    // connexion
    $this->router->get('/connexion', [AuthController::class, 'loginForm']);
    $this->router->post('/login', [AuthController::class, 'login']);
    $this->router->get('/inscription', [AuthController::class, 'registerForm']);
    $this->router->post('/register', [AuthController::class, 'register']);
    $this->router->get('/logout', [AuthController::class, 'logout']);

    // PARTIE ANNONCE:
    $this->router->get('/annonce/{id}', [AnnonceController::class, 'getAnnonceById'] );

    // PARTIE FILTER
    $this->router->post('/search-filter', [FilterController::class, 'filterAnnonces'] );


    //PARTIE USER
    $this->router->get('/user/create-annonce/{id}', [UserController::class, 'createAnnonce']);
    $this->router->post('/add-annonce-form', [AnnonceController::class, 'addAnnonceForm']);
    $this->router->get('/user/my-annonce/{id}', [UserController::class, 'listAnnonceCustom']);
    $this->router->get('/user/my-annonce/delete/{id}', [UserController::class, 'deleteAnnonce']);
    $this->router->get('/user/info/{id}', [UserController::class, 'editUserForm']);
    $this->router->post('/update-user', [UserController::class, 'updateUserAdress']);

    // PARTIE MODIFICATION D'ANNONCE
    $this->router->get('/user/my-annonce/update/{id}', [UserController::class, 'editAnnonceForm']);
    $this->router->post('/update-equipements', [AnnonceController::class, 'editAnnonceEquipements']);
    $this->router->post('/update-title', [AnnonceController::class, 'updateLogementTitle']);
    $this->router->post('/update-description', [AnnonceController::class, 'updateLogementDescription']);
    $this->router->post('/update-detail', [AnnonceController::class, 'updateLogementDetail']);
    $this->router->post('/update-adress', [AnnonceController::class, 'updateLogementAdress']);
    $this->router->post('/update-media', [AnnonceController::class, 'updateLogementMedia']);

    // PARTIE RESERVATION
    $this->router->post('/order/reservation', [ReservationController::class, 'addReservation']);
    $this->router->get('/user/reservation/{id}', [ReservationController::class, 'orderReservationUser']);
    $this->router->get('/user/list-order/{id}', [ReservationController::class, 'OrderReservationLogementUser']);
    $this->router->get('/user/reservation/delete/{id}', [ReservationController::class, 'deleteReservationUser']);
    $this->router->get('/user/list-order/delete/{id}', [ReservationController::class, 'deleteReservationHote']);
  }

  //3. méthode qui démarre le router
  private function startRouter(): void
  {
    try {
      $this->router->dispatch();
    } catch (RouteNotFoundException $e) {
      echo $e;
    } catch (InvalidCallableException $e) {
      echo $e;
    }
  }

  public function getHost(): string
  {
    return DB_HOST;
  }

  public function getName(): string
  {
    return DB_NAME;
  }

  public function getUser(): string
  {
    return DB_USER;
  }

  public function getPass(): string
  {
    return DB_PASS;
  }
}
