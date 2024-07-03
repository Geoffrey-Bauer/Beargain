<?php

namespace App\Model;

use Core\Model\Model;

class Logement extends Model
{
  public string $title;
  public string $description;
  public int $price_per_night;
  public int $nb_room;
  public int $nb_bed;
  public int $nb_bath;
  public int $nb_traveler;
  public bool $is_active;
  public int $type_logement_id;
  public int $user_id;
  public int $information_id;

  public TypeLogement $typeLogement;
  public User $user;
  public Information $information;
  public array $medias;
  public array $equipements;
}