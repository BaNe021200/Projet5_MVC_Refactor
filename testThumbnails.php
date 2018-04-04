<?php
declare(strict_types=1);
echo "test1.jpg:<br />\n";
$exif = exif_read_data('users/img/user/Lady mohair/img_001.jpg', 'IFD0');
echo $exif===false ? "Aucun en-tête de donnés n'a été trouvé.<br />\n" : "L'image contient des en-têtes<br />\n";

$exif = exif_read_data('users/img/user/Lady mohair/img_002.jpg', 0, true);
echo "test2.jpg:<br />\n";
foreach ($exif as $key => $section) {
    foreach ($section as $name => $val) {
        echo "$key.$name: $val<br />\n";
    }
}


