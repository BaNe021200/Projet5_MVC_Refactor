<?php
/**
 * Created by PhpStorm.
 * User: connector
 * Date: 26/03/2018
 * Time: 12:13
 */

namespace model;
use PDO;



class UserManager
{
    /**
     * @var \PDO $pdo objet PDO lié à la base de donnée agenda
     */
    private $pdo;

    /**
     * @var \PDOStatement $pdostatement objet PDOStatement résultant des méthode PDO::QUERY
    et PDO::PREPARE */

    private $pdostatement;

    /**
     * ContactManager constructor.
     * initialisation de la connexion à la base de donnée
     */
    public function __construct()
    {
        $this->pdo = new PDO('mysql:host=localhost;dbname=twig','root','nzB69yCSBDz9eK46',array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

    }

    /**
     * Insert un objet User dans la BDD et met à jour l'objet passé en argument en lui attribuant un identifiant (id)
     * @param Projet5_user $user objet de type Projet5_user passé par référence
     * @return bool true si l'objet a été inséré; false si une erreur survient
     */
    private function create(Projet5_user &$user){


        $this->pdostatement=$this->pdo->prepare('INSERT INTO projet5_user VALUES (NULL,:gender,:first_name, :last_name,:username, :birthday,NULL, :email,:password,NOW(),NULL,NULL,:roles)');
        //liaison des paramètres
        $this->pdostatement->bindValue(':gender',$user->getGender(),PDO::PARAM_STR);
        $this->pdostatement->bindValue(':first_name',$user->getFirstName(),PDO::PARAM_STR);
        $this->pdostatement->bindValue(':last_name',$user->getLastName(),PDO::PARAM_STR);
        $this->pdostatement->bindValue(':username',$user->getUsername(),PDO::PARAM_STR);
        $this->pdostatement->bindValue(':birthday',$user->getBirthday(),PDO::PARAM_STR);
        $this->pdostatement->bindValue(':email',$user->getEmail(),PDO::PARAM_STR);
        $this->pdostatement->bindValue(':password',$user->getPassword(),PDO::PARAM_STR);


        $this->pdostatement->bindValue(':roles', $user->getRole(), PDO::PARAM_STR);
        $newUser=$this->pdostatement->execute();
        $lastId=$this->pdo->lastInsertId();

        if (!$newUser){
            return false;
        }
        else{

            $user= $this->read($lastId);
            return true;


        }

        $this->pdostatement->closeCursor();

        $this->pdostatement=$this->pdo->prepare('
        SELECT first_name,email,username,user_age
        FROM projet5_user
        WHERE id=:id');
        $this->pdostatement->bindValue(':id',$lastId,PDO::PARAM_INT);
        $newUser= $this->pdostatement->execute();
        $newUser=$this->pdostatement->fetchObject('model\Projet5_user');

        if($newUser){
            $user = $this->pdostatement->fetchObject('modele\Projet5_user');
            if($user===false){
                return null;
            }
            else{
                return $user;
            }
        }else{
            return false;
        }



    }


    /**
     * récupère un objet user à partir de son identifiant
     * @param int $id identifiant d'un user
     *
     * @return bool|Projet5_user|Null false si une erreur survient, un objet si une correspondance est trouvée,
     * Null s'il n'y aucune correspondance
     */
    public function read($id){
        $this->pdostatement= $this->pdo->prepare('SELECT * FROM projet5_user WHERE id= :id');
        //liaison paramètres
        $this->pdostatement->bindValue(':id',$id,PDO::PARAM_INT);
        //Execution de la requête
        $executeIsOk= $this->pdostatement->execute();
        if($executeIsOk){
            $user = $this->pdostatement->fetchObject('model\Projet5_user');
            if($user===false){
                return null;
            }
            else{
                return $user;
            }
        }else{
            return false;
        }
    }

    /**
     * Récupère tous les objets Projet5_user de la BDD
     *
     * @return array|bool tableau d'ojbet Projet5_user ou un tableau vide s'il n'y aucun d'ojet ou false si une erreur survient
     *
     */
    public function  readAll()
    {
        $this->pdostatement= $this->pdo->query('SELECT * FROM projet5_user ORDER BY registry_date');
        //construction tableau d'objet de type Contact
        $users = [];
        while ($user= $this->pdostatement->fetchObject("model\Projet5_user")){
            $users[]= $user;
        }

        return $users;


    }

    /**
     * Met à jour un objet stocké en base de données
     * @param Projet5_user $user objet de type Projet5_user
     * @return bool true en cas de succès et false en cas d'erreur
     */
    private function update(Projet5_user $user){
        $this->pdostatement=$this->pdo->prepare('UPDATE projet5_user set :genre,:prenom, :nom,
:email WHERE id=:id LIMIT 1');
        //liaison des éléments
           $this->pdostatement->bindValue(':genre',$user->getGender(),PDO::PARAM_STR);
        $this->pdostatement->bindValue(':prenom',$user->getFirstName(),PDO::PARAM_STR);
           $this->pdostatement->bindValue(':nom',$user->getLastName(),PDO::PARAM_STR);
           $this->pdostatement->bindValue(':email',$user->getEmail(),PDO::PARAM_STR);
            $this->pdostatement->bindValue(':id',$user->getId(),PDO::PARAM_INT);

        //exécution de la requête

        return $this->pdostatement->execute();



    }

    /**
     * Supprime un objet stocké en base de données
     * @param Projet5_user $user objet de type Projet5_user
     * @return bool true en cas de succès et false en cas d'erreur
     */
    public function delete(Projet5_user $user){

        $this->pdostatement=$this->pdo->prepare('DELETE FROM projet5_user WHERE id= :id LIMIT 1');
        $this->pdostatement->bindValue(':id',$user->getId(), PDO::PARAM_INT);
        //execution de la requête
        return $this->pdostatement->execute();
    }

    /**
     * Insère un objet en bdd et met à jour l'objet passé en argument en lui spécifiant un identifiant ou le met simplement à jour dans la bdd s'il en est issu
     *
     * @param Projet5_user $user
     * @return bool
     */
    public function save(Projet5_user &$user){

        //il faut utiliser la méthode create lorsque l'objet est nouveau et la methode update si l'objet n'est pas nouveau
        //pour le savoir  ->
        //un nouvel objet n'a pas d'id
        //un objet issu de la base de donnée a un id

        if(is_null($user->getId())){
            return $this->create($user);
        }
        else{
            return $this->update($user);

        }
    }

    public function getProfils()
    {
        $this->pdostatement=$this->pdo->query('
        
        SELECT projet5_images.user_id,filename, projet5_user.id,username,user_age,registry_date,connected,projet5_infosuser.city
            FROM projet5_images
            JOIN projet5_user
            ON projet5_images.user_id = projet5_user.id
            LEFT JOIN projet5_infosuser
            ON projet5_images.user_id=projet5_infosuser.user_id
            WHERE projet5_images.filename="img-userProfil"

            AND projet5_user.connected_self IS NULL
            ORDER BY registry_date DESC LIMIT 0,6');



        $profils=[];
        while ($profil=$this->pdostatement->fetchObject()){
            $profils[]=$profil;

        }

        return $profils;

    }

    public function getProfil($userId)
    {
        $this->pdostatement=$this->pdo->prepare('
        SELECT projet5_images.dirname,filename,extension, projet5_user.id,username,gender,user_age
        FROM projet5_images
        INNER JOIN projet5_user
        ON projet5_images.user_id = projet5_user.id
        WHERE user_id = :userId AND filename=:filename');
        $this->pdostatement->bindValue(':userId',$userId,PDO::PARAM_INT);
        $this->pdostatement->bindValue(':filename',"img-userProfil",PDO::PARAM_STR);

        $executeIsOk= $this->pdostatement->execute();
        if($executeIsOk){
            $profil = $this->pdostatement->fetchObject('model\Projet5_user');
            if($profil===false){
                return null;
            }
            else{
                return $profil;
            }
        }else{
            return false;
        }


    }



}