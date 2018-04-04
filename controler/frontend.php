<?php
declare(strict_types=1);

require_once 'model/UserManager.php';
require_once 'twig.php';
require_once 'functions.php';
require_once 'model/User.php';
require_once 'resize2.php';
require_once 'crop.php';
require_once 'lib/Session.php';

use model\UserManager;
use model\User;



function homeUserFront($userId)
{
    $user = new UserManager();
    $data= $user->getUserProfile($userId);
    $infos=$user->getUserInfos($userId);

    twigRender('frontend/homeUserFront.html.twig','data',$data,'userInfos',$infos);
}

function userGalerie($userId,$username)
{
    $user= new UserManager();
    $userGalerie= $user->frontUsergalerie($userId,$username);

    twigRender('frontend/userGalerie.html.twig','images',$userGalerie,'','');
}

function get_registry()
{

    $user = new UserManager();

    $newUser = $user->addUser();




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
    bonjour '.$newUser['first_name']. ' ! Bienvenue !  votre inscription est confirmé. Notez votre pseudo : '.$newUser['username']. '<br> Votre mot de passe est celui que vous avez tapé pour vous inscrire. Merci et à bientôt

</p>



</body></html>' ;



    if($newUser)
    {


        $message[] = 'Bienvenue ! votre inscription à bien été prise en compte, vous pouvez désormais vous connecter avec vos idenfiants';
    }
    else
    {
        $message[]= 'Nous sommes navrés mais un erreur est survenue et votre inscription n\'a pas pu est prise en compte. vous êtes invitez à recommencer ultérieurement ';
    }
    session_destroy();
       mail($newUser['email'],'Confirmation d\'incription','bonjour '.$newUser['first_name']. ' ! Bienvenue !  votre inscription est confirmé. Notez votre pseudo : '.$newUser['username']. '<br> Votre mot de passe est celui que vous avez tapé pour vous inscrire. Merci et à bientôt' );




    twigRender('frontend/registrySucces.html.twig','message',$message,'','');

}

function home()
{
    $user=new UserManager();
    $usersProfilPictures=$user->getUserProfilePictures();

    twigRender('frontend/home.html.twig','userdata',$usersProfilPictures ,'','');
}

function signUp()
{


    twigRender('frontend/signUp.html.twig','session',$_SESSION,'','');
}

function sendMessage($expeditor, $receiver)
{

    $user= New UserManager();
    $sendMessage = $user->sendMail($expeditor, $receiver);


    if ($sendMessage)
    {
        $Session = new Session();
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