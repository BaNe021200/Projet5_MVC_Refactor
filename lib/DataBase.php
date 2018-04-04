<?php
/**
 * Created by PhpStorm.
 * User: connector
 * Date: 27/03/2018
 * Time: 11:02
 */
namespace Model;
use PDO;
class DataBase
{
    private $pdo;

    public function __construct($login='root',$password='nzB69yCSBDz9eK46',$dbname='twig',$host='localhost')
    {
        $this->pdo = new \PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $login, $password);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_OBJ);
    }


    public function query($query,$params= false,$lastId=false)
    {
        if($params)
        {
            $Pdostat = $this->pdo->prepare($query);
            $lastId=$this->pdo->lastInsertId($lastId);
            $Pdostat->execute($params);
        }else{
           $Pdostat = $this->pdo->query($query);
        }

        return $Pdostat;
    }
}