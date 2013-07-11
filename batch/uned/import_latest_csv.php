<?php
/**
 * import_latest_csv.php
 * Procesa los archivos .csv, comprueba el contenido ya importado,
 * importa los contenidos que no existan en uned_media_old
 * creando todos los mms, files, pics, etc. nuevos.
 *
 * En principio no crea nuevas nodoseries, sólo asigna los nuevos
 * contenidos a una serie y nodoserie "Nuevos vídeos y audios"
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
require_once './HumanNameParser/Name.php';
require_once './HumanNameParser/Parser.php';
require_once './UnedDesbrozatorHardcoded.php';

// initialize database manager
$databaseManager = new sfDatabaseManager();
$databaseManager->initialize();


if(count($argv) > 2  || (count($argv) == 2 && strpos('012345',$argv[1]) === false)){
    echo "Uso: php uned-desbrozator [opcional - nivel de detalle, de 0 a 4]\n";
    echo "El valor por defecto es 1: php uned-desbrozator 1";
    exit;
}
$parametro_debug  = (isset($argv[1])) ? $argv[1]: 1;
$columnasTerminal = exec('tput cols') - 10;

// Salida por terminal
define ('MAX_TERMINAL', $columnasTerminal);
define ('NIVEL_DEBUG', $parametro_debug);
define ('IS_A_TTY' , posix_isatty(STDOUT));

// Nombres arbitrarios
define ('NODOSERIE_UNICA_IMPORTADOS', '##Varios sin nodoserie--'); // Serie única donde se meterán todos
define ('SERIE_UNICA_IMPORTADOS', 'Importados genéricos UNED');
define ('RAIZ_NODOSERIES_UNED', 'Nodoseries UNED');
define ('RAIZ_CATEGORIAS_TEMATICAS', 'Tematicas UNED');
define ('RAIZ_CATEGORIAS_UNESCO', 'UNESCO');
define ('WEB_PERSON', "http://www.canaluned.com"); // Establezco esta web para distinguir person importadas
define ('ROL_AUTOR', 'Autor importado');
define ('ROL_REALIZADOR', 'Realizador importado');
define ('HOST_FICHEROS', "www.canaluned.com");
define ('IMPORT_CSV_FOLDER', realpath(dirname(__file__)).'/import_csv_resources_2');
define ('PUNTO_MONTAJE_FILES', "/mnt/nfsuned"); // path para file.file

// Constantes internas
define ('ACTUALIZA_INDICES_LUCENE', false); // Actualiza el buscador cada vez que se graba un mm


// desactiva output buffering para que muestre por pantalla los echos en tiempo real
ob_implicit_flush(true);
ob_end_flush();



// ----------------------------- Inicio del script -------------------
$array_tiempos = array();
pintaln("\nNota: ¡¡¡ Es normal que salten php warnings de cookie o sesión symfony!!!\n","amarillo");
cronometraEvento("Inicio del script", $array_tiempos);

// VARIABLES GLOBALES para leer una sola vez los objetos importantes de la BD.
$serie_importados     = serieUnicaImportados();
$nodoserie_importados = nodoserieUnicaImportados();
$role_autor           = persisteRole(ROL_AUTOR);
$role_realizador      = persisteRole(ROL_REALIZADOR);
$cat_raiz_tematicas   = creaSubRaiz(RAIZ_CATEGORIAS_TEMATICAS);
$cat_raiz_unesco      = creaSubRaiz(RAIZ_CATEGORIAS_UNESCO);
$array_cod_cat_unesco = inicializaCodCategoriaUnesco(); // array ( cod_unesco => objeto categoría unesco)

// Prueba nuevas funciones antes de ejecutar el script (y habitualmlente exit)
testNuevaFuncion();

creaDefaultBroadcastSiEstaVacio();
compruebaCsvImportaNuevosUmos();
// La chicha está en importaFilaCsvCreaTodo($fila_csv);

pintaln ("\n¡¡Final correcto!!","verde", 0);
pintaTiempos($array_tiempos);
exit;

// ------------------------------ Fin del script ---------------------

// *** FUNCIONES PARA UTILIDADES GENERALES ***************************
function pinta($str, $color = 'verde', $debug = 1)
{
    if ( $debug <= NIVEL_DEBUG){
        if (!IS_A_TTY){ // se ha redirigido a un fichero
            echo $str;
        } else {
            $c = array('verde'      => "\033[32m",
                       'verdeclaro' => "\033[1;32m",
                       'rojo'       => "\033[31m",
                       'error'      => "\033[31m",
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
            $str = substr($str,0,MAX_TERMINAL + 3);
            echo $c[$color] . $str . $c['fin'];
        }
            
    }
}

function pintaln($str, $color = 'blanco', $debug=1)
{
    if (is_numeric($color)){
            $debug = $color;
            $color = 'blanco';
        }
    if ( $debug <= NIVEL_DEBUG){
        pinta($str,$color, $debug);
        echo "\n";
    }
}

/**
 * cronometraEvento - añade un timestamp y descripción a un array de tiempos
 */
function cronometraEvento($nombre, array &$array_tiempos)
{
    $nombre = sprintf('%9d %9d', memory_get_usage(true), memory_get_usage(true)) . "\t" . $nombre;
    $array_tiempos[$nombre] = microtime(true);
    pintaln($nombre,"blanco");
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
    pintaln("\n\t\t\t  Uso RAM  Pico Ram");
    pintaln("--------------------------------------------------------------------");
    foreach ($array_tiempos as $evento => $t){
        if (0 == $t_anterior){
            pintaln("\t\t\t".$evento);
            $t_inicial  = $t;
            $t_anterior = $t;
        } else {
            $t_duracion = (float) $t - $t_anterior;
            pintaln("Duración: ". sprintf('%5.4F',$t_duracion) . "\t" . $evento );
            $t_anterior = $t;
        }
    }
    pintaln ("Total:\t" . sprintf('%5.4F', (float)($t_anterior - $t_inicial)) . " segundos\n\n");
}

// *******************************************************************
// *** FUNCIONES PARA IMPORTAR CSV A U_M_O ***************************
// *******************************************************************

