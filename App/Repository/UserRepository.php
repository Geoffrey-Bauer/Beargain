<?php

namespace App\Repository;

use App\Model\User;
use Core\Repository\Repository;
use InvalidArgumentException;

class UserRepository extends Repository
{
  public function getTableName(): string
  {
    return "user";
  }

  /**
   * Méthode pour ajouter un utilisateur
   */
  public function addUser(array $data): ?User
  {
    // Ajout de valeurs par défaut pour 'is_active' et 'is_verified'
    $data_more = [
      'is_active' => 1,
      'is_verified' => 0
    ];
    // Fusion des tableaux de données
    $data = array_merge($data, $data_more);

    // Création de la nouvelle entrée dans la table 'information'
    $queryInfo = 'INSERT INTO information () VALUES ()';
    $stmtInfo = $this->pdo->prepare($queryInfo);
    // Vérification que la requête a été correctement préparée
    if (!$stmtInfo) return null;
    // Exécution de la requête
    $stmtInfo->execute();

    // Récupération de l'ID de la nouvelle entrée 'information'
    $information_id = $this->pdo->lastInsertId();
    // Ajout de l'information_id aux données utilisateur
    $data['information_id'] = $information_id;

    // Création de la requête SQL pour insérer un nouvel utilisateur
    $queryUser = sprintf(
      'INSERT INTO %s (`email`, `password`, `firstname`, `lastname`, `is_active`, `is_verified`, `information_id`) 
        VALUES (:email, :password, :firstname, :lastname, :is_active, :is_verified, :information_id)',
      $this->getTableName()
    );
    // Préparation de la requête
    $stmtUser = $this->pdo->prepare($queryUser);
    // Vérification que la requête a été correctement préparée
    if (!$stmtUser) return null;

    // Exécution de la requête avec les valeurs fournies
    $stmtUser->execute($data);

    // Récupération de l'ID de l'utilisateur fraîchement créé
    $id = $this->pdo->lastInsertId();

    // Retour de l'objet User grâce à son ID
    return $this->readById(User::class, $id);
  }


  /**
   * méthode qui recupère un utilisateur par son email
   * @param string $email
   * @return User|null
   */
  public function findUserByEmail(string $email): ?User
  {
    //on crée notre requete SQL
    $q = sprintf('SELECT * FROM %s WHERE email = :email', $this->getTableName());
    //on prépare la requete
    $stmt = $this->pdo->prepare($q);
    //on vérifie que la requete est bien bien préparée
    if (!$stmt) return null;
    //si tout est bon, on bind les valeurs
    $stmt->execute(['email' => $email]);
    while ($result = $stmt->fetch()) {
      $user = new User($result);
    }
    return $user ?? null;
  }

  /*---------------------------- FUNCTION SQL RECUP ANNONCES ID ------------------------*/
  /**
   * méthode qui permet de récupérer une pizza grace à son id
   * @param int $user_id
   * @return User
   */
  public function getUserById(int $user_id): User
  {
    //on crée la requete SQL
    $q = sprintf(
      'SELECT * FROM %s WHERE `id` = :id',
      $this->getTableName()
    );

    //on prépare la requete
    $stmt = $this->pdo->prepare($q);

    //on vérifie que la requete est bien préparée
    if(!$stmt) return $user_id;

    //on execute la requete en passant les paramètres
    $stmt->execute(['id' => $user_id]);

    //on récupère le résultat
    $result = $stmt->fetch();

    //si je n'ai pas de résultat, je retourne null
    if(!$result) return $user_id;

    //si j'ai un résultat, j'instancie un objet Pizza
    $user = new User($result);

    //je retourne l'objet Pizza
    return $user;
  }
}