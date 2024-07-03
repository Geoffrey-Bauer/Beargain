<?php

namespace App\Repository;

use App\Model\Equipements;
use Core\Repository\Repository;

class EquipementsRepository extends Repository
{
  public function getTableName(): string
  {
    return 'equipements';
  }

  /**
   * méthode qui va récupérer tout les équipements
   * @return array
   */
  public function getAllEquipements():array
  {
    return $this->readAll(Equipements::class);
  }

  public function getEquipementById(int $id):Equipements
  {
    return $this->readById(Equipements::class, $id);
  }
}