/** 
 * Convierte el formato que usa DateTime::createFromFormat($f, $v)
 * al usado por strptime (lo único usable en php < 5.3). Copiado de import_csv.php
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

    // p.ej.: "2015-10-21 00:00:00"
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
    if (NIVEL_DEBUG > 4) echo "Fecha a comprobar: " . $dvalue . "\tFormato: " . $dformat . "\tResultado: " . $string_fecha_hora_valido. "\n";

    return $string_fecha_hora_valido;
}


// copiado de import_csv.php
function processInt($v)
{
    return intval($v);
}
// copiado de import_csv.php
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
            pintaln("La fecha " . $fecha . " es errónea, corrigiendola por " 
                . $fecha_corregida, "rojo");
            
            return $fecha_corregida;
        }
    }

    return $fecha;
}

/**
 * parseCSV - código modificado a partir de import_csv.php
 * Recorre un fichero csv.
 * Para cada línea correcta y no incluída en la lista $umo_original_ids,
 *      llama a importaFilaCsvCreaTodo().
 *
 * @param string $csv csv path and filename
 * @param array $umo_original_ids list of all ids present.
 */
function parseCSV($csv, $umo_original_ids)
{
    global $array_tiempos;
    $fila = 1;
    if (($gestor = fopen($csv, "r")) !== FALSE) {
        $contenido_anterior = '';
        $filas_importadas = 0;
        $filas_existentes_o_antiguas = 0;
        while (($fila_csv = fgetcsv($gestor, 0, ";")) !== FALSE) {
            $total_columnas_csv = count($fila_csv);
            if ($total_columnas_csv == 36 ){
                if (trim($fila_csv[0]) == "Id") { // Descarta la fila de títulos
                    continue;
                }
                $contenido_anterior = $fila_csv;
                $fila_csv[0] = trim($fila_csv[0]);

                // podemos comprobar e importar fila
                if ($fila % 100 == 0 ) {
                    cronometraEvento("Procesando fila del csv: " . $fila, $array_tiempos);
                    // pintaln("\nProcesando fila del csv: " . $fila . "\n","verdeclaro");
                }
                // if (NIVEL_DEBUG > 2 && ($fila % 100 != 0)) {
                    // echo "Procesando fila " . $fila . " del csv con uned_media_old.original_id: " . $fila_csv[0] . "\n";
                // }               

                if (compruebaFilaCsvAnteriorAMarzo($fila_csv)){
                    pintaln("Fila " . $fila . " con uned_media_old.original_id: " .
                        $fila_csv[0] . " anterior a marzo " . $fila_csv[1], "gris");
                    $filas_existentes_o_antiguas++;
                
                } else if (compruebaFilaCsvYaImportada($fila_csv, $umo_original_ids)){
                    pintaln("Fila " . $fila . " con uned_media_old.original_id: " . $fila_csv[0] . " ya importada", "gris");
                    $filas_existentes_o_antiguas++;
                
                } else { 
                    pintaln("Importando fila " . $fila . " del csv con uned_media_old.original_id: " . $fila_csv[0]); 
                    //echo "DEBUG - prueba importando fila del csv\n";
                    importaFilaCsvCreaTodo($fila_csv);
                    $filas_importadas++;
                }
        
            } else { 
                echo "\nERROR: en la linea $fila, tiene $total_columnas_csv de elementos ($fila_csv[0])\n\n";
                echo "\n Última fila válida = \n". print_r($contenido_anterior) . "\n";
                var_dump($fila_csv);
            }          
            
            $fila++;        
        } // end while
        fclose($gestor);
    } else {
        echo "\n<error>ERROR: in fopen($csv)</error>\n\n";
    }
    pintaln("\n\nFin del procesado de " . $csv, "azul");
    pintaln("Total: " . ($fila - 1) . " filas con " . $filas_existentes_o_antiguas . " filas existentes o antiguas y " . 
        $filas_importadas . " filas importadas", "azul");

}

// Comprueba si existe la fila del csv en uned_media_old (original_id)
function compruebaFilaCsvYaImportada($fila_csv, $umo_original_ids)
{
    return in_array($fila_csv[0], $umo_original_ids);
}

function compruebaFilaCsvAnteriorAMarzo($fila_csv)
{
    $marzo2013 = strtotime("2013-03-01 00:00:00");
    $fecha_csv = strtotime($fila_csv[1]);

    return $fecha_csv < $marzo2013;
}

// crea uned_media_old con datos parseados de una fila del csv
function creaUmoDesdeFilaCsv($fila_csv)
{
    $umo = new UnedMediaOld();
    
    $umo->setOriginalId(processInt($fila_csv[0]));
    $umo->setFechaDeCreacion(processDateTime($fila_csv[1]));
    $umo->setFechaDeActualizacion(processDateTime($fila_csv[2]));
    $umo->setTitulo($fila_csv[3]);
    $umo->setDescripcionCorta($fila_csv[4]);
    $umo->setDescripcion($fila_csv[5]);
    $umo->setAlt($fila_csv[6]);
    $umo->setPie($fila_csv[7]);
    $umo->setOrigen($fila_csv[8]);
    $umo->setAutor($fila_csv[9]);
    $umo->setRealizador($fila_csv[10]);
    $umo->setAno(processDateTime($fila_csv[11], array("d/m/Y")));
    $umo->setTituloOriginal($fila_csv[12]);
    $umo->setReferenciaALaFuente($fila_csv[13]);
    $umo->setStreaming($fila_csv[14]);
    $umo->setDenegarDescarga($fila_csv[15]);
    // Algunas fechas son "13/06/2011 20:00" y otras "13/06/2011"
    $umo->setFechaInicio(processDateTime($fila_csv[16], array("d/m/Y H:i","d/m/Y")));
    $umo->setFechaFin(processDateTime($fila_csv[17], array("d/m/Y H:i","d/m/Y")));
    $umo->setCritico($fila_csv[18]);
    $umo->setDerechos($fila_csv[19]);
    $umo->setSubtitulos($fila_csv[20]);
    $umo->setAutoria($fila_csv[21]);
    $umo->setPresetOriginal($fila_csv[22]);
    $umo->setPresetAlta($fila_csv[23]);
    $umo->setPresetMedia($fila_csv[24]);
    $umo->setPresetBaja($fila_csv[25]);
    $umo->setTematicas($fila_csv[26]);
    $umo->setCategorias($fila_csv[27]);
    $umo->setDestinosDePublicacion($fila_csv[28]);
    $umo->setThumbs($fila_csv[29]);
    $umo->setTags($fila_csv[30]);
    $umo->setEnlaces($fila_csv[31]);
    $umo->setRelacionados($fila_csv[32]);
    $umo->setDocumentosAdjuntos($fila_csv[33]);
    $umo->setEstado($fila_csv[34]);
    
    $resultado = $umo->save();
      //echo "DEBUG - umo->save() con resultado " . $resultado. "\n";

    $umo = UnedMediaOldPeer::retrieveByPk($umo->getId()); //Fuerza a obtener los datos de BD.

    if (NIVEL_DEBUG > 4) { 
        echo "Las fechas grabadas son:" . 
            "\t\tFechaDeCreacion\t\t" . $umo->getFechaDeCreacion() . "\n" . 
            "\t\t\t\t\tFechaDeActualizacion\t" . $umo->getFechaDeActualizacion() . "\n" .
            "\t\t\t\t\tAño\t\t\t" . $umo->getAno() . "\n" .
            "\t\t\t\t\tFechaInicio\t\t" . $umo->getFechaInicio() . "\n" .
            "\t\t\t\t\tFechaFin\t\t" . $umo->getFechaFin() . "\n";
    }

    return $umo;
}


