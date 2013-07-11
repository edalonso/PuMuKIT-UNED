<?php

/**
 * Crea un nuevo mat_type para subtítulos .vtt
 * Recorre los material con mat_type.type .srt
 * Crea un archivo .vtt a partir de cada .srt (¿Acceso de escritura?)
 * Asignar el .vtt al mismo mm que el material.
 *
 * Usa srtfile.class.php
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
require_once(realpath(dirname(__file__)).'/srtfile.class.php');

define('SF_WEB_DIR',     sfConfig::get('sf_web_dir'));

// initialize database manager
$databaseManager = new sfDatabaseManager();
$databaseManager->initialize();
$log_str  = '';
$log_file = realpath(dirname(__file__)).'/log_srt_vtt_pumukit_material.log';

// desactiva output buffering para que muestre por pantalla los echos en tiempo real
ob_implicit_flush(true);
ob_end_flush();
compruebaCondicionesIniciales($argv);

//---------------------- INICIO DEL SCRIPT ---------------------------------

$total_subtitulos = 0;
$total_errores    = 0;
$time_start       = microtime(true);
$mat_type         = findOrPersistMatType('vtt', 0, 'text/vtt', 'Texto-subtítulos webVTT');
$mat_type_id      = $mat_type->getId();

pintaln("Buscando .srt en la base de datos de Pumukit", "verde");

$materials = selectMaterialsByType('srt');
convertMaterialsWithNewMatTypeId($materials, $mat_type_id);

pintaln("\nProcesados: " . count($materials) . " subtítulos");
pintaln("Creados:  " . $total_subtitulos . " subtítulos nuevos\n","verde");

compruebaTotalSubtitulos();

$color = (0 == $total_errores) ? 'verde' : 'rojo';
pintaln("Encontrados: " . $total_errores . " errores\n", $color);

$time_end = microtime(true);
pintaln("Memoria usada:\t\t" . memory_get_usage(true));
pintaln("Tiempo de ejecución:\t" . sprintf('%3.4F', (float)($time_end - $time_start)) . " segundos");
pintaln("\nSi añades más .srt en el futuro, puedes volver a ejecutar este script");

escribeLog($log_file, $log_str);

exit;
//--------------------------------------------------------------------------

function compruebaCondicionesIniciales($argv)
{
    if(count($argv) != 1 ){
        echo "\nUso: su apache -c 'php " . basename(__FILE__) . "'\n";
        exit;
    }

    if ('coruxo' == php_uname('n')){
        // Desarrollo => no comprobar permisos de ficheros.
        return true;
    }

    $file_owner = posix_getpwuid(fileowner(__file__)); 
    if ( $file_owner['name'] != 'apache' && $file_owner['name'] != 'www-data'){
        echo "\nEl propietario del fichero es: " . $file_owner['name'] . 
        " - hay que cambiarlo a apache o www-data\n";
        exit;
    }

    $current_user = get_current_user();
    if ($current_user != 'apache' && $current_user != 'www-data'){
        echo "\nUsuario actual: " . $current_user . " - hay que cambiarlo por apache, www-data o similar"; 
        echo "\nUso: su apache -c 'php " . basename(__FILE__) . "'";
        echo "\nNo te olvides de revisar el propietario del script\n";
        exit;
    }

    return true;
}

function findOrPersistMatType($type = 'vtt', $default_sel = 0, 
    $mime_type = 'text/vtt', $name = 'Texto-subtítulos webVTT')
{
    $c = new Criteria();
    $c->add(MatTypePeer::TYPE, $type);
    if (!$mat_type = MatTypePeer::doSelectOne($c)){
        pintaln("mat_type " . $type . " no existe - creándolo en la BD", "azul");
        
        $mat_type = new MatType();
        $mat_type->setType($type);
        $mat_type->setDefaultSel($default_sel);
        $mat_type->setMimeType($mime_type);

        $mat_type->setCulture('es');
        $mat_type->setName($name);
        $mat_type->save();

    } else {
        pintaln("mat_type " . $type . " existe - recuperándolo de la BD", "amarillo");
    }

    return $mat_type;
}

function findOrPersistMaterial($mm_id, $url, $mat_type_id, $display = 0, $name = 'Subtítulos')
{
    $c = new Criteria();
    $c->add(MaterialPeer::MM_ID, $mm_id);
    $c->add(MaterialPeer::URL, $url);
    $c->add(MaterialPeer::MAT_TYPE_ID, $mat_type_id);
    if (!$material = MaterialPeer::doSelectOne($c)){
        pintaln("material mm_id:" . sprintf("% 6d",$mm_id) . " url: " . $url .
            " mat_type_id: " .  $mat_type_id . " no existe - creándolo en la BD");
        
        $material = new Material();
        $material->setMmId($mm_id);
        $material->setUrl($url);
        $material->setMatTypeId($mat_type_id);
        $material->setDisplay($display);

        $material->setCulture('es');
        $material->setName($name);
        $material->save();
    } else {
        pintaln("material mm_id:" . sprintf("% 6d",$mm_id) . " url: " . $url .
            " mat_type_id: " .  $mat_type_id . " existe - recuperándolo de la BD", "amarillo");
    }

    return $material;
}

function selectMaterialsByType($type = 'srt')
{
    $c = new Criteria();
    $c->add(MatTypePeer::TYPE, $type);
    $c->addJoin(MatTypePeer::ID, MaterialPeer::MAT_TYPE_ID);

    return MaterialPeer::doSelect($c);
}

function convertMaterialsWithNewMatTypeId($materials, $mat_type_id)
{
    if (!$materials || !$mat_type_id){
        pintaln("Error en los parámetros de entrada - convertMaterials...", "rojo");
        return;
    }

    foreach ($materials as $material){
        pintaln("Procesando material id: " . sprintf("% 5d",$material->getId()) .
            " - mm_id: " . sprintf("% 5d",$material->getMmId()) . 
            " - url: " . $material->getUrl());

        $mm_id          = $material->getMmId();
        $srt_input_path = SF_WEB_DIR . $material->getUrl();


        
        if ($vtt_path = procesaSubtitulo($srt_input_path)){
            $vtt_url  = str_replace(SF_WEB_DIR, '', $vtt_path);
            $material = findOrPersistMaterial($mm_id, $vtt_url, $mat_type_id, 0, 'Subtítulos');
            compruebaMaterial($material);
        } else {
            pintaln("Error: No se ha podido procesar material id: " . 
                sprintf("% 5d",$material->getId()) . " - " . $srt_input_path, "rojo");
        }
    }
}

/**
 * Recibe el path  completo /var/www/pumukit/.../12354.srt
 * Convierte y crea .vtt,
 * Devuelve el path completo /var/www/pumukit/.../12354.vtt    
 */
