<?php
declare(strict_types=1);
session_start();
require_once 'vendor/autoload.php';
require_once 'model/functions.php';
require_once 'model/UserManager.php';
require_once 'controler/backend.php';
require_once 'lib/Session.php';
//require_once 'vendor/twig/twig/lib/Twig/Extension/Session.php';
use model\UserManager;









        function twigRender($renderPath,$argument1,$argument2,$argument3,$argument4)
        {
            $user = new UserManager();





            // $userData = $user->userData();
            // $userDatum = $user->getUserMainData();

            if (file_exists("users/img/user/" . @$_COOKIE['username'] . "/crop/img_001-cropped-center.jpg")) {
                @$src = "users/img/user/" . $_COOKIE['username'] . "/crop/img_001-cropped-center.jpg";
            } else {
                @$src = "users/img/user/" . $_COOKIE['username'] . "/crop/img_001-cropped.jpg";
            }

            @$getArchiveMessages=$user->getArchiveMessages($_COOKIE['ID']);
            @$getReadMessages=$user->getReadMessages($_COOKIE['ID']);
            @$countUreadMessages = $user->countUnreadMessage($_COOKIE['ID']);
            @$countReadMessages = $user->countReadMessage($_COOKIE['ID']);
            @$archiveMessage = $user->countArchiveMessage($_COOKIE['ID']);
            @$countSentMessage = $user->countSentMessages($_COOKIE['ID']);










            $loader = new Twig_Loader_Filesystem(__DIR__ . '/templates');
            $twig = new Twig_Environment($loader, [

                'cache' => false,//__DIR__.'/tmp',
                'debug' => true
            ]);
            $twig->addExtension(new Twig_Extension_Debug());
            $twig->addExtension(new Twig_Extensions_Extension_Text());
            $twig->addGlobal('unreadMessages',$countUreadMessages);

           // $twig->addExtension(new Twig_Extension_Session());

            echo $twig->render($renderPath,[
                'userDatum' => $_SESSION,
                @'imageProfil'=>$src,
                'Messagesread'=>$getReadMessages,
                'archiveMessages'=>$getArchiveMessages,
               // 'unreadMessages'=>$countUreadMessages,
                'countReadMessages'=>$countReadMessages,
                'countArchiveMessages'=>$archiveMessage,
                'countSentMessages'=>$countSentMessage,




                $argument1 => $argument2,
                $argument3 => $argument4,



            ]);

        }



