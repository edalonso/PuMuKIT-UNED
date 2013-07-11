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
define('SF_ENVIRONMENT', 'prod');
define('SF_DEBUG',       0);

require_once(SF_ROOT_DIR.DIRECTORY_SEPARATOR.'apps'.DIRECTORY_SEPARATOR.SF_APP.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'config.php');

// initialize database manager
$databaseManager = new sfDatabaseManager();
$databaseManager->initialize();

// batch process here

echo "\nBuscando PubCHannel obsoleto\n";
$c = new Criteria();
$c->add(PubChannelPeer::NAME, 'GoogleVideoSiteMap');
if ($pc = PubChannelPeer::doSelectOne($c)){
  echo "Había un pubchannel de GoogleVideoNosequeixon y procedo a borrarlo\n\n";
  $pc->delete();
} else {
  echo "No había pubchannel obsoleto\n\n";
}


