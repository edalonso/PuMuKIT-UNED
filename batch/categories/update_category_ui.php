#!/usr/bin/env php

<?php

/**
 * pr batch script
 *
 * Here goes a brief description of the purpose of the batch script
 *
 * @package    pumukituvigo
 * @subpackage batch
 * @version    $Id$
 */

define('SF_ROOT_DIR',    realpath(dirname(__file__).'/../..'));
define('SF_APP',         'editar');
define('SF_ENVIRONMENT', 'dev');
define('SF_DEBUG',       1);

require_once(SF_ROOT_DIR.DIRECTORY_SEPARATOR.'apps'.DIRECTORY_SEPARATOR.SF_APP.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'config.php');

// initialize database manager
$databaseManager = new sfDatabaseManager();
$databaseManager->initialize();


//START

// batch process here
echo "START\n\n\n";

function getSerialsByCat($id)
{
  $c = new Criteria();
  $c->setDistinct(true);
  SerialPeer::addPublicCriteria($c);
  SerialPeer::addBroadcastCriteria($c, array('pub'));

  $c->addJoin(CategoryMmPeer::MM_ID, MmPeer::ID);
  $c->add(CategoryMmPeer::CATEGORY_ID, $id);
  
  $c->clearOrderByColumns();
  
  return SerialPeer::doSelectWithI18n($c);
}


$uiRootCat = CategoryPeer::retrieveByCode('UVIGOTVUI');

if(!$uiRootCat){
  die("No existe nodo UVIGOTVUI");
}


foreach($uiRootCat->getDescendants() as $cat) {
  echo $cat->getId() . " " . $cat->getCod() . " " . $cat->getName() . " (" . $cat->getNumMm() . ")\n";
  $aux = 0;
  foreach(getSerialsByCat($cat->getId()) as $s) {
      //echo $s->getTitle() . "\n";
      $aux += ($s->countMmsPublic());
  }
  echo "Objetos multimedia totales para " . $cat->getName() . ": " . $aux . "\n";
  $cat->setNumMm($aux);
  $cat->save();
}