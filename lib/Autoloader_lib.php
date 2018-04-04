<?php
/**
 * Created by PhpStorm.
 * User: connector
 * Date: 26/03/2018
 * Time: 15:01
 */

namespace lib;


class Autoloader_lib
{

   static function register(){

       spl_autoload_register(array(__CLASS__,'autoload'));
   }

    static function autoload($class)
    {
       
        var_dump($class);
        
        require_once 'lib/'.$class.'.php';
        


    }



}

