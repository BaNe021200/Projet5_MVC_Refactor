<?php
declare(strict_types=1);
/*
require_once 'model/UserManager.php';
require_once 'model/User.php';
require_once 'model/Manager.php';
require_once 'lib/Session.php';*/

require_once 'resize2.php';
require_once 'twig.php';
require_once 'functions.php';
require_once 'crop.php';


//Autoloader_lib::register();

function home()
{


    $userManager= new \model\UserManager();
    $profils= $userManager->getProfils();


    twigRender('frontend/home.html.twig','userdata',$profils ,'','');
}

function signUp()
{



    twigRender('frontend/signUp.html.twig','session',$_SESSION,'','');
}


function homeUserFront($userId)
{
    $userManager= new \model\UserManager();
    $InfosManager= new \model\InfosuserManager();
    $data= $userManager->getProfil($userId);
    $infos=$InfosManager->read($userId);

    twigRender('frontend/homeUserFront.html.twig','data',$data,'userInfos',$infos);
}

function userGalerie($userId,$username)
{
    $imageManager = new \model\ThumbnailsManager();
    $usermanager=new \model\Manager();

    //$userGalerie= $user->frontUsergalerie($userId,$username);
    $userGalerie= $imageManager->read($userId);
    $username=$usermanager->readQItemUser($username,"username");



    twigRender('frontend/userGalerie.html.twig','images',$userGalerie,'username',$username);
}


function frontGalerieViewer($imageId,$username)
{

    $imageManager=new \model\ImagesManager();
    $view = $imageManager->read($imageId);


    twigRender('frontend/frontGalerieViewer.html.twig','view',$view,'username',$username);
}




function get_registry()
{
    $hashPwd = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $JSON= json_encode(["ROLE_USER"]);


    $user = new \model\Projet5_user();
    $manager= new \model\Manager();



    $user
        ->setGender($_POST['gender'])
        ->setFirstName($_POST['first_name'])
        ->setLastName($_POST['last_name'])
        ->setUsername($_POST['username'])
        ->setBirthday($_POST['birthday'])
        ->setEmail($_POST['email'])
        ->setPassword($hashPwd)
        ->setRole($JSON);


    $userManager= new \model\UserManager();
    $addUser= $userManager->save($user);
    $finalUpdate=$manager->newUserFinalUpdate($user);
    $newUser=$userManager->read($user->getId());




    $message=[];
    $messagemail ='
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Mail</title>
</head>
<body>

<p>
    bonjour '.$newUser->getFirstName(). ' ! Bienvenue !  votre inscription est confirmé. Notez votre pseudo : '.$newUser->getUsername(). '<br> Votre mot de passe est celui que vous avez tapé pour vous inscrire. Merci et à bientôt

</p>



</body></html>' ;



    if($addUser)
    {


        $message[] = 'Bienvenue ! votre inscription à bien été prise en compte, vous pouvez désormais vous connecter avec vos idenfiants. Un mail vous a été envoyé';
    }
    else
    {
        $message[]= 'Nous sommes navrés mais un erreur est survenue et votre inscription n\'a pas pu est prise en compte. vous êtes invitez à recommencer ultérieurement ';
    }
    session_destroy();
       mail($newUser->getEmail(),'Confirmation d\'incription','bonjour '.$newUser->getFirstName(). ' ! Bienvenue !  votre inscription est confirmé. Notez votre pseudo : '.$newUser->getUsername(). '<br> Votre mot de passe est celui que vous avez tapé pour vous inscrire. Merci et à bientôt' );




    twigRender('frontend/registrySucces.html.twig','message',$message,'','');

}




function sendMessage($expeditor, $receiver)
{

    $message = new \model\Projet5_mails();
    $message
        ->setExpeditor(intval($_GET['expeditor']))
        ->setReceiver(intval($_GET['receiver']))
        ->setTitle($_POST['title'])
        ->setMessage($_POST['message']);

    $mailManager = new \model\MailsManager();
    $sendMessage = $mailManager->create($message);


    if ($sendMessage)
    {
        $Session = new \model\Session();
        $Session->setFlash('votre message est envoyé','success');
        $Session->flash();
        //header('Location:index.php?p=homeUserFront&userId='.$receiver);

       twigRender('homeUser.html.twig','','','','');
    }
    else
    {
        $Session = new Session();
        $Session->setFlash('Une erreur est survenue votre message n\'est pas envoyé','danger');
        $Session->flash();
        twigRender('homeUser.html.twig','','','','');
    }










}