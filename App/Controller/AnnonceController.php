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

class AnnonceController extends Controller
{
  /*---------------------------- FUNCTION CREATION D'ANNONCE ------------------------*/
  /**
   * Méthode qui permet d'ajouter une annonce
   * @param ServerRequest $request
   * @return void
   */
  public function addAnnonceForm(ServerRequest $request): void
  {
    $data_form = $request->getParsedBody();
    $form_result = new FormResult();
    $public_path = 'public/assets/images/';

    // Récupération des données des fichiers téléchargés
    $file_data = $_FILES['image_path'];

    // Récupération des autres données du formulaire
    $title = $data_form['title'] ?? '';
    $user_id = $data_form['user_id'] ?? '';
    $typeLogementId = $data_form['type_logement_id'] ?? '';
    $equipements = $data_form['equipements'] ?? [];
    $description = $data_form['description'] ?? '';
    $price = $data_form['price_per_night'] ?? '';
    $nb_traveler = $data_form['nb_traveler'] ?? '';
    $nb_room = $data_form['nb_room'] ?? '';
    $nb_bath = $data_form['nb_bath'] ?? '';
    $nb_bed = $data_form['nb_bed'] ?? '';
    $adress = $data_form['adress'] ?? '';
    $city = $data_form['city'] ?? '';
    $zipcode = $data_form['zipcode'] ?? '';
    $country = $data_form['country'] ?? '';
    $phone = $data_form['phone'] ?? '';

    // Vérification des données
    if (empty($title) || empty($user_id) || empty($typeLogementId) || empty($price) || empty($city)) {
      $form_result->addError(new FormError('Tous les champs sont obligatoires'));
    } else {
      // Tableau pour stocker les noms de fichiers téléchargés
      $uploaded_files = [];

      // Boucle à travers chaque fichier téléchargé
      foreach ($file_data['tmp_name'] as $key => $tmp_name) {
        $image_name = $file_data['name'][$key];

        // Redéfinition d'un nom unique pour l'image
        $filename = uniqid() . '_' . $image_name;
        $imgPathPublic = PATH_ROOT . $public_path . $filename;

        // Déplacer l'image téléchargée vers le dossier public
        if (move_uploaded_file($tmp_name, $imgPathPublic)) {
          // Ajouter le nom de fichier téléchargé à votre tableau $uploaded_files
          $uploaded_files[] = $filename;
        } else {
          $form_result->addError(new FormError('Erreur lors du téléchargement de l\'image'));
        }
      }

      // Si des images ont été téléchargées avec succès
      if (!empty($uploaded_files)) {
        // Insertion des informations sur le logement
        $logement_information_data = [
          'adress' => $adress,
          'city' => htmlspecialchars(trim($city)),
          'zip_code' => intval($zipcode),
          'country' => htmlspecialchars(trim($country)),
          'phone' => htmlspecialchars(trim($phone))
        ];

        $logement_information = AppRepoManager::getRm()->getInformationRepository()->insertInformation($logement_information_data);

        if (is_null($logement_information)) {
          $form_result->addError(new FormError('Erreur lors de l\'ajout de l\'adresse'));
        } else {
          // Insertion des données sur le logement
          $logement_data = [
            'title' => htmlspecialchars(trim($title)),
            'description' => htmlspecialchars(trim($description)),
            'price_per_night' => floatval($price),
            'user_id' => intval($user_id),
            'nb_traveler' => intval($nb_traveler),
            'nb_room' => intval($nb_room),
            'nb_bed' => intval($nb_bed),
            'nb_bath' => intval($nb_bath),
            'information_id' => $logement_information,
            'type_logement_id' => intval($typeLogementId),
            'is_active' => 1
          ];

          $logement_id = AppRepoManager::getRm()->getLogementRepository()->addLogement($logement_data);

          if (is_null($logement_id)) {
            $form_result->addError(new FormError('Erreur lors de la création du logement'));
          } else {
            // Insertion des médias (images) dans la table Media
            foreach ($uploaded_files as $filename) {
              $media_data = [
                'label' => $title,
                'image_path' => $filename,
                'is_active' => 1,
                'logement_id' => $logement_id
              ];

              $media_id = AppRepoManager::getRm()->getMediaRepository()->insertMedia($media_data);

              if (is_null($media_id)) {
                $form_result->addError(new FormError('Erreur lors de l\'ajout de l\'image'));
                break; // Arrêter le traitement si une erreur se produit
              }
            }

            // Insertion des équipements du logement
            foreach ($equipements as $equipement) {
              $logement_equipement_data = [
                'logement_id' => intval($logement_id),
                'equipement_id' => intval($equipement)
              ];

              $logement_equipement = AppRepoManager::getRm()->getLogementEquipementRepository()->insertLogementEquipement($logement_equipement_data);

              if (!$logement_equipement) {
                $form_result->addError(new FormError('Erreur lors de l\'ajout des équipements'));
              } else {
                $form_result->addSuccess(new FormSuccess('Votre annonce est bien ajoutée.'));
              }
            }
          }
        }
      }
    }

    // Gestion des résultats
    if ($form_result->hasErrors()) {
      Session::set(Session::FORM_RESULT, $form_result);
      self::redirect('/user/create-annonce/' . $user_id);
    }

    if ($form_result->hasSuccess()) {
      Session::remove(Session::FORM_RESULT);
      Session::set(Session::FORM_SUCCESS, $form_result);
      self::redirect('/user/my-annonce/' . $user_id);
    }
  }

