<?php
declare(strict_types=1);

function resizeByHeight()
{
    $images= glob('users/img/user/'.$_POST['username'].'/*.jpg');
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

function resizeImage()
{
    $images= glob('users/img/user/'.$_POST['username'].'/*.jpg');
    foreach ($images as $image)
    {
        $src=$image;//
        $image= imagecreatefromjpeg($src);
        $imageSize = getimagesize($src);//var_dump($imageSize);die;

        if($imageSize[0]>700)
        {
            $newWidth = 700;
            $newHeight= intval(($imageSize[1] * (($newWidth)/$imageSize[0])));//var_dump($newHeight);

            $newImage = imagecreatetruecolor($newWidth,$newHeight);
            imagecopyresampled($newImage, $image,0,0,0,0,$newWidth, $newHeight,$imageSize[0],$imageSize[1]);

            imagejpeg($newImage,$src);
            imagedestroy($image);
        }


    }

}

function UploadPictures()
{

    $user=new UserManager();

    $messages = [];

    if(!file_exists('users/img/user/'.$_POST['username']))
    {   $i=1;
        newFolder();
        foreach ($_FILES as $file) {//var_dump($file['name']);


            if ($file['error'] == UPLOAD_ERR_NO_FILE) {
                continue;
            }

            if (is_uploaded_file($file['tmp_name'])) {
                //on vérifie que le fichier est d'un type autorisé
                $typeMime = mime_content_type($file['tmp_name']);
                if ($typeMime == 'image/jpeg') {
                    //on verifie la taille du fichier
                    $size = filesize($file['tmp_name']);
                    if ($size > 1600000) {
                        $message = "le fichier est trop gros";
                    } else {

                        $beforeCleanning = $file['name'];
                        //on remplace les caractères qui ne sont ni des lettres ni des chiffres par des tirets

                        $onCleanning = preg_replace('~[^\\pL\d]+~u', '-', $beforeCleanning);
                        //on retire les tirets en début et en fin de chaine
                        $onCleanning = trim($onCleanning, '-');
                        //passage d'un encodage utf 8 à ascii afin d'éviter tous problème d'encodage dans le nom du fichier
                        $onCleanning = iconv('utf-8', 'us-ascii//TRANSLIT', $onCleanning);
                        //on met le nom de fichier en minuscule
                        $onCleanning = strtolower($onCleanning);
                        $afterCleanning = preg_replace('~[^-\w]+~', '', $onCleanning);


                        //var_dump($afterCleanning);


                        //$extension = substr(strchr($file['name'],"."),1);
                        $extension = strtolower(substr(strchr($file['name'], "."), 1));
                        //$destinationPath='upload/user/'.$file['name'];
                        $destinationPath = 'users/img/user/' . $_POST['username'] . '/' . 'img_00'.$i++ . '.' . $extension;
                        //var_dump($destinationPath);die;


                        $temporaryPath = $file['tmp_name'];

                        if (move_uploaded_file($temporaryPath, $destinationPath)) {
                            $messages[] = "le fichier " . $file['name'] . " a été correctement uploadé";


                        } else {
                            $messages[] = "le fichier " . $file['name'] . " n'a pas été correctement uploadé";

                        }

                    }
                } else {
                    $messages[] = 'type de fichiers non valide';
                }
            } else {
                $messages[] = 'un problème est survenu lors de l\'upload';
            }
            $destinationPath= $user->addUserFiles($_SESSION['id']);
        }//twigRender('homeUserFront.html.twig', 'message', $messages);

    }
    else
    { //$i=0;
      $countFiles = count(glob('users/img/user/'.$_POST['username'].'/*.jpg'))+1;
      $maxFiles = count(glob('users/img/user/'.$_POST['username'].'/*.jpg'));
        if ($maxFiles===6) {
            throw  new Exception(("vous ne pouvez pas uploader plus de 6 photos"));
        }


      /*if ($countFiles !=0 && $countFiles< 5) {
            for ($i === $countFiles ; $i < 5; $i++) {


                $i;  //var_dump($i);die;
            }
        }
        elseif ($countFiles===5) {
            throw  new Exception(("vous ne pouvez pas uploader plus de 5 photos"));
        }
        elseif($countFiles===0)
            {
                for($i=1;$i<=5; $i++)
                {
                    $i;
                }

            }*/
        foreach ($_FILES as $file) {//var_dump($file['name']);


            if ($file['error'] == UPLOAD_ERR_NO_FILE) {
                continue;
            }

            if (is_uploaded_file($file['tmp_name'])) {
                //on vérifie que le fichier est d'un type autorisé
                $typeMime = mime_content_type($file['tmp_name']);
                if ($typeMime == 'image/jpeg') {
                    //on verifie la taille du fichier
                    $size = filesize($file['tmp_name']);
                    if ($size > 1600000) {
                        $message = "le fichier est trop gros";
                    } else {

                        $beforeCleanning = $file['name'];
                        //on remplace les caractères qui ne sont ni des lettres ni des chiffres par des tirets

                        $onCleanning = preg_replace('~[^\\pL\d]+~u', '-', $beforeCleanning);
                        //on retire les tirets en début et en fin de chaine
                        $onCleanning = trim($onCleanning, '-');
                        //passage d'un encodage utf 8 à ascii afin d'éviter tous problème d'encodage dans le nom du fichier
                        $onCleanning = iconv('utf-8', 'us-ascii//TRANSLIT', $onCleanning);
                        //on met le nom de fichier en minuscule
                        $onCleanning = strtolower($onCleanning);
                        $afterCleanning = preg_replace('~[^-\w]+~', '', $onCleanning);


                        //var_dump($afterCleanning);


                        //$extension = substr(strchr($file['name'],"."),1);
                        $extension = strtolower(substr(strchr($file['name'], "."), 1));
                        $i=$countFiles++;
                        $destinationPath = 'users/img/user/' . $_POST['username'] . '/' .  'img_00'.$i++ . '.' . $extension;



                       // $destinationPath=$user->addUserFiles($_SESSION['id']);

                        $temporaryPath = $file['tmp_name'];

                        if (move_uploaded_file($temporaryPath, $destinationPath)) {
                            $messages[] = "le fichier " . $file['name'] . " a été correctement uploadé";


                        } else {
                            $messages[] = "le fichier " . $file['name'] . " n'a pas été correctement uploadé";

                        }

                    }
                } else {
                    $messages[] = 'type de fichiers non valide';
                }
            } else {
                $messages[] = 'un problème est survenu lors de l\'upload';
            }
            $destinationPath= $user->addUserFiles($_SESSION['id']);
        }
    }
    //resizeImage();
    resizeByHeight();
    cropImagess();


twigRender('success.html.twig','message', $messages,'','');

}

function slideShow($imageId)
{
    $folder='users/img/user/'.$_COOKIE['username'].'/';
    $slide= glob($folder.'*.jpg');
    usort($slide, 'strnatcmp');
    $nb=count($slide);

    if(isset($_GET['image']) && ctype_digit($_GET['image']))
    {
        $img=$_GET['image'];
    }
    else
    {
        $img=0;
    }
    if($img>0 && $img<$nb)
    {

    }




}

/*public function getUserProfilePicture2()
        {

            $pdo=$this->dbConnect();
            $PdoStat=$pdo->query('
            SELECT COUNT(id) AS nbUsers
            FROM projet5_user');
            $data=$PdoStat->execute();
            $data=$PdoStat->fetch();
            foreach ($data as $datum)
            {
                $nbUsers=$datum ;
            }
            $perPage=6;
            $nbPage= ceil($nbUsers/$perPage);



            if (isset($_GET['p'])&& $_GET['p']>0 && $_GET['p']<=$nbPage)
            {
                $currentPage=$_GET['p'];
            }
            else{
                $currentPage='1';
            }


            for ($i=1; $i<=$nbPage; $i++ )
            {
               if($i==$currentPage){
                   echo " $i/";
               }
               else{
                   echo"<a href=\"index.php?p=$i\">$i</a> ";
               }

            }


            $PdoStat->closeCursor();
            $PdoStat=$pdo->query('
            SELECT projet5_images.user_id,filename, projet5_user.id,username,registry_date
            FROM projet5_images
            INNER JOIN projet5_user
            ON projet5_images.user_id = projet5_user.id
            WHERE projet5_images.filename="img-userProfil"
            ORDER BY registry_date DESC LIMIT '.(($currentPage-1)*$perPage). ','.$perPage);

            $username= $PdoStat->execute();
            $username= $PdoStat->fetchAll();

            return $username;
        }*/