/**
 * Usa las funciones copiadas de import_csv e importa los archivos.
 */
function compruebaCsvImportaNuevosUmos()
{
    $csv_files = glob(IMPORT_CSV_FOLDER . '/*.csv');
    if (0 === count($csv_files)){
        throw new Exception ("No se encuentran ficheros ?.csv en " . IMPORT_CSV_FOLDER);           
    }

    $umo_original_ids = getUmoOriginalIds();
    foreach ($csv_files as $csv_file){
        pintaln(str_repeat("-", 80), "azul");
        pintaln("Procesando fichero " . $csv_file, "azul");
        pintaln(str_repeat("-", 80), "azul");
        parseCsv($csv_file, $umo_original_ids);
    }
}

function getUmoOriginalIds()
{
    $umos = UnedMediaOldPeer::doSelect(new Criteria());
    $umo_original_ids = array();
    foreach ($umos as $umo){
        $umo_original_ids[] = $umo->getOriginalId();
        unset($umo);
    }
    unset($umos);

    return $umo_original_ids;
}

// *******************************************************************
// *** CHICHA: FUNCIONES PARA CREAR ESTRUCTURA DE DATOS DESDE U_M_O **
// *******************************************************************
function importaFilaCsvCreaTodo($fila_csv)
{
    global $serie_importados, $nodoserie_importados;

    pintaln("Voy a importar un umo con id ".$fila_csv[0], "azul");
    $umo = creaUmoDesdeFilaCsv($fila_csv);
    //echo "DEBUG - umo creado\n";
    if (esteUmoTieneMultimedia($umo)){
        $mm  = creaMmYActualizaUmo($umo, $serie_importados);
        //echo "DEBUG - mm ya creado " . $mm->getId() . "\n";
        $nodoserie_importados->addMmIdAndUpdateCategoryTree($umo->getMmId());

        importaPresets($umo);
        importaTematicasAsignaUnescos($umo);
        importaPersonasDeUmo($umo, $mm);
        importaThumbsCreaPicMms($umo, $mm);
        importaEnlaces($umo);
        importaDocumentosAdjuntos($umo);
        importaSubtitulos($umo);
        //¿pubchannel?
    } else {
        pintaln("El umo.original_id " . $umo->getoriginalId() . " no contiene multimedia (p.ej. sólo streaming)", "cyan");
        pintaln("No se creará mm, thumbs, etc; sólo se cubre uned_media_old", "cyan");

    }
}

// Al crear la descripción del mm, puedo hacer una versión de corrigeTyposIniciales

/**
 * serieUnicaImportados - crea o busca y devuelve la serie de pumukit 
 * que contendrá a todos los mm importados.
 */
function serieUnicaImportados()
{
    $c = new Criteria();
    $c->add(SerialI18nPeer::TITLE, SERIE_UNICA_IMPORTADOS, Criteria::LIKE);
    $serie_importados = SerialPeer::doSelectWithI18n($c, 'es');  

    if (!$serie_importados) {
// Comentar para que cree serie. En producción debería lanzar extensión.
        // pintaln("No se encuentra la serie pumukit donde se meterán los importados.", "rojo", 0);
        // pintaln("Editar en el código: SERIE_UNICA_IMPORTADOS usando una existente.", "rojo", 0); 
        // pintaln('Su valor actual es: "' . SERIE_UNICA_IMPORTADOS . '"', "rojo", 0);
        // throw new Exception ("No se encuentra la serie pumukit donde se meterán los importados.\n");

        $serie_importados = new Serial();
        $serie_importados->setCulture('es');
        $serie_importados->setPublicdate("now");
        $serie_importados->setTitle(SERIE_UNICA_IMPORTADOS);
        $serie_importados->setSerialTypeId(SerialTypePeer::getDefaultSelId());
        $serie_importados->setSerialTemplateId(1);
        $serie_importados->save();

        return $serie_importados;
    } else if (count($serie_importados) > 1) {
        throw new Exception ("Hay más de una serie con el título \"".SERIE_IMPORTADOS."\"");
    }

    pintaln("Inicializada la serie única donde se importarán los objetos: " . SERIE_UNICA_IMPORTADOS, "azul");
    return $serie_importados[0];
}

/**
 * serieUnicaImportados - crea o busca y devuelve la serie de pumukit 
 * que contendrá a todos los mm importados.
 */
function nodoserieUnicaImportados()
{
    if (!$nodoserie = CategoryPeer::retrieveByName(NODOSERIE_UNICA_IMPORTADOS)){
        pintaln("No se encuentra la nodoserie pumukit donde se meterán los importados." , "rojo", 0);
        pintaln("Editar en el código: NODOSERIE_UNICA_IMPORTADOS. Su valor actual es: " . 
            SERIE_UNICA_IMPORTADOS, "rojo", 0);
        throw new Exception ("No se encuentra la nodoserie pumukit donde se meterán los importados.\n");
    } else {

        pintaln("Inicializada la nodoserie única donde se importarán los objetos: " . NODOSERIE_UNICA_IMPORTADOS, "azul");
        return $nodoserie;
    }
}

/**
 * creaMm - Crea un nuevo mm importando el id de UnedMediaOld y asignándolo a $serie
 * Actualiza uned_media_old.mm_id con el nuevo mm.
 */
function creaMmYActualizaUmo($umo, $serie_importados)
{
    $audio  = ('Audios' == $umo->getCategorias())? 1 : 0;
    $umo_estado_mm_status_id = array(
        'PUB'   => MmPeer::STATUS_NORMAL,
        'EDI'   => MmPeer::STATUS_BLOQ,
        'REV'   => MmPeer::STATUS_BLOQ,
        'UNPUB' => MmPeer::STATUS_BLOQ);

    $mm  = new Mm();
    $mm->setSerial($serie_importados);
    $mm->setCulture('es');

    $metodos_mm_umo = array('RecordDate'    => 'FechaDeCreacion',
                            'PublicDate'    => 'FechaDeActualizacion',
                            'Title'         => 'Titulo',
                            'Description'   => 'Descripcion');
    foreach ($metodos_mm_umo as $s_mm => $g_umo){
        $setter = 'set'.$s_mm;
        $getter = 'get'.$g_umo;       
        $mm->$setter( $umo->$getter() );
    }

    $mm->setGenreId(GenrePeer::getDefaultSelId()); // Default = -- Sin Género --
    //FIXME - no existe broadcast por defecto y salta error.
    $mm->setBroadcastId(BroadcastPeer::getDefaultSelId());
    $mm->setAudio($audio);
    $mm->setStatusId($umo_estado_mm_status_id[$umo->getEstado()]);

    (ACTUALIZA_INDICES_LUCENE)? $mm->save() : $mm->saveInDB();

    $umo->setMmId($mm->getId());
    $umo->save();
    pintaln("Mm: " . $mm->getId() . " creado correctamente " . 
        "a partir de umo.original_id: " . $umo->getOriginalId(), "verde");
    $umo->clearAllReferences(true);
    unset($umo);

    return $mm;
}

