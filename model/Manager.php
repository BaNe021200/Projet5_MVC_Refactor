<?php
/**
 * Created by PhpStorm.
 * User: connector
 * Date: 03/04/2018
 * Time: 17:11
 */

namespace model;

require_once 'model/Projet5_images.php';
require_once 'model/Projet5_user.php';
use model\Projet5_images;
Use model\Projet5_user;
use PDO;
class Manager
{
    /**
     * @var \PDO $pdo objet PDO lié à la base de donnée
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

    public function getProfil()
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

    public function newUserFinalUpdate(Projet5_user $user)
    {

        $this->pdostatement=$this->pdo->prepare("UPDATE projet5_user SET username = REPLACE(username,'_',' '), user_age= timestampdiff(YEAR,birthday,now())
        WHERE id=:id");
        $this->pdostatement->bindValue(':id',$user->getId(),PDO::PARAM_INT);
       return $this->pdostatement->execute();



    }

    public function isConnectedSelf($id)
    {
        $this->pdostatement= $this->pdo->prepare('
        UPDATE projet5_user 
        SET connected_self = :connected
        WHERE id=:id');
        $this->pdostatement->bindValue(':id',$id,PDO::PARAM_INT);
        $this->pdostatement->bindValue(':connected',1,PDO::PARAM_INT);
        return $this->pdostatement->execute();
    }








    public function readUser($item,$Queryitem){





        $this->pdostatement= $this->pdo->prepare('SELECT * FROM projet5_user WHERE '.$Queryitem.' = :item');
        //liaison paramètres
        $this->pdostatement->bindValue(':item',$item,PDO::PARAM_STR);
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

}