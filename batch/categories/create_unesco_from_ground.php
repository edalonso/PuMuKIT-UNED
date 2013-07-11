#!/usr/bin/env php

<?php

/**
 * create_unesco_from_ground batch script
 *
 * Borrar el contenido de categories y lo crea desde la tabla ground.
 * Ver: create_all_from_ground
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

function get($code) //"U%0000"
{ 
  $c = new Criteria();
  $c->addAscendingOrderByColumn(GroundPeer::COD);
  $c->add(GroundPeer::COD, $code, Criteria::LIKE);
  $c->setDistinct(true);
  //$c->setLimit(8);
  return GroundPeer::doSelect($c);
}


function create_root()
{
  $parent = new Category();
  $parent->makeRoot();
  $parent->setMetacategory(false);
  $parent->setDisplay(true);
  $parent->setRequired(false);
  $parent->setCod("root");
  
  $langs = sfConfig::get('app_lang_array', array('es'));
  foreach($langs as $lang){
    $parent->setCulture($lang);
    $parent->setName("root");
  }
  
  $parent->save();

  $category = new Category(); 
  $category->insertAsFirstChildOf($parent);
  $category->setMetacategory(false);
  $category->setDisplay(true);
  $category->setRequired(false);
  $category->setCod("UNESCO");
  
  $langs = sfConfig::get('app_lang_array', array('es'));
  foreach($langs as $lang){
    $category->setCulture($lang);
    $category->setName("UNESCO");
  }
  return $category;
}


function new_cateogry_from_ground($ground, $parent)
{
  $category = new Category(); 
  //$category->insertAsFirstChildOf($parent);  //OK
  $category->insertAsLastChildOf($parent); //KO
  $category->setMetacategory(false);
  $category->setDisplay(true);
  $category->setRequired(false);
  $category->setCod($ground->getCod());
  
  $langs = sfConfig::get('app_lang_array', array('es'));
  foreach($langs as $lang){
    $category->setCulture($lang);
    $ground->setCulture($lang);
    $category->setName($ground->getName());
  }
  return $category;
}


function get_category_from_code($code)
{
  $c = new Criteria();
  $c->add(CategoryPeer::COD, $code);
  return CategoryPeer::doSelectOne($c);
}


function get_parent($ground)
{
  if(substr($ground->getCod(), -4) == "0000"){
    return get_category_from_code("UNESCO");
  }elseif(substr($ground->getCod(), -2) == "00"){
    return get_category_from_code(substr($ground->getCod(), 0, -4) . "0000");    
  }else{
    return get_category_from_code(substr($ground->getCod(), 0, -2) . "00");
  }
}


// START
echo "START\n\n\n";

echo "Seguro???";
// exit;

CategoryPeer::doDeleteAll();
$root = create_root();
$root->save();

$grounds = get("U%");
foreach($grounds as $g) {
  $p = get_parent($g);
  $c = new_cateogry_from_ground($g, $p);
  $c->save();

  echo $g->getId();
  echo "\n";
  echo $g->getCod();
  echo "\n";
  echo $p->getCod();
  echo "\n------------------\n";
}



