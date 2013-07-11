<?php

/**
 * Recorre un directorio y convierte todos los subtítulos .srt 
 * a .vtt dentro de la misma ruta y con el mismo nombre.
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
require_once('./srtfile.class.php');

// desactiva output buffering para que muestre por pantalla los echos en tiempo real
ob_implicit_flush(true);
ob_end_flush();

if(count($argv) != 2 ){
    echo "\nUso: php ". basename(__FILE__) . " input_path\n\n";
    exit;
}

$input_path       = $argv[1];
$total_subtitulos = 0;
$time_start       = microtime(true);
pintaln("Buscando .srt en la ruta " . $input_path . " para convertirlos a .vtt", "verde");

$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($input_path));
iterateDirectory($iterator);

pintaln("\nEncontrados " . $total_subtitulos . " subtítulos","verde");
$time_end = microtime(true);
pintaln("Memoria usada:\t\t" . memory_get_peak_usage(true));
pintaln("Tiempo de ejecución:\t" . sprintf('%3.4F', (float)($time_end - $time_start)) . " segundos\n");

exit;



function iterateDirectory($i)
{
    foreach ($i as $path) {
        if ($path->isDir())
        {
            pintaln("Revisando directorio " . $path , "gris" );
            iterateDirectory($path);
        }
        else
        {
            procesaSubtitulo($path);
        }
    }
}

function procesaSubtitulo($input_path)
{
    global $total_subtitulos;
    $pathinfo = pathinfo ($input_path);  
    if ("srt" == strtolower($pathinfo['extension'])){
        $vtt_path = $pathinfo['dirname'] . DIRECTORY_SEPARATOR . $pathinfo['filename'] . ".vtt";
        pintaln("Convirtiendo ". $input_path . " a " . $vtt_path);
        try{
            $srt = new srtFile($input_path);
            $srt->setWebVTT(true);
            $srt->build(true);
            $srt->save($vtt_path, true);
        }
        catch(Exeption $e){
            echo "Error: ".$e->getMessage()."\n";
        }
        
        $total_subtitulos++;
    } else {
        // pintaln($input_path . " no es un subtítulo");
    }   
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