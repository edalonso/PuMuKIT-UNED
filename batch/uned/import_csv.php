<?php

/**
 * import_csv importa la información de los csv exportados por UNED
 * para almacenarlos en la tabla idéntica uned_media_old
 *
 * @package    pumukituvigo
 * @subpackage batch
 * @version    1
 */

define('SF_ROOT_DIR',    realpath(dirname(__file__).'/../..'));
define('SF_APP',         'editar');
define('SF_ENVIRONMENT', 'dev');
define('SF_DEBUG',       1);
define('DEBUG',          false); // Salida "verbose".

require_once(SF_ROOT_DIR.DIRECTORY_SEPARATOR.'apps'.DIRECTORY_SEPARATOR.SF_APP.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'config.php');
require_once './UnedDesbrozatorHardcoded.php';

// initialize database manager
$databaseManager = new sfDatabaseManager();
$databaseManager->initialize();

// batch process here
// desactiva output buffering para que muestre por pantalla los echos en tiempo real
ob_implicit_flush(true);
ob_end_flush();


if(count($argv) != 2 ){
    echo "Usage: php import_csv.php uned_data.csv \n";
    exit;
}

parseCSV($argv[1]);


function processInt($v)
{
    return intval($v);
}

/** 
 * Convierte el formato que usa DateTime::createFromFormat($f, $v)
 * al usado por strptime (lo único usable en php < 5.3). 
 */
function createFromFormatParaPobres( $dformat, $dvalue )
{ // http://stackoverflow.com/questions/2312354

    if ((strlen($dvalue) == 10 && strlen($dformat) != 5)
            || (strlen($dvalue) == 16 && strlen($dformat) != 9)) {
        // tengo que asegurarme para que no trague formatos incorrectos

        return false;
    }

    $new_format_2_strptime = array(
        'd' => '%d', // día  2 dígitos
        'm' => '%m', // mes  2 dígitos
        'M' => '%b', // mes en formato 3 letras: Jan Feb...
        'Y' => '%Y', // año  4 dígitos
        'H' => '%H', // hora 2 dígitos
        'i' => '%M', // min  2 dígitos
        's' => '%S');// seg  2 dígitos   

    $dformat = str_replace(array_keys($new_format_2_strptime), 
        $new_format_2_strptime, $dformat);

    $ugly = strptime($dvalue, $dformat);

    $string_fecha_hora_valido  = sprintf(
        // This is a format string that takes six total decimal
        // arguments, then left-pads them with zeros to either
        // 4 or 2 characters, as needed
        '%04d-%02d-%02d %02d:%02d:%02d',
        $ugly['tm_year'] + 1900,  // This will be "111", so we need to add 1900.
        $ugly['tm_mon'] + 1,      // This will be the month minus one, so we add one.
        $ugly['tm_mday'],
        
        // strptime devuelve valores indefinidos para h:m:s si no están definidos
        (strpos($dformat,'%H') !== false) ? $ugly['tm_hour'] : '00', 
        (strpos($dformat,'%M') !== false) ? $ugly['tm_min'] : '00', 
        (strpos($dformat,'%S') !== false) ? $ugly['tm_sec'] : '00'
    );
    
    if (DEBUG) echo "Fecha a comprobar: " . $dvalue . "\tFormato: " . $dformat . "\tResultado: " . $string_fecha_hora_valido. "\n";
    return $string_fecha_hora_valido;
}

function processDateTime($v, $formats = array ("d M Y H:i:s"))
{  
    if ("" == $v) return null; 
    foreach ($formats as $f){
        // php >= 5.3
        // $d = \DateTime::createFromFormat($f, $v);

        // php < 5.3
        $string_fecha_hora = createFromFormatParaPobres($f, $v);
        if ($string_fecha_hora) {
            // php >= 5.3 acepta getTimestamp(); 
            // $string_fecha_hora = $d->format("Y-m-d H:i");// algo que entienda strtotime más adelante
            
            return corrigeTyposAnhos($string_fecha_hora);
        }
    }
    
    echo "\n\nError con una fecha, esperaba los formatos: ";
    foreach ($formats as $f) {
        echo "\"" . $f . "\" ";
    }
    echo " y me encontré con: \"".$v."\"\n\n";
    exit;
}

