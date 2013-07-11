<?php

/**    
 * Crea un árbol de categorías nuevo para UI y asigna mms a esas categorías.
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


function new_cateogry_from_ground($cod, $name, $parent_cod)
{
  $parent = CategoryPeer::retrieveByCode($parent_cod);
  if(!$parent) {
    throw new Exception ("No existe parent ". $parent_cod);
  }

  $category = new Category();
  //$category->insertAsFirstChildOf($parent);  //OK
  $category->insertAsLastChildOf($parent); //KO
  $category->setMetacategory(false);
  $category->setDisplay(true);
  $category->setRequired(false);
  $category->setCod($cod);

  $langs = sfConfig::get('app_lang_array', array('es'));
  foreach($langs as $lang){
    $category->setCulture($lang);
    $category->setName($name);
  }

  $category->save();
  return $category;
}


if (!$cat_root = CategoryPeer::doSelectRoot()){
  throw new Exception ("No está creada la categoría root, ejecutar el script tras poblar category");
}

$unesco = CategoryPeer::retrieveByCode("UNESCO");
if(!$unesco) {
  throw new Exception ("No existe unesco");
}

$cod_99 = CategoryPeer::retrieveByCode("U990100");
if($cod_99) {
  throw new Exception ("Ya existe codigo U990100");
}

new_cateogry_from_ground("U990000", "UNED", "UNESCO");
new_cateogry_from_ground("U990100", "UNED Institucional", "U990000");
new_cateogry_from_ground("U990101", "Tomas de Posesión", "U990100");
new_cateogry_from_ground("U990102", "Apertura de Curso", "U990100");
new_cateogry_from_ground("U990103", "Sto. Tomás/Patrón", "U990100");
new_cateogry_from_ground("U990104", "Homenajes", "U990100");
new_cateogry_from_ground("U990105", "Premios", "U990100");
new_cateogry_from_ground("U990106", "Inauguraciones", "U990100");
new_cateogry_from_ground("U990107", "Visitas", "U990100");
new_cateogry_from_ground("U990108", "Honoris Causa", "U990100");
new_cateogry_from_ground("U990109", "Claustros", "U990100");
new_cateogry_from_ground("U990110", "Electoral", "U990100");
new_cateogry_from_ground("U990111", "Imagen Corporativa", "U990100");
new_cateogry_from_ground("U990112", "Aperturas y clausuras congresos, jornadas", "U990100");
new_cateogry_from_ground("U990113", "Consejo Social", "U990100");
new_cateogry_from_ground("U990200", "UNED Cultural", "U990000");
new_cateogry_from_ground("U990201", "Exposiciones", "U990200");
new_cateogry_from_ground("U990202", "Teatro", "U990200");
new_cateogry_from_ground("U990203", "Conciertos", "U990200");
new_cateogry_from_ground("U990204", "Danza", "U990200");
new_cateogry_from_ground("U990205", "Deportes", "U990200");
new_cateogry_from_ground("U990206", "Artes Plásticas/concursos", "U990200");
new_cateogry_from_ground("U990207", "Actividades culturales y científicas", "U990200");
