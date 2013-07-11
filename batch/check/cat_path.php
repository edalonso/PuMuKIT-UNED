<?php

/**
 * cat_path.php
 *
 * Script mira si algun objeto multimedia esta catalogado en una categoria y no esta en algun elemento de su path.
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
echo "START\n\n\n";



function process($mm){
  echo "  # ", $mm->getId(), "\n";
  $cats = $mm->getCategories();
  foreach($cats as $c) {
    foreach($c->getPath() as $p) {
      echo $p->getId(), "-", $p->getName(), "\n";
      if($mm->hasCategoryId($p->getId())) {
	echo "    ERROR: ", $mm->getId(), " tiene ", $c->getId(), "-", $c->getName(), " y no tiene ", $p->getId(), "-", $p->getName(), "\n";
      }
    }
  }
  unset($cats);
}


$offset = 0;
$limit = 10;
while (true) {
  $c = new Criteria();
  $c->add(MmPeer::ID, $offset, Criteria::GREATER_THAN);
  $c->addAscendingOrderByColumn(MmPeer::ID);
  $c->setLimit($limit);
  $mms = MmPeer::doSelect($c);
  if ($mms) {
    foreach($mms as $mm) {
      $offset = $mm->getId();
      process($mm);
      $mm->clearAllReferences(true);
    }
    unset($mms);
  } else {
    break;
  }
}


