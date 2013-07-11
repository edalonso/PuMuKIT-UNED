<?php
/**
 * update_material_name_with_canaluned_xml.php
 * Recorre la BD de pumukit, procesa los materials con display = true
 * actualizando su nombre desde los ficheros xml de canaluned.com (o unedresource.teltek.es)
 *
 * @package    pumukituvigo
 * @subpackage batch
 * @author     Andres Perez <aperez@teltek.es>
 * @version    0.9
 * @copyright  Teltek 2012
 */

define('SF_ROOT_DIR',    realpath(dirname(__file__).'/../..'));
define('SF_APP',         'editar');
define('SF_ENVIRONMENT', 'dev');
define('SF_DEBUG',       1);

require_once(SF_ROOT_DIR.DIRECTORY_SEPARATOR.'apps'.DIRECTORY_SEPARATOR.SF_APP.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'config.php');

$databaseManager = new sfDatabaseManager();
$databaseManager->initialize();

// desactiva output buffering para que muestre por pantalla los echos en tiempo real
ob_implicit_flush(true);
ob_end_flush();

// ----------------------------- Inicio del script -------------------
$array_tiempos = array();
cronometraEvento("Buscando los materiales con display = 1", $array_tiempos);

// parámetros = limit y offset de resultados a obtener.
$materials = getDisplayableMaterials();
$total     = count($materials);

echo "\n\n\nEncontrados un total de: " . $total . " materials con display=true\n";
cronometraEvento("Actualizando descripciones de materiales", $array_tiempos);
echo "\n\n Ojo: una referencia de la velocidad de este script está entre 50 y 200 materiales / minuto\n\n";

$i               = 0;
$resultados = array('ya_actualizados'  => 0, 
                    'persistidos'      => 0,
                    'no_encontrados'   => array(),
                    'sin_nombre'       => array(),
                    'con_nombre_vacio' => array());

$nombre_vacio = 'Material adjunto';

foreach ($materials as $material){
    $i++;
    if ($i % 25 == 0) muestraProgresoEstimaDuracion($array_tiempos, $i, $total);

    // canaluned.com no es accesible desde algunas máquinas de uned.
    $url  = getXmlUrlFromMaterial($material, 'unedresource.teltek.es');

    if (!$file_contents = file_get_contents($url)){
        $resultados['no_encontrados'][] = obtieneDatosIncidencia($material, $url);
        muestraError('no se encuentra el fichero XML - se usará "' . $nombre_vacio . '"', $material, $url);
        $material->setName($nombre_vacio);
        $material->save();
        continue;
    }

    if (!$names = extractNamesFromXml($file_contents)){
        $resultados['sin_nombre'][] = obtieneDatosIncidencia($material, $url);
        muestraError('el fichero xml no contiene nombres - se usará "' . $nombre_vacio . '"', $material, $url);
        $material->setName($nombre_vacio);
        $material->save();
        continue;    
    } 

    $material_filename = lcBasename($material->getUrl());
    if (!isset($names[$material_filename])){
        $resultados['sin_nombre'][] = obtieneDatosIncidencia($material, $url);
        muestraError('el fichero xml tiene nombres pero no el que necesito - se usará "' . $nombre_vacio . '"', $material, $url);
        $material->setName($nombre_vacio);
        $material->save();
        continue;        
    } 

    if ('' == $names[$material_filename]){
        $resultados['con_nombre_vacio'][] = obtieneDatosIncidencia($material, $url);
        muestraError('el nombre existe pero está vacío - se usará "' . $nombre_vacio . '"', $material, $url);
        $names[$material_filename] = $nombre_vacio;
    } 

    $name = $names[$material_filename];
    echo "Al material id: " . sprintf('%4d del mm_id: %5d', $material->getId(),
        $material->getMmId()) . " le corresponde el nombre: " . $name;
    
    if ($material->getName() == $name) {
        $resultados['ya_actualizados']++;
        echo " - ya estaba actualizado.\n";
    } else {
        $resultados['persistidos']++;
        $material->setName($name);
        $material->save();    
        echo " - persistido.\n";
    }
}

echo "\n\n-----------------------------------------------------------\n";
echo "Resultado: " . $total . " materiales totales, " . $resultados['ya_actualizados'] . 
    " ya estaban actualizados y " . $resultados['persistidos'] . " se persistieron.\n";

echo "\n\n----------  No se encontraron: " . count($resultados['no_encontrados']) . 
    ' se persistieron como "' . $nombre_vacio . '"' . "  ------------------------\n";
muestraIncidencias($resultados['no_encontrados']);

echo "\n\n----------  xml sin nombre: " . count($resultados['sin_nombre']) . 
    ' se persistieron como "' . $nombre_vacio . '"' . "  ------------------------\n";
muestraIncidencias($resultados['sin_nombre']);

echo "\n\n----------  materials con nombre vacío: " . count($resultados['con_nombre_vacio']) .
    ' se persistieron como "' . $nombre_vacio . '"' . "  ------------------------\n";
muestraIncidencias($resultados['con_nombre_vacio']);

echo "\n\n";
cronometraEvento("Final correcto", $array_tiempos);
pintaTiempos($array_tiempos);
exit;

// ------------------------------ Fin del script ---------------------

/**
 * Devuelve los material con display = 1 (pdfs, imágenes, etc menos subtítulos)
 * @param $limit máx. resultados o null = sin límite
 * @return resultset of Material.
 */
