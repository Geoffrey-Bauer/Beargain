<?php

namespace App\Repository;

use App\AppRepoManager;
use App\Model\Information;
use App\Model\Logement;
use App\Model\Media;
use Core\Repository\Repository;

class LogementRepository extends Repository
{
  public function getTableName() : string
  {
    return "logement";
  }

  /*---------------------------- FUNCTION SQL RECUP ALL ANNONCES ------------------------*/
  /**
   * méthode qui permet de récupérer toutes les annonces
   * @return array
   */
  public function getAllLogement() : array
  {
    //on déclare un tableau vide
    $array_result = [];

    //on crée la requête SQL
    $q = sprintf(
      'SELECT * FROM %s WHERE `is_active` = 1',
      $this->getTableName() //correspond au %1$s
    );

    //on peut directement executer la requete
    $stmt = $this->pdo->query($q);
    //on vérifie que la requete est bien executée
    if (!$stmt) return $array_result;
    //on récupère les données que l'on met dans notre tableau
    while ($row_data = $stmt->fetch()) {

      //a chaque passage de la boucle on instancie un objet pizza
      $logement = new Logement($row_data);
      $logement->medias = AppRepoManager::getRm()->getMediaRepository()->getMediaByAnnonceId($logement->id);
      $array_result[]=$logement;
    }
    //on retourne le tableau fraichement rempli
    return $array_result;
  }

  /*---------------------------- FUNCTION SQL RECUP ANNONCES ID ------------------------*/
  /**
   * méthode qui permet de récupérer une pizza grace à son id
   * @param int $pizza_id
   * @return ?Logement
   */
  public function getAnnonceById(int $logement_id): ?Logement
  {
    //on crée la requete SQL
    $q = sprintf(
      'SELECT * FROM %s WHERE `id` = :id',
      $this->getTableName()
    );

    //on prépare la requete
    $stmt = $this->pdo->prepare($q);

    //on vérifie que la requete est bien préparée
    if(!$stmt) return null;

    //on execute la requete en passant les paramètres
    $stmt->execute(['id' => $logement_id]);

    //on récupère le résultat
    $result = $stmt->fetch();

    //si je n'ai pas de résultat, je retourne null
    if(!$result) return null;

    //si j'ai un résultat, j'instancie un objet Pizza
    $logement = new Logement($result);

    //on va hydrater les ingredients de la pizza
    $logement->equipements = AppRepoManager::getRm()->getLogementEquipementRepository()->getEquipementByAnnonceId($logement_id);
    //on va hydrater les prix de la pizza
    $logement->medias = AppRepoManager::getRm()->getMediaRepository()->getMediaByAnnonceId($logement_id);

    $logement->information = AppRepoManager::getRm()->getInformationRepository()->readById(Information::class, $logement->information_id);

    $logement->media = AppRepoManager::getRm()->getMediaRepository()->readById(Media::class, $logement->id);

    //je retourne l'objet Pizza
    return $logement;
  }

  /*---------------------------- FUNCTION SQL RECUP ANNONCES USER ------------------------*/
  /**
   * Méthode qui permet de récupérer les pizzas de l'utilisateur
   * @param int $id
   * @return array
   */
  public function getAnnonceByUser(int $id) : array
  {
    //on déclare un tableau vide
    $array_result = [];

    //on crée la requête SQL
    $q = sprintf(
      'SELECT * FROM %s WHERE `user_id` = :id AND `is_active` = 1 ',
      $this->getTableName() //correspond au %1$s
    );

    //on prépare la requete
    $stmt = $this->pdo->prepare($q);

    //on vérifie que la requete est bien préparée
    if(!$stmt) return $array_result;

    //on execute la requete en passant les paramètres
    $stmt->execute(['id' => $id]);

    // On récupère les résultats
    while($row_data = $stmt->fetch()){
      $logement = new Logement($row_data);

      // On va hydrater les ingrédients de la pizza
      $logement->equipements = AppRepoManager::getRm()->getLogementEquipementRepository()->getEquipementByAnnonceId($logement->id);

      // On va hydrater les prix
      $logement->medias = AppRepoManager::getRm()->getMediaRepository()->getMediaByAnnonceId($logement->id);

      $array_result[] = $logement;
    }

    return $array_result;

  }

