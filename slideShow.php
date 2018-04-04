<?php
declare(strict_types=1);
require_once 'twig.php';

function slideShow($img)
{
    $folder = glob('users/img/user/creapulka/*.jpg');
    $sort = usort($folder, 'strnatcmp');;
    $nb = count($folder);

    $src='users/img/user/creapulka/img_00'.$img.'.jpg';

    var_dump($src);
    twigRender('viewer2.html.twig','images',$src,'slide',$img);
}
slideShow($img);