function getDisplayableMaterials($limit = null, $offset = null){
    $c = new Criteria();
    $c->add(MaterialPeer::DISPLAY, 1);
    $c->addJoin(MaterialPeer::MM_ID, MmPeer::ID);
    $c->addJoin(MmPeer::ID, UnedMediaOldPeer::MM_ID);
    if ($limit) $c->setLimit($limit);
    if ($limit) $c->setOffset($offset);
    
    return MaterialPeer::doSelectWithI18n($c);
}

function getXmlUrlFromMaterial($material, $host='canaluned.com'){
    $original_id = $material->getMm()->getUnedMediaOld()->getOriginalId();    
    $url         = 'http://' . $host . '/xml/contents/es/ASSET-' .
                        $original_id . '-AS_ASD_VID.xml';

    return $url;
}

/**
 * @param string $file_contents
 * @return array $array_file_name del tipo ('fichero_lowercase.pdf' => 'Nombre (título) del material adjunto')
 */
function extractNamesFromXml($file_contents){
    $xml = new  SimpleXMLElement($file_contents);

    if (!$xml->AS_ASD_DOC){

        return false;
    }
    
    $array_file_name = array();
    foreach ($xml->AS_ASD_DOC as $AS_ASD_DOC){
        if ($AS_ASD_DOC->ASD_FILNAM && $AS_ASD_DOC->ASD_FILE && $AS_ASD_DOC->ASD_FILE != '') {
            $file = lcBasename((string) $AS_ASD_DOC->ASD_FILE);
            $name = (string) $AS_ASD_DOC->ASD_FILNAM;
            $array_file_name[$file] = $name;
        }
    }

    return $array_file_name;
}

/**
 * cronometraEvento - añade un timestamp y descripción a un array de tiempos
 */
function cronometraEvento($nombre, array &$array_tiempos)
{
    $nombre = sprintf('%9d', memory_get_usage(true)) . "\t" . $nombre;
    $array_tiempos[$nombre] = microtime(true);
    echo $nombre . "\n";
}

/**
 * pintaTiempos - Computa y muestra resumen de tiempos.
 */
function pintaTiempos(array $array_tiempos)
{
    $t_anterior = 0;
    $t_inicial = 0;
    if (1 == count($array_tiempos)){
        cronometraEvento ("Final", $array_tiempos);
    }
    echo "\n\t\t\t  Uso RAM\n";
    echo "-----------------------------------------------------------\n";
    foreach ($array_tiempos as $evento => $t){
        if (0 == $t_anterior){
            echo "\t\t\t" . $evento . "\n";
            $t_inicial  = $t;
            $t_anterior = $t;
        } else {
            $t_duracion = (float) $t - $t_anterior;
            echo "Duración: ". sprintf('%5.4F',$t_duracion) . "\t" . $evento . "\n";
            $t_anterior = $t;
        }
    }
    $total_seg  = (float)($t_anterior - $t_inicial);
    $t_minutos  =  floor($total_seg / 60);
    $t_segundos = $total_seg % 60;        
    echo "Total:\t" . $t_minutos . " minutos y " . $t_segundos . " segundos\n\n";
}

function muestraProgresoEstimaDuracion(array $array_tiempos, $procesados, $total){
    $now         = microtime(true);
    $origin      = reset($array_tiempos);
    $elapsed_sec = (float) $now - $origin;
    $eta_sec     = ($total * $elapsed_sec) / $procesados;
    $eta_min     = $eta_sec / 60;
    $elapsed_min = $elapsed_sec / 60;
    $procesados_min = (integer) ($procesados / $elapsed_min);

    echo "Material " . $procesados . " / " . $total . "\n";
    echo "Tiempo transcurrido: " . sprintf('%.2F', $elapsed_min) .
     " minutos - estimado: " . sprintf('%.2F', $eta_min) . 
     " minutos. Velocidad: " . $procesados_min . " materiales / minuto\n";
}

/**
 * @param string $tipo - elige la salida.
 * @param Material $material 
 * @param string $url
 */
function muestraError($texto, $material, $url = null)
{
    $umo = $material->getMm()->getUnedMediaOld();
    $texto_error = "\nERROR - " . $texto . " material id: " .
        sprintf('%4d mm_id: %5d', $material->getId(), $material->getMmId()) .
            " umo.original_id: " . $umo->getOriginalId() . "\n";  

    $texto_error .= ($url) ? $url . "\n\n" : "\n\n";
    echo $texto_error;
}

/**
 * @param string $path path absoluto dentro del host. Ej.: /dir1/dir2/archivo.PDF
 * @return string filename lowercase trimeado. Ej.: archivo.pdf
 */
function lcBasename($path)
{
    return strtolower(basename(trim($path)));
}

/**
 * @param Material $material 
 * @param string $url
 * @return array $datos_incidencia
 */
function obtieneDatosIncidencia($material, $url)
{
    $umo = $material->getMm()->getUnedMediaOld();
    $datos_incidencia = array(
        'url'             => $url,
        'material_id'     => $material->getId(),
        'material_url'    => $material->getUrl(),
        'material_name'   => $material->getName(),
        'mm_id'           => $material->getMmId(),
        'umo_original_id' => $umo->getOriginalId());

    return $datos_incidencia;
}

/**
 * @param array $array_incidencias - lista de arrays generados con obtieneDatosIncidencia
 */
function muestraIncidencias($array_incidencias)
{
    foreach ($array_incidencias as $incidencia){
        $texto = $incidencia['url'] .
            sprintf("\nmat_id: %4d", $incidencia['material_id']) .
            " mat_url: " . $incidencia['material_url'] . 
            " mat_name: " . $incidencia['material_name'] .
            sprintf(' mm_id: %5d', $incidencia['mm_id']) .
            sprintf(' umo_orig_id: %5d', $incidencia['umo_original_id']);
        
        echo $texto . "\n";
    }
}