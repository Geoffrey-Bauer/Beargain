<?php

namespace App\Model;

use Core\Model\Model;

class LogementEquipement extends Model
{
  public int $logement_id;
  public int $equipement_id;
  public Logement  $logement;
  public Equipements $equipements;


}