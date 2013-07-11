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

//ID de noticias es 3
$genre = '%: Noticias%';
setOOMMGenre($genre);


function setOOMMGenre($genre)
{
    $c = new Criteria();
    $c->addJoin(MmI18nPeer::ID, MmPeer::ID);
    $c->add(MmI18nPeer::DESCRIPTION, $genre, Criteria::LIKE);
    $mms = MmPeer::doSelect($c);

    foreach ($mms as $mm){
        $mm->setGenreId(3);
        $mm->save();
        //var_dump($real_mm->getGenre());
    }
}
