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

if (!$cat_root = CategoryPeer::doSelectRoot()){
	throw new Exception ("No está creada la categoría root, ejecutar el script tras poblar category");
}

$unedtvui       = "UNEDTVUI";
$prefijo_ui     = 'UI_';
$tecnologias    = "Tecnologías";
$iinformatica   = "Ingeniería Informática";
$iindustrial    = "Ingeniería Industrial";
$experimentales = "Ciencias Experimentales y Tecnología";

$tematicas_iinformatica = array('INFORMÁTICA',
								'ING. TEC. EN INFORMÁTICA DE GESTIÓN PLAN 2000',
								'ING. TÉC. EN INFORMÁTICA DE SISTEMAS PLAN 2000',
								'INGENIERO EN INFORMÁTICA');

$tematicas_iindustrial = array( 'ING. TÉC. INDUSTRIAL EN ELECTRICIDAD',
								'ING. TÉC. INDUSTRIAL EN ELECTRÓNICA INDUSTRIAL',
								'ING. TÉC. INDUSTRIAL EN MECÁNICA',
								'INGENIERÍA INDUSTRIAL');

$tematicas_experimentales = array('CIENCIAS EXPERIMENTALES Y TECNOLOGÍA',
								  'CIENCIAS AMBIENTALES');
// crea o selecciona si ya existe.
$cat_raiz_unedtvui  = creaCategory($unedtvui, $cat_root, '' );

// Borro el arbol de categorías hasta nuevo aviso.
$cat_raiz_unedtvui->deleteDescendants();
$cat_raiz_unedtvui->delete();
exit;


$cat_tecnologias    = creaCategory($tecnologias, $cat_raiz_unedtvui, $prefijo_ui);
$cat_iindustrial    = creaCategory($iindustrial, $cat_tecnologias, $prefijo_ui);
$cat_iinformatica   = creaCategory($iinformatica, $cat_tecnologias, $prefijo_ui);
$cat_experimentales = creaCategory($experimentales, $cat_tecnologias, $prefijo_ui);

addMmFromTematicasToCategory($tematicas_iinformatica, $cat_iinformatica);
addMmFromTematicasToCategory($tematicas_iindustrial, $cat_iindustrial);
addMmFromTematicasToCategory($tematicas_experimentales, $cat_experimentales);

exit;

function creaCategory($name, $parent, $cod_prefix = '')
{
    $parent   = CategoryPeer::retrieveByPK($parent->getId());
    if (!$parent) {
        throw new Exception ("Error: no se encuentra categoría padre para crear ". $name);
    }

    $cod = substr($cod_prefix . $name, 0, 25); // long. máx. de category.cod

    $c = new Criteria();
    // $c->add(CategoryI18nPeer::NAME, $nombre);
    // $c->addJoin(CategoryI18nPeer::ID, CategoryPeer::ID);
    $c->add(CategoryPeer::COD, $cod);
    
    if (!$category = CategoryPeer::doSelectOne($c)){
	    $category = new Category(); 
	    $category->insertAsLastChildOf($parent);
	    $category->setMetacategory(false);
	    $category->setDisplay(true);
	    $category->setRequired(false);
	    $category->setCod($cod);
	  
	    $category->setCulture('es');
	    $category->setName($name);
	    
	    $category->save();
	    echo"Creada categoría " . $category->getCod() . " - " . $name . "\n";
	} else {
		echo "La categoría " . $name . " ya existe, la recupero de la BD\n";
	}
    
    return $category;
}

function addMmFromTematicasToCategory($tematicas, $category)
{
	$mms = retrieveMmsWithAnyTematicas ($tematicas);
	echo "Añadiendo los " . count($mms) . " mms de las temáticas " 
		. implode(',', $tematicas) . " a la categoría " . $category->getName() . "\n"; 
	
	foreach ($mms as $mm){
		$category->addMmIdAndUpdateCategoryTree($mm->getId());
	}
}

// el collation de mysql es case insensitive. En principio, devuelve primero las mayúsculas,
// se queda con las categorías antiguas, no deberían interferir las nuevas con el mismo nombre.
function retrieveMmsWithAnyTematicas ($tematicas)
{
	if (!is_array($tematicas)) $tematicas = array($tematicas);

	$c = new Criteria();
	$c->add(CategoryI18nPeer::NAME, $tematicas, Criteria::IN);
	$c->addJoin(CategoryI18nPeer::ID, CategoryMmPeer::CATEGORY_ID);
	$c->addJoin(CategoryMmPeer::MM_ID, MmPeer::ID);

	return MmPeer::doSelect($c);
}