  /*---------------------------- FUNCTION DETAIL D'ANNONCE ------------------------*/
  /**
   * Méthode qui permet de renvoyer la vue détail d'une annonce par son id
   * @param int $id
   * @return void
   */
  public function getAnnonceById(int $id) : void
  {
    $user_id = Session::get(Session::USER)->id;
    $logement = AppRepoManager::getRm()->getLogementRepository()->getAnnonceById($id);
    $view_data = [
      'logement' => $logement,
      'user' => AppRepoManager::getRm()->getUserRepository()->getUserById($logement->user_id),
      'form_result' => Session::get(Session::FORM_RESULT),
      'form_success' => Session::get(Session::FORM_SUCCESS),
    ];
		

    $view = new View('home/annonce');

    $view->render($view_data);
  }

  /*---------------------------- FUNCTION VIEW UPDATE D'ANNONCE ------------------------*/
  public function editAnnonceEquipements(ServerRequest $request) : void
  {
    $post_data = $request->getParsedBody();
    $form_result = new FormResult();
    $user_id = Session::get(Session::USER)->id;

    // Validation des champs
    $id = $post_data['logement_id'] ?? '';
    $array_equipements = $post_data['equipements'] ?? [];

    // Validation des champs requis
    if (empty($id) || empty($array_equipements)) {
      $form_result->addError(new FormError('Veuillez remplir tous les champs'));
    } else {
      // Suppression des anciens ingrédients de la pizza
      $deleteLogementEquipement = AppRepoManager::getRm()->getLogementEquipementRepository()->deleteLogementEquipement($id);

      // Vérification de la suppression des anciens ingrédients
      if (!$deleteLogementEquipement) {
        $form_result->addError(new FormError('Erreur lors de la modification des équipements'));
      } else {
        // Boucle sur le tableau d'ingrédients
        foreach ($array_equipements as $equipement_id) {
          // Création du tableau de données
          $data_logement_equipements = [
            'logement_id' => intval($id),
            'equipement_id' => intval($equipement_id)
          ];

          // Insertion des nouveaux ingrédients de la pizza
          $logement_equipements = AppRepoManager::getRm()->getLogementEquipementRepository()->insertLogementEquipement($data_logement_equipements);

          // Vérification de l'insertion des ingrédients
          if (!$logement_equipements) {
            $form_result->addError(new FormError('Erreur lors de l\'insertion des équipements'));
          } else {
            $form_result->addSuccess(new FormSuccess('Votre annonce à bien été modifier'));
          }
        }
      }
    }

    // Gestion des erreurs
    if ($form_result->hasErrors()) {
      Session::set(Session::FORM_RESULT, $form_result);
      self::redirect('/user/my-annonce/update/' . $id);
    }

    // Redirection vers la liste des pizzas en cas de succès
    if ($form_result->hasSuccess()) {
      Session::set(Session::FORM_SUCCESS, $form_result);
      Session::remove(Session::FORM_RESULT);
      self::redirect('/user/my-annonce/' . $user_id);
    }
  }

