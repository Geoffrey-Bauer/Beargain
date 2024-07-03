<?php

namespace App\Controller;

use App\AppRepoManager;
use Core\Controller\Controller;
use Core\Form\FormError;
use Core\Form\FormResult;
use Core\Form\FormSuccess;
use Core\Session\Session;
use Core\View\View;
use DateTime;
use Laminas\Diactoros\ServerRequest;

class ReservationController extends Controller
{

  /*---------------------------- FUNCTION GENERATION NUMERO ORDER ------------------------*/
  /**
   * méthode qui permet de générer un numéro de commmande
   * @return string
   *
   */
  private function generateOrderNumber()
  {
    // je veux un numéro de commande du type : FACT2406_00001 par exemple
    $order_number = 1;
    $order = AppRepoManager::getRm()->getReservationsRepository()->findLastOrder();
    $order_number = str_pad($order + 1, 6, '0', STR_PAD_LEFT);
    $year = date('Y');
    $month = date('m');
    $day = date('d');
    $final = "ORDER'{$year}{$month}{$day}_{$order_number}";
    return $final;
  }

  /*---------------------------- FUNCTION RESERVATION ------------------------*/
  public function addReservation(ServerRequest $request) : void
  {
    // On réceptionne le formulaire de réservation
    $data_form = $request->getParsedBody();
    $form_result = new FormResult();
    $order_number = $this->generateOrderNumber();

    $date_start_night = new DateTime($data_form['date_start']);
    $date_end_night = new DateTime($data_form['date_end']);
    $date_start = $data_form['date_start'] ?? '' ;
    $date_end = $data_form['date_end'] ?? '';

    $logement_id = $data_form['logement_id'] ?? '';
    $user_id = $data_form['user_id'] ?? '';
    $nb_adult = $data_form['nb_adult'] ?? '';
    $nb_child = $data_form['nb_child'] ?? '';
    $nb_traveler = $data_form['nb_traveler'] ?? '';
    $user_logement = $data_form['user_logement'] ?? '';

    // Calcul des données pour le traitement de réservation
    $nb_traveler_sum = intval($nb_adult) + intval($nb_child);
    $interval = $date_start_night->diff($date_end_night); // Correction ici
    $night = $interval->days;
    $price = $data_form['price_per_night'] * $night;

    if (empty($date_start) || empty($date_end) || empty($nb_traveler)) {
      $form_result->addError(new FormError('Tous les champs sont obligatoires'));
    } elseif ($nb_adult > 15) {
      $form_result->addError(new FormError('Le nombre d\'adulte doit être inférieur à 15'));
    } elseif ($nb_adult < 1) {
      $form_result->addError(new FormError('Le nombre d\'adulte doit être supérieur à 1'));
    } elseif ($date_start == $date_end) {
      $form_result->addError(new FormError('La date de départ doit être différente de la date d\'arrivée'));
    } elseif ($date_start > $date_end) {
      $form_result->addError(new FormError('La date de d\'arrivé doit être supérieure à la date de départ'));
    } elseif ($user_id == $user_logement) {
      $form_result->addError(new FormError('Vous ne pouvez pas réserver votre propre logement'));
    } elseif ($nb_traveler_sum > $nb_traveler) {
      $form_result->addError(new FormError('Le nombre de voyageurs doit être inférieur au nombre de voyageurs maximum'));
    } else {
      $reservation_data = [
        'order_number' => $order_number,
        'date_start' => $date_start,
        'date_end' => $date_end,
        'logement_id' => intval($logement_id),
        'user_id' => intval($user_id),
        'nb_adult' => intval($nb_adult),
        'nb_child' => intval($nb_child),
        'price_total' => floatval($price)
      ];
      $reservation = AppRepoManager::getRm()->getReservationsRepository()->addReservation($reservation_data);
      if (!$reservation) {
        $form_result->addError(new FormError('La réservation n\'a pas pu être effectuée'));
      } else {
        $form_result->addSuccess(new FormSuccess('La réservation a bien été effectuée'));
      }
    }

    //si on a des erreurs, on les mets en sessions
    if ($form_result->hasErrors()) {
      Session::set(Session::FORM_RESULT, $form_result);
      //on redirige sur la page detail
      self::redirect('/annonce/' . $logement_id . "/#formreservation");
    }

    //on crée un message de succès
    if ($form_result->hasSuccess()) {
      //ici on supprime les messages erreurs des sessions
      Session::remove(Session::FORM_RESULT);
      //dans la session je crée une clé USER et je la stocke dans $user
      Session::set(Session::FORM_SUCCESS, $form_result);
      //on redirige vers l'accueil
      self::redirect('/annonce/' . $logement_id . "/#formreservation");
    }
  }


  public function orderReservationUser(int $id): void
  {
    $reservations = AppRepoManager::getRm()->getReservationsRepository()->findReservationByUser($id);
    $view_data = [
      'form_result' => Session::get(Session::FORM_RESULT),
      'form_success' => Session::get(Session::FORM_SUCCESS),
      'reservations' => $reservations
    ];


    $view = new View('user/orderReservation');
    $view->render($view_data);
  }

  public function OrderReservationLogementUser(int $id): void
  {
    $reservations = AppRepoManager::getRm()->getReservationsRepository()->findReservationsByLogementUserId($id);
    $view_data = [
      'form_result' => Session::get(Session::FORM_RESULT),
      'form_success' => Session::get(Session::FORM_SUCCESS),
      'reservations' => $reservations
    ];


    $view = new View('user/listReservation');
    $view->render($view_data);
  }

  public function deleteReservationUser(int $id): void
  {
    $form_result = new FormResult();
    $user_id = Session::get(Session::USER)->id;
		
    $deleteReservationUser = AppRepoManager::getRm()->getReservationsRepository()->deleteReservationUser($id);

    // On vérife le retour de la méthode
    if (!$deleteReservationUser) {
      $form_result->addError(new FormError('Erreur lors de la suppression de votre réservation.'));
    } else {
      $form_result->addSuccess(new FormSuccess('Votre réservation est bien annulée.'));
    }

    //si on a des erreur on les met en sessions
    if ($form_result->hasErrors()) {
      Session::set(Session::FORM_RESULT, $form_result);
      self::redirect('/user/reservation/' . $user_id);
    }

    //si on a des success on les met en sessions
    if ($form_result->getSuccessMessage()) {
      Session::remove(Session::FORM_RESULT);
      Session::set(Session::FORM_SUCCESS, $form_result);
      self::redirect('/user/reservation/' . $user_id);
    }
  }

  public function deleteReservationHote(int $id): void
  {
    $form_result = new FormResult();
    $user_id = Session::get(Session::USER)->id;
		
    $deleteReservationUser = AppRepoManager::getRm()->getReservationsRepository()->deleteReservationHote($id);

    // On vérife le retour de la méthode
    if (!$deleteReservationUser) {
      $form_result->addError(new FormError('Erreur lors de la suppression de votre réservation.'));
    } else {
      $form_result->addSuccess(new FormSuccess('Votre réservation est bien annulée.'));
    }

    //si on a des erreur on les met en sessions
    if ($form_result->hasErrors()) {
      Session::set(Session::FORM_RESULT, $form_result);
      self::redirect('/user/list-order/' . $user_id);
    }

    //si on a des success on les met en sessions
    if ($form_result->getSuccessMessage()) {
      Session::remove(Session::FORM_RESULT);
      Session::set(Session::FORM_SUCCESS, $form_result);
      self::redirect('/user/list-order/' . $user_id);
    }
  }

}