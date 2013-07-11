#!/usr/bin/env php

<?php

/**
 * create_root batch script
 *
 * Crear el root del arbol de categorias para su correcto funcionamiento
 *
 * @package    pumukituvigo
 * @subpackage batch
 * @version    $Id$
 */

define('SF_ROOT_DIR',    realpath(dirname(__file__).'/../..'));
define('SF_APP',         'editar');
define('SF_ENVIRONMENT', 'prod');
define('SF_DEBUG',       0);

require_once(SF_ROOT_DIR.DIRECTORY_SEPARATOR.'apps'.DIRECTORY_SEPARATOR.SF_APP.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'config.php');

// initialize database manager
$databaseManager = new sfDatabaseManager();
$databaseManager->initialize();

// START
echo "START\n\n\n";

echo "Seguro???"
exit;

$parent = new Category();
$parent->makeRoot();
$parent->setMetacategory(true);
$parent->setDisplay(true);
$parent->setRequired(false);
$parent->setCod("root");

$langs = sfConfig::get('app_lang_array', array('es'));
foreach($langs as $lang){
  $parent->setCulture($lang);
  $parent->setName("root");
}

$parent->save();
