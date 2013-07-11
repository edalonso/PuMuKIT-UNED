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
$sf_web_dir = sfConfig::get('sf_web_dir');
//START

// batch process here
echo "START\n\n\n";

/**
 * Devuelve los material con display = 1 (pdfs, imágenes, etc menos subtítulos)
 * @param $limit máx. resultados o null = sin límite
 * @return resultset of Material.
 */
function getDisplayableMaterials($limit = null){
    $c = new Criteria();
    $c->add(MaterialPeer::DISPLAY, 1);
    if ($limit) $c->setLimit($limit);

    return MaterialPeer::doSelectWithI18n($c);
}

$materials = getDisplayableMaterials();

foreach ($materials as $material) {
  $tamano = filesize($sf_web_dir . $material->getUrl());
  if ( !$tamano ) continue;
  $material->setSize($tamano);
  $material->save();
}
