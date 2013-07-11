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


$c = new Criteria();
//$c->setLimit(50);
$mms = MmPeer::doSelect($c);
$download = true;



foreach ($mms as $key => $mm){
  echo ".";
  $download = true;

  $c = new Criteria();
  $c->add(UnedMediaOldPeer::MM_ID, $mm->getId());
  $umo = UnedMediaOldPeer::doSelectOne($c);

  if ($umo && ($umo->getDenegarDescarga() !== "false")) {
    $download = false;
    echo "\n", $mm->getId() , " ", $umo->getDenegarDescarga(), "\n";
  }
  
  $files = $mm->getFiles();

  foreach($files as $f) {
    if($f->isMaster()) {
      $f->setDownload(false);
    } else {
      $f->setDownload($download);
    }
    $f->saveInDB();
    $f->clearAllReferences(true);
  }
  $mm->clearAllReferences(true);
  unset($mm);
  unset($mms[$key]);
  
}