  public function updateLogementTitle(ServerRequest $request): void
  {

    $post_data = $request->getParsedBody();
    $form_result = new FormResult();
    $user_id = Session::get(Session::USER)->id;

    // Vérification des champs requis
    $title = $post_data['title'] ?? '';
    $logement_id = $post_data['logement_id'] ?? '';

    if (empty($title) || empty($logement_id)) {
      $form_result->addError(new FormError('Veuillez remplir tous les champs requis.'));
    } else {

      // Mise à jour du nom de la pizza
      $data_logement = [
        'id' => intval($logement_id),
        'title' => htmlspecialchars(trim($title))
      ];

      $updateLogementTitle = AppRepoManager::getRm()->getLogementRepository()->updateLogementTitle($data_logement);

      if (!$updateLogementTitle) {
        $form_result->addError(new FormError('Erreur lors de la modification du nom de la pizza.'));
      } else {
        $form_result->addSuccess(new FormSuccess('Nom de la pizza modifié avec succès'));
      }
    }

    // Gestion des erreurs
    if ($form_result->hasErrors()) {
      Session::set(Session::FORM_RESULT, $form_result);
      self::redirect('/user/my-annonce/update/' . $logement_id);
    }

    // Redirection vers la liste des pizzas en cas de succès
    if ($form_result->hasSuccess()) {
      Session::set(Session::FORM_SUCCESS, $form_result);
      Session::remove(Session::FORM_RESULT);
      self::redirect('/user/my-annonce/' . $user_id);
    }
  }

  public function updateLogementDescription(ServerRequest $request): void
  {

    $post_data = $request->getParsedBody();
    $form_result = new FormResult();
    $user_id = Session::get(Session::USER)->id;

    // Vérification des champs requis
    $description = $post_data['description'] ?? '';
    $logement_id = $post_data['logement_id'] ?? '';

    if (empty($description) || empty($logement_id)) {
      $form_result->addError(new FormError('Veuillez remplir tous les champs requis.'));
    } else {

      // Mise à jour du nom de la pizza
      $data_logement = [
        'id' => intval($logement_id),
        'description' => htmlspecialchars(trim($description))
      ];

      $updateLogementTitle = AppRepoManager::getRm()->getLogementRepository()->updateLogementDescription($data_logement);

      if (!$updateLogementTitle) {
        $form_result->addError(new FormError('Erreur lors de la modification du nom de la pizza.'));
      } else {
        $form_result->addSuccess(new FormSuccess('Nom de la pizza modifié avec succès'));
      }
    }

    // Gestion des erreurs
    if ($form_result->hasErrors()) {
      Session::set(Session::FORM_RESULT, $form_result);
      self::redirect('/user/my-annonce/update/' . $logement_id);
    }

    // Redirection vers la liste des pizzas en cas de succès
    if ($form_result->hasSuccess()) {
      Session::set(Session::FORM_SUCCESS, $form_result);
      Session::remove(Session::FORM_RESULT);
      self::redirect('/user/my-annonce/' . $user_id);
    }
  }

  public function updateLogementDetail(ServerRequest $request): void
  {

    $post_data = $request->getParsedBody();
    $form_result = new FormResult();
    $user_id = Session::get(Session::USER)->id;

    // Vérification des champs requis
    $logement_id = $post_data['logement_id'] ?? '';
    $price_per_night = $post_data['price_per_night'] ?? '';
    $nb_room = $post_data['nb_room'] ?? '';
    $nb_bath = $post_data['nb_bath'] ?? '';
    $nb_bed = $post_data['nb_bed'] ?? '';
    $nb_traveler = $post_data['nb_traveler'] ?? '';

    if (empty($price_per_night) || empty($nb_room) || empty($nb_bath) || empty($nb_bed) || empty($nb_traveler) || empty($logement_id)) {
      $form_result->addError(new FormError('Veuillez remplir tous les champs requis.'));
    } else {

      // Mise à jour du nom de la pizza
      $data_logement = [
        'id' => intval($logement_id),
        'price_per_night' => floatval($price_per_night),
        'nb_room' => intval($nb_room),
        'nb_bath' => intval($nb_bath),
        'nb_bed' => intval($nb_bed),
        'nb_traveler' => intval($nb_traveler),
      ];

      $updateLogementDetail = AppRepoManager::getRm()->getLogementRepository()->updateLogementDetail($data_logement);

      if (!$updateLogementDetail) {
        $form_result->addError(new FormError('Erreur lors de la modification du nom de la pizza.'));
      } else {
        $form_result->addSuccess(new FormSuccess('Nom de la pizza modifié avec succès'));
      }
    }

    // Gestion des erreurs
    if ($form_result->hasErrors()) {
      Session::set(Session::FORM_RESULT, $form_result);
      self::redirect('/user/my-annonce/update/' . $logement_id);
    }

    // Redirection vers la liste des pizzas en cas de succès
    if ($form_result->hasSuccess()) {
      Session::set(Session::FORM_SUCCESS, $form_result);
      Session::remove(Session::FORM_RESULT);
      self::redirect('/user/my-annonce/' . $user_id);
    }
  }

