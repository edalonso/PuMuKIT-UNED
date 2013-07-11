<?php

/**
 * actualizaTimeframes - Decisiones editoriales temporizables
 *
 * Comprueba la tabla category_mm_timeframes
 * Asigna o desasigna las categorías que indican esas decisiones
 * según la hora actual caiga o no dentro del intervalo de tiempo seleccionado.
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
echo "START\n\n\n";

// Comprueba requisitos para correr el script
if ($cat_raiz_timeframes = CategoryPeer::retrieveByCode("Timeframes")){
    $cat_tf1 = creaCategory("Destacados_Tv", $cat_raiz_timeframes, "");
} else {
    echo "No existe la categoría root->Timeframes\n".
        "Es necesario crearla manualmente para testear el script\n\n".
        "Si confías en las categorías de los timeframes, modifica el código\n".
        "y retira esta restricción\n\n";
    throw new Exception();
}

// IMPORTANTE: descomentar la función que corresponda, tests o actualizar.
testSoloActivaTimeframeActual();
// testSoloDesactivaTimeframeAntiguo();

// actualizaTimeframes();


exit;

// ---------------------- FIN EJECUCIÓN SCRIPT -----------------------------

/**
 * @param array of timetables.
 * Sin parámetro, busca los timeframes a partir de los que terminaron 
 * hace menos de una semana, incluyendo los que aún no han comenzado.
 *
 * Activa (asigna la categoría) o desactiva según estemos dentro del timeframe
 */
function actualizaTimeframes($tfs = null)
{
    if ($tfs == null){              
        $semana_pasada = date('Y-m-d H:i:s', strtotime('-7 day'));
        $c = new Criteria();
        $c->add(CategoryMmTimeframePeer::TIMEEND, $semana_pasada, Criteria::GREATER_EQUAL);
        $tfs = CategoryMmTimeframePeer::doSelect($c);

        // TO DO: Limitar los timeframes a los que tengan timeend > ayer
        // (-24 horas ) para no recorrer la lista a lo tonto.
    }

    foreach ($tfs as $tf){
        if (compruebaDentroTimeframe($tf)) {
            activaTimeframe($tf);
        } else {
            desactivaTimeframe($tf);
        }
    }
}

function compruebaDentroTimeframe($tf)
{
    $now      = strtotime('now');
    $start_ts = strtotime($tf->getTimestart());
    $end_ts   = strtotime($tf->getTimeend());

    return ($start_ts < $now && $end_ts > $now);
}

function desactivaTimeframe($tf)
{   
    if (eliminaCategoriaYAscendientesDeMm($tf->getCategoryId(), $tf->getMmId())){
        echo "Desactivando timeframe:\t";
    } else {
        echo "Ya estaba desactivado:\t";
    }
    pintaDatosTf($tf);
}

function activaTimeframe($tf)
{  
    if ($cat_mm = CategoryMmPeer::retrieveByPk($tf->getCategoryId(), $tf->getMmId())){
        echo "Ya estaba activo:\t";

    } else {
        echo "Activando timeframe:\t";
        $cat = CategoryPeer::retrieveByPk($tf->getCategoryId());
        $cat->addMmIdAndUpdateCategoryTree($tf->getMmId());
    }
    pintaDatosTf($tf);
}

// Ojo, esto sólo se puede hacer con el árbol de edición temporizable
// porque no es multivaluado ni hay conflictos entre ramas
// Acepta objetos o ids para categoría o mm.
function eliminaCategoriaYAscendientesDeMm($cat, $mm)
{
    $cat_id = (is_int($cat)) ? $cat : $cat->getId();
    $mm_id  = (is_int($mm)) ? $mm : $mm->getId();
    if (is_int($cat)){
        $cat_id    = $cat;
        $categoria = CategoryPeer::retrieveByPk($cat) ;
    } else {
        $cat_id    = $cat->getId();
        $categoria = $cat;
    }
    if ($cat_mm = CategoryMmPeer::retrieveByPk($cat_id, $mm_id)){        
        if ($categoria->hasParent()){
            $cat_padre = $categoria->getParent();
            if (!$cat_padre->isRoot()){
                eliminaCategoriaYAscendientesDeMm($cat_padre, $mm);
            }
        }
        $cat_mm->delete();

        return true;
    } else {

        return false;
    }
}

