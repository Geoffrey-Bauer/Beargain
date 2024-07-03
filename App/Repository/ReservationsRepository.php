<?php

namespace App\Repository;

use App\AppRepoManager;
use App\Model\Logement;
use App\Model\Reservations;
use Core\Repository\Repository;
use PDO;

class ReservationsRepository extends Repository
{
  public function getTableName(): string
  {
    return 'reservations';
  }

  public function addReservation(array $reservation_data)
  {
    $sql = "INSERT INTO reservations (order_number, date_start, date_end, logement_id, user_id, nb_adult, nb_child, price_total) 
            VALUES (:order_number, :date_start, :date_end, :logement_id, :user_id, :nb_adult, :nb_child, :price_total)";
    $stmt = $this->pdo->prepare($sql);

    $stmt->bindParam(':order_number', $reservation_data['order_number']);
    $stmt->bindParam(':date_start', $reservation_data['date_start']);
    $stmt->bindParam(':date_end', $reservation_data['date_end']);
    $stmt->bindParam(':logement_id', $reservation_data['logement_id']);
    $stmt->bindParam(':user_id', $reservation_data['user_id']);
    $stmt->bindParam(':nb_adult', $reservation_data['nb_adult']);
    $stmt->bindParam(':nb_child', $reservation_data['nb_child']);
    $stmt->bindParam(':price_total', $reservation_data['price_total']);

    return $stmt->execute();
  }

  public function findLastOrder(): ?int
  {
    $query = sprintf(
      'SELECT *
        FROM `%s`
        ORDER BY id DESC LIMIT 1',
      $this->getTableName()
    );
    $stmt = $this->pdo->query($query);

    if (!$stmt) return null;

    $result = $stmt->fetchObject();

    return $result->id ?? 0;
  }


  public function findReservationByUser($id): array
  {
    $array_result = [];

    $query = sprintf(
      'SELECT r.*, l.id as logement_id, l.title as logement_title
        FROM %1$s as r
        INNER JOIN %2$s as l ON l.id = r.logement_id
        WHERE r.user_id = :id',
      $this->getTableName(),
      AppRepoManager::getRm()->getLogementRepository()->getTableName()
    );

    $stmt = $this->pdo->prepare($query);
    if (!$stmt) {
      return $array_result;
    }

    $stmt->execute(['id' => $id]);

    while ($row_data = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $reservation = new Reservations($row_data);

      // Hydratez avec les données du logement
      $logement = new Logement();
      $logement->id = $row_data['logement_id'];
      $logement->title = $row_data['logement_title'];
      $reservation->logement = $logement;

      $array_result[] = $reservation;
    }

    return $array_result;
  }

  public function findReservationsByLogementUserId($id): array
  {
    $array_result = [];

    $query = sprintf(
      'SELECT r.* FROM %1$s as r
  INNER JOIN %2$s as l ON r.`logement_id` = l.`id`
  INNER JOIN %3$s as u ON l.`user_id` = u.`id`
  INNER JOIN %4$s as i ON l.`information_id` = i.`id`
  WHERE u. `id` = :id',
      $this->getTableName(), // correspond à la table reservation
      AppRepoManager::getRm()->getLogementRepository()->getTableName(), // correspond à la table logement
      AppRepoManager::getRm()->getUserRepository()->getTableName(), // correspond à la table user
      AppRepoManager::getRm()->getInformationRepository()->getTableName() // correspond à la table information
    );

    $stmt = $this->pdo->prepare($query);

    if (!$stmt) return $array_result;

    $stmt->execute(['id'=>$id]);

    while ($row_data = $stmt->fetch()) {
      $reservation = new Reservations($row_data);

      //on va hydrater l'objet logement*
      $reservation->logement = AppRepoManager::getRm()->getLogementRepository()->getAnnonceById($row_data['logement_id']);

      $reservation->logement->information = AppRepoManager::getRm()->getInformationRepository()->getInformationByLogementId($reservation->logement->information_id);

      $array_result[] = $reservation;
    }

    return $array_result;
  }

  public function deleteReservationUser(int $id) : bool
  {
    $q = sprintf(
      'DELETE FROM `%s` WHERE `id` = :id',
      $this->getTableName()
    );

    // Prépare la requête
    $stmt = $this->pdo->prepare($q);

    // On vérifie que la requête est bien préparée
    if(!$stmt) return false;

    // On exécute la requête en passant les paramètres
    return $stmt->execute(['id' => $id]);
  }

  public function deleteReservationHote(int $id) : bool
  {
    $q = sprintf(
      'DELETE FROM `%s` WHERE `id` = :id',
      $this->getTableName()
    );

    // Prépare la requête
    $stmt = $this->pdo->prepare($q);

    // On vérifie que la requête est bien préparée
    if(!$stmt) return false;

    // On exécute la requête en passant les paramètres
    return $stmt->execute(['id' => $id]);
  }

}