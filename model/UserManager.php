<?php
/**
 * Created by PhpStorm.
 * User: connector
 * Date: 14/02/2018
 * Time: 19:39
 */

namespace model;
require_once 'model/Manager.php';
require_once 'lib/DataBase.php';
require_once 'lib/DbConnect.php';
use model\Manager;

use PDO;

class UserManager extends Manager
{
        public function addUser(){

        $hashPwd = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $JSON= json_encode(["ROLE_USER"]);
        //$userAge=timestampdiff(YEAR,birthday,now());
        $pdo=$this->dbConnect();//var_dump($pdo);die;
        $PdoStat = $pdo->prepare('INSERT INTO projet5_user VALUES (NULL,:gender, :first_name, :last_name, :username,:birthday,:age,:email,:password, NOW(),NULL,NULL,:roles)');
        $PdoStat->bindValue(':gender', $_POST['gender'], PDO::PARAM_STR);
        $PdoStat->bindValue(':first_name', $_POST['first_name'], PDO::PARAM_STR);
        $PdoStat->bindValue(':last_name', $_POST['last_name'], PDO::PARAM_STR);
        $PdoStat->bindValue(':username', $_POST['username'], PDO::PARAM_STR);
        $PdoStat->bindValue(':birthday', $_POST['birthday'], PDO::PARAM_STR);
        $PdoStat->bindValue(':age',0, PDO::PARAM_INT);
        $PdoStat->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
        $PdoStat->bindValue(':password', $hashPwd, PDO::PARAM_STR);
        $PdoStat->bindValue(':roles', $JSON, PDO::PARAM_STR);
        $newUser= $PdoStat->execute();
        $lastId= $pdo->lastInsertId();
        $PdoStat->closeCursor();
        $PdoStat= $pdo->prepare("UPDATE projet5_user SET username = REPLACE(username,'_',' ')
        WHERE username=:username");
        $PdoStat->bindValue(':username',$_POST['username'],PDO::PARAM_STR);
        $newUser=$PdoStat->execute();
        $PdoStat->closeCursor();
        $PdoStat=$pdo->prepare('UPDATE projet5_user SET user_age= timestampdiff(YEAR,birthday,now())
        WHERE id=:id');
        $PdoStat->bindValue(':id',$lastId,PDO::PARAM_INT);
        $userAge=$PdoStat->execute();
        $PdoStat->closeCursor();
        $PdoStat=$pdo->prepare('
        SELECT first_name,email,username,user_age
        FROM projet5_user
        WHERE id=:id');
        $PdoStat->bindValue(':id',$lastId,PDO::PARAM_INT);
        $newUser = $PdoStat->execute();
        $newUser = $PdoStat->fetch();

        return $newUser;
        }

        public function eraseUser($userId)
        {
            $pdo=$this->dbConnect();
            $PdoStat=$pdo->prepare('
            DELETE 
            FROM projet5_images
            WHERE user_id= :id');
            $PdoStat->bindValue(':id',$userId,PDO::PARAM_INT);
            $eraseImagesUser= $PdoStat->execute();
            $PdoStat->closeCursor();
            $PdoStat=$pdo->prepare('
            DELETE 
            FROM projet5_thumbnails
            WHERE user_id= :id');
            $PdoStat->bindValue(':id',$userId,PDO::PARAM_INT);
            $eraseUser= $PdoStat->execute();
            $PdoStat->closeCursor();
            $PdoStat=$pdo->prepare('
            DELETE 
            FROM projet5_mails
            WHERE expeditor= :id OR receiver=:id');
            $PdoStat->bindValue(':id',$userId,PDO::PARAM_INT);
            $eraseUser= $PdoStat->execute();
            $PdoStat->closeCursor();
            $PdoStat=$pdo->prepare('
            DELETE 
            FROM projet5_infosuser
            WHERE user_id= :id');
            $PdoStat->bindValue(':id',$userId,PDO::PARAM_INT);
            $eraseUser= $PdoStat->execute();
            $PdoStat->closeCursor();
            $PdoStat=$pdo->prepare('
            DELETE 
            FROM projet5_user
            WHERE id= :id');
            $PdoStat->bindValue(':id',$userId,PDO::PARAM_INT);
            $eraseUser= $PdoStat->execute();
            return $eraseUser;

        }


        public function getUserMainData()
        {

            $pdo=$this->dbConnect();
            $PdoStat=$pdo->prepare('SELECT id,gender, first_name, username  FROM projet5_user WHERE username=:username');
            $PdoStat->bindValue(':username',$_POST['username'],PDO::PARAM_STR);
            $userMainData = $PdoStat->execute();
            $userMainData = $PdoStat->fetch();//var_dump($userMainData);die;
            return $userMainData;
        }

        /*public function userData(){

        $pdo=$this->dbConnect();
        $userData = $pdo->query('SELECT * FROM projet5_user');
        return $userData;
        }*/

        /*
         * controlUsername controle dans la bdd si l'username existe déjà
         */
        public function controlUsername()
        {
            $username= $_POST['username'];
            $pdo= $this->dbConnect();
            $PdoStat = $pdo->query("SELECT id FROM projet5_user WHERE username = '$username'  ");
            $veryUsername= $PdoStat->execute();
            $veryUsername= $PdoStat->fetch();
            $getusernameId= $veryUsername['id'];
            return $getusernameId;
        }

        /*
         * getEmail controle dans la bdd si l'email existe déjà
         */
        public function getEmail()
        {
            $email= $_POST['email'];
            $pdo= $this->dbConnect();
            $PdoStat = $pdo->query("SELECT id FROM projet5_user WHERE email = '$email'  ");
            $veryEmail= $PdoStat->execute();
            $veryEmail= $PdoStat->fetch();
            $getEmailId= $veryEmail['id'];
            return $getEmailId;
        }

        public function getMail($userId)
        {
            $pdo= $this->dbConnect();
            $PdoStat = $pdo->prepare("SELECT email FROM projet5_user WHERE id=:id ");
            $PdoStat->bindValue(':id',$userId,PDO::PARAM_INT);
            $getMail=$PdoStat->execute();
            $getMail=$PdoStat->fetch();

            return $getMail;
        }






        public function getConnexion()
    {

        $pwd=$_POST['password'];


        $pdo=$this->dbConnect();
        $PdoStat=$pdo->prepare('SELECT id, password,gender  FROM projet5_user WHERE username=:username');
        $PdoStat->bindValue(':username',$_POST['username'],PDO::PARAM_STR);
        $hashedPass = $PdoStat->execute();
        $hashedPass = $PdoStat->fetch();
        $verifyPassword = $hashedPass['password'];
        return $verifyPassword;
    }

        public function getUserName($id)
        {
            $pdo=$this->dbConnect();
            $PdoStat=$pdo->prepare('
            SELECT id, username
            FROM projet5_user
            WHERE id=:id');
            $PdoStat->bindValue(':id',$id,PDO::PARAM_INT);
            $UserName=$PdoStat->execute();
            $UserName=$PdoStat->fetch();

            return $UserName;
        }

        public function isConnected($id)
        {
            $pdo=$this->dbConnect();
            $PdoStat=$pdo->prepare('
            UPDATE projet5_user
            SET connected =:connected
            WHERE id=:id');
            $PdoStat->bindValue(':id',$id,PDO::PARAM_INT);
            $PdoStat->bindValue(':connected',1,PDO::PARAM_INT);
            $isconnected= $PdoStat->execute();

            return $isconnected;

        }

        public function getConnectedUsers()
        {
            $pdo=$this->dbConnect();
            $PdoStat=$pdo->prepare('
                SELECT username, connected
                FROM projet5_user
                WHERE connected IS NULL limit 0,6');
            $connected= $PdoStat->execute();
            $connected= $PdoStat->fetchAll();

            return $connected;

        }



        /*
         * function isConnectedSelf($id)
         * rajoute 1 dans le champ connectedSezlf de la BDD
         */
        public function isConnectedSelf($id)
        {
            $pdo=$this->dbConnect();
            $PdoStat=$pdo->prepare('
            UPDATE projet5_user
            SET connected = :connected, connected_self =:connectedSelf
            WHERE id=:id');
            $PdoStat->bindValue(':id',$id,PDO::PARAM_INT);
            $PdoStat->bindValue(':connected',1,PDO::PARAM_INT);
            $PdoStat->bindValue(':connectedSelf',1,PDO::PARAM_INT);
            $isconnectedSelf= $PdoStat->execute();

            return $isconnectedSelf;

        }

    /**
     * @param $userId
     * @param $photoId
     * @return array|bool
     * getPhoto2Thumb récupère l'id pour la fonction thum
     */
        public function getPhoto2Thumb($userId,$photoId)
        {
            $pdo=$this->dbConnect();

            $PdoStat=$pdo->prepare('SELECT * FROM projet5_images WHERE id=:id  AND user_id=:userId ');

            $PdoStat->bindValue(':id',$photoId,PDO::PARAM_STR);
            $PdoStat->bindValue(':userId',$userId,PDO::PARAM_STR);

            $userImages=$PdoStat->execute();

            $userImages = $PdoStat->fetchAll();
            return $userImages;
        }

    /**
     * @param $userId
     * @param $img
     * @return string
     * ajoute les images à la bdd
     */
        public function addUserPicture($userId,$img)
        {

            $path_parts= pathinfo($img);
            $path_parts['dirname'];
            $path_parts['filename'];
            $path_parts['extension'];
            $pdo=$this->dbConnect();
            $PdoStat= $pdo->prepare('INSERT INTO projet5_images VALUES(NULL,:userId,:dirname, :filename ,:extension) ');
            $PdoStat->bindValue(':userId',$userId,PDO::PARAM_STR);
            $PdoStat->bindValue(':dirname',$path_parts['dirname'],PDO::PARAM_STR);
            $PdoStat->bindValue(':filename',$path_parts['filename'],PDO::PARAM_STR);
            $PdoStat->bindValue(':extension',$path_parts['extension'],PDO::PARAM_STR);
            $addImage = $PdoStat->execute();
            $lastId=$pdo->lastInsertId();
            return $lastId;
        }

    /**
     * @param $userId
     * @param $img
     * @return bool
     * ajoute les crop à la bdd
     */
        public function addCropFiles($userId,$img)
        {


            $pdo=$this->dbConnect();
            $PdoStat= $pdo->prepare('INSERT INTO projet5_images VALUES(NULL,:userId,:dirname, :filename ,:extension) ');
            $PdoStat->bindValue(':userId',$userId,PDO::PARAM_STR);
            $PdoStat->bindValue(':dirname','users/img/user/'.$_COOKIE['username'].'/crop',PDO::PARAM_STR);
            $PdoStat->bindValue(':filename','img_00'.$img.'-cropped',PDO::PARAM_STR);
            $PdoStat->bindValue(':extension','jpg',PDO::PARAM_STR);
            $addCropedImage = $PdoStat->execute();
            return $addCropedImage;
         }


    /**
     * @param $userId
     * @param $img
     * @return bool|mixed
     * Aoute les crop center à la bdd
     */
         public function addCropCenterFiles($userId,$img)
        {


            $pdo=$this->dbConnect();
            $PdoStat= $pdo->prepare('INSERT INTO projet5_images VALUES(NULL,:userId,:dirname, :filename ,:extension) ');
            $PdoStat->bindValue(':userId',$userId,PDO::PARAM_STR);
            $PdoStat->bindValue(':dirname','users/img/user/'.$_COOKIE['username'].'/crop',PDO::PARAM_STR);
            $PdoStat->bindValue(':filename','img_00'.$img.'-cropped-center',PDO::PARAM_STR);
            $PdoStat->bindValue(':extension','jpg',PDO::PARAM_STR);
            $addCropedImage = $PdoStat->execute();
            $lastId=$pdo->lastInsertId();
            $PdoStat->closeCursor();
            $PdoStat=$pdo->prepare('SELECT * FROM projet5_images WHERE id=:id AND user_id=:userId');
            $PdoStat->bindValue(':id',$lastId,PDO::PARAM_INT);
            $PdoStat->bindValue(':userId',$userId,PDO::PARAM_STR);
            $cropCenterFile=$PdoStat->execute();
            $cropCenterFile=$PdoStat->fetch();

            return $cropCenterFile;

        }

        public function addProfilPicture()
        {
            $pdo=$this->dbConnect();
            $PdoStat=$pdo->prepare('INSERT INTO projet5_images VALUES(NULL, :userid, :dirname,:filename ,:extension)');
            $PdoStat->bindValue(':userid',$_COOKIE['ID'],PDO::PARAM_STR);
            $PdoStat->bindValue(':dirname','users/img/user/'.$_COOKIE['username'].'/profilPicture',PDO::PARAM_STR);
            $PdoStat->bindValue(':filename','img-userProfil',PDO::PARAM_STR);
            $PdoStat->bindValue(':extension','jpg',PDO::PARAM_STR);
            $addProfilPicture = $PdoStat->execute();


        }


    /**
     * @param $userId
     * @param $photoId
     * @param $dirname
     * @param $fileName
     * ajoute les photos de profil à la bdd
     */
        public function addThumbnails($userId,$photoId,$dirname,$fileName)
        {
            $pdo=$this->dbConnect();
            $PdosStat=$pdo->prepare('REPLACE INTO projet5_thumbnails VALUES (NULL, :userId, :imageId,:thumbnail )');
            $PdosStat->bindValue(':userId',$userId,PDO::PARAM_STR);
            $PdosStat->bindValue(':imageId',$photoId,PDO::PARAM_STR);
            $PdosStat->bindValue(':thumbnail',$dirname.'/thumbnails/'.$fileName.'-thumb.jpg',PDO::PARAM_STR);
            $savedThumnail=$PdosStat->execute();

        }

        public function getThumbnails($userId)
        {
            $pdo=$this->dbConnect();
            $PdoStat=$pdo->prepare('SELECT * FROM projet5_thumbnails WHERE user_id=:userId ');
            $PdoStat->bindValue(':userId',$userId,PDO::PARAM_INT);
            $thumbnails=$PdoStat->execute();
            $thumbnails=$PdoStat->fetchAll();
            return $thumbnails;

        }

        /*
         * pas d'usage connu pour getUserFiles
         */
       /*public function getUserFiles($userId)
{



    $pdo=$this->dbConnect();
    $PdoStat=$pdo->prepare('SELECT * FROM projet5_images WHERE  user_id=:userId AND dirname=:dirname');
    $PdoStat->bindValue(':userId',$userId,PDO::PARAM_STR);
    $PdoStat->bindValue(':dirname','users/img/user/'.$_COOKIE['username'],PDO::PARAM_STR);
    $userImages=$PdoStat->execute();
    $userImages = $PdoStat->fetchAll();
    return $userImages;
}*/

    /**
     * @param $photoId
     * @return bool|mixed
     * récupère les photos des utilisateurs
     */
       public function getUserView($photoId)
        {
            $pdo=$this->dbConnect();
            $PdoStat=$pdo->prepare('SELECT * FROM projet5_images WHERE id=:id');
            $PdoStat->bindValue(':id',$photoId,PDO::PARAM_INT);
            $userView=$PdoStat->execute();
            $userView=$PdoStat->fetch();
            return $userView;
        }

        public function getFrontUserView($photoId,$username)
        {
            $pdo=$this->dbConnect();
            $PdoStat=$pdo->prepare('
            SELECT projet5_images.*, projet5_user.username 
            FROM projet5_images
            INNER JOIN projet5_user
            ON projet5_images.user_id = projet5_user.id
            WHERE projet5_images.id=:id AND username = :username
            ');

            $PdoStat->bindValue(':id',$photoId,PDO::PARAM_INT);
            $PdoStat->bindValue(':username',$username,PDO::PARAM_STR);

            $userView=$PdoStat->execute();
            $userView=$PdoStat->fetch();
            return $userView;
        }

        public function getAllFiles()
        {
            $pdo=$this->dbConnect();
            $PdoStat=$pdo->query('SELECT img_name FROM projet5_images');
            $files = $PdoStat->execute();
            $files = $PdoStat->fetchAll();

            return $files;
        }

        public function deleteImage($userId,$img){

            $pdo=$this->dbConnect();
            $PdoStat= $pdo->prepare('DELETE FROM projet5_images WHERE user_id=:userId AND filename=:filename ');
            $PdoStat->bindValue(':userId', $userId,PDO::PARAM_INT);
            $PdoStat->bindValue(':filename', 'img_00'.$img,PDO::PARAM_STR);
            $deletedImage=$PdoStat->execute();
            $PdoStat->closeCursor();
            $PdoStat= $pdo->prepare('DELETE FROM projet5_images WHERE user_id=:userId AND filename=:filename ');
            $PdoStat->bindValue(':userId', $userId,PDO::PARAM_INT);
            $PdoStat->bindValue(':filename', 'img_00'.$img.'-cropped',PDO::PARAM_STR);
            $deletedImage=$PdoStat->execute();
            $PdoStat->closeCursor();
            $PdoStat= $pdo->prepare('DELETE FROM projet5_images WHERE user_id=:userId AND filename=:filename ');
            $PdoStat->bindValue(':userId', $userId,PDO::PARAM_INT);
            $PdoStat->bindValue(':filename', 'img_00'.$img.'-cropped-center',PDO::PARAM_STR);
            $deletedImage=$PdoStat->execute();
            $PdoStat->closeCursor();
            $PdoStat= $pdo->prepare('DELETE FROM projet5_thumbnails WHERE user_id=:userId AND thumbnail=:thumbnail ');
            $PdoStat->bindValue(':userId', $userId,PDO::PARAM_INT);
            $PdoStat->bindValue(':thumbnail', 'users/img/user/'.$_COOKIE['username'].'/thumbnails/img_00'.$img.'-thumb.jpg',PDO::PARAM_STR);
            $deletedImage=$PdoStat->execute();


        }

        public function deleteImageCroppedCenter($userId,$img)
        {
            $pdo=$this->dbConnect();
            $PdoStat= $pdo->prepare('DELETE FROM projet5_images WHERE id=:id AND user_id=:userId ');
            $PdoStat->bindValue(':id', $img,PDO::PARAM_STR);
            $PdoStat->bindValue(':userId', $userId,PDO::PARAM_INT);

            $deletedImage=$PdoStat->execute();
        }


        /*
         * compmte le nombre de profil avec photos pour la pagination
         */
        public function homeDisplay()
        {

            $pdo = $this->dbConnect();
            $PdoStat = $pdo->query('
            SELECT COUNT(filename) AS nbUsers
            FROM projet5_images WHERE filename="img-userProfil"');
            $data = $PdoStat->execute();
            $data = $PdoStat->fetch();
            return $data;
        }

        public function getUserProfilePicture($currentPage,$perPage)
        {

            $pdo=$this->dbConnect();
            $PdoStat=$pdo->query('
            SELECT projet5_images.user_id,filename, projet5_user.id,username,user_age,registry_date,connected,projet5_infosuser.city
            FROM projet5_images
            JOIN projet5_user
            ON projet5_images.user_id = projet5_user.id
            LEFT JOIN projet5_infosuser
            ON projet5_images.user_id=projet5_infosuser.user_id
            WHERE projet5_images.filename="img-userProfil"

            AND projet5_user.connected_self IS NULL
            ORDER BY registry_date DESC LIMIT '.(($currentPage-1)*$perPage). ','.$perPage);

            $username= $PdoStat->execute();
            $username= $PdoStat->fetchAll();
            $PdoStat->closeCursor();
            $PdoStat=$pdo->query('
            
            SELECT city
            FROM projet5_infosuser');
            $city=$PdoStat->execute();
            $city=$PdoStat->fetchAll();

            return $username;
    }

        public function getUserProfilePictures()
        {

            $pdo=$this->dbConnect();
            $PdoStat=$pdo->query('
            SELECT projet5_images.user_id,filename, projet5_user.id,username,user_age,registry_date,connected,projet5_infosuser.city
            FROM projet5_images
            JOIN projet5_user
            ON projet5_images.user_id = projet5_user.id
            LEFT JOIN projet5_infosuser
            ON projet5_images.user_id=projet5_infosuser.user_id
            WHERE projet5_images.filename="img-userProfil"

            AND projet5_user.connected_self IS NULL
            ORDER BY registry_date DESC LIMIT 0,6');

            $username= $PdoStat->execute();
            $username= $PdoStat->fetchAll();

            return $username;
    }

        public function getUserProfile($userId)
         {
             $pdo=$this->dbConnect();
             $PdoStat=$pdo->prepare('
             SELECT projet5_images.dirname,filename,extension, projet5_user.id,username,gender,user_age
             FROM projet5_images
             INNER JOIN projet5_user
              ON projet5_images.user_id = projet5_user.id
             WHERE user_id = :userId AND filename=:filename');
             $PdoStat->bindValue(':userId',$userId,PDO::PARAM_INT);
             $PdoStat->bindValue(':filename',"img-userProfil",PDO::PARAM_STR);


             $PdoStat->execute();
             $data=$PdoStat->fetch();

             return $data;

         }

        public function disconnectUser($id){
            $pdo=$this->dbConnect();
            $PdoStat=$pdo->prepare('
            UPDATE projet5_user
            SET connected=:connected,connected_self =:connectedSelf
            WHERE id=:id');
            $PdoStat->bindValue(':id',$id,PDO::PARAM_INT);
            $PdoStat->bindValue(':connected',null,PDO::PARAM_INT);
            $PdoStat->bindValue(':connectedSelf',null,PDO::PARAM_INT);
            $disconnectUser= $PdoStat->execute();


        }

        public function frontUsergalerie($userId,$username)
         {
             $pdo=$this->dbConnect();
             $PdoStat=$pdo->prepare('
             SELECT projet5_thumbnails.image_id,thumbnail, projet5_user.id,username
             FROM projet5_thumbnails
             INNER JOIN projet5_user
             ON projet5_thumbnails.user_id = projet5_user.id
             WHERE user_id = :userId AND username=:username');
             $PdoStat->bindValue(':userId',$userId,PDO::PARAM_INT);
             $PdoStat->bindValue(':username',$username,PDO::PARAM_STR);

             $userGalerie = $PdoStat->execute();
             $userGalerie = $PdoStat->fetchAll();

             return $userGalerie;




         }


        public  function deleteUserProfilPicture()
        {
            $pdo=$this->dbConnect();
            $PdoStat=$pdo->prepare('
            DELETE FROM projet5_images WHERE user_id =:userId AND filename= :filename');
            $PdoStat->bindValue(':userId', $_COOKIE['ID'],PDO::PARAM_INT);
            $PdoStat->bindValue(':filename', 'img-userProfil',PDO::PARAM_STR);
            $deleteUserProfilPicture = $PdoStat->execute();


        }

        public function addUserInfos($userId)
        {
            $pdo=$this->dbConnect();
            $PdoStat=$pdo->prepare('REPLACE INTO projet5_infosuser VALUES (NULL,:userId,:search,:postal_code,:city,:purpose,:family,:children,:familyAdd,:physic,:speech,:school,:schoolAdd, :business,:buinessAdd)');
            $PdoStat->bindValue(':userId',$userId,PDO::PARAM_INT);
            $PdoStat->bindValue(':search',$_POST['search'],PDO::PARAM_STR);
            $PdoStat->bindValue(':postal_code',$_POST['postal_code'],PDO::PARAM_STR);
            $PdoStat->bindValue(':city',$_POST['city'],PDO::PARAM_STR);
            $PdoStat->bindValue(':purpose',$_POST['purpose'],PDO::PARAM_STR);
            $PdoStat->bindValue(':family',$_POST['family_situation'],PDO::PARAM_STR);
            $PdoStat->bindValue(':children',$_POST['children'],PDO::PARAM_STR);
            $PdoStat->bindValue(':familyAdd',$_POST['family_situation_add'],PDO::PARAM_STR);
            $PdoStat->bindValue(':physic',$_POST['physic_add'],PDO::PARAM_STR);
            $PdoStat->bindValue(':speech',$_POST['speech'],PDO::PARAM_STR);
            $PdoStat->bindValue(':school',$_POST['school_level'],PDO::PARAM_STR);
            $PdoStat->bindValue(':schoolAdd',$_POST['school_level_add'],PDO::PARAM_STR);
            $PdoStat->bindValue(':business',$_POST['work'],PDO::PARAM_STR);
            $PdoStat->bindValue(':buinessAdd',$_POST['work_add'],PDO::PARAM_STR);
            $addInfos = $PdoStat->execute();var_dump($addInfos);

            /*if($addInfos)
            {
                unset($_SESSION['postal_code']);
                unset($_SESSION['city']);
                unset($_SESSION['search']);
                unset($_SESSION['purpose']);
                unset($_SESSION['family_situation']);
                unset($_SESSION['children']);
                unset($_SESSION['family_situation_add']);
                unset($_SESSION['physic_add']);
                unset($_SESSION['speech']);
                unset($_SESSION['school_level']);
                unset($_SESSION['school_level_add']);
                unset($_SESSION['work']);
                unset($_SESSION['work_add']);
            }*/

        }

        public function getUserInfos($userId)
        {

            $pdo=$this->dbConnect();
            $PdoStat=$pdo->prepare('
            SELECT *
            FROM projet5_infosuser
            WHERE user_id=:userId');
            $PdoStat->bindValue(':userId',$userId,PDO::PARAM_INT);
            $userInfos= $PdoStat->execute();
            $userInfos=$PdoStat->fetchAll();

            return $userInfos;
        }

        function deleteUserInfos($userId)
        {

            $pdo=$this->dbConnect();
            $PdoStat=$pdo->prepare('
            DELETE 
            FROM projet5_infosuser
            WHERE user_id= :id');
            $PdoStat->bindValue(':id',$userId,PDO::PARAM_INT);
            $eraseUser= $PdoStat->execute();

            return $eraseUser;
        }





        public function whoIsOnLine($id,$ip)
        {

            $pdo=$this->dbConnect();
            $PdoStat=$pdo->prepare('INSERT INTO projet5_whosonline VALUES(:id, :time,:ip)
            ON DUPLICATE KEY UPDATE
            online_time = :time , online_id = :id');
            $PdoStat->bindValue(':id',$id,PDO::PARAM_INT);
            $PdoStat->bindValue(':time',time(), PDO::PARAM_INT);
            $PdoStat->bindValue(':ip', $ip, PDO::PARAM_INT);
            $PdoStat->execute();
            $PdoStat->CloseCursor();
            $time_max = time() - (60 * 5);
            $PdoStat=$pdo->prepare('DELETE FROM projet5_whosonline WHERE online_time < :timemax');
            $PdoStat->bindValue(':timemax',$time_max, PDO::PARAM_INT);
            $PdoStat->execute();
            $PdoStat->CloseCursor();

        }

        public function countWhoIsOnLine()
        {
            $count_online = 0;
            //count users
            $pdo=$this->dbConnect();
            $count_visitors=$pdo->query('SELECT COUNT(*) AS nbr_visiteurs FROM projet5_whosonline WHERE online_id = 0')->fetchColumn();
            $count_visitors->CloseCursor();

            //count Users online
            $texte_a_afficher = "<br />Liste des personnes en ligne : ";
            $time_max = time() - (60 * 5);
            $PdoStat=$pdo->prepare('SELECT id, username 
            FROM projet5_whosonline 
            LEFT JOIN projet5_user ON online_id = id
            WHERE online_time > :timemax AND online_id <> 0');
            $PdoStat->bindValue(':timemax',$time_max, PDO::PARAM_INT);
            $PdoStat->execute();
            $count_membres=0;
            while ($data = $PdoStat->fetch())
            {
                $count_membres ++;
                $texte_a_afficher .= '<a href="./voirprofil.php?m='.$data['membre_id'].'&amp;action=consulter">
	'.stripslashes(htmlspecialchars($data['membre_pseudo'])).'</a> ,';
            }

            $texte_a_afficher = substr($texte_a_afficher, 0, -1);
            $count_online = $count_visitors + $count_membres;
            echo '<p>Il y a '.$count_online.' connectés ('.$count_membres.' membres et '.$count_visitors.' invités)';
            echo $texte_a_afficher.'</p>';
            $PdoStat->CloseCursor();
        }

        public function getTowns()
        {
            $pdo=$this->dbConnect2();
            $PdoStat = $pdo->query('
            SELECT ville_nom_simple
            FROM villes_france_free');
            $towns=$PdoStat->execute();
            $towns=$PdoStat->fetchAll();

            return $towns;



        }

        public function sendMail($expeditor,$receiver)
        {
            $pdo=$this->dbConnect();
            $Pdostat= $pdo->prepare('
            INSERT INTO projet5_mails VALUES (NULL, :expeditor,:receiver,:title,:message,Now(),:see )');
            $Pdostat->bindValue(':expeditor',$expeditor,PDO::PARAM_INT);
            $Pdostat->bindValue(':receiver',$receiver,PDO::PARAM_INT);
            $Pdostat->bindValue(':title',$_POST['title'],PDO::PARAM_STR);
            $Pdostat->bindValue(':message',$_POST['message'],PDO::PARAM_STR);
            $Pdostat->bindValue(':see',0,PDO::PARAM_STR);
            $sendMessage=$Pdostat->execute();

            return $sendMessage;
        }


        public function getUnreadMessages($userId)
        {
            $pdo=$this->dbConnect();
            $PdoStat=$pdo->prepare('
            SELECT projet5_mails.id as mp_id,expeditor,receiver,title,message,DATE_FORMAT(time, \'%d/%m/%Y à %Hh%i\') AS time_date_fr,mp_read, projet5_user.id,username,projet5_images.user_id,dirname,filename,extension
            FROM projet5_mails
            JOIN projet5_user
            ON expeditor =projet5_user.id
            JOIN projet5_images
            ON user_id=projet5_user.id
            WHERE projet5_mails.receiver = :receiver AND mp_read=0 AND filename="img-userProfil" ORDER By time_date_fr DESC ');
            
            $PdoStat->bindValue(':receiver',$userId,PDO::PARAM_INT);
            $getMessage=$PdoStat->execute();
            $getMessage=$PdoStat->fetchAll();

            return $getMessage;


        }

        public function readUnreadMessages($messageId,$userId)
{
    $pdo=$this->dbConnect();
    $Pdostat=$pdo->prepare('
           SELECT projet5_mails.id as mp_id,expeditor,receiver,title,message,DATE_FORMAT(time, \' % d /%m /%Y à % Hh % i\') AS time_date_fr,mp_read, projet5_user.id,username,projet5_images.user_id,dirname,filename,extension
            FROM projet5_mails
            JOIN projet5_user
            ON expeditor =projet5_user.id
            JOIN projet5_images
            ON user_id=projet5_user.id
            WHERE projet5_mails.id=:id AND receiver = :receiver AND filename="img-userProfil"  ');
    $Pdostat->bindValue(':id',$messageId,PDO::PARAM_INT);
    $Pdostat->bindValue(':receiver',$userId,PDO::PARAM_INT);
    $Pdostat->bindValue(':id',$messageId,PDO::PARAM_INT);
    $readMessage=$Pdostat->execute();
    $readMessage=$Pdostat->fetch();
    $Pdostat->closeCursor();
    $Pdostat=$pdo->prepare('
            UPDATE projet5_mails
            SET mp_read=1
            WHERE projet5_mails.id = :mpId
            ');
    $Pdostat->bindValue('mpId',$messageId,PDO::PARAM_INT);
    $updateStatus= $Pdostat->execute();

    return $readMessage;


}

    public function readArchivedMessages($messageId,$userId)
    {
        $pdo=$this->dbConnect();
        $Pdostat=$pdo->prepare('
           SELECT projet5_mails.id as mp_id,expeditor,receiver,title,message,DATE_FORMAT(time, \' % d /%m /%Y à % Hh % i\') AS time_date_fr,mp_read, projet5_user.id,username,projet5_images.user_id,dirname,filename,extension
            FROM projet5_mails
            JOIN projet5_user
            ON expeditor =projet5_user.id
            JOIN projet5_images
            ON user_id=projet5_user.id
            WHERE projet5_mails.id=:id AND receiver = :receiver AND filename="img-userProfil"  ');
        $Pdostat->bindValue(':id',$messageId,PDO::PARAM_INT);
        $Pdostat->bindValue(':receiver',$userId,PDO::PARAM_INT);
        $Pdostat->bindValue(':id',$messageId,PDO::PARAM_INT);
        $readMessage=$Pdostat->execute();
        $readMessage=$Pdostat->fetch();

        return $readMessage;


    }
        public function deleteMessage($messageId)
        {
            $pdo=$this->dbConnect();
            $Pdostat=$pdo->prepare('
            DELETE
            FROM projet5_mails
            WHERE id = :id');
            $Pdostat->bindValue(':id',$messageId,PDO::PARAM_INT);
            $deleteMessage=$Pdostat->execute();

        }





        public function archiveMessages($messageId,$userId)
        {
            $pdo=$this->dbConnect();
            $Pdostat=$pdo->prepare('
            UPDATE projet5_mails
            SET mp_read=2
            WHERE projet5_mails.id = :mpId
            ');
            $Pdostat->bindValue('mpId',$messageId,PDO::PARAM_INT);
            $updateStatus= $Pdostat->execute();


        }


        public function getReadMessages($userId)
        {
            $pdo=$this->dbConnect();
            $PdoStat=$pdo->prepare('
                SELECT projet5_mails.id as mp_id,expeditor,receiver,title,message,DATE_FORMAT(time, \'%d/%m/%Y à %Hh%i\') AS time_date_fr,mp_read, projet5_user.id,username,projet5_images.user_id,dirname,filename,extension
                FROM projet5_mails
                JOIN projet5_user
                ON expeditor =projet5_user.id
                JOIN projet5_images
                ON user_id=projet5_user.id
                WHERE projet5_mails.receiver = :receiver AND mp_read=1 AND filename="img-userProfil" ORDER By time_date_fr DESC ');

            $PdoStat->bindValue(':receiver',$userId,PDO::PARAM_INT);
            $getMessage=$PdoStat->execute();
            $getMessage=$PdoStat->fetchAll();

            return $getMessage;


        }
         public function sentMessages($userId)
        {
            $pdo=$this->dbConnect();
            $PdoStat=$pdo->prepare('
                        SELECT projet5_mails.id as mp_id,expeditor,receiver,title,message,DATE_FORMAT(time, \'%d/%m/%Y à %Hh%i\') AS time_date_fr, projet5_user.id,username,projet5_images.user_id,dirname,filename,extension
                        FROM projet5_mails
                        JOIN projet5_user
                        ON receiver =projet5_user.id
                        JOIN projet5_images
                        ON user_id=projet5_user.id
                        WHERE projet5_mails.expeditor = :expeditor  AND filename="img-userProfil" ORDER By time_date_fr DESC ');

            $PdoStat->bindValue(':expeditor',$userId,PDO::PARAM_INT);
            $sentMessage=$PdoStat->execute();
            $sentMessage=$PdoStat->fetchAll();

            return $sentMessage;


        }

    public function sentMessage($messageId,$userId)
    {
        $pdo=$this->dbConnect();
        $PdoStat=$pdo->prepare('
                SELECT projet5_mails.id as mp_id,expeditor,receiver,title,message,DATE_FORMAT(time, \'%d/%m/%Y à %Hh%i\') AS time_date_fr, projet5_user.id,username,projet5_images.user_id,dirname,filename,extension
                FROM projet5_mails
                JOIN projet5_user
                ON receiver =projet5_user.id
                JOIN projet5_images
                ON user_id=projet5_user.id
                WHERE projet5_mails.id = :id AND projet5_mails.expeditor =:expeditor AND filename="img-userProfil" ORDER By time_date_fr DESC ');

        $PdoStat->bindValue(':id',$messageId,PDO::PARAM_INT);
        $PdoStat->bindValue(':expeditor',$userId,PDO::PARAM_INT);
        $sentMessage=$PdoStat->execute();
        $sentMessage=$PdoStat->fetch();

        return $sentMessage;


    }






    public function getArchiveMessages($userId)
    {
        $pdo=$this->dbConnect();
        $PdoStat=$pdo->prepare('
                SELECT projet5_mails.id as mp_id,expeditor,receiver,title,message,DATE_FORMAT(time, \'%d/%m/%Y à %Hh%i\') AS time_date_fr,mp_read, projet5_user.id,username,projet5_images.user_id,dirname,filename,extension
                FROM projet5_mails
                JOIN projet5_user
                ON expeditor =projet5_user.id
                JOIN projet5_images
                ON user_id=projet5_user.id
                WHERE projet5_mails.receiver = :receiver AND mp_read=2 AND filename="img-userProfil" ORDER By time_date_fr DESC ');

        $PdoStat->bindValue(':receiver',$userId,PDO::PARAM_INT);
        $getArchiveMessages=$PdoStat->execute();
        $getArchiveMessages=$PdoStat->fetchAll();

        return $getArchiveMessages;


    }







        public function countUnreadMessage($userId)
        {
            $pdo=$this->dbConnect();
            $Pdostat=$pdo->prepare('
            SELECT COUNT(mp_read)
            FROM projet5_mails
            WHERE mp_read=0 AND receiver=:receiver');
            $Pdostat->bindValue('receiver',$userId,PDO::PARAM_INT);
            $unreadMessages= $Pdostat->execute();
            $unreadMessages=$Pdostat->fetch();

            return $unreadMessages;
        }

        public function countReadMessage($userId)
        {
            $pdo=$this->dbConnect();
            $Pdostat=$pdo->prepare('
            SELECT COUNT(mp_read)
            FROM projet5_mails
            WHERE mp_read=1 AND receiver=:receiver');
            $Pdostat->bindValue('receiver',$userId,PDO::PARAM_INT);
            $readMessages= $Pdostat->execute();
            $readMessages=$Pdostat->fetch();

            return $readMessages;
        }

        public function countArchiveMessage($userId)
        {
            $pdo=$this->dbConnect();
            $Pdostat=$pdo->prepare('
            SELECT COUNT(mp_read)
            FROM projet5_mails
            WHERE mp_read=2 AND receiver=:receiver');
            $Pdostat->bindValue('receiver',$userId,PDO::PARAM_INT);
            $archiveMessage= $Pdostat->execute();
            $archiveMessage=$Pdostat->fetch();

            return $archiveMessage;
        }


        public function countSentMessages($userId)
        {
            $pdo=$this->dbConnect();
            $Pdostat=$pdo->prepare('SELECT COUNT(id)
            FROM projet5_mails WHERE expeditor=:exp');
            $Pdostat->bindValue(':exp',$userId,PDO::PARAM_INT);
            $countSendMessage=$Pdostat->execute();
            $countSendMessage=$Pdostat->fetch();

            return $countSendMessage;

        }

        /*public function addUser()
        {

            $hashPwd = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $JSON= json_encode(["ROLE_USER"]);
            $db= DbConnect::getDatabase();
            $newUser= $db->query('INSERT INTO projet5_user VALUES (NULL,:gender, :first_name, :last_name, :username,:birthday,:email,:password, NOW(),NULL,NULL,:roles)',[


                'gender'=>$_POST['gender'],
                'first_name'=>$_POST['first_name'],
                'last_name'=>$_POST['last_name'],
                'username'=>$_POST['username'],
                'birthday'=>$_POST['birthday'],
                'email'=>$_POST['email'],
                'password'=>$hashPwd,
                'roles'=>$JSON
                 ]);

               $lastId=$this->dbConnect()->lastInsertId();

                $newUser=$db->query("UPDATE projet5_user SET username = REPLACE(username,'_',' ')
            WHERE username=:username", [
                'username'=>$_POST['username'],

                ]);

                return $newUser;


        }*/





}