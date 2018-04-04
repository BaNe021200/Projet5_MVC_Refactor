<?php
/**
 * Created by PhpStorm.
 * User: connector
 * Date: 14/02/2018
 * Time: 19:39
 */

namespace model;
use PDO;

class Manager
{
   protected  function  dbConnect()
    {
        $db = new \PDO('mysql:host=localhost;dbname=twig;charset=utf8', 'root', 'nzB69yCSBDz9eK46');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
       // $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_OBJ);
        return $db;
    }




}