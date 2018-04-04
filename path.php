<?php
declare(strict_types=1);

/*
$images=glob('users/img/user/'.$_COOKIE['username'].'/*.jpg');
foreach ($images as $image){


   //$src= $image;
    $path_parts= pathinfo($image);

 echo("dirname :<strong>". $path_parts['dirname']."</strong></p>");
 echo("basename :<strong>". $path_parts['basename']."</strong></p>");
 echo("filename :<strong>". $path_parts['filename']."</strong></p>");
 echo(" extension :<strong>". $path_parts['extension']."</strong></p>");
 echo(" Address :<strong>".$path_parts['dirname']."/".$path_parts['filename'].".".$path_parts['extension']."</strong></p>");
*/
$crop=glob('users/img/user/'.$_COOKIE['username'].'/crop/*.jpg');
foreach ($crop as $imageCropped){


    //$src= $image;
    $path_parts= pathinfo($imageCropped);

    echo("dirname :<strong>". $path_parts['dirname']."</strong></p>");
    echo("basename :<strong>". $path_parts['basename']."</strong></p>");
    echo("filename :<strong>". $path_parts['filename']."</strong></p>");
    echo(" extension :<strong>". $path_parts['extension']."</strong></p>");
    echo(" Address :<strong>".$path_parts['dirname']."/".$path_parts['filename'].".".$path_parts['extension']."</strong></p>");



}