/**
 * Busca autor y realizador de umo, crea nuevas person o carga existentes
 * crea o carga mm_person con esa person, mm y el rol correspondiente.
 */
function importaPersonasDeUmo($umo, $mm)
{
    global $role_autor;
    global $role_realizador;
    pintaln("\tImportando personas de umo.original_id: " . $umo->getOriginalId());
    procesaRolDeUmo($role_autor, $umo, $mm);
    procesaRolDeUmo($role_realizador, $umo, $mm);
}

/** 
 * Parsea y persiste las personas contenidas en umo.autor y umo.realizador
 * Ej.: procesaRolDeUmo("Autor", "Fulanito Fulanítez; Mª Menganita Menganítez")
 */
function procesaRolDeUmo($role, $umo, $mm)
{
    $role_name = $role->getName();
    if (ROL_AUTOR == $role_name){
        $string_personas = trim($umo->getAutor());  
    } else if (ROL_REALIZADOR == $role_name){
        $string_personas = trim($umo->getRealizador());
    } else {
        pintaln("\nError - procesaRolDeUmo: rol con nombre no reconocido\n", "error");
    }

    if ($string_personas != null && $string_personas != ''){
        pintaln("\t\t" . $role_name . ":\t" . $string_personas,2);
        foreach (separaVariosNombres($string_personas) as $string_person){
            if (!$parseada = parseaUnaPersona($string_person)){
                continue;
            }
            pintaln("\t\t\t" . $parseada,"verde",3);
            $person = persistePerson($parseada);
            $mpr    = persisteMmPersonRole($mm, $person, $role);
            if (!$mpr){
                pintaln("\nError - procesaRolDeUmo: no se ha asignado correctamente mm_person\n", "error");
            }
        }

    } else {
        pintaln("\t\t" . $role_name . ": --- umo.original_id " . 
            $umo->getOriginalId() . " no tiene", "gris",2);
    }
}




function separaVariosNombres($linea)
{
    if ("" == $linea){
        return false;
    }

    $linea = UnedDesbrozatorHardcoded::removeUnwantedPerson($linea);

    if ( UnedDesbrozatorHardcoded::splitPeople($linea)){

        return UnedDesbrozatorHardcoded::splitPeople($linea);
    }

    // Retira un punto final pero sigue procesando.
    if ('.' == substr($linea, -1)){
        $linea = substr($linea, 0, -1);
    }

    if (strpos($linea, ';')){

        return array_filter(explode(";", $linea));
    }

    if (strpos($linea, ',') && wordCountutf8($linea) > 3){

        return array_filter(explode(",", $linea));
    }

    if (strpos($linea, '.')){

        if (wordCountutf8($linea) > 3){
            
            // deja pasar letra inicial + '.' y sustituye palabra + '.' por palabra + ';'
            $linea = preg_replace('/(\p{L}{2,})(\.)/u', '$1;', $linea);

            return array_filter(explode(";", $linea));  
        }
    }

    if (strpos($linea, ' y ')){
        return array_filter(preg_split('/ y /', $linea));
    }

    if (strpos($linea, ' / ')){
        return array_filter(preg_split('/ \/ /', $linea));
    }

    return array_filter(array(trim($linea)));       
}

function parseaUnaPersona($persona)
{
    if ("" == $persona){

        return false;
    }

    $persona = trim($persona);
    // Elimina los puntos finales de palabras con más de 1 letra.
    $persona = preg_replace('/(\p{L}{2,})(\.)/u', '$1', $persona);

    if (1 == str_word_count($persona, 0)){

        return trim($persona);
    }
    $persona = (corrigeTyposPersonas($persona));

    $parser = new HumanNameParser_Parser($persona);
    // TO DO persona. o apellido. que no sea una inicial 
    $nombre_apellidos = trim(implode(" ", array_filter($parser->getArray('int'))));

    return $nombre_apellidos;
}

function corrigeTyposPersonas($persona)
{
    foreach (UnedDesbrozatorHardcoded::$typos_people as $typo => $corregido){
        if ($persona == $typo){

            return $corregido;
        }
    }

    return $persona;
}

function wordCountutf8($string) {
    return preg_match_all("/\p{L}[\p{L}\p{Mn}\p{Pd}'\x{2019}]*/u",$string,$matches,PREG_PATTERN_ORDER);
}

/**
 * persistePerson persiste una persona
 */
function persistePerson($nombre)
{
    $c = new Criteria();
    $c->add(PersonPeer::NAME, $nombre);
    if ($person = PersonPeer::doSelectOne($c)){
        pintaln("\t\t\tPersona " . $nombre . " ya existe, recuperada de la BD", "amarillo", 3);

        return $person;
    }

    $person = new Person();
    $person->setName($nombre);
    $person->setWeb(WEB_PERSON);
    // Necesario para que cree entradas en person_i18n
    $person->setCulture('es');
    $person->setHonorific(' '); 
    
    $person->save();
    pintaln("\t\t\tPersona " . $nombre . " persistida", "verde", 3);

    return $person;
}

function persisteRole($nombre)
{
    $c = new Criteria();
    $c->add(RoleI18nPeer::NAME, $nombre);
    
    if ($role = RolePeer::doSelectWithI18n($c, 'es')){
        pintaln("Rol " . $nombre . " ya existe, recuperado de la BD","amarillo");

        return $role[0];
    }
    
    // Crea un rol nuevo, código en editar/modules/roles/actions
    $role = new Role();
    $role->setDisplay (false); // TO DO: ¿Autor podría ser true?
    $role->setXml($nombre);
    $role->setCod($nombre);
    $role->setCulture('es');
    $role->setName($nombre);

    $role->save();
    pintaln("Rol " . $nombre . " persistido");

    return $role;
}

