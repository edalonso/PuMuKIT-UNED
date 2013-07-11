#!/usr/bin/env php

<?php

/**
 * add_categories_from_places_and_itunes batch script
 *
 * [Relaciona Mm con Categories]
 * Cataloga los Objetos multimedia en base a la tabla places y la taba ground (solo la seccion de iTunes)
 * Usar list_categories_from_places_and_itunes.php antes de ejecutar este.
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



//ID Ground (Area conocimiento) => Id Category
$rel_ground = array(
                   34 => array(2518),
                   36 => array(2537),
                   68 => array(2534),
                   69 => array(2535),
                   71 => array(2536),
                   35 => array(2538),
                   197 => array(2535),
                   38 => array(2565),
                   39 => array(2611),
                   40 => array(2597),
                   61 => array(2589),
                   62 => array(2621),
                   63 => array(2576),
                   64 => array(2608),
                   43 => array(2550),
                   44 => array(2554),
                   45 => array(2552),
                   42 => array(2585),
                   70 => array(2572, 2574),
                   48 => array(2541),
                   49 => array(2543),
                   50 => array(2602)
                   );

//ID Place => Id Category
$rel_places = array(
                    84 => array(2621),
                    78 => array(2581),
                    130 => array(2523),
                    56 => array(2589),
                    56 => array(2597),
                    7 => array(2591, 2593),
                    8 => array(2528, 2538, 2533),
                    35 => array(2602, 2585),
                    41 => array(2527, 2529, 2536, 2520, 2524),
                    131 => array(2574),
                    15 => array(2543),
                    36 => array(2543),
                    101 => array(2625),
                    14 => array(2602),
                    9 => array(2541),
                    5 => array(2559),
                    6 => array(2569),
                    24 => array(2615),
                    77 => array(2571),
                    76 => array(2550),
                    16 => array(2585),
                    54 => array(2611),
                    82 => array(2552),
                    10 => array(2556),
                    59 => array(2593),
                    12 => array(2550, 2552, 2554),
                    11 => array(2562),
                    13 => array(2617),
                    2 => array(2591),
                    3 => array(2565),
                    52 => array(2554),
                    100 => array(2523),
                    116 => array(2858),
		    126 => array(2859)
                    );


$mms = MmPeer::doSelect(new Criteria());

foreach($mms as $mm){
  echo $mm->getId() . PHP_EOL;
  $grounds = $mm->getGrounds();
  foreach($grounds as $g){
    if(array_key_exists($g->getId(), $rel_ground)) {
      foreach($rel_ground[$g->getId()] as $aux){
	$cat = CategoryPeer::retrieveByPk($aux);
	if($cat) {
	  foreach($cat->getPath() as $p){
	    $p->addMmId($mm->getId());
	  }
	  $cat->addMmId($mm->getId());
	  echo "ITUNES - mm " . $mm->getId() . ", cod " . $cat->getId() . PHP_EOL;
	}
      }
    }
  }

  if(array_key_exists($mm->getPlaceId(), $rel_places)) {
    foreach($rel_places[$mm->getPlaceId()] as $aux) {
      $cat = CategoryPeer::retrieveByPk($aux);
      if($cat) {
	foreach($cat->getPath() as $p){
	  $p->addMmId($mm->getId());
	}
	$cat->addMmId($mm->getId());
	echo "PLACE - mm " . $mm->getId() . ", cod " . $cat->getId() . PHP_EOL;
      }
    }
  }
}
