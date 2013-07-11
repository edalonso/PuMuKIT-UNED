#!/usr/bin/env php

<?php

/**
 * add_categories_from_ground batch script
 *
 * [Relaciona Mm con Categories]
 * Cataloga los Objetos multimedia en base a la taba ground (se basa en que tiene el mismo codigo)
 * Usar list_categories_from_ground antes de ejecutar este script
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

// start batch script
echo("START \n");

$mms = MmPeer::doSelect(new Criteria());

foreach($mms as $mm){
  echo $mm->getId() . PHP_EOL;
  $grounds = $mm->getGrounds();
  foreach($grounds as $g){
    $cat = CategoryPeer::retrieveByCode($g->getCod());
    if($cat) {
      $cat->addMmId($mm->getId());
      echo "mm " . $mm->getId() . ", cod " . $cat->getId() . PHP_EOL;
    }
  }
}
