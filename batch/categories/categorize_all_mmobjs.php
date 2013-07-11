#!/usr/bin/env php

<?php

   /**
    * categorize_all_mmobjs  batch script
    *
    * [Pone categorias correctas en todos los videos de la serie a partir del primero]
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

function process($serie){
  CategoryPeer::categorizeAllFromFirst($serie, true);
}


$series = SerialPeer::doSelect(new Criteria());

foreach($series as $serie){
    process($serie);
}



//process(SerialPeer::retrieveByPk(1498));


