#!/usr/bin/env php

<?php

/**    
 * add_ui_categories  batch script
 *
 * [Relaciona Mm con Categories de UI]
 * Cataloga los Objetos multimedia en la rama UI en base a las relaciones
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

$uiRootCat = CategoryPeer::retrieveByCode('UVIGOTVUI');

if(!$uiRootCat){
  die("No existe nodo UVIGOTVUI");
}
$mms = MmPeer::doSelect(new Criteria());

foreach($mms as $mm){
    $categories = $mm->getCategorys();
    foreach($categories as $catInMm){
        foreach($catInMm->getRequired() as $relCat){
            if($relCat->isDescendantOf($uiRootCat)) {
                //echo "Tengo que añadir ". $mm->getId() . " a " . $relCat->getCod() . " por culpa de  " . $catInMm->getCod() . "\n";
                $relCat->addMmId($mm->getId());
            }
        }
    }
}