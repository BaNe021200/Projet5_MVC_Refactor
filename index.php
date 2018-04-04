<?php
declare(strict_types=1);
require_once 'twig.php';
require_once 'controler/backend.php';
require_once 'controler/frontend.php';


try
{

if (isset($_GET['p']))
{
    if ($_GET['p'] == 'home'){
        if(isset($_COOKIE['ID'])&& isset($_COOKIE['username']))
        {
            require_once 'listProfils.php';
        }
        else
        {
            home();
        }


    }



    elseif ($_GET['p'] == 'listProfile'){
        if(isset($_COOKIE['ID'])&& isset($_COOKIE['username'])){

            require_once 'listProfils.php';
            //listProfile();

        }
        else
        {
            throw new Exception("Erreur vous n'êtes pas connectez. Veuillez vous identifier");
        }
    }

    elseif ($_GET['p'] == 'homeUser'){
        if(isset($_COOKIE['ID'])&& isset($_COOKIE['username'])){
            
            homeUser();

        }
        else
        {
            throw new Exception("Erreur vous n'êtes pas connectez. Veuillez vous identifier");
        }
    }

    elseif ($_GET['p'] == 'homeUserFront'){
        if(isset($_COOKIE['ID'])&& isset($_COOKIE['username'])){

            homeUserFront($_GET['userId']);

        }
        else
        {
            throw new Exception("Erreur vous n'êtes pas connectez. Veuillez vous identifier");
        }

    }

    elseif ($_GET['p'] == 'userGalerie'){
        if(isset($_COOKIE['ID'])&& isset($_COOKIE['username'])){

            userGalerie($_GET['userId'],$_GET['username']);

        }
        else
        {
            throw new Exception("Erreur vous n'êtes pas connectez. Veuillez vous identifier");
        }

    }

    elseif ($_GET['p'] == 'galerie1'){
        if(isset($_COOKIE['ID'])&& isset($_COOKIE['username'])){

            galerie1();
        }
        else
        {
            throw new Exception("Erreur vous n'êtes pas connectez. Veuillez vous identifier");
        }
    }


    elseif ($_GET['p'] == 'infosUser'){
        if(isset($_COOKIE['ID'])&& isset($_COOKIE['username'])){

            infosUser();
        }
        else
        {
            throw new Exception("Erreur vous n'êtes pas connectez. Veuillez vous identifier");
        }
    }

    elseif ($_GET['p'] == 'saveUserinfos'){
        if(isset($_COOKIE['ID'])&& isset($_COOKIE['username'])){

            saveUserinfos($_GET['userid']);
        }
        else
        {
            throw new Exception("Erreur vous n'êtes pas connectez. Veuillez vous identifier");
        }
    }

    elseif ($_GET['p'] == 'deleteUserInfos'){
        if(isset($_COOKIE['ID'])&& isset($_COOKIE['username'])){

            deleteUserInfos($_GET['userid']);
        }
        else
        {
            throw new Exception("Erreur vous n'êtes pas connectez. Veuillez vous identifier");
        }
    }

    elseif ($_GET['p'] == 'signUp'){
        if(isset($_COOKIE['ID'])&& isset($_COOKIE['username']))
        {
            homeUser();
        }
        else
            {
                signUp();
            }


    }

    elseif($_GET['p']== 'register')
    {
        //;


            if (!empty($_POST['first_name']) || !empty($_POST['last_name']))
            {
                $_SESSION['first_name']=$_POST['first_name'];
                $_SESSION['last_name']=$_POST['last_name'];
                $_SESSION['username']=$_POST['username'];
                $_SESSION['email']=$_POST['email'];
                $_SESSION['birthday']=$_POST['birthday'];

                if(preg_match('/^[a-zéèA-Z-]+$/', $_POST['first_name']) && preg_match('/^[a-zéèA-Z-]+$/',($_POST['last_name'])))
                    {

                        if (empty($_POST['birthday']))
                        {
                            throw new Exception('Le champ anniversaire doit être rempli');
                        }


                        elseif (empty($_POST['email']) || empty($_POST['password']))
                        {
                            throw new Exception('Les champs email et mot de passe doivent être remplis');
                        }

                        if (empty($_POST['username']))
                        {
                            throw new Exception('Le champ pseudo doit être remplis');

                        }
                        elseif (preg_match('/^[a-zA-Z0-9_]+$/',$_POST['username'] ))
                        {
                            controlUsername($_POST['username']);
                        }
                        else
                        {
                            throw new Exception("le champs pseudos n'accepte que des lettres (majuscules et/ou minuscules).Les espaces sont représentés par des tirets bas (underscores : '_') et apparaitront sur le site. ");
                        }



                    }
                else
                {
                    throw new Exception('Vos noms et prénoms ne peuvent contenir que des minuscules et des majuscules ainsi que des tirets (-) ');
                }
            }
             else
            {
                throw new Exception('Les champs noms et prénoms doivent être remplis');
            }




    }

    elseif ($_GET['p'] == 'connexion'){
        if(isset($_COOKIE['ID'])&& isset($_COOKIE['username'])){
            homeUser();
        }
        else
            {
                connectUser();
            }
    }

    elseif ($_GET['p'] == 'getConnexion'){
        authentificationConnexion();
    }

    elseif ($_GET['p'] == 'deconnexion'){
        disconnectUser();
    }

    elseif ($_GET['p'] == 'upload'){

        uploadPicture($_COOKIE['ID'],$_GET['id']);
    }

    elseif ($_GET['p'] == 'galerie3'){
        if(isset($_COOKIE['ID'])&& isset($_COOKIE['username'])){
            if (file_exists("users/img/user/" . $_COOKIE['username'] . "/crop/img_001-cropped-center.jpg")) {
                $src = "users/img/user/" . $_COOKIE['username'] . "/crop/img_001-cropped-center.jpg";
            } else {
                $src = "users/img/user/" . $_COOKIE['username'] . "/crop/img_001-cropped.jpg";
            }
            getUserImages($_COOKIE['ID']);
        }
        else
        {
            throw new Exception("Erreur vous n'êtes pas connectez. Veuillez vous identifier");
        }
    }

    elseif ($_GET['p'] == 'deleteImage'){
        if(isset($_COOKIE['ID'])&& isset($_COOKIE['username'])){

            deleteImage($_COOKIE['ID'],$_GET['id']);
        }
        else
        {
            throw new Exception("Erreur vous n'êtes pas connectez. Veuillez vous identifier");
        }
    }

    elseif ($_GET['p'] == 'eraseProfil'){
        if(isset($_COOKIE['ID'])&& isset($_COOKIE['username'])){



            eraseUser($_COOKIE['ID']);

        }
        else
        {
            throw new Exception("Erreur vous n'êtes pas connectez. Veuillez vous identifier");
        }
    }

    elseif ($_GET['p'] == 'recropped'){
        if(isset($_COOKIE['ID'])&& isset($_COOKIE['username'])){
            recropped($_COOKIE['ID'],$_GET['id']);
        }
        else
        {
            throw new Exception("Erreur vous n'êtes pas connectez. Veuillez vous identifier");
        }
    }

    elseif ($_GET['p'] == 'CroppedChoice'){
        if(isset($_COOKIE['ID'])&& isset($_COOKIE['username'])){
            croppedChoice($_COOKIE['ID'],$_GET['id']);
        }
        else
        {
            throw new Exception("Erreur vous n'êtes pas connectez. Veuillez vous identifier");
        }
    }

    elseif ($_GET['p'] == 'viewerGalerie'){
        if(isset($_COOKIE['ID'])&& isset($_COOKIE['username'])){
            viewerGalerie($_GET['id']);
        }
        else
        {
            throw new Exception("Erreur vous n'êtes pas connectez. Veuillez vous identifier");
        }
    }

    elseif ($_GET['p'] == 'frontGalerieViewer'){
        if(isset($_COOKIE['ID'])&& isset($_COOKIE['username'])){
            frontGalerieViewer($_GET['id'],$_GET['username']);
        }
        else
        {
            throw new Exception("Erreur vous n'êtes pas connectez. Veuillez vous identifier");
        }
    }

    elseif ($_GET['p'] == 'viewer2'){
        if(isset($_COOKIE['ID'])&& isset($_COOKIE['username'])){
            viewerGalerie($_GET['img']);
        }
        else
        {
            throw new Exception("Erreur vous n'êtes pas connectez. Veuillez vous identifier");
        }
    }

    elseif ($_GET['p'] == 'test'){

            require_once 'test_form.php';

    }

    elseif ($_GET['p'] == 'messages'){
        if(isset($_COOKIE['ID'])&& isset($_COOKIE['username'])){
            messages($_COOKIE['ID']);
        }
        else
        {
            throw new Exception("Erreur vous n'êtes pas connectez. Veuillez vous identifier");
        }
    }

    elseif ($_GET['p'] == 'readMessage'){
        if(isset($_COOKIE['ID'])&& isset($_COOKIE['username'])){

            readUnreadMessages($_GET['messageId'],$_COOKIE['ID']);

        }
        else
        {
            throw new Exception("Erreur vous n'êtes pas connectez. Veuillez vous identifier");
        }
    }

    elseif ($_GET['p'] == 'readArchivedMessages'){
        if(isset($_COOKIE['ID'])&& isset($_COOKIE['username'])){

            readArchivedMessages($_GET['messageId'],$_COOKIE['ID']);

        }
        else
        {
            throw new Exception("Erreur vous n'êtes pas connectez. Veuillez vous identifier");
        }
    }

    elseif ($_GET['p'] == 'deleteMessage'){
        if(isset($_COOKIE['ID'])&& isset($_COOKIE['username'])){

            deleteMessage($_GET['messageId']);

        }
        else
        {
            throw new Exception("Erreur vous n'êtes pas connectez. Veuillez vous identifier");
        }
    }





    elseif ($_GET['p'] == 'archiveMessages'){
        if(isset($_COOKIE['ID'])&& isset($_COOKIE['username'])){

            archiveMessages($_GET['messageId'],$_COOKIE['ID']);

        }
        else
        {
            throw new Exception("Erreur vous n'êtes pas connectez. Veuillez vous identifier");
        }
    }
    elseif ($_GET['p'] == 'sentMessages'){
        if(isset($_COOKIE['ID'])&& isset($_COOKIE['username'])){

            sentMessages($_GET['messageId'],$_COOKIE['ID']);

        }
        else
        {
            throw new Exception("Erreur vous n'êtes pas connectez. Veuillez vous identifier");
        }
    }




    elseif ($_GET['p'] == 'writeMessage'){
        if(isset($_COOKIE['ID'])&& isset($_COOKIE['username'])){
            writeMessage($_COOKIE['ID']);
        }
        else
        {
            throw new Exception("Erreur vous n'êtes pas connectez. Veuillez vous identifier");
        }
    }

    elseif ($_GET['p'] == 'sendMessage'){
        if(isset($_COOKIE['ID'])&& isset($_COOKIE['username'])){
            sendMessage($_GET['expeditor'],$_GET['receiver']);
        }
        else
        {
            throw new Exception("Erreur vous n'êtes pas connectez. Veuillez vous identifier");
        }
    }

    elseif ($_GET['p'] == 'messageToWebmaster')
    {
        if(isset($_COOKIE['ID'])&& isset($_COOKIE['username'])){
            sendMessageToWebmaster($_GET['expeditor'],$_GET['receiver']);
        }
        else
        {
            throw new Exception("Erreur vous n'êtes pas connectez. Veuillez vous identifier");
        }
    }








    else{
        throw new Exception('This page doesn\'t exist');
    }
}


else
{
    home();
}

}


catch (Exception $e)
{
    $errorMessage= $e->getMessage();
    $bg_ramdom = mt_rand(1, 4);


   // require('templates/404.html.twig');
    twigRender('404.html.twig','errorMessage',$errorMessage,'bgRamdom',$bg_ramdom);



}







