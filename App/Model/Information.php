<?php

namespace App\Model;

use Core\Model\Model;

class Information extends Model
{
  public ?string $adress;
  public ?int $zip_code;
  public ?string $city;
  public ?string $country;
  public ?string $phone;
}