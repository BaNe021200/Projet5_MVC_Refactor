<?php

if(isset($_GET['stop']) && $_GET['stop']=='yes'){
    $_SESSION=array();
}
session_start();

if(empty($_SESSION['diaporama']))
{
    $dos='/users/img/user/Lady mohair';var_dump($dos);
    $diapo=glob($dos.'*.jpg');
    usort($diapo, "strnatcmp");

    $_SESSION['diaporama']=$diapo;
    $nb=count($diapo);
}
else
{
    $nb=count($_SESSION['diaporama']); var_dump($nb);
}
if(isset($_GET['image']) && ctype_digit($_GET['image']))
{
    $img=$_GET['image'];
}
else
{
    $img=0;var_dump($img);
}
echo '<table style="width:500px;border:1px solid;">
        <tr>
        <td colspan="3" style="text-align:center;">
        Mon Diaporama
        <br /><br />
         <img src="\',$_SESSION[\'diaporama\'][$img],\'" alt="" />
        </td>
        </tr>
        <tr>
        <td style="width:100px;">';
if($img>0 && $img<$nb){
    echo '<a href="diaporama1.php?image=',$img-1,'">Précédente</a>';
}
else
{
    echo '&nbsp;';
}
echo '</td>
        <td></td>
        <td style="width:100px;">';
if($img<$nb-1 && $img>=0){
    echo '<a href="diaporama1.php?image=',$img+1,'">Suivante</a>';
}
else
{
    echo '&nbsp;';
}
echo '</td>
        </tr>
        <tr>
        <td colspan="3" style="text-align:center;">
        <hr />
        <a href="diaporama1.php?stop=yes">[ Reprendre du début ]</a>
        </td>
        </tr>
        </table>';