function procesaSubtitulo($input_path)
{
    global $total_subtitulos; 
    $pathinfo = pathinfo ($input_path);

    if ("srt" != strtolower($pathinfo['extension'])){
        pintaln("ERROR: " . $input_path . " no es un subtítulo", "rojo");
        
        return false;
    }

    if (!file_exists($input_path)){
        pintaln("ERROR: " . $input_path . " no existe", "rojo");

        return false;
    }
    $pathinfo['filename'] = substr($pathinfo['basename'], 0, -1-strlen($pathinfo['extension'])); // php <5.2
    $vtt_path = $pathinfo['dirname'] . DIRECTORY_SEPARATOR . $pathinfo['filename'] . ".vtt";
    
    if (file_exists($vtt_path)){
        pintaln("Ya existe el archivo " . $vtt_path, "amarillo");
        compruebaArchivoMuestraErrores($vtt_path);

        return $vtt_path;

    } else {
        pintaln("Convirtiendo ". $input_path . " a " . $vtt_path);
        try{
            $srt = new srtFile($input_path);
            $srt->setWebVTT(true);
            $srt->build(true);
            $srt->save($vtt_path, true);
        }
        catch(Exception $e){
            echo "Error: ".$e->getMessage()."\n";
        }

        if (!file_exists($vtt_path)){
            pintaln("Error: No se ha creado " . $vtt_path , "rojo");
            return false;
        }

        $total_subtitulos++;

        return $vtt_path;
    }    
}

function compruebaArchivoMuestraErrores($path)
{
    if (!is_readable($path)){
        pintaln("Error: ¡No lo puedo leer!","rojo");
        exit;
    }
    if (!is_file($path)){
        pintaln("Error: ¡No es un archivo válido!", "rojo");
        exit;
    }
    if (filesize($path)<11){
        pintaln("Error: ¡Tiene mala pinta, su tamaño es ínfimo!");
        exit;
    }
}

function compruebaMaterial($material)
{
    if (!$material){
        pintaln("Error: material inexistente", "rojo");
        
        return false;

    } 

    $path = SF_WEB_DIR . $material->getUrl();
    if (!file_exists($path)) {
        pintaln("Error: no encuentro el archivo " . $path .
            " del material_id: " . $material->getId(), "rojo");
        
        return false;
    } else {
        compruebaArchivoMuestraErrores($path);
        pintaln("Archivo " . $path . " existe y tiene buena pinta", "verde");
    }
}

function compruebaTotalSubtitulos()
{
    $c = new Criteria();
    $c->addJoin(MatTypePeer::ID, MaterialPeer::MAT_TYPE_ID);
    $c->add(MatTypePeer::TYPE, 'vtt');
    $total_vtt = MaterialPeer::doCount($c);

    $c = new Criteria();
    $c->addJoin(MatTypePeer::ID, MaterialPeer::MAT_TYPE_ID);
    $c->add(MatTypePeer::TYPE, 'srt');
    $total_srt = MaterialPeer::doCount($c);

    $color = ($total_vtt == $total_srt) ? 'verde' : 'rojo';
    pintaln("Comprobación final: total de subtítulos en la BD: " . $total_srt  . " srt y " .
        $total_vtt . " vtt", $color);

}

function testDeleteMaterialWithMaterialTypeId($mat_type_id)
{
    $c = new Criteria();
    $c->add(MaterialPeer::MAT_TYPE_ID, $mat_type_id);
    $materials = MaterialPeer::doSelect($c);
    foreach($materials as $material){
        $path = SF_WEB_DIR . $material->getUrl();
        pintaln("Borrando: ".$path);
        unlink($path);
        pintaln("Borrando material");
        $material->delete();
    }
}

/**
 * pintaln echo coloreado por terminal con \n
 */
function pintaln($str, $color = 'blanco')
{
    global $log_str;
    global $total_errores;
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
    $log_str .= $str . "\n";
    if ('rojo' == $color) $total_errores++;
}

function escribeLog($log_file, $log_str)
{
    pintaln("\nEscribiendo log con toda la información sacada por terminal a:");
    pintaln($log_file);
    file_put_contents($log_file, $log_str, LOCK_EX);
}
