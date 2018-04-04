<?php
/**
 * Created by PhpStorm.
 * User: connector
 * Date: 26/03/2018
 * Time: 11:57
 */

namespace model;



class Projet5_user
{
    /**
     * @var int $id   identifiant de l'utilisateur
     */
    private $id ;


    /**
     * @var string $gender sexe de l'utilisateur
     */
    private $gender ;


    /**
     * @var string $first_name prénom de l'utilisateur
     */
    private $first_name  ;


    /**
     * @var string $last_name nom de l'utilsateur
     */
    private $last_name ;


    /**
     * @var string $username   pseudo utilisateur
     */
    private $username ;


    /**
     * @var string $birthday date de naissance de l'utilisateur
     */
    private $birthday ;


    /**
     * @var int $user_age  age de l'utilsateur calculer dans la requête d'insertion
     */
    private $user_age;





    /**
     * @var string $email mail de l'utilisateur
     */
    private $email ;


    /**
     * @var
     */
    private $password ;


    /**
     * @var date  $registry_date  date d'inscription du membre
     */
    private $registry_date ;


    /**
     * @var int $connected  si un utilisateur est connecté
     */
    private $connected;


    /**
     * @var int $connected_self  si l'on est connecté
     */
    private $connected_self;










    /**
     * @var string $role role de l'utilisateur
     */
    private $role;







    /**
     * @param string $gender
     * @return Projet5_user
     */
    public function setGender(string $gender): Projet5_user
    {
        $this->gender = $gender;
        return $this;
    }

    /**
     * @param string $first_name
     * @return Projet5_user
     */
    public function setFirstName(string $first_name): Projet5_user
    {
        $this->first_name = $first_name;
        return $this;
    }

    /**
     * @param string $last_name
     * @return Projet5_user
     */
    public function setLastName(string $last_name): Projet5_user
    {
        $this->last_name = $last_name;
        return $this;
    }

    /**
     * @param string $username
     * @return Projet5_user
     */
    public function setUsername(string $username): Projet5_user
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @param string $birthday
     * @return Projet5_user
     */
    public function setBirthday(string $birthday): Projet5_user
    {
        $this->birthday = $birthday;
        return $this;
    }

    /**
     * @param string $email
     * @return Projet5_user
     */
    public function setEmail(string $email): Projet5_user
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @param mixed $password
     * @return Projet5_user
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }



    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getGender(): string
    {
        return $this->gender;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->first_name;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->last_name;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return datetime
     */
    public function getBirthday(): string
    {
        return $this->birthday;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return date
     */
    public function getRegistryDate(): date
    {
        return $this->registry_date;
    }

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * @param string $role
     */
    public function setRole(string $role): void
    {
        $this->role = $role;
    }

    /**
     * @return int
     */
    public function getConnected(): int
    {
        return $this->connected;
    }

    /**
     * @param int $connected
     * @return Projet5_user
     */
    public function setConnected(int $connected): Projet5_user
    {
        $this->connected = $connected;
        return $this;
    }

    /**
     * @return int
     */
    public function getConnectedSelf(): int
    {
        return $this->connected_self;
    }

    /**
     * @param int $connected_self
     * @return Projet5_user
     */
    public function setConnectedSelf(int $connected_self): Projet5_user
    {
        $this->connected_self = $connected_self;
        return $this;
    }

    /**
     * @return int
     */
    public function getUserAge(): int
    {
        return $this->user_age;
    }

    /**
     * @param int $user_age
     * @return Projet5_user
     */
    public function setUserAge(int $user_age): Projet5_user
    {
        $this->user_age = $user_age;
        return $this;
    }










}

