<?php
/**
 * Created by PhpStorm.
 * User: connector
 * Date: 27/03/2018
 * Time: 11:33
 */
namespace Model;
class DbConnect
{
    static $db=null;

    static function getDatabase()
    {
        if(!self::$db)
        {
            self::$db= new DataBase();
        }
        return self::$db;
    }
}