<?php

/**
 * Script que borra la lista de generos y la crea de nuevo con los suministrados
 * por el personal de la UNED - tarea #58 del redmine de soporte
 *
 * @package    pumukituvigo
 * @subpackage batch
 * @version    1
 */

define('SF_ROOT_DIR',    realpath(dirname(__file__).'/../..'));
define('SF_APP',         'editar');
define('SF_ENVIRONMENT', 'prod');
define('SF_DEBUG',       0);

require_once(SF_ROOT_DIR.DIRECTORY_SEPARATOR.'apps'.DIRECTORY_SEPARATOR.SF_APP.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'config.php');

// initialize database manager
$databaseManager = new sfDatabaseManager();
$databaseManager->initialize();

echo "Se establecerá la imagen por defecto a todas las series\n";
echo " ¿estás seguro? (edita el código)\n";


$por_defecto = DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'folder.png';
setSerialImg($por_defecto);


function setSerialImg($por_defecto)
{
    $c = new Criteria();
    // $c->add(MmPeer::GENRE_ID, null);
    $serials = SerialPeer::doSelect($c);
    $i = 1;
    echo "Estableciendo todas las imagenes por defecto\n";
    $pic = new Pic();
    $pic->setUrl($por_defecto);
    $pic->save();
    foreach ($serials as $serial){

        $picSerial = new PicSerial();
        $picSerial->setPicId($pic->getId());
        $picSerial->setOtherId($serial->getId());
        //$pic->setId($serial->getId());

        $picSerial->save();
        if ($i%100 == 0) echo "Grabando PIC: " . $i . "\n";
        $i++;
    }
}
