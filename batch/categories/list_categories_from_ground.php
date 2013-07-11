#!/usr/bin/env php

<?php

/**
 * list_categories_from_ground batch script
 *
 * Lista la catalogacion que se va a realizar con add_categories_from_ground
 * Util para ver que va a pasar antes de ejecutar add_categories_from_ground
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


$grounds = GroundPeer::doSelect(new Criteria());

foreach($grounds as $g){
  $cat = CategoryPeer::retrieveByCode($g->getCod());
  if($cat) {
    echo $g->getCod() ."(" . $g->getName().") -> " . $cat->getCod() ."(" . $cat->getName().") \n";
  }
}
