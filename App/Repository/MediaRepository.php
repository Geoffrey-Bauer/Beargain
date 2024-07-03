<?php

namespace App\Repository;

use App\Model\Media;
use Core\Repository\Repository;

class MediaRepository extends Repository
{
  public function getTableName() : string
  {
    return "media";
  }

  /**
   * méthode qui permet de récupérer le prix d'une annonce grace à son id
   * @param int $logement_id
   * @return array
   */
  public function getMediaByAnnonceId(int $logement_id): array
  {
    //on déclare un tableau vide
    $array_result = [];
    //on crée la requete SQL
    $q = sprintf(
      'SELECT *
            FROM %s 
            WHERE `logement_id` = :id',
      $this->getTableName() //correspond au %1$s
    );

    //on prépare la requete
    $stmt = $this->pdo->prepare($q);

    //on vérifie que la requete est bien executée
    if (!$stmt) return $array_result;
		
    $stmt->execute(['id' => $logement_id]);

    while ($row = $stmt->fetch()) {
      $array_result[] = new Media($row);
    }

    //on retourne le tableau fraichement rempli
    return $array_result;
  }

  public function insertMedia(array $data): ?int
  {
    $query = $this->pdo->prepare(
      'INSERT INTO ' . $this->getTableName() . ' (label, image_path, is_active, logement_id) VALUES (:label, :image_path, :is_active, :logement_id)'
    );

    $query->execute([
      'label' => $data['label'],
      'image_path' => $data['image_path'],
      'is_active' => $data['is_active'],
      'logement_id' => $data['logement_id']
    ]);

    return $this->pdo->lastInsertId();
  }

  public function updateLogementMedia(array $data): ?bool
  {
    //on crée la requête
    $query = sprintf(
      'UPDATE %s SET `image_path`=:image_path, `label`=:label WHERE `id`=:id',
      $this->getTableName()
    );

    //on prépare la requete
    $stmt = $this->pdo->prepare($query);

    //on vérifie si la requete s'est bien préparée
    if (!$stmt) return null;

    //on execute la requete en bindant les paramètres
    return $stmt->execute($data);
  }
}