  public function updateLogementAdress(ServerRequest $request): void
  {

    $post_data = $request->getParsedBody();
    $form_result = new FormResult();
    $user_id = Session::get(Session::USER)->id;

    // Vérification des champs requis
    $logement_id = $post_data['logement_id'] ?? '';
    $adress = $post_data['adress'] ?? '';
    $zip_code = $post_data['zip_code'] ?? '';
    $city = $post_data['city'] ?? '';
    $country = $post_data['country'] ?? '';
    $phone = $post_data['phone'] ?? '';

    if (empty($adress) || empty($zip_code) || empty($city) || empty($country) || empty($phone) || empty($logement_id)) {
      $form_result->addError(new FormError('Veuillez remplir tous les champs requis.'));
    } else {

      // Mise à jour du nom de la pizza
      $data_logement = [
        'id' => intval($logement_id),
        'adress' => htmlspecialchars(trim($adress)),
        'zip_code' => intval($zip_code),
        'city' => htmlspecialchars(trim($city)),
        'country' => htmlspecialchars(trim($country)),
        'phone' => htmlspecialchars(trim($phone))
      ];

      $updateLogementDetail = AppRepoManager::getRm()->getInformationRepository()->updateLogementAdress($data_logement);

      if (!$updateLogementDetail) {
        $form_result->addError(new FormError('Erreur lors de la modification du nom de la pizza.'));
      } else {
        $form_result->addSuccess(new FormSuccess('Nom de la pizza modifié avec succès'));
      }
    }

    // Gestion des erreurs
    if ($form_result->hasErrors()) {
      Session::set(Session::FORM_RESULT, $form_result);
      self::redirect('/user/my-annonce/update/' . $logement_id);
    }

    // Redirection vers la liste des pizzas en cas de succès
    if ($form_result->hasSuccess()) {
      Session::set(Session::FORM_SUCCESS, $form_result);
      Session::remove(Session::FORM_RESULT);
      self::redirect('/user/my-annonce/' . $user_id);
    }
  }

  public function updateLogementMedia(ServerRequest $request): void
  {
    $post_data = $request->getParsedBody();
    $file_data = $_FILES['image_path'];
    $form_result = new FormResult();
    $user_id = Session::get(Session::USER)->id;

    // Validation des champs
    $id = $post_data['logement_id'] ?? '';
    $label = $file_data['label'] ?? '';
    $tmp_path = $file_data['tmp_name'] ?? '';
    $image_name = $file_data['name'] ?? '';
    $public_path = 'public/assets/images/';

    // Validation du format de l'image
    $allowed_image_types = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
    if (!in_array($file_data['type'], $allowed_image_types)) {
      $form_result->addError(new FormError('Le format de l\'image n\'est pas valide'));
    } elseif (empty($id) || empty($image_name)) {
      // Vérification des autres champs requis
      $form_result->addError(new FormError('Veuillez remplir tous les champs'));
    } else {
      // Redéfinition d'un nom unique pour l'image
      $filename = uniqid() . '_' . $image_name;
      $imgPathPublic = PATH_ROOT . $public_path . $filename;

      // Construction des données de la pizza
      $data_logement = [
        'id' => intval($id),
        'label' => htmlspecialchars(trim($label)),
        'image_path' => htmlspecialchars(trim($filename))
      ];

      // Déplacement du fichier temporaire vers le dossier de destination
      if (move_uploaded_file($tmp_path, $imgPathPublic)) {
        // Appel du repository pour insérer dans la base de données
        $updateLogementMedia = AppRepoManager::getRm()->getMediaRepository()->updateLogementMedia($data_logement);

        // Vérification du succès de l'opération
        if (!$updateLogementMedia) {
          $form_result->addError(new FormError('Erreur lors de la modification de l\'image de la pizza'));
        } else {
          $form_result->addSuccess(new FormSuccess('Image de la pizza modifiée avec succès'));
        }
      } else {
        $form_result->addError(new FormError('Erreur lors de l\'upload de l\'image'));
      }
    }

    // Gestion des erreurs
    if ($form_result->hasErrors()) {
      Session::set(Session::FORM_RESULT, $form_result);
      self::redirect('/user/my-annonce/update/' . $user_id);
    }

    // Redirection vers la liste des pizzas en cas de succès
    if ($form_result->hasSuccess()) {
      Session::set(Session::FORM_SUCCESS, $form_result);
      Session::remove(Session::FORM_RESULT);
      self::redirect('/user/my-annonce/' . $user_id);
    }
  }

}
