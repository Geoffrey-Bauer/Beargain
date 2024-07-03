<?php

namespace App\Model;

use Core\Model\Model;

class User extends Model
{
  public string $email;
  public string $password;
  public string $lastname;
  public string $firstname;
  public bool $is_active;
  public bool $is_verified;
  public int $information_id;
  public Information $information;
  public Logement $logement;
}