<?php

namespace App; // Déclare le namespace de la classe

use App\Repository\EquipementsRepository; // Importe la classe EquipementsRepository
use App\Repository\InformationRepository; // Importe la classe InformationRepository
use App\Repository\LogementEquipementRepository; // Importe la classe LogementEquipementRepository
use App\Repository\LogementRepository; // Importe la classe LogementRepository
use App\Repository\MediaRepository; // Importe la classe MediaRepository
use App\Repository\ReservationsRepository; // Importe la classe ReservationsRepository
use App\Repository\TypeLogementRepository; // Importe la classe TypeLogementRepository
use App\Repository\UserRepository; // Importe la classe UserRepository
use Core\Repository\RepositoryManagerTrait; // Importe le trait RepositoryManagerTrait

class AppRepoManager
{
  use RepositoryManagerTrait; // Utilise le trait RepositoryManagerTrait pour inclure ses méthodes et propriétés

  // Déclaration des propriétés privées pour chaque repository
  private EquipementsRepository $equipementsRepository;
  private InformationRepository $informationRepository;
  private LogementEquipementRepository $logementEquipementRepository;
  private LogementRepository $logementRepository;
  private MediaRepository $mediaRepository;
  private ReservationsRepository $reservationsRepository;
  private TypeLogementRepository $typeLogementRepository;
  private UserRepository $userRepository;

  /**
   * Constructeur pour initialiser les repositories
   */
  public function __construct()
  {
    // Récupère la configuration de l'application
    $config = App::getApp();

    // Initialise chaque repository avec la configuration de l'application
    $this->equipementsRepository = new EquipementsRepository($config);
    $this->informationRepository = new InformationRepository($config);
    $this->logementEquipementRepository = new LogementEquipementRepository($config);
    $this->logementRepository = new LogementRepository($config);
    $this->mediaRepository = new MediaRepository($config);
    $this->reservationsRepository = new ReservationsRepository($config);
    $this->typeLogementRepository = new TypeLogementRepository($config);
    $this->userRepository = new UserRepository($config);
  }

  // Méthodes getter pour accéder à chaque repository

  /**
   * Retourne l'instance d'EquipementsRepository
   * @return EquipementsRepository
   */
  public function getEquipementsRepository(): EquipementsRepository
  {
    return $this->equipementsRepository; // Retourne l'instance de EquipementsRepository
  }

  /**
   * Retourne l'instance d'InformationRepository
   * @return InformationRepository
   */
  public function getInformationRepository(): InformationRepository
  {
    return $this->informationRepository; // Retourne l'instance de InformationRepository
  }

  /**
   * Retourne l'instance de LogementEquipementRepository
   * @return LogementEquipementRepository
   */
  public function getLogementEquipementRepository(): LogementEquipementRepository
  {
    return $this->logementEquipementRepository; // Retourne l'instance de LogementEquipementRepository
  }

  /**
   * Retourne l'instance de LogementRepository
   * @return LogementRepository
   */
  public function getLogementRepository(): LogementRepository
  {
    return $this->logementRepository; // Retourne l'instance de LogementRepository
  }

  /**
   * Retourne l'instance de MediaRepository
   * @return MediaRepository
   */
  public function getMediaRepository(): MediaRepository
  {
    return $this->mediaRepository; // Retourne l'instance de MediaRepository
  }

  /**
   * Retourne l'instance de ReservationsRepository
   * @return ReservationsRepository
   */
  public function getReservationsRepository(): ReservationsRepository
  {
    return $this->reservationsRepository; // Retourne l'instance de ReservationsRepository
  }

  /**
   * Retourne l'instance de TypeLogementRepository
   * @return TypeLogementRepository
   */
  public function getTypeLogementRepository(): TypeLogementRepository
  {
    return $this->typeLogementRepository; // Retourne l'instance de TypeLogementRepository
  }

  /**
   * Retourne l'instance de UserRepository
   * @return UserRepository
   */
  public function getUserRepository(): UserRepository
  {
    return $this->userRepository; // Retourne l'instance de UserRepository
  }

}