function corrigeTyposAnhos($fecha)
{
    foreach (UnedDesbrozatorHardcoded::$typos_fechas as $typo => $corregido){
        if (substr($fecha, 0,4) == $typo){
            $fecha_corregida = substr_replace ($fecha, $corregido, 0, 4);
            echo"La fecha " . $fecha . " es errónea, corrigiendola por " 
                . $fecha_corregida;
            
            return $fecha_corregida;
        }
    }

    return $fecha;
}

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
                $umo = new UnedMediaOld();
                
                $umo->setOriginalId(processInt($datos[0]));
                $umo->setFechaDeCreacion(processDateTime($datos[1]));
                $umo->setFechaDeActualizacion(processDateTime($datos[2]));
                $umo->setTitulo($datos[3]);
                $umo->setDescripcionCorta($datos[4]);
                $umo->setDescripcion($datos[5]);
                $umo->setAlt($datos[6]);
                $umo->setPie($datos[7]);
                $umo->setOrigen($datos[8]);
                $umo->setAutor($datos[9]);
                $umo->setRealizador($datos[10]);
                $umo->setAno(processDateTime($datos[11], array("d/m/Y")));
                $umo->setTituloOriginal($datos[12]);
                $umo->setReferenciaALaFuente($datos[13]);
                $umo->setStreaming($datos[14]);
                $umo->setDenegarDescarga($datos[15]);
                // Algunas fechas son "13/06/2011 20:00" y otras "13/06/2011"
                $umo->setFechaInicio(processDateTime($datos[16], array("d/m/Y H:i","d/m/Y")));
                $umo->setFechaFin(processDateTime($datos[17], array("d/m/Y H:i","d/m/Y")));
                $umo->setCritico($datos[18]);
                $umo->setDerechos($datos[19]);
                $umo->setSubtitulos($datos[20]);
                $umo->setAutoria($datos[21]);
                $umo->setPresetOriginal($datos[22]);
                $umo->setPresetAlta($datos[23]);
                $umo->setPresetMedia($datos[24]);
                $umo->setPresetBaja($datos[25]);
                $umo->setTematicas($datos[26]);
                $umo->setCategorias($datos[27]);
                $umo->setDestinosDePublicacion($datos[28]);
                $umo->setThumbs($datos[29]);
                $umo->setTags($datos[30]);
                $umo->setEnlaces($datos[31]);
                $umo->setRelacionados($datos[32]);
                $umo->setDocumentosAdjuntos($datos[33]);
                $umo->setEstado($datos[34]);
                
                $umo->save();
                if (DEBUG) { 
                    $umo = UnedMediaOldPeer::retrieveByPk($umo->getId()); //Fuerza a obtener los datos de BD.
                    echo "Las fechas grabadas son:" . 
                        "\t\tFechaDeCreacion\t\t" . $umo->getFechaDeCreacion() . "\n" . 
                        "\t\t\t\t\tFechaDeActualizacion\t" . $umo->getFechaDeActualizacion() . "\n" .
                        "\t\t\t\t\tAño\t\t\t" . $umo->getAno() . "\n" .
                        "\t\t\t\t\tFechaInicio\t\t" . $umo->getFechaInicio() . "\n" .
                        "\t\t\t\t\tFechaFin\t\t" . $umo->getFechaFin() . "\n";
                }
        
            } else {
                echo "\n Última fila válida = \n". print_r($contenido_anterior) . "\n";
                echo "\n<error>ERROR: en la linea $fila, tiene $numero de elementos ($datos[0])</error>\n\n";
            var_dump($datos);
            }
            if (DEBUG){
                echo "Importando fila del csv a uned_media_old: " . $fila . "\n";
            } else if ($fila % 100 == 0 ) {
                echo "Importando fila del csv a uned_media_old: " . $fila . "\n";
            }
            $contenido_anterior = $datos;
            
            $fila++;        
        } // end while
        fclose($gestor);
    } else {
        echo "\n<error>ERROR: in fopen($csv)</error>\n\n";
    }
}
