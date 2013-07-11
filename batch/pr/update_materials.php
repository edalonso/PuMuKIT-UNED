<?php

/**
 * files.php
 *
 * Script que muestra por un lado los files de la base de datos que no 
 * poseen Archivo fisico real. Y por otro lado los archivos fisico que
 * no estan catalogados en la base de datos.
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

// batch process here
echo "START\n\n\n";

$materials = MaterialPeer::doSelect(new Criteria());

foreach($materials as $material){

  if(substr($material->getUrl(), -3) == "vtt") {

    $langs = sfConfig::get('app_lang_array', array('es'));
    foreach($langs as $lang){
      $material->setCulture($lang);
      $material->setName("Subtitulo VTT");
    }
    
    $material->save();
  }
}