function persisteMmPersonRole($mm, $person, $role)
{
    $c = new Criteria();
    $c->add(MmPersonPeer::MM_ID, $mm->getId());
    $c->add(MmPersonPeer::PERSON_ID, $person->getId());
    $c->add(MmPersonPeer::ROLE_ID, $role->getId());
    
    if ($mpr = MmPersonPeer::doSelectOne($c)){
        pintaln("MmPersonRole con Ids: " . $mm->getId() . ", " . $person->getId() . ", " .
            $role->getId() . "\tya existe - recuperándolo de la BD", "amarillo", 3);

        return ($mpr);
    }

    $mpr = new MmPerson();
    $mpr->setMmId($mm->getId());
    $mpr->setPersonId($person->getId());
    $mpr->setRoleId($role->getId());
    $mpr->save();
    pintaln("\t\t\tmm: " . $mm->getId() ."\tRole: " . $role->getId() . "\tPerson: " . 
        $person->getId() . "\tCreado correctamente", "verde", 3);

    return $mpr;
}

/**
 * Importa uned_media_old.thumbs, crea pics y las asigna a mm
 */
function importaThumbsCreaPicMms($umo, $mm)
{
    pintaln("\tImportando thumbs-pics de umo.original_id: " . $umo->getOriginalId());  

    if ($thumbs = $umo->getThumbs()){       
        $thumb_paths = explode(",", $thumbs);
        
    } else if (1 == $mm->getAudio()){
        $thumb_paths = array('/images/sound_bn.png');
        pintaln("\t\tasignada pic audio a umo.original_id: ". $umo->getOriginalId(),2);

    } else {
        pintaln("\t\tumo.original_id: " . $umo->getOriginalId() . 
            " no tiene thumbs - no se importan pics", "gris",2);

        return false;
    }

    foreach ($thumb_paths as $thumb_path){
        // Ojo, monto y enlazo resources dentro de mi /web/
        $thumb_path = trim($thumb_path);
        $pic        = persistePic($thumb_path);
        $pic_mm     = persistePicMm($pic, $mm);
    }
}

function persistePic($url){
    $c = new Criteria();
    $c->add(PicPeer::URL, $url);
    if ($pic = PicPeer::doSelectOne($c)){
        pintaln("\t\tPic " . $url . " ya existe, recuperada de la BD", "amarillo" , 3);
    } else {
        $pic = new Pic();
        $pic->setUrl($url);
        $pic->save();
        pintaln("\t\tPic " . $url . " persistida", "verde", 3);
    }

    return $pic;
}

function persistePicMm($pic, $mm){
    $c = new Criteria();
    $c->add(PicMmPeer::PIC_ID, $pic->getId());
    $c->add(PicMmPeer::OTHER_ID, $mm->getId());
    if ($pic_mm = PicMmPeer::doSelectOne($c)){
        pintaln("\t\tpic_mm " . $pic->getId() . " , " . $mm->getId() .
         " ya existe, recuperada de la BD", "amarillo" , 3);

        return $pic_mm;
    }
    $pic_mm = new PicMm();
    $pic_mm->setPicId($pic->getId());
    $pic_mm->setOtherId($mm->getId());
    $pic_mm->save();
    pintaln("\t\tpic_mm " . $pic->getId() . " , " . $mm->getId() .
         " persistido", "verde" , 3);
    return $pic_mm;
}

/**
 * Crea o recupera perfil, streamserver, format, mimetype, files según los presets
 */
function importaPresets($umo)
{
    pintaln("\tImportando presets-files de umo.original_id: " . $umo->getOriginalId());
    $presets   = array('original', 'alta', 'media', 'baja');
    $categoria = $umo->getCategorias(); // Audio / Video
    $language  = getLanguage("ES");

    foreach ($presets as $preset){
        $getter = 'getPreset' . ucfirst($preset);
        if (!$umo->$getter()){
            pintaln("\n\t\tPreset " . $preset . " umo.original_id: " . $umo->getOriginalId() . 
                " no tiene\n", "gris" , 3);
        
        } else { // Este perfil contiene información de 1 file
            $path          = (PUNTO_MONTAJE_FILES) . trim($umo->$getter());
            $ext           = extraeExtension($path);
            $url           = trim($umo->$getter());
            $nombre_perfil = nombrePerfil($preset, $categoria, $ext);
            $perfil        = persistePerfil($nombre_perfil, $preset, $categoria, $ext);
            $format        = persisteFormat($ext);
            $mimetype      = persisteMimetype($ext);

            $c = new Criteria();
            $c->add(FilePeer::URL, $url);
            if ($file = FilePeer::doSelectOne($c)){
                pintaln ("\t\t\tFile ". $file->getId() . "\t" . $preset .
                    " " . $path . " ya existe, recuperado de la BD", "amarillo",2);
            } else {
                $file = new File();
                $file->setMmId($umo->getMmId());
                $file->setLanguage($language);
                $file->setUrl($url);
                $file->setFile($path);
                $file->setPerfilId($perfil->getId());
                $file->setFormatId($format->getId());
                $file->setAudio($perfil->getAudio());
                $file->setMimeTypeId($mimetype->getId());
                
                $description = '';
                $file->setCulture('es');
                $file->setDescription($description);
                
                $file->save();

                pintaln ("\t\t\tFile ". $file->getId() . "\t" . $preset . " " . $path . 
                    " persistido", "verde",2);    
            }
        } // umo.preset_loquesea existe
    } //foreach presets
}

function nombrePerfil($preset, $categoria, $ext)
{
    return 'Importado_' . substr(ucfirst($preset), 0, 4) . "_"
            . substr($categoria, 0, 3) . '_' . $ext;    
}

function getLanguage ($cod = "ES")
{
    $c = new Criteria();
    $c->add(LanguagePeer::COD, $cod);
    return LanguagePeer::doSelectOne($c);
}

function extraeExtension($file){
    return substr(strrchr($file, '.'), 1);
}

function persisteMimetype($ext)
{
    $c = new Criteria();
    $c->add(MimeTypePeer::NAME, $ext);
    if ($mimetype = MimeTypePeer::doSelectOne($c)){
        pintaln("\t\tMimetype: " . $ext . " ya existe, recuperado de la BD", "amarillo" , 4);
    } else {
        $mimetype = new MimeType();
        $mimetype->setName($ext);
        $mimetype->setType(UnedDesbrozatorHardcoded::getMimeType($ext));
        $mimetype->save();
        pintaln("\t\tMimetype: " . $ext . "\t" . $mimetype->getType() . " persistido", "verde", 3);
    }

    return $mimetype;
}

