<?php

/**
 * Revisa las descripciones de los objetos multimedia (mm_i18n.description)
 * con la base de datos uned_media_old, iguala mm en las que no coincidan.
 *
 * Revisa el estado de publicación para que coincida con el esperado.
 *
 * Cambia el material_i18n.name de los pdfs de "(123)" a "loquesea.pdf (123)"
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

// desactiva output buffering para que muestre por pantalla los echos en tiempo real
ob_implicit_flush(true);
ob_end_flush();

//----------------------------INICIO DEL SCRIPT ----------------------------
$time_start = microtime(true);

$umos = getValidUmos();
compruebaDescripciones($umos);
compruebaEstadoPublicacion($umos);

$pdfs = selectMaterialsWithI18nByNotType("srt");
modificaNombrePdf($pdfs);


$time_end = microtime(true);
pintaln("Memoria usada:\t\t" . memory_get_usage(true));
pintaln("Tiempo de ejecución:\t" . sprintf('%3.4F', (float)($time_end - $time_start)) . " segundos\n");
//----------------------------FIN DEL SCRIPT -------------------------------

// Devuelve todos los umos con mm_id no nulo. 
// Debería dejar "hidratados" sus mms correspondientes.
function getValidUmos()
{
    $c = new Criteria();
    $c->addJoin(UnedMediaOldPeer::MM_ID, MmPeer::ID);
    $c->addJoin(MmPeer::ID, MmI18nPeer::ID);
    $umos_totales = UnedMediaOldPeer::doCount($c);

    $c->add(UnedMediaOldPeer::MM_ID, null, Criteria::ISNOTNULL);
    $umos = UnedMediaOldPeer::doSelectJoinAll($c);

    pintaln("\nTotal de objetos uned_media_old: " . $umos_totales);
    pintaln("Revisando " . count($umos) . " umos con mm_id no nulo", "azul");

    return $umos;
}

function compruebaDescripciones($umos)
{
    pintaln("\n Comprobando que las descripciones de umo sean iguales que mm\n", "azul");
    $fila = 1;
    foreach ($umos as $umo){
        $mm = $umo->getMm();
        if ($umo->getDescripcion() == $mm->getDescription()){
            pintaln("umo.original_id: " . sprintf("% 6d",$umo->getOriginalId()) . 
              " coincide con mm.id: " . sprintf("% 6d",$mm->getId()), "verde");
        } else {
            pintaln("ERROR umo.original_id: " . sprintf("% 6d",$umo->getOriginalId()) . 
                " difiere con mm.id: " . sprintf("% 6d",$mm->getId()), "rojo");
            pintaln($umo->getDescripcion(), "amarillo");
            pintaln($mm->getDescription());

            $mm->setDescription($umo->getDescripcion());
            $mm->save();
            $mm = MmPeer::retrieveByPk($mm->getId());
            pintaln("Nueva descripción:");
            pintaln($mm->getDescription());
        }
      
        if ($fila % 100 == 0) pintaln("\nFila: " . $fila . "\n");
        $fila++;
    }
}

function compruebaEstadoPublicacion($umos)
{
    pintaln("\n Comprobando que el estado de publicación de mm sea el esperado\n", "azul");
    $umo_estado_mm_status_id = array(
        'PUB'   => MmPeer::STATUS_NORMAL,
        'EDI'   => MmPeer::STATUS_BLOQ,
        'REV'   => MmPeer::STATUS_BLOQ,
        'UNPUB' => MmPeer::STATUS_BLOQ);

    $string_mm_status = array(
        0 => 'STATUS_NORMAL',
        1 => 'STATUS_BLOQ',
        2 => 'STATUS_HIDE');

    $fila = 1;
    foreach ($umos as $umo){
        $mm           = $umo->getMm();
        $mm_status_id = $mm->getStatusId();
        $mm_status    = $string_mm_status[$mm_status_id];
        $umo_estado   = $umo->getEstado();

        if ($mm_status_id == $umo_estado_mm_status_id[$umo_estado] ){
            pintaln("umo.original_id: " . sprintf("% 6d",$umo->getOriginalId()) . 
              " estado: " . $umo_estado . " - mm.id: " . 
              sprintf("% 6d",$mm->getId()) . " status:" . $mm_status, "verde");
        } else {

            pintaln("ERROR umo.original_id: " . sprintf("% 6d",$umo->getOriginalId()) . 
              " estado: " . $umo_estado . " - mm.id: " . 
              sprintf("% 6d",$mm->getId()) . " status:" . $mm_status . 
              " - Procediendo a cambiarlo", "rojo");

            $mm->setStatusId($umo_estado_mm_status_id[$umo_estado]);
            $mm->save();
            $mm = MmPeer::retrieveByPk($mm->getId());

            $mm_status_id = $mm->getStatusId();
            $mm_status    = $string_mm_status[$mm_status_id];

            pintaln("umo.original_id: " . sprintf("% 6d",$umo->getOriginalId()) . 
              " estado: " . $umo_estado . " - mm.id: " . 
              sprintf("% 6d",$mm->getId()) . " status:" . $mm_status);
        }
      
        if ($fila % 100 == 0) pintaln("\nFila: " . $fila . "\n");
        $fila++;
    }
}

function modificaNombrePdf($materials)
{
    $fila = 1;
    foreach ($materials as $material){
        if (strpos(strtolower($material->getName()),'pdf') !== false) {
            pintaln("El material.id: " . sprintf("% 5d",$material->getId()) . 
                " ya contiene un pdf en el nombre: " . $material->getName(), "verde");
        } else {
            $basename = basename($material->getUrl());
            $old_name  = $material->getName();
            $new_name  = $basename . " " . $old_name;
            
            $material->setCulture('es');
            $material->setName($new_name);
            $material->save();
            $material = MaterialPeer::retrieveByPk($material->getId());
            pintaln("material.id: " . sprintf("% 5d",$material->getId()) . "-" . 
                $material->getUrl() ." - cambiando " . $old_name . 
                " por " . $material->getName());
        }

        if ($fila % 100 == 0) pintaln("\nFila: " . $fila . "\n");
        $fila++;  
    }
}

function selectMaterialsWithI18nByType($type = 'pdf')
{
    $c = new Criteria();
    $c->add(MatTypePeer::TYPE, $type);
    $c->addJoin(MatTypePeer::ID, MaterialPeer::MAT_TYPE_ID);

    $materials = MaterialPeer::doSelectWithI18n($c, 'es');
    pintaln("\nHay " . count($materials) . " materials con " . $type , "azul");
    
    return $materials;
}

function selectMaterialsWithI18nByNotType($type = 'srt')
{
    $c = new Criteria();
    $c->add(MatTypePeer::TYPE, $type, Criteria::NOT_EQUAL);
    $c->addJoin(MatTypePeer::ID, MaterialPeer::MAT_TYPE_ID);
     
    $materials = MaterialPeer::doSelectWithI18n($c, 'es');
    pintaln("\nHay " . count($materials) . " materials con " . $type , "azul");

    return $materials;
}

/**
 * pintaln echo coloreado por terminal con \n
 */
function pintaln($str, $color = 'blanco')
{
    $c = array('verde'      => "\033[32m",
               'verdeclaro' => "\033[1;32m",
               'rojo'       => "\033[31m",
               'azul'       => "\033[34m",
               'cyan'       => "\033[36m",
               'amarillo'   => "\033[0;33m",
               'gris'       => "\033[1;30m",
               'blanco'     => "\033[0;37m",
               'fin'        => "\033[0;37m" );
                // echo "\033[35mAviso:\033[37m ";
                // echo "\033[133mDebug:\033[37m ";
    if (!array_key_exists($color,$c)){
        $color='rojo';
    }
    echo $c[$color] . $str . $c['fin'] . "\n";
}