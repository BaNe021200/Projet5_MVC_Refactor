<?php
declare(strict_types=1);
session_start();
require_once 'vendor/autoload.php';
require_once 'model/Autoloader.php';
require_once 'controler/backend.php';
require_once 'lib/Session.php';
use model\Autoloader;
Autoloader::register();








        function twigRender($renderPath,$argument1,$argument2,$argument3,$argument4)
        {
            //$user = new UserManager();
            $mailManager = new \model\MailsManager();




            // $userData = $user->userData();
            // $userDatum = $user->getUserMainData();

            if (file_exists("users/img/user/" . @$_COOKIE['username'] . "/crop/img_001-cropped-center.jpg")) {
                @$src = "users/img/user/" . $_COOKIE['username'] . "/crop/img_001-cropped-center.jpg";
            } else {
                @$src = "users/img/user/" . $_COOKIE['username'] . "/crop/img_001-cropped.jpg";
            }
           @$getSeenMessages=$mailManager->getMessages($_COOKIE['ID'],1);
           @$getArchiveMessages=$mailManager->getMessages($_COOKIE['ID'],2);
           @$countUnseenMessages = $mailManager->countMessages($_COOKIE['ID'],0);
            @$countSeenMessages = $mailManager->countMessages($_COOKIE['ID'],1);
            @$countArchivedMessage = $mailManager->countMessages($_COOKIE['ID'],2);
            @$countSentMessage = $mailManager->countSentMessages($_COOKIE['ID']);










            $loader = new Twig_Loader_Filesystem(__DIR__ . '/templates');
            $twig = new Twig_Environment($loader, [

                'cache' => false,//__DIR__.'/tmp',
                'debug' => true
            ]);
            $twig->addExtension(new Twig_Extension_Debug());
            $twig->addExtension(new Twig_Extensions_Extension_Text());
            //$twig->addGlobal('unreadMessages',$countUreadMessages);

           // $twig->addExtension(new Twig_Extension_Session());

            echo $twig->render($renderPath,[
                'userDatum' => $_SESSION,
                @'imageProfil'=>$src,
                @'Messagesread'=>$getSeenMessages,
                'archiveMessages'=>$getArchiveMessages,
                'unreadMessages'=>$countUnseenMessages,
                'countReadMessages'=>$countSeenMessages,
                'countArchiveMessages'=>$countArchivedMessage,
                'countSentMessages'=>$countSentMessage,




                $argument1 => $argument2,
                $argument3 => $argument4,



            ]);

        }