function persisteFormat($ext)
{

    $c = new Criteria();
    $c->add(FormatPeer::NAME, $ext);
    if ($format = FormatPeer::doSelectOne($c)){
        pintaln("\t\tFormat:   " . $ext . " ya existe, recuperado de la BD", "amarillo" , 4);
    } else {
        $format = new Format();
        $format->setName($ext);
        $format->save();
        pintaln("\tFormat:   " . $ext . " persistido", "verde", 4);
    }

    return $format;
}

function persistePerfil($nombre, $preset, $categoria, $ext, $display = false)
{
    $c = new Criteria();
    $c->add(PerfilPeer::NAME, $nombre);
    if ($perfil = PerfilPeer::doSelectWithI18n($c, 'es')){
        pintaln("\t\tPerfil:   " .$nombre . " ya existe, recuperado de la BD", "amarillo", 4);

        return $perfil[0];

    } else{
        $streamserver = persisteStreamServerParaImportados();

        $audio  = ('Audios' == $categoria)? 1 : 0;
        $link   = ('Audios' == $categoria)? 'Audio' : 'Vídeo';
        $perfil = new Perfil();
        $perfil->setName($nombre);
        $perfil->setDisplay($display);
        $perfil->setWizard(0);
        $perfil->setMaster(1);
        $perfil->setFormat($ext);
        $perfil->setMimetype(UnedDesbrozatorHardcoded::getMimeType($ext));
        $perfil->setExtension($ext);  
        $perfil->setAudio($audio);
        $perfil->setApp('uned_desbrozator');
        $perfil->setStreamserverId($streamserver->getId());


        $descripcion = 'Importado preset_' . strtolower($preset) . ' ' . $categoria . ' ' . $ext ;
        
        $perfil->setCulture('es');
        $perfil->setDescription($descripcion);
        $perfil->setLink($link);

        $perfil->save();
        pintaln("\tNuevo perfil:   " . $nombre . "\tpersistido", "verde", 2);

        return $perfil;
    }
}

function persisteStreamServerParaImportados(){
    $ip = '127.0.0.1';
    $name = 'LocalhostUNED';
    $description = 'Archivos importados de UNED y montados por nfs';
    $dir_out = '/var/www/pumukit/web/resources';
    $url_out = '/resources';

    $c = new Criteria();
    $c->add(StreamserverTypePeer::NAME, "Download");
    $streamserver_type = StreamserverTypePeer::doSelectOne($c);

    $c = new Criteria();
    $c->add(StreamserverPeer::STREAMSERVER_TYPE_ID, $streamserver_type->getId());
    $c->add(StreamserverPeer::IP, $ip);
    $c->add(StreamserverPeer::NAME, $name);
    $c->add(StreamserverPeer::DESCRIPTION, $description);
    $c->add(StreamserverPeer::DIR_OUT, $dir_out);
    $c->add(StreamserverPeer::URL_OUT, $url_out);

    if ($streamserver = StreamServerPeer::doSelectOne($c)){
        pintaln("Streamserver " . $streamserver->getName() . " ya existe, recuperado de la BD", "amarillo", 3);

        return $streamserver;
    }

    $streamserver = new Streamserver();
    $streamserver->setStreamserverTypeId($streamserver_type->getId());
    $streamserver->setIp($ip);
    $streamserver->setName($name);
    $streamserver->setDescription($description);
    $streamserver->setDirOut($dir_out);
    $streamserver->setUrlOut($url_out);
    $streamserver->save();

    pintaln("Streamserver " . $streamserver->getName() . " persistido", "verde", 4);

    return $streamserver;
}

/**
 * Lee campo temáticas, parsea términos, busca y asigna la categoría de cada término
 * Busca los (códigos) unesco que correspondan a cada temática (si los hay)
 * Busca y asigna la categoría de cada unesco.
 */
function importaTematicasAsignaUnescos($umo){
    global $cat_raiz_tematicas, $array_cod_cat_unesco;
    pintaln("\tImportando temáticas y asignando unescos a umo.original_id: " . $umo->getOriginalId());

    $tematicas = parseaTematicas( $umo->getTematicas());
    if (0 == count($tematicas)){
        pintaln("\t\tumo.original_id: " . $umo->getOriginalId() . 
            " no tiene temáticas", "gris",2);

        return false;
    }

    foreach ($tematicas as $tematica){ // existen temáticas en umo
        if (!$cat_tematica = CategoryPeer::retrieveByName($tematica, $cat_raiz_tematicas)){
            $cod_prefix = 'T_' . ($cat_raiz_tematicas->getNumberOfChildren() + 1);
            pintaln("\n--------------------------------------------", "cyan");
            pintaln("¡Ojo! creando nueva temática: " . $cod_prefix . $tematica, "cyan");
            pintaln("--------------------------------------------\n", "cyan");
            $cat_tematica = createCategory($tematica, $cat_raiz_tematicas, $cod_prefix);
            // throw new Exception("No se encuentra la temática " . $tematica . " en la BD");
        
        } // En la BD existe categoría uned para esta temática

        $cat_tematica->addMmIdAndUpdateCategoryTree($umo->getMmId());
        pintaln("\t\tAsignada temática UNED: " . $tematica , "blanco", 2);

        $cod_unescos = obtieneUnescosDeTematica($tematica);
        foreach ($cod_unescos as $cod_unesco){ // Existen códigos de unesco para esta temática
            
            if (!$cat_unesco = $array_cod_cat_unesco[$cod_unesco]){
                throw new Exception("A la temática UNED: " . $tematica . 
                    " le corresponde el código UNESCO: " . $cod_unesco  . 
                    " pero no encuentro la categoría en la BD");
            
            } else { // En la BD existe una categoría unesco para este código
                $cat_unesco->addMmIdAndUpdateCategoryTree($umo->getMmId());
                pintaln("\t\t\tAsignada la categoría UNESCO: " . $cod_unesco . 
                    " " . $cat_unesco->getName());
            }
        }
    }
}

/**
 * parseaTematicas recibe 1 línea y devuelve array con temáticas corregidas
 */
function parseaTematicas($linea_tematicas)
{
    $tematicas_provisionales = superExplode(',', $linea_tematicas);
    $array_tematicas = array();
    foreach ($tematicas_provisionales as $tp){
        $array_tematicas[] = mb_strtoupper(corrigeTyposTematicas($tp),'UTF-8');
    }
    ksort($array_tematicas, SORT_LOCALE_STRING );

    return $array_tematicas;
}

/**
 * superExplode devuelve array con las entradas no vacías de $string trimeadas
 */
function superExplode($delimitador, $string)
{
    if (1 === strlen($delimitador)){
        
        return array_filter(array_map('trim', explode($delimitador, $string)));    
    } else {
        $delimitador = '/'.$delimitador.'/';
        return array_filter(array_map('trim', preg_split($delimitador, $string)));
    }   
    
}

