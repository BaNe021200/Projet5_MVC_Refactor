<?php
/**
 * Created by PhpStorm.
 * User: connector
 * Date: 26/03/2018
 * Time: 15:01
 */

namespace model;


class Autoloader
{

   static function register(){

       spl_autoload_register(array(__CLASS__,'autoload'));
   }

    static function autoload($class)
    {

     $class=str_replace('model\\','',$class);
     //$class=str_replace('lib\\','',$class);

        //var_dump($class);
        require_once 'model/'.$class.'.php';



    }



}

