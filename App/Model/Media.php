<?php

namespace App\Model;

use Core\Model\Model;

class Media extends Model
{
  public string $label;
  public string $image_path;
  public bool $is_active;
  public int $logement_id;
  public Logement  $logement;
}