function estaActivo($tf)
{
    return CategoryMmPeer::retrieveByPk($tf->getCategoryId(), $tf->getMmId());
}

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

// acepta objetos o ids para category y mm
function creaTimeframe($category, $mm, $timestart, $timeend, $description)
{
    $category_id = (is_int($category)) ? $category : $category->getId();
    $mm_id       = (is_int($mm)) ? $mm : $mm->getId();
    echo "Creando el timeframe para cat: " . $category_id . " mm: " . $mm_id .
        " inicio: " . $timestart . " fin: " . $timeend . " description: " . $description . "\n";
    $timeframe = new CategoryMmTimeframe();
    $timeframe->setCategoryId($category_id);
    $timeframe->setMmId($mm_id);
    $timeframe->setTimestart($timestart);
    $timeframe->setTimeend($timeend);
    $timeframe->setDescription($description);
    $timeframe->save();

    return ($timeframe);
}

function testSoloActivaTimeframeActual()
{
    // Todos los mms importados tendrán al menos 1 categoría.
    // Creo a mano tres mm desde el backend y sus id empiezan en 7977
    // Creo a mano una categoría raiz "timeframes" 615 y una subcategoría
    // para edición temporal 617  (en el futuro habrá radio y tv)
    $array_tfs = creaTimeframesDesactivadosAntiguoActualFuturo(617 ,array(7977,7978,7979));
    echo "\n\nRecorro la lista de timeframes para activar/desactivar los que correspondan\n";
    actualizaTimeframes($array_tfs);
    echo "\n\nCompruebo timeframes después de activar sólamente el actual:\n\n";
    foreach ($array_tfs as $tf){
        echo (estaActivo($tf)) ? "Activo:\t\t" : "Inactivo:\t";
        pintaDatosTf($tf);
    }
}

function testSoloDesactivaTimeframeAntiguo()
{
    $array_tfs = creaTimeframesDesactivadosAntiguoActualFuturo(617 ,array(7977,7978,7979));
    echo "\n\nForzando la activación de todos los timeframes\n\n";
    foreach ($array_tfs as $tf){
        activaTimeframe($tf);
    }
    echo "\n\nRecorro la lista de timeframes para activar/desactivar los que correspondan\n";
    actualizaTimeframes($array_tfs);
    echo "\n\nCompruebo timeframes después de desactivar todos menos el actual:\n\n";
    foreach ($array_tfs as $tf){
        echo (estaActivo($tf)) ? "Activo:\t\t" : "Inactivo:\t";
        pintaDatosTf($tf);
    }
}

function creaTimeframesDesactivadosAntiguoActualFuturo($category_id, $array_mm_ids)
{
    $hora_antes   = date("Y-m-d H:i:s", time() - 3600);
    $hora_despues = date("Y-m-d H:i:s", time() + 3600);
    $ayer         = date('Y-m-d H:i:s', strtotime('-1 day'));
    $manhana      = date('Y-m-d H:i:s', strtotime('+1 day'));

    echo "\nCreando timeframes desactivados, para comprobar posteriormente\n\n";
    $tf1 = creaTimeframe($category_id, $array_mm_ids[0], $ayer, $hora_antes, "Timeframe pasado - no debería estar activo");
    $tf2 = creaTimeframe($category_id, $array_mm_ids[1], $hora_antes, $hora_despues, "Timeframe actual - comprobar si se activa");
    $tf3 = creaTimeframe($category_id, $array_mm_ids[2], $hora_despues, $manhana, "Timeframe futuro - no debería estar activo");
    echo "\n";
    return array($tf1, $tf2, $tf3);
}

function pintaDatosTf($tf)
{
    echo "cat_id: " . $tf->getCategoryId() . " mm_id: " . $tf->getMmId();
    echo " período: " .$tf->getTimestart() . " y " . $tf->getTimeend() . 
        " - " . $tf->getDescription() . "\n";  
}