  /**
   * méthode qui va insérer un logement
   * @param array $data
   * @return ?int
   */
  public function addLogement(array $data): ?int
  {

    $query = sprintf(
      'INSERT INTO `%s` (`title`, `description`, `price_per_night`, `nb_room`, `nb_traveler`, `nb_bed`, `nb_bath`, `user_id`, `information_id`,`type_logement_id`, `is_active`) 
    VALUES (:title, :description, :price_per_night, :nb_room, :nb_traveler, :nb_bed, :nb_bath, :user_id, :information_id, :type_logement_id, :is_active)',
      $this->getTableName()
    );


    $stmt = $this->pdo->prepare($query);

    if (!$stmt) return null;

    //on exécute la requete en passant les paramètres
    $stmt->execute($data);

    return $this->pdo->lastInsertId();
  }

  /*---------------------------- FUNCTION SQL MODIFICATION ANNONCES USER ------------------------*/
  /**
   * Méthode qui désactive une Annonce
   * @param int $id
   * @return bool
   * */
  public function deleteAnnonce(int $id) : bool
  {
    $q = sprintf(
      'UPDATE `%s` SET `is_active` = 0 WHERE `id` = :id',
      $this->getTableName()
    );

    // Prépare la requête
    $stmt = $this->pdo->prepare($q);

    // On vérifie que la requête est bien préparée
    if(!$stmt) return false;

    // On exécute la requête en passant les paramètres
    return $stmt->execute(['id' => $id]);
  }

  /**
   * Met à jour le nom de la pizza
   * @param array $data
   * @return ?bool
   */
  public function updateLogementTitle(array $data): ?bool
  {
    //on crée la requête
    $query = sprintf(
      'UPDATE %s SET `title`=:title WHERE `id`=:id',
      $this->getTableName()
    );

    //on prépare la requete
    $stmt = $this->pdo->prepare($query);

    //on vérifie si la requete s'est bien préparée
    if (!$stmt) return null;

    //on execute la requete en bindant les paramètres
    return $stmt->execute($data);
  }

  public function updateLogementDescription(array $data): ?bool
  {
    //on crée la requête
    $query = sprintf(
      'UPDATE %s SET `description`=:description WHERE `id`=:id',
      $this->getTableName()
    );

    //on prépare la requete
    $stmt = $this->pdo->prepare($query);

    //on vérifie si la requete s'est bien préparée
    if (!$stmt) return null;

    //on execute la requete en bindant les paramètres
    return $stmt->execute($data);
  }

  public function updateLogementDetail(array $data): ?bool
  {
    // Crée la requête avec les bons placements des assignations
    $query = sprintf(
      'UPDATE %s SET `price_per_night` = :price_per_night, `nb_room` = :nb_room, `nb_bed` = :nb_bed, `nb_bath` = :nb_bath, `nb_traveler` = :nb_traveler WHERE `id` = :id',
      $this->getTableName()
    );

    // Prépare la requête
    $stmt = $this->pdo->prepare($query);

    // Vérifie si la requête s'est bien préparée
    if (!$stmt) {
      return null;
    }

    // Exécute la requête en bindant les paramètres
    return $stmt->execute($data);
  }

  public function findByCity($word)
  {
    $q = sprintf(
      'SELECT * FROM `%s` WHERE `title` LIKE :word OR `description` LIKE :word',
      $this->getTableName()
    );

    $stmt = $this->pdo->prepare($q);

    $cityParam = '%' . $word . '%';

    $array_result = [];

    $stmt->execute(['word' => $cityParam]);

    while ($row_data = $stmt->fetch()) {

      //a chaque passage de la boucle on instancie un objet pizza
      $logement = new Logement($row_data);
      $logement->medias = AppRepoManager::getRm()->getMediaRepository()->getMediaByAnnonceId($logement->id);
      $array_result[]=$logement;
    }

    return $array_result;
  }

}