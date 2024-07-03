<?php

namespace App\Controller;

use App\AppRepoManager;
use Core\Controller\Controller;
use Core\Form\FormError;
use Core\Form\FormResult;
use Core\Form\FormSuccess;
use Core\Session\Session;
use Core\View\View;
use Laminas\Diactoros\ServerRequest;

class UserController extends Controller
{
  /*---------------------------- FUNCTION VIEW CREATION D'ANNONCE ------------------------*/
  /**
   * Méthode pour afficher le formulaire de création de Pizza Custom
   * @param int $id
   * @return void
   */
  public function createAnnonce(int $id): void
  {
    $view_data = [
      'form_result' => Session::get(Session::FORM_RESULT),
      'form_success' => Session::get(Session::FORM_SUCCESS)
    ];

    $view = new View('user/createAnnonce');

    $view->render($view_data);
  }

  /*---------------------------- FUNCTION VIEW DETAIL D'ANNONCE ------------------------*/
  public function listAnnonceCustom(int $id): void
  {
    //le controlleur doit récupérer le tableau de pizzas pour le donnée à la vue
    $view_data = [
      'logements' => AppRepoManager::getRm()->getLogementRepository()->getAnnonceByUser($id),
      'form_result' => Session::get(Session::FORM_RESULT),
      'form_success' => Session::get(Session::FORM_SUCCESS)
    ];

    // On doit récupérer les pizzas custom de l'utilisateur getPizzaByUser
    $view = new View('user/mesAnnonces');
    $view->render($view_data);
  }

  /*---------------------------- FUNCTION VIEW MODIFICATION D'ANNONCE ------------------------*/
  public function deleteAnnonce(int $id): void
  {
    $form_result = new FormResult();
    $user_id = Session::get(Session::USER)->id;

    // Appel de la méthode qui désactive la pizza
    $deletePizza = AppRepoManager::getRm()->getLogementRepository()->deleteAnnonce($id);

    // On vérife le retour de la méthode
    if (!$deletePizza) {
      $form_result->addError(new FormError('Erreur lors de la suppression de l\'annonce.'));
    } else {
      $form_result->addSuccess(new FormSuccess('Votre Annonce à bien été supprimée.'));
    }

    //si on a des erreur on les met en sessions
    if ($form_result->hasErrors()) {
      Session::set(Session::FORM_RESULT, $form_result);
      //on redirige sur la page List Page Custom Pizza
      self::redirect('/user/my-annonce/' . $user_id);
    }

    //si on a des success on les met en sessions
    if ($form_result->getSuccessMessage()) {
      Session::remove(Session::FORM_RESULT);
      Session::set(Session::FORM_SUCCESS, $form_result);
      //on redirige sur la page List Page Custom Pizza
      self::redirect('/user/my-annonce/' . $user_id);
    }
  }

  public function editAnnonceForm(int $id): void
  {
    $information_id = Session::get(Session::USER)->information_id;

    $view_data = [
      'listEquipements' => AppRepoManager::getRm()->getEquipementsRepository()->getAllEquipements(),
      'logement' => AppRepoManager::getRm()->getLogementRepository()->getAnnonceById($id)
    ];

    $view = new View('user/updateAnnonce');

    $view->render($view_data);
  }

  public function editUserForm(int $id): void
  {
    $user = AppRepoManager::getRm()->getUserRepository()->getUserById($id);

    $view_data = [
      'user' => $user,
      'information' => AppRepoManager::getRm()->getInformationRepository()->getInformationByUserId($user->information_id),
      'form_result' => Session::get(Session::FORM_RESULT),
      'form_success' => Session::get(Session::FORM_SUCCESS)
    ];

    $view = new View('user/updateUser');

    $view->render($view_data);
  }

  public function updateUserAdress(ServerRequest $request): void
  {
    $post_data = $request->getParsedBody();
    $form_result = new FormResult();
    $user_id = Session::get(Session::USER)->id;
    $information_id = Session::get(Session::USER)->information_id;


    // Vérification des champs requis
    $adress = $post_data['adress'] ?? '';
    $zip_code = $post_data['zip_code'] ?? '';
    $city = $post_data['city'] ?? '';
    $country = $post_data['country'] ?? '';
    $phone = $post_data['phone'] ?? '';

    if (empty($adress) || empty($zip_code) || empty($city) || empty($country) || empty($phone))  {
      $form_result->addError(new FormError('Veuillez remplir tous les champs requis.'));
    } else {

      // Mise à jour du nom de la pizza
      $data_user = [
        'id' => $information_id, // Assurez-vous que $user_id est défini correctement
        'adress' => htmlspecialchars(trim($adress)),
        'zip_code' => intval($zip_code),
        'city' => htmlspecialchars(trim($city)),
        'country' => htmlspecialchars(trim($country)),
        'phone' => htmlspecialchars(trim($phone))
      ];

      $updateUserInfo = AppRepoManager::getRm()->getInformationRepository()->updateUserInformation($data_user);

      if (!$updateUserInfo) {
        $form_result->addError(new FormError('Erreur lors de la modification de vos informations.'));
      } else {
        $form_result->addSuccess(new FormSuccess('Vos informations ont bien été modifiée.'));
      }
    }

    // Gestion des erreurs
    if ($form_result->hasErrors()) {
      Session::set(Session::FORM_RESULT, $form_result);
      self::redirect('/user/info/' . $user_id);
    }

    // Redirection vers la liste des pizzas en cas de succès
    if ($form_result->hasSuccess()) {
      Session::set(Session::FORM_SUCCESS, $form_result);
      Session::remove(Session::FORM_RESULT);
      self::redirect('/user/info/' . $user_id);
    }
  }
}