function corrigeTyposTematicas($tematica)
{
    foreach (UnedDesbrozatorHardcoded::$typos_tematicas as $typo => $corregido){
        if ($tematica == $typo){

            return $corregido;
        }
    }

    return $tematica;
}

function persisteCategoria($nombre, $padre, $cod_prefix = '')
{
    $c = new Criteria();
    $c->add(CategoryI18nPeer::NAME, $nombre);
    $c->add(CategoryPeer::COD, $cod_prefix);
    $c->addJoin(CategoryI18nPeer::ID, CategoryPeer::ID);
    $category = CategoryPeer::doSelectWithI18n($c, 'es');
    if (!$category){
        $category = createCategory($nombre, $padre, $cod_prefix);
        
        return $category;       

    } else if (count($category) > 1) {
        throw new Exception ("Hay más de una category con el título \"".$nombre."\"");
    }

    pintaln ("existe la categoría " . $nombre, "amarillo", 4);
    return $category[0];
}

/**
 * createCategory crea 1 categoría; ojo, el objeto devuelto NO está actualizado con la BD.
 * @param string $name
 * @param $parent - objeto Category
 * @param string $cod_prefix - valor para category.cod
 */
function createCategory($name, $parent, $cod_prefix = '')
{

    if (!$parent) {
        throw new Exception ("Error: no se encuentra categoría padre");
    }

    $category = new Category(); 
    $category->insertAsLastChildOf($parent);
    $category->setMetacategory(false);
    $category->setDisplay(true);
    $category->setRequired(false);
    // $category->setCod($cod_prefix . $name);
    $category->setCod($cod_prefix);
     
    $category->setCulture('es');
    $category->setName($name);

    $category->save();
    pintaln("Creada categoría " . $cod_prefix . $name, "verde", 1);

    return $category;
}

/**
 * creaSubRaiz - ----- devuelve, dentro de la raíz absoluta de category, 
 * una raíz para un nuevo tipo de arbol de categorías (nodoseries, unesco...)
 */
function creaSubRaiz($nombre)
{
    $cat_raiz = CategoryPeer::doSelectRoot();
    if (!$subcat = CategoryPeer::retrieveByCode($nombre)){
        throw new Exception("No existe la raíz del arbol de categorías de " . $nombre);
    } else{

        return $subcat;
    }
}

/**
 * Devuelve un array para acceso rápido: código unesco => objeto categoría unesco.
 */
function inicializaCodCategoriaUnesco()
{
    $raiz_cat_unesco = creaSubRaiz(RAIZ_CATEGORIAS_UNESCO);   
    $cat_unescos     = $raiz_cat_unesco->getChildren();

    $array_cod_cat_unesco = array();
    foreach ($cat_unescos as $cu){
        $array_cod_cat_unesco [$cu->getCod()] = $cu;
    }

    return $array_cod_cat_unesco;
}

/**
 * @param string $tematica
 * @return array $array_cod_unesco strings con los category.cod de unesco
 */
function obtieneUnescosDeTematica($tematica)
{
    global $array_cod_cat_unesco;
    if (!$array_cod_unesco = UnedDesbrozatorHardcoded::tematicaUnesco($tematica)){
        pintaln("\t\t\tTemática: ". $tematica . " no tiene unesco", "amarillo", 3);

        return array();
    } else {
        
        return $array_cod_unesco;
    }
}

function importaEnlaces($umo)
{
    pintaln("\tImportando enlaces de umo.original_id: " . $umo->getOriginalId());
    $array_enlaces = parseaEnlaces($umo->getEnlaces());
    if (0 == count($array_enlaces)){
        pintaln("\t\tumo.original_id: " . $umo->getOriginalId() . 
            " no tiene enlaces", "gris",2);

        return false;
    } else{
        foreach ($array_enlaces as $array_enlace){       

            foreach ($array_enlace as $descripcion => $url){
                // pintaln("\t\tPersistiendo enlace mm_id: ". $umo->getMmId() .
                // " " . $descripcion . $url);
                persisteLink($umo->getMmId(), $descripcion, $url);
            }
        }
    }
}

/**
 * @param $string_enlaces campo importado de uned_media_old
 * @return $array_enlaces array de strings con los enlaces procesados.
 */
function parseaEnlaces($string_enlaces)
{
    $enlaces = superExplode(' , ', $string_enlaces);
    $array_enlaces = array();
    foreach ($enlaces as $enlace) {
        // 0 = todo; 1 = descripcion; 2 = enlace (contenido del último paréntesis) 
        preg_match('/(.*) \((.*)\)$/', $enlace, $result);
        
        if (count($result) != 3){
            var_dump($result);
            throw new Exception ("Error en la expresión regular");
        }

        $descripcion     = trim(stripslashes($result[1]));
        $url             = httpiza($result[2]);
        $array_enlaces[] = array ($descripcion => $url);
    }

    return $array_enlaces;
}

function httpiza($url)
{
    if (strpos($url, '://') === false){
        $url = 'http://' . $url;
    }

    return $url;    
}

function persisteLink($mm_id, $descripcion, $url)
{   
    $c = new Criteria();
    $c->add(LinkPeer::MM_ID, $mm_id);
    $c->add(LinkPeer::URL, $url);
    if ($link = LinkPeer::doSelectOne($c)){
        pintaln("\t\t\tLink mm_id: " . $mm_id . "\turl:" . $url . " ya existe, recuperado de la BD", "amarillo", 3);

    } else {
        $link = new Link();
        $link->setMmId($mm_id);
        $link->setUrl($url);

        $link->setCulture('es');
        $link->setName($descripcion);

        $link->save();
        pintaln("\t\t\tLink mm_id: " . $mm_id . "\t" . $url . "\t" . $descripcion . " persistido", "verde", 2);
    }
    
    return $link;
}

function importaDocumentosAdjuntos($umo)
{
    pintaln("\tImportando documentos_adjuntos como materials visibles del umo.original_id: " . $umo->getOriginalId());
    
    $array_adjuntos = parseaAdjuntos($umo->getDocumentosAdjuntos());
    if (0 == count($array_adjuntos)){
        pintaln("\t\tumo.original_id: " . $umo->getOriginalId() . 
            " no tiene documentos adjuntos", "gris",2);

        return false;
    }
    foreach ($array_adjuntos as $url => $descripcion){
        $ext = extraeExtension($url);

        if (UnedDesbrozatorHardcoded::checkExtensionErronea($url)){
            pintaln("El umo.original_id: " . $umo->getOriginalId() . " tiene un documento_adjunto html estropeado:","rojo");
            pintaln("\t". $url . "\t- No se importará","rojo");
        } else { 
            persisteMaterial($umo->getMmId(), $descripcion, $url);
        }
    }
}

