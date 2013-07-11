<?php

/**
 * profile batch script
 *
 * Script que elementos que tiene relaciones rotas.
 * (Objetos multimedia sin series; Archivos de video, Materiales,
 * Links, Areas de Conocimiento y Personas que no tienen Objetos Multimedia)
 *
 * @package    pumukituvigo
 * @subpackage batch
 * @version    1
 */

define('SF_ROOT_DIR',    realpath(dirname(__file__).'/../..'));
define('SF_APP',         'editar');
define('SF_ENVIRONMENT', 'dev');
define('SF_DEBUG',       1);

require_once(SF_ROOT_DIR.DIRECTORY_SEPARATOR.'apps'.DIRECTORY_SEPARATOR.SF_APP.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'config.php');

// initialize database manager
$databaseManager = new sfDatabaseManager();
$databaseManager->initialize();

// batch process here

function processInt($v)
{
    return intval($v);
}

function processDateTime($v, $format = array ("d M Y H:i:s"))
{
     
    $d = \DateTime::createFromFormat($format, $v);
    // echo $d->format('U') . "\n";
    // echo date("d M Y H:i:s", $d->format('U'))."\n";
    return $d ?$d->getTimestamp(): null;
}

if(count($argv) != 2 ){
    echo "Usage: php retrieve_resources_from_csv.php uned_data.csv\n";
    exit;
}

parseCSV($argv[1]);

function parseCSV($csv)
{
    $fila = 1;
    if (($gestor = fopen($csv, "r")) !== FALSE) {
        while (($datos = fgetcsv($gestor, 0, ";")) !== FALSE) {
            $numero = count($datos);
            if ($numero == 36 ){
                if (trim($datos[0]) == "Id") {
                    continue;
                }

                // Subtitulos - ojo que empiezan por 'deliverty/demo' pero la url real es sin eso.
                if ("" != $datos[20]) echo str_replace('/deliverty/demo', '', $datos[20]) . "\n";
                // PresetOriginal
                if ("" != $datos[22]) echo ($datos[22]) . "\n";
                // PresetAlta
                if ("" != $datos[23]) echo ($datos[23]) . "\n";
                // PresetMedia
                if ("" != $datos[24]) echo ($datos[24]) . "\n";
                // PresetBaja
                if ("" != $datos[25]) echo ($datos[25]) . "\n";
                
                // Thumbs
                // Puede haber varias como:
                // /resources/jpg/8/5/1314176791158.jpg , /resources/jpg/7/9/1314176791897.jpg
                if ("" != $datos[29]) {
                    $aThumbs = explode(',', $datos[29]);
                    foreach ($aThumbs as $thumb){
                        echo (trim($thumb)) . "\n";    
                    }
                }

                // DocumentosAdjuntos
                // Ejemplo de valor: /resources/pdf/8/8/1327184163088.pdf (3612),
                //  /resources/pdf/0/8/1326711817180.pdf (3585)              
                if ("" != $datos[33]) {
                    $sRegExp = '/(\/resources\/.+) \(.*/U'; 
                    preg_match_all($sRegExp, $datos[33], $aAdjuntos);
                    // $aAdjuntos[0] = coincidencias completas, [1] = array con las URLs
                    foreach ($aAdjuntos[1] as $adjunto){
                        if ("" != $datos[33]) echo $adjunto . "\n";
                    }
                }
       
            } else {
                echo "\n Última fila válida = \n". print_r($contenido_anterior) . "\n";
                echo "\n<error>ERROR: en la linea $fila, tiene $numero de elementos ($datos[0])</error>\n\n";
            var_dump($datos);
            }
            
            $contenido_anterior = $datos;
            
            $fila++;        
        } // end while
        fclose($gestor);
    } else {
        echo "\n<error>ERROR: in fopen($csv)</error>\n\n";
    }
}
