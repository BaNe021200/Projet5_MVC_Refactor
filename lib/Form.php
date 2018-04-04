<?php
namespace lib;
/**
 * Created by PhpStorm.
 * User: connector
 * Date: 26/03/2018
 * Time: 12:34
 */



class Form
{
    private $data;
    public $surround = 'p';

    public function __construct($data =array())
    {
        $this->data=$data;
    }

    private function surround($html)
    {
        return "<{$this->surround}>{$html}</{$this->surround}>";
    }


    public function input($type,$name,$class,$placeHolder,$value)
    {
        return $this->surround('<input type="'.$type.'" name="'.$name.'" class="'.$class.'" placeholder="'.$placeHolder.'" value="'.$value.'" >') ;
    }


    public function radio($idfor,$name,$label,$value)
    {
        return $this->surround('<input type="radio" id="'.$idfor.'"  name="'.$name.'" class="custom-control-input" value="'.$value.'" required>
       <p> <label class="custom-control-label" for="'.$idfor.'"> '."$label".'</label></p>');

    }




    public function submit($class){
       return $this->surround('<button type="submit" class="'.$class.'">Envoyer</button>');
    }


}