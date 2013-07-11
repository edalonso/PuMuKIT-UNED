<?php

/**
 * profile batch script
 *
 * Script que elementos que tiene relaciones rotas.
 * (Objetos multimedia sin series; Archivos de video, Materiales,
 * Links, Areas de Conocimiento y Personas que no tienen Objetos Multimedia)
 *
 * @package    pumukituvigo
 * @subpackage batch
 * @version    1
 */

define('SF_ROOT_DIR',    realpath(dirname(__file__).'/../..'));
define('SF_APP',         'editar');
define('SF_ENVIRONMENT', 'dev');
define('SF_DEBUG',       1);

require_once(SF_ROOT_DIR.DIRECTORY_SEPARATOR.'apps'.DIRECTORY_SEPARATOR.SF_APP.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'config.php');

// initialize database manager
$databaseManager = new sfDatabaseManager();
$databaseManager->initialize();

// batch process here

$query = "\"reactor\"";
$hits = MmPeer::getLuceneIndex()->find($query);
var_dump($hits);
$pks  = array();
foreach ($hits as $hit){
  $pks[] = $hit->pk;
}   
var_dump($pks);































/*
//MmPeer::getLuceneIndex();
$mms = MmPeer::getForLuceneQuery($argv[1]);
foreach($mms as $mm){
  echo "ID: ". $mm->getId()."\n";
  echo "Title: ". $mm->getTitle()."\n";
  echo "Subtitle: ". $mm->getSubtitle()."\n";
  echo "Description: ". $mm->getDescription()."\n";

  $persons = $mm->getPersons();
  $personStr = "";

  foreach($persons as $person){
    $personStr .= $person->getName();
  }
  
  echo "Persons: " . $personStr."\n";
  //  var_dump("Title: ". $mm->getKeywords());
  
}
*/