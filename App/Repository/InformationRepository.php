<?php

namespace App\Repository;

use App\Model\Information;
use App\Model\Logement;
use Core\Repository\Repository;
use PDO;

class InformationRepository extends Repository
{
  public function getTableName() : string
  {
    return "information";
  }

  /*---------------------------- FUNCTION AJOUT D'INFORMATION ------------------------*/
  /**
   * méthode qui va permettre d'ajouter des addresses
   * @param array $data
   * @return ?int
   */
  public function insertInformation(array $data):?int
  {
    $query = sprintf('INSERT INTO `%s` (`adress`, `zip_code`, `city`, `country`, `phone`) VALUES (:adress, :zip_code, :city, :country, :phone)',
      $this->getTableName());
    $stmt = $this->pdo->prepare($query);

    if(!$stmt)return null;

    //on exécute la requete en passant les paramètres
    $stmt->execute($data);
    //on regarder si on a au moins une ligne qui a été ionsérere
    return $this->pdo->lastInsertId();
  }

  /*---------------------------- FUNCTION RENVOIE TOUTE LES INFORMATIONS ------------------------*/
  /**
   * méthode qui va permettre de récupérer toutes les informations
   * @return array
   */
  public function getAllInformation():array
  {
    return $this->readAll(Information::class);
  }

  /*---------------------------- FUNCTION SQL MODIFICATION ANNONCES USER ------------------------*/

  public function updateLogementAdress(array $data): ?bool
  {
    // Crée la requête SQL avec jointure
    $query = sprintf(
      'UPDATE %s 
     SET adress = :adress, zip_code = :zip_code, city = :city, country = :country, phone = :phone
     WHERE id = :id',
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

  public function getInformationByLogementId(int $id):?Information
  {
    $query = sprintf('SELECT * FROM `%s` WHERE `id` = :id',
      $this->getTableName());
    $stmt = $this->pdo->prepare($query);
    if (!$stmt) return null;
    $stmt->execute(['id' => $id]);
    $result=$stmt->fetch();

    if(!$result) return null;

    $information = new Information($result);

    return $information;
  }

  public function updateUserInformation(array $data): ?bool
  {
    // Crée la requête SQL avec jointure
    $query = sprintf(
      'UPDATE %s 
     SET adress = :adress, zip_code = :zip_code, city = :city, country = :country, phone = :phone
     WHERE id = :id',
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

  public function getInformationByUserId(int $id):?Information
  {
    $query = sprintf('SELECT * FROM `%s` WHERE `id` = :id',
      $this->getTableName());
    $stmt = $this->pdo->prepare($query);
    if (!$stmt) return null;
    $stmt->execute(['id' => $id]);
    $result=$stmt->fetch();

    if(!$result) return null;

    $information = new Information($result);

    return $information;
  }
}