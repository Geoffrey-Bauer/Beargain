<?php

namespace App\Repository;

use App\AppRepoManager;
use App\Model\Equipements;
use App\Model\Logement;
use Core\Repository\Repository;
use PDO;

class LogementEquipementRepository extends Repository
{
  public function getTableName() : string
  {
    return "logement_equipement";
  }

  /**
   * méthode qui récupère les équipements du logement grâce à son id
   * @param int $pizza_id
   * @return array
   */
  public function getEquipementByAnnonceId(int $logement_id):array
  {
    //on déclare un tableau vide
    $array_result = [];
    //on crée la requete SQL
    $q = sprintf(
      'SELECT * 
            FROM %1$s AS le 
            INNER JOIN %2$s AS e ON le.`equipement_id` = e.`id` 
            WHERE le.`logement_id` = :id',
      $this->getTableName(), //correspond au %1$s
      AppRepoManager::getRm()->getEquipementsRepository()->getTableName() //correspond au %2$s
    );

    //on prépare la requete
    $stmt = $this->pdo->prepare($q);

    //on vérifie que la requete est bien executée
    if(!$stmt) return $array_result;

    //on execute la requete en passant l'id de la pizza
    $stmt->execute(['id' => $logement_id]);

    //on récupère les résultats
    while($row_data = $stmt->fetch()){
      //a chaque passage de la boucle on instancie un objet ingredient
      $array_result[] = new Equipements($row_data);
    }

    //on retourne le tableau fraichement rempli
    return $array_result;
  }

  public function insertLogementEquipement(array $data): bool
  {
    $query = sprintf(
      'INSERT INTO `%s` (`logement_id`, `equipement_id`) VALUES ( :logement_id, :equipement_id)',
      $this->getTableName()
    );

    $stmt = $this->pdo->prepare($query);

    if (!$stmt) return false;

    //on exécute la requete en passant les paramètres
    $stmt->execute($data);
    //on regarder si on a au moins une ligne qui a été ionsérere
    return $stmt->rowCount() > 0;
  }

  public function deleteLogementEquipement(int $logement_id): bool
  {
    //on crée la requete
    $query = sprintf(
      'DELETE FROM %s WHERE `logement_id`=:logement_id',
      $this->getTableName()
    );

    //on prépare la requete
    $stmt = $this->pdo->prepare($query);

    //on vérifie si la requete s'est bien préparée
    if(!$stmt) return false;

    //on execute la requete en bindant les paramètres
    $stmt->execute(['logement_id' => $logement_id]);

    //on regarde si au moins une ligne a été enregistrée
    return $stmt->rowCount() > 0;
  }

  public function getEquipementById(int $logement_id) : array
  {
    $query = sprintf(
      'SELECT * FROM `%s` WHERE `logement_id` = :logement_id',
      $this->getTableName()
    );

    $stmt = $this->pdo->prepare($query);

    if (!$stmt) return [];

    // On exécute la requête en passant les paramètres
    $stmt->execute(['logement_id' => $logement_id]);

    // On récupère tous les résultats
    $activeEquipements = $stmt->fetchAll(PDO::FETCH_OBJ);

    return $activeEquipements;
  }

}