<?php
declare(strict_types=1);

function newFolder()
{
    $folder='users/img/user/'.$_COOKIE['username'];

    mkdir($folder);
}

function newFolderCrop()
{
    $folderCrop='users/img/user/'.$_COOKIE['username'].'/crop';

    mkdir($folderCrop);
}

function newFolderThumbnails()
{
    $folderThumbnails='users/img/user/'.$_COOKIE['username'].'/thumbnails';

    mkdir($folderThumbnails);
}

function newFolderProfilPicture()
{
    $folderProfilPicture = 'users/img/user/'.$_COOKIE['username'].'/profilPicture';
    mkdir($folderProfilPicture);
}

function str_random($length){
    $alphabet = "0123456789azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN";
    return substr(str_shuffle(str_repeat($alphabet, $length)), 0, $length);
}

function displayUserName($name)
{
    $username=$name;

    $username = str_replace('_',' ',$username);
}

function resizeByHeight()
{
    $images= glob('users/img/user/'.$_COOKIE['username'].'/*.jpg');
    foreach ($images as $image)
    {
        $src=$image;//var_dump($src);die;
        $image= imagecreatefromjpeg($src);
        $imageSize = getimagesize($src);



        $newHeight = 700;
        $newWidth= intval(($imageSize[0] * (($newHeight)/$imageSize[1])));//var_dump($newHeight);

        $newImage = imagecreatetruecolor($newWidth,$newHeight);
        imagecopyresampled($newImage, $image,0,0,0,0,$newWidth, $newHeight,$imageSize[0],$imageSize[1]);

        imagejpeg($newImage,$src);
        imagedestroy($image);



    }

}

/*function newpage(){

    if(!isset ($_GET['p'])){

        for($i=1; $i<=$nbPage; $i++){
            $_GET['p']== $i;
        }
    }



}*/




//upload_max_filesize = 40M