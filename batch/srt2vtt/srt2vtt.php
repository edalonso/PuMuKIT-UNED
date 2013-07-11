<?php

/**
 * Convierte subtítulos .srt en .vtt usando srtfile.class.php
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

if(count($argv) != 3 ){
    echo "Usage: php input.srt output.vtt\n";
    exit;
} 
$input_file = $argv[1];
$output_file = $argv[2];
echo "\nConvirtiendo " . $input_file . " al archivo WebVTT: " . $output_file . "\n";

try{
	$srt = new srtFile($input_file);
	$srt->setWebVTT(true);
	$srt->build(true);
	$srt->save($output_file, true);
}
catch(Exeption $e){
	echo "Error: ".$e->getMessage()."\n";
}