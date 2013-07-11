#!/usr/bin/env php

<?php

/**
 * create_all_from_ground batch script
 *
 * Borrar el contenido de categories y lo crea desde la tabla ground y desde el array guadado en UI_UVIGOTVUNESCO_TREE
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

require_once('UI_UVIGOTVUNESCO_TREE.php');


function get($code) //"U%0000"
{ 
  $c = new Criteria();
  $c->addAscendingOrderByColumn(GroundPeer::COD);
  $c->add(GroundPeer::COD, $code, Criteria::LIKE);
  $c->setDistinct(true);
  //$c->setLimit(8);
  return GroundPeer::doSelect($c);
}


function getByType($type) //"U%0000"
{ 
  $c = new Criteria();
  $c->addAscendingOrderByColumn(GroundPeer::COD);
  $c->add(GroundPeer::GROUND_TYPE_ID, $type);
  $c->setDistinct(true);
  //$c->setLimit(8);
  return GroundPeer::doSelect($c);
}


function create_root()
{
  $root = new Category();
  $root->makeRoot();
  $root->setMetacategory(true);
  $root->setDisplay(true);
  $root->setRequired(false);
  $root->setCod("root");
  
  $langs = sfConfig::get('app_lang_array', array('es'));
  foreach($langs as $lang){
    $root->setCulture($lang);
    $root->setName("root");
  }
  
  return $root;
}

function create_block($name, $root)
{
  $category = new Category(); 
  $category->insertAsLastChildOf($root);
  $category->setMetacategory(true);
  $category->setDisplay(true);
  $category->setRequired(false);
  $category->setCod($name);
  
  $langs = sfConfig::get('app_lang_array', array('es'));
  foreach($langs as $lang){
    $category->setCulture($lang);
    $category->setName($name);
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


function new_cateogry_from_string($cod, $name, $parent)
{
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
  return $category;
}


function uvigotv_new_cateogry_from_ground($ground, $parent)
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
    
    $pos = strpos($ground->getName(), ' - ');
    if($pos === false) {
      $category->setName($ground->getName());
    } else {
      $category->setName(trim(substr($ground->getName(), $pos + 3)));
    }

  }
  return $category;
}


function get_category_from_code($code)
{
  $c = new Criteria();
  $c->add(CategoryPeer::COD, $code);
  return CategoryPeer::doSelectOne($c);
}


function unesco_get_parent($ground)
{
  if(substr($ground->getCod(), -4) == "0000"){
    $p = get_category_from_code("UNESCO");
  }elseif(substr($ground->getCod(), -2) == "00"){
    $p = get_category_from_code(substr($ground->getCod(), 0, -4) . "0000");    
  }else{
    $p = get_category_from_code(substr($ground->getCod(), 0, -2) . "00");
  }
  if(!$p) {
    throw new Exception("Error buscasndo padre de " . $ground->getCod());
  }

  return $p;
}


function uvigotv_get_parent($ground)
{
  if(strlen($ground->getCod()) == 2){
    $p = get_category_from_code("UVIGOTV");
  }else{
    $p =  get_category_from_code(substr($ground->getCod(), 0, -2));
  }
  if(!$p) {
    throw new Exception("Error buscasndo padre de " . $ground->getCod());
  }

  return $p;
}



function itunes_get_parent($ground)
{
  if(strlen($ground->getCod()) == 2){
    $p = get_category_from_code("ITUNESU");
  }else{
    $p =  get_category_from_code(substr($ground->getCod(), 0, -3));
  }
  if(!$p) {
    throw new Exception("Error buscasndo padre de " . $ground->getCod());
  }

  return $p;
}

////////////////////////////////////////////
// START
////////////////////////////////////////////
echo "START\n\n\n";



echo "Seguro???";
// exit;

CategoryPeer::doDeleteAll();
$root = create_root();
$root->save();

//UNESCO
$root_unesco = create_block("UNESCO", CategoryPeer::doSelectRoot());
$root_unesco->save();

$grounds = get("U%");
foreach($grounds as $g) {
  $p = unesco_get_parent($g);
  $c = new_cateogry_from_ground($g, $p);
  $c->save();

  echo $g->getId();
  echo "\n";
  echo $g->getCod();
  echo "\n";
  echo $p->getCod();
  echo "\n------------------\n";
}

//UVIGOTVUI
$root_directriz = create_block("UNESCOUVIGOTV", CategoryPeer::doSelectRoot());
$root_directriz->save();

$ii = 1;
foreach($unescouvigotvTree as $k => $v){
  $c = new_cateogry_from_string("U99" . $ii++, $k, get_category_from_code("UNESCOUVIGOTV"));
  $c->save();
  
  if(is_array($v)){
    foreach($v as $k1 => $v1){
      //UNO
      $c1 = new_cateogry_from_string("U99" . $ii++, $k1, get_category_from_code($c->getCod()));
      $c1->save();
      
      if(is_array($v1)){
	foreach($v1 as $k2 => $v2){
	  //DOS
	  $c2 = new_cateogry_from_string("U99" . $ii++, $k2, get_category_from_code($c1->getCod()));
	  $c2->save();
      
	  if(is_array($v2)){
	    foreach($v2 as $k3 => $v3){
	      //TRES
	      $c3 = new_cateogry_from_string("U99" . $ii++, $k3, get_category_from_code($c2->getCod()));
		  $c3->save();
	  
		  if(is_array($v3)){
		    foreach($v3 as $k4 => $v4){
		      //CUATRO
		      var_dump($v4);
		    }
		  }else{
		    $cc3 = new_cateogry_from_string("U99" . $ii++, $v3, $c3);
		    $cc3->save();
		  }
	    }
	  }else{
	    $cc2 = new_cateogry_from_string("U99" . $ii++, $v2, $c2);
	    $cc2->save();
	  }
	}
      }else{
	$cc1 = new_cateogry_from_string("U99" . $ii++, $v1, $c1);
	$cc1->save();
      }
    }
  }else{
    $cc = new_cateogry_from_string("U99" . $ii++, $v, $c);
    $cc->save();
  }
}



//////OLD iTunesU - UvigoTV
////$root_uvigotv = create_block("UVIGOTV", CategoryPeer::doSelectRoot());
////$root_uvigotv->save();
////
////$grounds = get("T%");
////foreach($grounds as $g) {
////  $p = uvigotv_get_parent($g);
////  $c = uvigotv_new_cateogry_from_ground($g, $p);
////  $c->save();
////
////  echo $g->getId();
////  echo "\n";
////  echo $g->getCod();
////  echo "\n";
////  echo $p->getCod();
////  echo "\n------------------\n";
////}


//iTunes 
$root_itunes = create_block("ITUNESU", CategoryPeer::doSelectRoot());
$root_itunes->save();

$grounds = getByType(4); //ItunesPadre
foreach($grounds as $g) {
  $c = new_cateogry_from_ground($g, get_category_from_code("ITUNESU"));
  $c->save();

  echo $g->getId();
  echo "\n";
  echo $g->getCod();
  echo "\n------------------\n";
}


$grounds = getByType(5); //Itunes Hijo
foreach($grounds as $g) {
  $p = itunes_get_parent($g);
  $c = new_cateogry_from_ground($g, $p);
  $c->save();

  echo $g->getId();
  echo "\n";
  echo $g->getCod();
  echo "\n";
  echo $p->getCod();
  echo "\n------------------\n";
}


//YOUTUBE
$root_directriz = create_block("YOUTUBE", CategoryPeer::doSelectRoot());
$root_directriz->save();

$grounds = get("Y%");
foreach($grounds as $g) {
  $c = new_cateogry_from_ground($g, get_category_from_code("YOUTUBE"));
  $c->save();

  echo $g->getId();
  echo "\n";
  echo $g->getCod();
  echo "\n------------------\n";
}

//DIRECTRIZ
$root_directriz = create_block("DIRECTRIZ", CategoryPeer::doSelectRoot());
$root_directriz->save();

$grounds = get("D%");
foreach($grounds as $g) {
  $c = new_cateogry_from_ground($g, get_category_from_code("DIRECTRIZ"));
  $c->save();

  echo $g->getId();
  echo "\n";
  echo $g->getCod();
  echo "\n------------------\n";
}




//UVIGOTVUI
$root_directriz = create_block("UVIGOTVUI", CategoryPeer::doSelectRoot());
$root_directriz->save();

$ii = 1;
foreach($uiTree as $k => $v){
  $c = new_cateogry_from_string("UI" . $ii++, $k, get_category_from_code("UVIGOTVUI"));
  $c->save();
  
  if(is_array($v)){
    foreach($v as $k1 => $v1){
      //UNO
      $c1 = new_cateogry_from_string("UI" . $ii++, $k1, get_category_from_code($c->getCod()));
      $c1->save();
      
      if(is_array($v1)){
	foreach($v1 as $k2 => $v2){
	  //DOS
	  $c2 = new_cateogry_from_string("UI" . $ii++, $k2, get_category_from_code($c1->getCod()));
	  $c2->save();
      
	  if(is_array($v2)){
	    foreach($v2 as $k3 => $v3){
	      //TRES
	      $c3 = new_cateogry_from_string("UI" . $ii++, $k3, get_category_from_code($c2->getCod()));
		  $c3->save();
	  
		  if(is_array($v3)){
		    foreach($v3 as $k4 => $v4){
		      //CUATRO
		      var_dump($v4);
		    }
		  }else{
		    $cc3 = new_cateogry_from_string("UI" . $ii++, $v3, $c3);
		    $cc3->save();
		  }
	    }
	  }else{
	    $cc2 = new_cateogry_from_string("UI" . $ii++, $v2, $c2);
	    $cc2->save();
	  }
	}
      }else{
	$cc1 = new_cateogry_from_string("UI" . $ii++, $v1, $c1);
	$cc1->save();
      }
    }
  }else{
    $cc = new_cateogry_from_string("UI" . $ii++, $v, $c);
    $cc->save();
  }
}