/**
 * @param $string_adjuntos campo documentos_adjuntos de umo
 * @return $array_adjuntos array de strings con los adjuntos procesados
 */
function parseaAdjuntos($string_adjuntos)
{
    $adjuntos = superExplode(' , ', $string_adjuntos);
    $array_adjuntos = array();
    foreach ($adjuntos as $adjunto) {
        // ejemplo de adjunto: "/resources/pdf/8/6/1362486447268.pdf (5385)"
        // $result: 0 = todo; 1 = path; 2 = número entre paréntesis
        preg_match('/(.*) (\(.*\))$/', $adjunto, $result);       
        
        if (count($result) != 3){
            var_dump($result);
            throw new Exception ("Error en la expresión regular procesando el adjunto " . $string_adjuntos);
        }

        $path     = trim($result[1]);
        $numero   = $result[2]; // incluye paréntesis
        $basename = basename($path);
        $fullname = $basename . " " . $numero;

        $array_adjuntos[$path] = $fullname;
    }
    return $array_adjuntos;
}

function persisteMaterial($mm_id, $descripcion, $url, $display = 1)
{   
    $c = new Criteria();
    $c->add(MaterialPeer::MM_ID, $mm_id);
    $c->add(MaterialPeer::URL, $url);
    if ($material = MaterialPeer::doSelectOne($c)){
        pintaln("\t\tMaterial mm_id: " . $mm_id . "\turl:" . $url . " ya existe, recuperado de la BD", "amarillo", 4);        
    
    } else {
        $material = new Material();
        $material->setMmId($mm_id);
        $material->setUrl($url);
        $material->setDisplay($display);

        $mt = persisteMatType(extraeExtension($url));
        $material->setMatTypeId($mt->getId());

        $material->setCulture('es');
        $material->setName($descripcion);
        $material->save();
        pintaln("\t\tMaterial mm_id: " . $mm_id . "\t" . $url . "\t" .
            $descripcion . " persistido", "verde", 4);
    }
    
    return $material;
}

function persisteMatType($ext)
{
    $ext = strtolower($ext);
    $c = new Criteria();
    $c->add(MatTypePeer::TYPE, $ext);
    if ($mt = MatTypePeer::doSelectWithI18n($c,'es')){
        pintaln("\t\tMatType " . $ext . " ya existe, recuperado de la BD", "amarillo", 5);
        
        return $mt[0];

    } else {
        $mt = new MatType();
        $mt->setType($ext);
        $mt->setDefaultSel(0);
        $mt->setMimeType(UnedDesbrozatorHardcoded::getMimeType($ext));

        $mt->setCulture('es');
        $mt->setName(UnedDesbrozatorHardcoded::descripcionMimetype($ext));

        $mt->save();
        pintaln("\t\tMatType " . $ext . "\tpersistido", "blanco", 2);
    }

    return $mt;
}

function importaSubtitulos($umo)
{
    pintaln("\tImportando subtítulos como materials no visibles del umo.original_id: " . $umo->getOriginalId());

    if (!$subtitulos = $umo->getSubtitulos()){
        pintaln("\t\tumo.original_id: " . $umo->getOriginalId() . 
            " no tiene subtítulos", "gris",2);

        return false;
    } else{
        $url = str_replace('/deliverty/demo', '', $subtitulos);
        $mm_id = $umo->getMmId();

        if (UnedDesbrozatorHardcoded::checkExtensionErronea($url, "extension_subtitulos_erronea")){
            // $descripcion = "Archivo pdf";
            $descripcion = basename($url);
            $display     = 1;
            pintaln("\tArchivo " . $url . " incorrectamente catalogado como subtítulos","rojo");
            pintaln("\tSe persistirá como material convencional - " . $descripcion, "rojo");

        } else {
            $descripcion = "Subtítulos";
            $display     = 0;
        }
        persisteMaterial($mm_id, $descripcion, $url, $display);
    }
}

// para probar el script, primero intento borrar todo.
function borraPosterioresAFebrero()
{
    $febrero = '2013-02-28 23:59:59';
    $c = new Criteria();
    $c->add(UnedMediaOldPeer::FECHA_DE_CREACION, $febrero, Criteria::GREATER_THAN);
    $umos = UnedMediaOldPeer::doSelect($c);
    
    foreach ($umos as $umo){
        pintaln("Borrando umo id: " . sprintf('%5d',$umo->getId()) .
            " - original_id: " . sprintf('%5d',$umo->getOriginalId()) .
            " - mm_id: " . sprintf('%5d',$umo->getMmId()) );
        // el mm es el que borra un tocho de objetos en cascada
        if ($mm = $umo->getMm()) $mm->delete(); 
        $umo->delete();
    }
}

function esteUmoTieneMultimedia($umo){
    return (bool)  ($umo->getPresetOriginal() != '' ||
                    $umo->getPresetAlta() != '' ||
                    $umo->getPresetMedia() != '' || 
                    $umo->getPresetBaja() != '' );
}

function creaDefaultBroadcastSiEstaVacio()
{
    $c = new Criteria();
    $c->add(BroadcastPeer::DEFAULT_SEL, 1);
    if (!$b = BroadcastPeer::doSelectOne($c)){
        pintaln("Ojo, no existe Broadcast por defecto, creando uno", "rojo");
        $b = new Broadcast();
        $b->setName("pub");
        $b->setBroadcastTypeId(1);
        $b->setDefaultSel(1);
        $b->setCulture('es');
        $b->setDescription('Público');
        $b->save();    
    }

    
}

//-----------------------------------------------------------------------------
/** 
 * Para probar las cosas antes de ejecutar ninguna otra función del script,
 * aprovechando la BD poblada.
 */
function testNuevaFuncion()
{
    return; // Mantener descomentada esta línea para el funcionamiento normal del script 
    echo "\nProbar nuevas funciones\n";

    $array_tiempos = array();
    cronometraEvento("Inicio del script", $array_tiempos);

    // $umo1 = UnedMediaOldPeer::retrieveByPk(487);//
    // $mm1  = $umo1->getMm();
    // $umo2 = UnedMediaOldPeer::retrieveByPk(489);//
    // $mm2  = $umo2->getMm();
    // $umo3 = UnedMediaOldPeer::retrieveByPk(43); //sin temáticas
    // $mm3  = $umo2->getMm();

    // borraPosterioresAFebrero();

    cronometraEvento("Final de testNuevaFuncion", $array_tiempos);
    pintaTiempos($array_tiempos);
    exit;
}