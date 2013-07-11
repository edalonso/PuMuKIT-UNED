 <?php
/**
 * uned-desbrozator.php
 * Procesa el campo "descripción" de un extracto mysql de la BD de UNED.
 * Crea una lista de categorías(*) a partir de los segmentos comunes.
 *
 * Ejemplo:
 * "El Mundo del Derecho: Historia del Derecho Español - Bla bla bla"
 * debería ser desbrozado como:
 *
 * EL MUNDO DEL DERECHO
 *  └> HISTORIA DEL DERECHO ESPAÑOL
 *       └> Bla bla bla bla
 *
 * (*) También conocidas como "NodoSeries".
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

if(count($argv) > 2  || (count($argv) == 2 && strpos('01234',$argv[1]) === false)){
    echo "Usage: php uned-desbrozator [optional debug level, ranging from 0 to 4]\n";
    echo "Default debug value is 1: php uned-desbrozator 1";
    exit;
}

$parametro_debug  = (isset($argv[1])) ? $argv[1]: 1;
$columnasTerminal = exec('tput cols') - 10;
//ojo, en el servidor de desarrollo voy a borrar tablas a pelo.
$dev_host         = (bool) ('coruxo' == php_uname('n') || 'D-pumukit'== php_uname('n')); // php>= 5.3 gethostname()

// Salida por terminal
define ('MAX_TERMINAL', $columnasTerminal);
define ('NIVEL_DEBUG', $parametro_debug); 

// Importante: delimitadores habituales que separarán las categorías.
define ('DELIMITADORES', '.;:(-');
define ('NO_COMPARAR',' y.,;:()-');         // No tiene en cuenta diferencias en delimitadores, espaciado, etc. al comparar descripciones.
define ('MAX_PADRE', 190);                  // Si el nombre del padre es más largo, recorta y busca el último delimitador.

// Nombres arbitrarios
define ('SIN_CATEGORIA', '##Varios sin nodoserie--');
define ('NODOSERIE_VARIOS', ' - Varios sin subcategoria' );
define ('REVISAR_CATEGORIA',"##Revisar--");
define ('CSV_FILENAME',"arbol.csv");
define ('SERIE_IMPORTADOS', 'Importados genéricos UNED');
define ('RAIZ_NODOSERIES_UNED', 'Nodoseries UNED');
define ('RAIZ_CATEGORIAS_TEMATICAS', 'Tematicas UNED');
define ('RAIZ_CATEGORIAS_UNESCO', 'UNESCO');
define ('WEB_PERSON', "http://www.canaluned.com"); // Establezco esta web para distinguir person importadas
define ('ROL_AUTOR', 'Autor importado');
define ('ROL_REALIZADOR', 'Realizador importado');
define ('HOST_FICHEROS', "www.canaluned.com");
define ('IMPORT_CSV_FOLDER', realpath(dirname(__file__)).'/import_csv_resources');
define ('PUNTO_MONTAJE_FILES', "/mnt/nfsuned"); // path para file.file

// Constantes internas
define ('USA_SUBCONJUNTO', false);  // Limita los resultados tras la búsqueda.
define ('INI_SUBCONJUNTO', 1000);   // Casos de test 1999 - 2785
define ('LEN_SUBCONJUNTO',  500);   // Casos de test 100 - 7
define ('NUM_PASADAS', 2);          // Niveles de categorías y subcategorías (nodoseries)
define ('I18N', false);             // Guarda 'es' o app_lang_array para los nombres
define ('BD_BORRABLE', $dev_host);
define ('ACTUALIZA_INDICES_LUCENE', false); // Actualiza el buscador cada vez que se graba un mm

// desactiva output buffering para que muestre por pantalla los echos en tiempo real
ob_implicit_flush(true);
ob_end_flush();

// FIXME  - TO DO - da errores en la tercera pasada, en emancipaPadre se puede hacer que no separe
// los casos conflictivos en los que no encuentra delimitador.

// Prueba nuevas funciones antes de ejecutar el script (y habitualmlente exit)
testNuevaFuncion();

/****************************************************************************/

pintaln("Nota: ¡¡¡ es normal que salten php warnings de cookie o sesión !!!","amarillo");
$array_tiempos = array();
cronometraEvento("Inicio del script", $array_tiempos);

compruebaImportaCsv(true); //fuerza borrado e importación uned_media_old 
cronometraEvento("Tabla uned_media_old poblada", $array_tiempos);

$filas = leeUmosOrdenados();

if (USA_SUBCONJUNTO) {
    $filas  = array_slice($filas, INI_SUBCONJUNTO, LEN_SUBCONJUNTO);
}
cronometraEvento("Leídos datos de uned_media_old", $array_tiempos);

$filas = corrigeTyposIniciales($filas);
cronometraEvento("Corregidos typos iniciales", $array_tiempos);

$arbol = creaArbolNodoseries ($filas);
cronometraEvento("Creado Árbol nodoseries", $array_tiempos);

if (NIVEL_DEBUG > 1){
  pintaArbolEntero($arbol);
  cronometraEvento("Pintado Árbol de nodoseries", $array_tiempos);
} 

// pintaDosRamas($arbol);
// guardaCsvConTodo($arbol);

$serie = serieUnicaImportados();

(BD_BORRABLE) ? borraTablasAPelo(array('mm', 'mm_i18n','pic', 'pic_mm', 
        'category_mm', 'mm_person', 'file', 'file_i18n')) 
    : borraMmsImportadosDesdeSerie($serie);
cronometraEvento("Serie inicializada y mms borrados de la BD", $array_tiempos);

// Ojo, no borro category porque importo árboles unesco etc.
(BD_BORRABLE) ? borraTablasAPelo(array ('category', 'category_i18n', 'category_mm')) :
borraCategoriasImportadas(array(RAIZ_NODOSERIES_UNED, RAIZ_CATEGORIAS_TEMATICAS));

// Para crear categorias unesco.
exec("php ". dirname(__file__) . '/../categories/create_unesco_from_ground.php');
// Para crear categorias Destacados Radio / TV.
exec("php ". dirname(__file__) . '/../timeframes/creacategoriasdestacadosradiotv.php');
cronometraEvento('Creadas categorías UNESCO y DESTACADOS RADIO/TV', $array_tiempos);
pintaln("Puedes preparar un cafecito, esto durará unos minutos...","amarillo");
pintaln("Para 10.000 entradas en la BD => entre 15 y 30","amarillo");
creaCategoriasConMm($arbol, $serie);
cronometraEvento("Árbol de categorías creado y mms importados", $array_tiempos);

creaNodoseriesVariosParaMmsSinSubcategoria();
cronometraEvento("Creadas subcategorías de segundo nivel para mms huerfanitos ", $array_tiempos);

// ---- útil para sacar csv personalizado con sólo las categorías y temáticas o unescos ----
// // El más completo: nodoseries y temáticas
// guardaCsvNodoseriesTematicasDesdeBD("tematicas");
// guardaCsvNodoseriesTematicasDesdeBD("unesco");

// // Otros ejemplos de uso:
// $tree_array = CategoryPeer::buildTreeArray();
// $nodo_raiz_nodoseries = encuentraNodoCategoriaEnTreeArray(RAIZ_NODOSERIES_UNED, $tree_array[0]);
// $nodo_raiz_tematicas = encuentraNodoCategoriaEnTreeArray(RAIZ_CATEGORIAS_TEMATICAS, $tree_array[0]);
// recorreTreeArray ($nodo_raiz_tematicas);
// guardaCsvDesdeBD ($nodo_raiz_nodoseries);
// --------------------------------------------------------------

$array_people = creaListaPersonas();
(BD_BORRABLE)? borraTablasAPelo(array('person', 'person_i18n')): borraPersonImportadas();
persisteListaPersonas($array_people);
cronometraEvento("Person creadas", $array_tiempos);

(BD_BORRABLE)? borraTablasAPelo('mm_person'): borraMmPersonImportados();
asignaListaMmListaPersonas();
cronometraEvento("MmPerson creados - Autores y realizadores de UNED asignados a los Mm.", $array_tiempos);

if (compruebaNumeroPersonasImportadas()) pintaln ("El número de personas importadas, parseadas y asignadas es correcto. ¡Cojonudo!");

(BD_BORRABLE)? borraTablasAPelo(array('pic','pic_mm')): borraPicsImportadas();
importaThumbsCreaPicMms();
cronometraEvento("Pics importadas y asignadas.", $array_tiempos);

(BD_BORRABLE)? borraTablasAPelo(array('perfil', 'perfil_i18n', 'format')): borraPerfilesImportados();
compruebaPresetsCreaPerfilesFormatMimetypes();
cronometraEvento("Presets comprobados y perfiles creados", $array_tiempos);

(BD_BORRABLE)? borraTablasAPelo(array('file','file_i18n')): borraFilesImportados();
importaPresetsCreaFiles();
cronometraEvento("Files creados y asignados a los perfiles", $array_tiempos);

// conservo las categorías nodoseries, no borro tablas.
borraCategoriasImportadas(RAIZ_CATEGORIAS_TEMATICAS);
creaArbolTematicas();
cronometraEvento("Árbol de temáticas creado y Mm asignados a las temáticas", $array_tiempos);

asignaUnescoATematicas();
cronometraEvento("Unesco asignados a los Mm según el mapeado temáticas uned - unesco", $array_tiempos);

(BD_BORRABLE) ? borraTablasAPelo(array('link', 'link_i18n')) : borraLinksImportados();
importaEnlaces();
cronometraEvento("Links creados y asignados a los Mm", $array_tiempos);

(BD_BORRABLE)? borraTablasAPelo(array('material', 'material_i18n', 
                                      'mat_type', 'mat_type_i18n')) : borraMaterialsImportados();
importaDocumentosAdjuntos();
cronometraEvento("Documentos adjuntos importados como materials", $array_tiempos);
importaSubtitulosComoMaterials();
cronometraEvento("Subtítulos importados como materials", $array_tiempos);

creaPicAudioAsignaMms('/images/sound_bn.png');
cronometraEvento("Creada pic de audio y asignada a los mms de audio sin pic", $array_tiempos);

// Importante: hay que elegir creaNuevasSeriesMueveMms() o creaSeriesParaNodoseriesFinales();
(BD_BORRABLE)? borraTablasAPelo(array('serial', 'serial_i18n')) : borraNuevasSeriesMueveMmsSerieUnica(); // Ojo: borra todas las series existentes
creaSeriesParaNodoseriesFinales();

arreglaMmAudio();

pintaln ("\n¡¡Final correcto!!","verde", 0);
pintaTiempos($array_tiempos);


if(1 == count($argv)){
    pintaln("Recuerda que puedes pasarle como parámetro el nivel de debug/verbose, [0..4]\n", "gris");
    pintaln("Por defecto se usa php uned-desbrozator.php 1","gris");
}
pintaln("El servidor de desarrollo puede tardar unos segundos en responder","amarillo");

exit;
/****************************************************************************/

function leeUmosOrdenados()
{
    $c = criteriaUmosWithMultimedia();
    $c->addAscendingOrderByColumn(UnedMediaOldPeer::DESCRIPCION);
    $umos = UnedMediaOldPeer::doSelect($c);

    if (count($umos) < 2){
        throw new Exception('No uned_media_old records');
    }

// Actualización iterativa - primero paso de objetos SF a los arrays antiguos
// Ojo, antes había una clave y un índice para cada valor. Con la clave parece llegar.
// -----------------------------
    $array_filas = array();
    foreach ($umos as $umo){
        $array_umo = array( 'Id'          => $umo->getId(),
                            'Descripcion' => $umo->getDescripcion(),
                            'Titulo'      => $umo->getTitulo(),
                            'Tematicas'   => $umo->getTematicas());
        $array_filas[] = $array_umo;
    }

    $umos = $array_filas;
// -----------------------------

    return $umos;
}

/**
 * Llama a lobaton en varias pasadas y crea un árbol de nodosoeries
 * en memoria (arrays)
 */
function creaArbolNodoseries(array $filas)
{
    $array1 = $filas;
    for ($i = 1; $i <= NUM_PASADAS; $i++){
        
        $ultimo_arbol = lobaton(${'array' . $i}, (1 == $i)); // Encuentra parientes desaparecidos

        pintaln("\n-------------------------------------------------------------------", "amarillo");
        pintaln("Pasada " . $i . " finalizada con éxito, en " .  count(${'array' . $i}) . " objetos encontré " . count($ultimo_arbol) . " categorías.");
        pintaln("-------------------------------------------------------------------\n", "amarillo");
        
        $sgte = $i + 1;
        ${'array' . $sgte} = array();
        ${'array' . $sgte} = arbol2array($ultimo_arbol);
    }
    pintaln("\n\n¡¡Procesado inicial realizado con éxito!!\n\n");

    $arbol_raiz = array();
    $arbol_raiz ['Descripcion'] = 'UNED';
    $arbol_raiz ['Children'] = $ultimo_arbol;

    return $arbol_raiz;
}


/**
 * lobaton - Encuentra 1 padre para cada elemento, comparando cada fila con
 * el último progenitor encontrado y la coincidencia con la siguiente fila.
 *
 **/
function lobaton(array $filas, $primera_pasada = true)
{
    $arbol = array();
    $coincidenciasAnterior = null;
    $hijos = 0;
    // Añade una fila extra para que la última tenga con quién compararse
    $filas[]=array('Descripcion'=>" ");

    for ($i = 0; $i < count($filas) -1 ; $i++){

        $filaActual  = $filas[$i];
        $descrActual = $filas[$i]['Descripcion'];
        $descrSgte   = $filas[$i+1]['Descripcion'];

        $coincidenciasSgte = superTrim(longestCommonSubstring($descrActual, $descrSgte));

        $id_fila_dentro_lista_ordenada = (USA_SUBCONJUNTO) ? INI_SUBCONJUNTO + $i : $i;
        pinta( $id_fila_dentro_lista_ordenada, "blanco",3);
        if (NIVEL_DEBUG < 3 &&  $i % 100 == 0) pintaln("Procesado línea ".$id_fila_dentro_lista_ordenada, "blanco");

        //********************** CHICHA DE LA FUNCIÓN: comparar filas y extraer padres ***************
        if (null == $coincidenciasAnterior && false == $coincidenciasSgte){
            // huérfano
            $padreActual = ($primera_pasada) ? SIN_CATEGORIA : REVISAR_CATEGORIA ;
            superEcho($padreActual, $padreActual . $descrActual, "cyan", 3);
            $arbol = asignaPadre($padreActual, $filaActual, $arbol);

        } else if (null == $coincidenciasAnterior && $coincidenciasSgte){
            // Asumo que hay un padre común; a lo mejor es abuelo en las siguientes filas si tienen más padres por el medio.
            $hijos = 1; //poco fiable.
            $padreActual = $coincidenciasSgte;
            $coincidenciasAnterior = $coincidenciasSgte;        

            superEcho($padreActual, $descrActual, "verde", 3);
            $arbol = asignaPadre($padreActual, $filaActual, $arbol);
            //debug
            pintaln("\t" . $i . "Pasa de null a coincidencia","amarillo",4);

        } else if (null != $coincidenciasAnterior && false == $coincidenciasSgte){
            // Tiene un padre común con la fila  aterior, pero el siguiente es completamente nuevo.
            $hijos++;
            $padreActual = $coincidenciasAnterior;
            $coincidenciasAnterior = null;

            superEcho($padreActual, $descrActual, "verde", 3);
            $arbol = asignaPadre($padreActual, $filaActual, $arbol);
            //debug
            pintaln("\t\tSupuestamente " . $hijos . " hijos, después de $id_fila_dentro_lista_ordenada no hay coincidencia","azul",4);
            //pintaln("\t" . $i . "Pasa de coincidencia a null","amarillo",3);
            $hijos = 0;
       
        } else if (null != $coincidenciasAnterior && $coincidenciasAnterior == $coincidenciasSgte){
            // Asumo que el padre sigue siendo el mismo; a lo mejor es abuelo porque los siguientes tienen padres por el medio.
            $hijos++;
            $padreActual = $coincidenciasSgte;

            superEcho($padreActual, $descrActual,'verdeclaro', 3);
            $arbol = asignaPadre($padreActual, $filaActual, $arbol);
       
        } else if (null != $coincidenciasAnterior && strlen($coincidenciasAnterior) < strlen($coincidenciasSgte)){
            // El padre de esta fila tiene que incluir más cadenas que coincidenciasAnterior
            $hijos = 1;
            $padreActual = $coincidenciasSgte;
            $coincidenciasAnterior = $coincidenciasSgte;

            superEcho($padreActual, $descrActual, "verde", 3);
            $arbol = asignaPadre($padreActual, $filaActual, $arbol);
            // debug
            pintaln("\t\tSupuestamente " . $hijos . " hijos, después de $id_fila_dentro_lista_ordenada empiezo una coincidencia mayor","azul",4);

        } else if (null != $coincidenciasAnterior && strlen($coincidenciasAnterior) > strlen($coincidenciasSgte)){
            // Asumo que es el último hijo de coincidenciasAnterior
            $hijos++;
            $padreActual = $coincidenciasAnterior;
            $coincidenciasAnterior = $coincidenciasSgte;

            superEcho($padreActual, $descrActual, "verde", 3);
            $arbol = asignaPadre($padreActual, $filaActual, $arbol);            
            // debug
            pintaln("\t\tSupuestamente " . $hijos . " hijos, después de $id_fila_dentro_lista_ordenada paso a coincidencia menor", "azul",4);
            $hijos = 0;

        } else if (null != $coincidenciasAnterior && strlen($coincidenciasAnterior) == strlen($coincidenciasSgte)){
// DEBUG    por dios no quiero usar levenshtein()
            // Asumo que el padre es equivalente salvo cambios en puntuación, espacios, etc;
            // me quedo arbitrariamente con el primero.
            // a lo mejor es abuelo porque los siguientes tienen padres por el medio.
            $hijos++;
            $padreActual = $coincidenciasSgte;

            superEcho($padreActual, $descrActual,'verdeclaro', 3);
            $arbol = asignaPadre($padreActual, $filaActual, $arbol, $primera_pasada);
         } else {
            // Me falta por considerar alguna situación, depurar con echos.
            pintaln("coincidencia anterior\t$coincidenciasAnterior]", "blanco", 4);
            pintaln("descrip. fila actual\t$descrActual", "blanco", 4);
            pintaln("coincidencia sigte\t$coincidenciasSgte]","blanco", 4);
            pintaln("descrip. fila sigte\t$descrSgte", "blanco", 4);

            pintaln("ERROR, caso inclasificable",'rojo',0);
            exit;
        }
    } // end for

    if (!$primera_pasada){
        pintaln("\n");

        foreach ($arbol[REVISAR_CATEGORIA]['Children'] as $indice => $categoria_dudosa){
            $strCategoria = $categoria_dudosa['Descripcion'];
       
            if (isset($arbol[$strCategoria])) {
                pintaln ("Revisando categorías huérfanas: Existe " . $strCategoria, "amarillo",4);
               
                // Opción1 : colgar a los nietos directamente del abuelo, en el mismo nivel que otros padres con hijos.
                $arbol = reagrupamientoFamiliar ($strCategoria, $categoria_dudosa, $arbol);

                // To Do:
                // Opción2 : crear una subcategoría "Abuelo--Varios--" de la que cuelguen los nietos huérfanos.
               
            } else {
                pintaln ("Revisando categorías huérfanas: No existe " . $categoria_dudosa['Descripcion'], "gris", 4);  
                $arbol[$strCategoria] = $categoria_dudosa;
            }  
            unset ($arbol[REVISAR_CATEGORIA]['Children'][$indice]);
        }
        unset($arbol[REVISAR_CATEGORIA]);
    }
   
    return $arbol;
}

function elimina_acentos_upper($texto)
{   //www.webenphp.com    
    // $con_acento = utf8_decode("ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ");   
    $con_acento = utf8_decode("ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿ");
    $sin_acento = utf8_decode("AAAAAAaaaaaaOOOOOOooooooEEEEeeeeCcIIIIiiiiUUUUuuuuy");
    $texto = strtr(utf8_decode($texto), $con_acento, $sin_acento);
    $texto = mb_strtoupper(trim ($texto));

    return utf8_encode($texto);
}

/**
 * longestCommonSubstring devuelve la coincidencia (en mayúsculas) entre cadenas.
 *  Trata de aceptar pequeñas diferencias en delimitadores como coincidencia.
 */
function longestCommonSubstring($str1, $str2)
{
    $array_coincidencias = array();

    $len1 = mb_strlen($str1);
    $len2 = mb_strlen($str2);
    if (!$len1 || !$len2)
        return false;

    $str1u = elimina_acentos_upper($str1);
    $str2u = elimina_acentos_upper($str2);

    $i1 = 0;
    $i2 = 0;

    while ($i1 < $len1 && $i2 < $len2){
        // Obtiene caracteres "normales" evitando espacios y delimitadores (NO_COMPARAR)
        do {
            $c1 = mb_substr($str1u, $i1, 1); 
            $i1++;
        } while (strpos($c1,NO_COMPARAR) !== false && $i1 < $len1 );
        do {
            $c2 = mb_substr($str2u, $i2, 1); 
            $i2++;
        } while (strpos($c1,NO_COMPARAR) !== false && $i2 < $len2 );

        if ($c1 !== $c2) {                       

            break;
        }
    }

    if (1 == $i1 || 1 == $i2) {
        pintaln("No hay coincidencias entre la próxima fila y la siguiente","gris",4);
        // pintaln ("--".$str1u,"gris",3);
        // pintaln ("--".$str2u,"gris",3);
        return false;
    } else if ($i2 > $i1){

        // return mb_substr($str2u, 0, $i2 - 1);
        return mb_substr($str2u, 0, $i2); // coge un caracter de más
    } else{

        // return mb_substr($str1u, 0, $i1 - 1);
        return mb_substr($str1u, 0, $i1); // coge un caracter de más
    }

    // for ($i = 0; $i < min($len1, $len2); $i++){
    //     $caracter = mb_substr($str1u, $i, 1);
    //     if ($caracter === mb_substr($str2u, $i, 1)) {
    //         $array_coincidencias[] = $caracter;
    //     } else {
            
    //         break;
    //     }
    // }

    if (0==count($array_coincidencias)) {
        pintaln("No hay coincidencias entre la próxima fila y la siguiente","gris",3);
        // pintaln ("--".$str1u,"gris",3);
        // pintaln ("--".$str2u,"gris",3);
        return false;
    }

    // FIXME
    // // Comprobación para devolver el más largo en casos como"padre. xxx" y "padre - yyy"
    // // porque superTrim necesita un delimitador.
    // $lenc = count($array_coincidencias);
    // if ($len1 >= $lenc + 2 && strpos(DELIMITADORES,$str1u[$lenc + 1]) !== false) {
    //     return substr($str1u,0,$lenc + 2);

    // } else if ($len2 >= $lenc + 2 && strpos(DELIMITADORES,$str2u[$lenc + 1]) !== false) {
    //     return substr($str2u,0,$lenc + 2);

    // } else if ($len1 >= $lenc + 1 && strpos(DELIMITADORES,$str1u[$lenc + 0]) !== false) {
    //     return substr($str1u,0,$lenc + 1);

    // } else if ($len2 >= $lenc + 1 && strpos(DELIMITADORES,$str2u[$lenc + 0]) !== false) {
    //     return substr($str2u,0,$lenc + 1);
    // }

    return implode('', $array_coincidencias);
}

/**
 *longestCommonSubstringWithRelevantWords - importante el orden, 
 * si hay coincidencia devuelve la parte coincidente de la primera string.
 */
function longestCommonSubstringWithRelevantWords($str1u, $str2u, $nivel_debug = 4)
{
            // fixme  revisar caso de que las primeras sean distintas
    $array_words1  = splitRelevantWordsUtf8(utf8_encode($str1u));
    $array_words2  = splitRelevantWordsUtf8(utf8_encode($str2u));
    if (!$array_words1 || !$array_words2){
        pintaln("\tlongestCommon...Words recibió una string false str1u[" . $str1u . "] o str2u[" . $str2u . "]","gris", $nivel_debug);

        return false;
    }

    $shorter_array = (count($array_words1) < count($array_words2)) ? $array_words1 : $array_words2;
    $min_num_words = count($shorter_array);

    $nw1 = 0;
    $nw2 = 0;
    while ($nw1 < $min_num_words && $nw2 < $min_num_words){
        if ($array_words1[$nw1][0] == "Y") $nw1++;
        if ($array_words2[$nw2][0] == "Y") $nw2++;

        if ($array_words1[$nw1][0] != $array_words2[$nw2][0]) break;
        $nw1++;
        $nw2++;
    }

    // hasta offset de la última palabra coincidente + strlen de esa palabra + 1
    pintaln("\tlongestCommon...Words str1u:","gris", $nivel_debug);
    pintaln($str1u, "blanco", $nivel_debug);
    pintaln("\tlongestCommon...Words str2u:","gris", $nivel_debug);
    pintaln($str2u, "blanco", $nivel_debug);

    if (0 == $nw1 || 0 == $nw2){
        pintaln("\tlongestCommon...Words encontró distinta la primera palabra","gris", $nivel_debug);
        pintaln($str1u . "]","gris", $nivel_debug);
        pintaln($str2u . "]","gris", $nivel_debug);

        return false;
    }

    pintaln("DEBUG - longestCommon...Words última palabra coincidente de str1 [" . $array_words1[$nw1 - 1][0] . "] str2 [". $array_words2[$nw2 - 1][0] . "]", "azul", $nivel_debug);
    // $debug_siguiente1 = (isset($array_words1[$nw1][0]))? $array_words1[$nw1][0] : "[]" ;
    // $debug_siguiente2 = (isset($array_words2[$nw2][0]))? $array_words2[$nw2][0] : "[]" ;
    // $resultado_comparacion = ($debug_siguiente1 != $debug_siguiente2)?"DISTINTAS":"IGUALES";
    // pintaln("las siguientes palabras, si existen, son: " . $debug_siguiente1 . " y " . $debug_siguiente2 . "; ambas " . $resultado_comparacion, "azul", $nivel_debug);

    // offset de la última palabra coincidente + longitud de esa última palabra
    $coincidencias_str1u = mb_substr($str1u, 0, $array_words1[$nw1 - 1][1] + mb_strlen($array_words1[$nw1 - 1][0]) + 1);
    $coincidencias_str2u = mb_substr($str2u, 0, $array_words2[$nw2 - 1][1] + mb_strlen($array_words2[$nw2 - 1][0]) + 1);

    // $nw1-1 y $nw2-1 son los índices de las últimas palabras coincidentes
    // podría analizar qué string es más largo (offset, no número de palabras) y decidir
    // de momento elijo str1 por cojones.

    pintaln("\tlongestCommon...Words  delimitadores flexibles - coincidencia antes de terminar las dos strings", "gris", $nivel_debug);
    pintaln($coincidencias_str1u."]","gris");
    pintaln($coincidencias_str2u."]","gris");
    pintaln("\tescojo arbitrariamente el primero", "gris", $nivel_debug);
    $coincidencias = $coincidencias_str1u;

    pintaln($coincidencias . "]", "gris", $nivel_debug);

    return $coincidencias;       
}

function nodoseriesEquivalentes ($str1, $str2)
{
    return (splitRelevantWordsUtf8($str1) === splitRelevantWordsUtf8($str2));
}


/**
 * superTrim - recibe una cadena con la coincidencia entre una fila y la siguiente.
 * devuelve una subcadena desde el principio hasta el último delimitador, trimeada.
 * Si no existen delimitadores, se considera que no hay coincidencia.
 */
function superTrim($stringCoincidencias)
{
    if (false == $stringCoincidencias){

        return false;
    }

    // Comprobaciones preliminares para afinar la búsqueda de nodos.
    if (strlen($stringCoincidencias) > MAX_PADRE) {
        $stringCoincidencias = substr($stringCoincidencias, 0, MAX_PADRE);
    }
    if (UnedDesbrozatorHardcoded::checkParent($stringCoincidencias)){

        return UnedDesbrozatorHardcoded::checkParent($stringCoincidencias);
    }
    if (UnedDesbrozatorHardcoded::trimShorter($stringCoincidencias)){
        $stringCoincidencias = UnedDesbrozatorHardcoded::trimShorter($stringCoincidencias);
    }

    // Chicha: busca delimitadores
    $str_delimitada = strpbrk(strrev($stringCoincidencias), DELIMITADORES);
    if ($str_delimitada !== false){
        
        // Si detecta varios delimitadores juntos, quitarlos. Ejemplo de casos conflictivos que deberían tener el mismo abuelo:
        // [Espacio y Tiempo] [Historia Antigua Universal] Espacio y Tiempo: Historia Antigua Universal - bla bla bla
        // [Espacio y Tiempo] Espacio y Tiempo - bla bla bla
        // [Espacio y Tiempo:] Espacio y Tiempo: (Im�genes de la Edad Media ) Historia Medieval - bla bla bla
        // [Espacio y Tiempo:] Espacio y Tiempo: (Im�genes de la Edad Media) - bla bla bla

        // Elimino 1 delimitador + espacios, si existen más delimitadores sigo eliminando.
        do {
            $str_delimitada = substr($str_delimitada, 1);
            $str_delimitada = trim($str_delimitada);
            $primer_caracter = mb_substr($str_delimitada, 0, 1);
            if ("" == $primer_caracter){
                break;
                // debug
                // throw new Exception ("Buscando delimitadores en [".$stringCoincidencias."] está vacío");
            }
        } while (strpos(DELIMITADORES, $primer_caracter) !== false);
        
        return trim(strrev($str_delimitada));

    } else {
        // no hay delimitador => considero que no hay coincidencia.

        return false;
    }
}

/**
 * asignaPadre - crea arbol [categoría] ['Children'] = array (fila entera de la BD UNED)
 */
function asignaPadre( $padre, array $nodo, array $arbol)
{
    if (!isset($arbol[$padre]['Children'] )) {

        $este_padre_es_nuevo = true;
        // comprobar si existe nodoserie equivalente y tomarla por el padre actual
        foreach ($arbol as $posible_padre_equivalente => $v){
            if (nodoseriesEquivalentes($posible_padre_equivalente, $padre)){
                $este_padre_es_nuevo = false;
                pintaln ("Voy a tomar $padre como equivalente de la NS existente $posible_padre_equivalente", "gris", 4);

                $padre = $posible_padre_equivalente;
            }
        }

        if ($este_padre_es_nuevo){
            $arbol[$padre]['Children'] = array();
            $arbol[$padre]['Descripcion'] = $padre; // Para poder repetir pasadas de lobaton().
        }
    }

    if (isset($nodo['Children']) && $padre != REVISAR_CATEGORIA) {
        pintaln ("Hay abuelos - $padre contiene a " . $nodo['Descripcion'], "rojo", 4);
        $subcategoria = emancipaPadre($padre, $nodo['Descripcion']);
        // pintaln("La nueva subcategoría es ". $subcategoria,"amarillo",4);
        $nodo['Descripcion'] = $subcategoria;
    }
   
    $arbol[$padre]['Children'][] = $nodo;
   
    return $arbol;
}

/**
 * emancipaPadre - devuelve la diferencia entre el segundo string y el primero,
 * eliminando espacios y delimitadores.
 */
function emancipaPadre($abuelo, $abuelo_y_padre)
{
    // Como las coincidencias pueden variar por unos pocos caracteres, reviso desde un poco antes.
    $inicio         = strlen($abuelo) - 2; // sólo 1 caracter de menos
    $str_a_recortar = substr($abuelo_y_padre, $inicio);
    $str_delimitada = strpbrk($str_a_recortar, DELIMITADORES);

    // Debug - busca delimitadores incluido espacio, 'y', etc.
    if ($str_delimitada === false) {
        $str_delimitada = strpbrk($str_a_recortar, NO_COMPARAR);
    }
    
    if ($str_delimitada !== false){       
        // Elimino 1 delimitador + espacios, si existen más delimitadores sigo eliminando.
        do {
            $str_delimitada  = substr($str_delimitada, 1);
            $str_delimitada  = trim($str_delimitada);
            $primer_caracter = mb_substr($str_delimitada, 0, 1);
        } while (strpos(DELIMITADORES, $primer_caracter) !== false);

    } else {
        pintaln("Hay un problema al separar " . $abuelo . " y " . $abuelo_y_padre, "rojo", 0);
        pintaln("No encuentro delimitadores en " . $str_a_recortar, "rojo", 0);
// FIXME - en 3ª pasada da error - probar a devolver categoría entera sin emancipar.
        exit;
    }

    if (strlen($str_delimitada) < (strlen($str_a_recortar) - 6)) {
        pintaln("Hay un problema al separar " . $abuelo . " y " . $abuelo_y_padre, "rojo", 0);
        pintaln("La string a recortar es: ".$str_a_recortar,"blanco",0);
        pintaln("El recorte de ".  $str_delimitada . " está muy lejos", "rojo", 0);
        exit;
    }

    return $str_delimitada;
}

/**
 * reagrupamientoFamiliar - asigna los hijos de la categoría actual a otra existente
 * de mayor nivel con el mismo nombre.
 * Si hay un abuelo + varios padres con hijos + varios "nietos" sueltos, estos últimos colgarían del abuelo.
 * La otra opción es crear un padre "Otros" del que cuelguen los nietos huérfanos.
 **/
function reagrupamientoFamiliar ($abuelo, array $nodo, array $arbol)
{
    foreach ($nodo['Children'] as $nieto){
        $arbol = asignaPadre($abuelo, $nieto, $arbol);
    }

    return $arbol;
}


// Con debug >=2, también saca los hijos.
function pintaArbolEntero(array $elemento, $indenta = '')
{
    if (!isset($elemento['Children'])){
        pintaln($indenta.$elemento['Descripcion'], "blanco", 2);

        return;
    } else{
        pinta ($indenta.$elemento['Descripcion'],"gris");
        //pintaln(" (" . count($elemento['Children']) . " hijos directos)", "blanco");
        pintaln(" (" . cuentaHijos ($elemento) . ")", "blanco");
        foreach ($elemento['Children'] as $subElemento){
            pintaArbolEntero ($subElemento, $indenta . "   . ");
        }    
    }  
}

/**
 * csvArbolEntero recorre el array recursivamente y construye un string
 * del tipo "categoría1"; "nº elementos"; ... ;"descripción del elemento";\n
 * 
 * Estructura típica con 3 pasadas:
 * Uned ; total ; cat1 ; total ; subcat2 ; total ; subcat3 ; total ; descripción.
 */
function csvArbolEntero(array $elemento, $columnas_previas = '', $num_columnas = 0)
{
    
    if (!isset($elemento['Children'])){
        $repeticiones = (2 * (NUM_PASADAS + 1)) - $num_columnas;
        $columnas_vacias = str_repeat ( "\"\";" , $repeticiones);
        return $columnas_previas . $columnas_vacias . "\"" . 
            str_replace('"',"'",$elemento['Descripcion']) . "\";\n";

    } else{
        // Cuentahijos en cada nodo es poco eficiente pero sirve.
        $columna_actual = "\"" . str_replace('"',"'",$elemento['Descripcion']) . 
            "\";\"" . cuentaHijos ($elemento) . "\";";
        $string_csv = '';
        foreach ($elemento['Children'] as $subElemento){           
            $string_csv .= csvArbolEntero($subElemento, $columnas_previas . $columna_actual, $num_columnas + 2);
        }

        return $string_csv;
    }  
}

function guardaCsvConTodo(array $raiz)
{
    // Guarda cabecera con un nº de categorías según las pasadas
    $string_csv  = utf8_decode('"Raíz";"Nº Elementos";');
    for ($i=1; $i <= NUM_PASADAS; $i++){
        $string_csv .= utf8_decode('"Categoría-NodoSerie ' . $i . '";"Nº Elementos";');
    }
    $string_csv .= utf8_decode('"Descripción";'."\n");

    $string_csv .= csvArbolEntero($raiz);
    // file_put_contents(CSV_FILENAME, utf8_encode($string_csv));
    file_put_contents(CSV_FILENAME, $string_csv);
}

/** 
 * cuentaHijos recorre un array con un árbol y cuenta sólo los elementos finales.
 */
function cuentaHijos(array $elemento)
{    
    if (!isset($elemento['Children'])){
       
        return 1;
    } else{
        $h = 0;
        foreach ($elemento['Children'] as $subElemento){
            $h += cuentaHijos($subElemento);
        }

        return $h;
    }  
}

function pintaDosRamas(array $arbol)
{

    foreach ($arbol['Children'] as $rama1){
        pinta ("\t" . $rama1['Descripcion'],"verde");
        pintaln(" (" . cuentaHijos ($rama1) . ")", "blanco");

        if (isset($rama1['Children'])){
            foreach ($rama1['Children'] as $rama2){
                if (!isset($rama2['Children'])){
                    pintaln("\t\t" . $rama2['Descripcion'], "blanco", 2);
                } else {
                    pinta ("\t\t" . $rama2['Descripcion'],"verde");
                    pintaln(" (" . cuentaHijos ($rama2) . ")", "blanco");
                }
            }
        }
    }
}


/**
 * arbol2array - pasa un árbol (el array de mayor nivel es asociativo) a array con índices
 * Solución chapucera para no retocar mucho la función lobaton()
 */
function  arbol2array(array $arbol)
{
    $array = array();
    $array_aux_ordenar = array();
    foreach ($arbol as $elemento) {
        $array_aux_ordenar[] = $elemento['Descripcion'];
    }
    // php 5.4 también admite
    // sort($array_aux_ordenar, SORT_NATURAL | SORT_FLAG_CASE);
    sort($array_aux_ordenar, SORT_LOCALE_STRING );
    foreach ($array_aux_ordenar as $descripcion) {
        $array[] = $arbol[$descripcion];
    }

    return $array;
}


/**
 * superEcho : si recibe un solo string, hace un echo recortado para que quepa en la terminal
 *   si recibe dos strings, saca por pantalla el primero coloreado +
 *   lo que quepa de (str2 - str1).
 **/
function superEcho ($str1, $str2 = null, $color = 'verde', $debug=1)
{
    if ( $debug <= NIVEL_DEBUG){
        $len1 = strlen($str1);
       
        if ($str2 != null) {
            $len2 = strlen($str2);
            pinta($str1, $color);
            if ($len2 > MAX_TERMINAL){
                $str2 = substr($str2, $len1, MAX_TERMINAL - $len1) . "...";    
            } else {
                $str2 = substr($str2, $len1);
            }
            $str = $str2;
        } else {
            if ($len1 > MAX_TERMINAL){
                $str = substr($str1, 0, MAX_TERMINAL - $len1) . "...";
            } else {
                $str = $str1;
            }
        }
        echo $str."\n";
    }
}

function pinta($str, $color = 'verde', $debug = 1)
{
    if ( $debug <= NIVEL_DEBUG){
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
        $str = substr($str,0,MAX_TERMINAL + 3);
        echo $c[$color] . $str . $c['fin'];
        flush();
    }
}

function pintaln($str, $color = 'verde', $debug=1)
{
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
    $nombre = sprintf('%9d %9d', memory_get_usage(true), memory_get_peak_usage(true)) . "\t" . $nombre;
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

    foreach ($array_tiempos as $evento => $t){
        if (0 == $t_anterior){
            pintaln("\t\t\t\t".$evento);
            $t_inicial  = $t;
            $t_anterior = $t;
        } else {
            $t_duracion = (float) $t - $t_anterior;
            pintaln("Duración: ". sprintf('%5.4F',$t_duracion) . "\t" . $evento );
            $t_anterior = $t;
        }
    }
    pintaln ("Total:\t" . sprintf('%5.4F', (float)($t_anterior - $t_inicial)) . " segundos");
}

/**
 * Sustituye cadenas en las descripciones de uned_media_old y vuelve a ordenar
 * para que queden adyacentes las descripciones relacionadas.
 */
function corrigeTyposIniciales($filas)
{
    foreach ($filas as &$fila){
        foreach (UnedDesbrozatorHardcoded::$typos_iniciales as $typo => $corrected){
            $inicio_cadena_original = substr($fila['Descripcion'],0,strlen($typo));
            if ( $inicio_cadena_original == $typo){
                $completa_corregida = $corrected . substr($fila['Descripcion'],strlen($typo));
                
                pintaln("Corregido " . $inicio_cadena_original . " por " . $completa_corregida, "amarillo", 4);               
                $fila['Descripcion'] = $completa_corregida;
                $fila['1']           = $completa_corregida; 
                // Ojo, revisar con var_dump. Cada elemento tiene repetido [Id] = xxx, [0] = xxx, [Descripcion] = abc, [1] = abc ...

            }
        }
    }

    // Vuelve a ordenar el array inicial
    $array_descripciones = array();
    foreach ($filas as $indice => $valor){
        $array_descripciones[] = $valor['Descripcion'];
    }
    array_multisort($array_descripciones, $filas);
// debug
    // foreach ($array_descripciones as $descripcion) pintaln($descripcion,"blanco");
    // print_r($filas);
    // exit;

    return $filas;
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


function creaCategoriasConMm (array $nodo, $serie)
{
    $cat_raiz_uned = creaSubRaiz(RAIZ_NODOSERIES_UNED);
    $id_raiz_uned  = $cat_raiz_uned->getId();
    creaArbolCategoriasConMm($nodo, $id_raiz_uned, $serie, '');

    return ;
}

function borraCategoriasImportadas( $raices = array(RAIZ_NODOSERIES_UNED,
                                                    RAIZ_CATEGORIAS_TEMATICAS))
{
    if (!is_array($raices)) {
        $raices = array($raices);
    }

    foreach ($raices as $cat_raiz){
        if ($cat_raiz = CategoryPeer::retrieveByCode($cat_raiz)){
            $cat_raiz->deleteDescendants();
            $cat_raiz->delete();
        }
    }

}

function creaRaizAbsoluta($nombre = 'root')
{
    if (!$cat_raiz = CategoryPeer::doSelectRoot()){
        $cat_raiz = new Category();
        $cat_raiz->makeRoot();
        $cat_raiz->setMetacategory(true);
        $cat_raiz->setDisplay(true);
        $cat_raiz->setRequired(false);
        $cat_raiz->setCod($nombre);

        $langs = (I18N)? sfConfig::get('app_lang_array', array('es')) : array('es');
        foreach($langs as $lang){
            $cat_raiz->setCulture($lang);
            $cat_raiz->setName($nombre);
        }

        $cat_raiz->save();
        $cat_raiz = CategoryPeer::doSelectRoot(); // Para asegurarme de que está actualizada
        pintaln ("Creada la categoría raíz");

    } else {
        pintaln("Recuperada la categoría raíz");
    }

    return $cat_raiz;
}

/**
 * creaSubRaiz - crea o devuelve, dentro de la raíz absoluta de category, 
 * una raíz para un nuevo tipo de arbol de categorías (nodoseries, unesco...)
 */
function creaSubRaiz($nombre)
{
    $cat_raiz = creaRaizAbsoluta();

    // // Por si más adelante creo raiz absoluta con cod y nombre diferentes
    // $c = new Criteria();
    // $c->add(CategoryI18nPeer::NAME, $nombre);
    // $c->add(CategoryPeer::COD, $cod_prefix);
    // $c->addJoin(CategoryI18nPeer::ID, CategoryPeer::ID);
    // $category = CategoryPeer::doSelectWithI18n($c, 'es');
    // if (!$category){

    if (!$subcat = CategoryPeer::retrieveByCode($nombre)){
        $subcat = new Category();
        $subcat->insertAsLastChildOf($cat_raiz);
        $subcat->setMetacategory(false); // True no actualiza num_mm
        $subcat->setDisplay(true);
        $subcat->setRequired(false);
        $subcat->setCod($nombre);

        $langs = (I18N)? sfConfig::get('app_lang_array', array('es')) : array('es');
        foreach($langs as $lang){
            $subcat->setCulture($lang);
            $subcat->setName($nombre);
        }

        $subcat->save();
        pintaln ("Creada la categoría " . $nombre);

    } else {
        pintaln("Recuperada la categoría " . $nombre);
    }


    return CategoryPeer::retrieveByPk($subcat->getId());
}

/**
 * createCategory crea 1 categoría; ojo, el objeto devuelto NO está actualizado con la BD.
 */
function createCategory($name, $id_parent, $cod_prefix = '')
{
    $parent   = CategoryPeer::retrieveByPK($id_parent);

    if (!$parent) {
        throw new Exception ("Error: no se encuentra categoría con id: ". $id_parent, "rojo");
    }

    $category = new Category(); 
    $category->insertAsLastChildOf($parent);
    $category->setMetacategory(false);
    $category->setDisplay(true);
    $category->setRequired(false);
    // $category->setCod($cod_prefix . $name);
    $category->setCod($cod_prefix);
  
    $langs = (I18N)? sfConfig::get('app_lang_array', array('es')) : array('es');
    foreach($langs as $lang){
        $category->setCulture($lang);
        $category->setName($name);
    }
    $category->save();
    pintaln("Creada categoría " . $cod_prefix . $name, "verde", 4);

    return $category;
}

/**
 * Recorre un array recursivamente y crea categorías para los hijos del nodo actual.
 */
function creaArbolCategoriasConMm(array &$arbol, $id_cat_padre, $serie, $cod_prefix_parent = '')
{
    $cod_prefix_int = 1;
    foreach ($arbol['Children'] as &$nodo){
        // if (!isset($nodo['Children']) && esteUmoTieneMultimedia($nodo['Id'])){
        if (!isset($nodo['Children'])){

            // Crea un mm nuevo, lo asigna a la categoría padre y superiores.
            

            $mm        = creaMm($nodo['Id'], $serie);
            $cat_padre = CategoryPeer::retrieveByPK($id_cat_padre);
            $array_cat = $cat_padre->addMmIdAndUpdateCategoryTree($mm->getId());

            pintaln("Se ha añadido el mm" . $mm->getTitle() . "a las categorías (1xlínea): ","blanco",3);
            foreach ($array_cat as $cat){
                pintaln("\t" . $cat->getName(),"blanco",3);
            }

            continue;
        }

        // Para asegurar que categoría->cod es único le añado índices.        
        if ('' == $cod_prefix_parent){
            $cod_prefix = "NS_" . sprintf("%03s",$cod_prefix_int) ;    
        } else{
            $cod_prefix = $cod_prefix_parent . "_" . sprintf("%02s",$cod_prefix_int);
        }
        $cod_prefix_int++;

        pintaln("Voy a crear otra categoría hija de " . $id_cat_padre . 
            " con prefijo " . $cod_prefix . " y descripcion " . 
            $nodo['Descripcion'], "amarillo", 4);

        $cat_actual = createCategory($nodo['Descripcion'], $id_cat_padre, $cod_prefix);
        pintaln("Creada categoría - LFT: " . $cat_actual->getTreeLeft() . 
            " RGT: " . $cat_actual->getTreeRight() . " Id: " . $cat_actual->getId() .
            " Descripcion " . $nodo['Descripcion'] , "verde" , 3);

        if (isset($nodo['Children'])){
            creaArbolCategoriasConMm($nodo, $cat_actual->getId(), $serie, $cod_prefix);
        }
    }
}

/**
 * esteUmoTieneMultimedia - comprueba que la entrada de uned_media_old
 * contiene información válida para crear files de audio o vídeo
 * devuelve false si sólo tiene documentos o dir. streaming.
 */
function esteUmoTieneMultimedia($umo_id){
    $umo = UnedMediaOldPeer::retrieveByPk($umo_id);

    return (bool)  ($umo->getPresetOriginal() != '' ||
                    $umo->getPresetAlta() != '' ||
                    $umo->getPresetMedia() != '' || 
                    $umo->getPresetBaja() != '' );
}

/**
 * serieUnicaImportados - crea o busca y devuelve la serie de pumukit 
 * que contendrá a todos los mm importados. Útil para borrarlos de un paso.
 */
function serieUnicaImportados()
{
    $c = new Criteria();
    $c->add(SerialI18nPeer::TITLE, SERIE_IMPORTADOS);
    $serie_importados = SerialPeer::doSelectWithI18n($c, 'es');  

    if (!$serie_importados) {
        $serie_importados = new Serial();
        $serie_importados->setCulture('es');
        $serie_importados->setPublicdate("now");
        $serie_importados->setTitle(SERIE_IMPORTADOS);
        $serie_importados->setSerialTypeId(SerialTypePeer::getDefaultSelId());
        $serie_importados->setSerialTemplateId(1);
        $serie_importados->save();

        return $serie_importados;
    } else if (count($serie_importados) > 1) {
        throw new Exception ("Hay más de una serie con el título \"".SERIE_IMPORTADOS."\"");
    }

    return $serie_importados[0];
}

/**
 * creaMm - Crea un nuevo mm importando el id de UnedMediaOld y asignándolo a $serie
 */
function creaMm($umo_id, $serie)
{

    $umo = UnedMediaOldPeer::retrieveByPK($umo_id);
    $audio  = ('Audios' == $umo->getCategorias())? 1 : 0;
    $umo_estado_mm_status_id = array(
        'PUB'   => MmPeer::STATUS_NORMAL,
        'EDI'   => MmPeer::STATUS_BLOQ,
        'REV'   => MmPeer::STATUS_BLOQ,
        'UNPUB' => MmPeer::STATUS_BLOQ);

    $mm  = new Mm();
    $mm->setSerial($serie);
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
    $mm->setBroadcastId(BroadcastPeer::getDefaultSelId());
    $mm->setAudio($audio);
    $mm->setStatusId($umo_estado_mm_status_id[$umo->getEstado()]);
    (ACTUALIZA_INDICES_LUCENE)? $mm->save() : $mm->saveInDB();
    $umo->setMmId($mm->getId());
    $umo->save();

    return $mm;
}

/**
 * Borra los mms fiándose de la única serie a la que están asignados los importados
 * Ojo, también borra en cascada pic_mm y pic (todos), files y file_i18n,
 * category_mm (respeta category), person_mm (respeta person).
 */
function borraMmsImportadosDesdeSerie($serie)
{
    $mms_todos = MmPeer::doSelect(new Criteria());
    $mms_serie = $serie->getMms();

    if (count($mms_todos) == count($mms_serie)){
        MmPeer::doDeleteAll();
        pintaln ("borrado con doDeleteAll");
    } else {
        foreach ($mms_serie as $mm){
            $mm->delete();
        }
        pintaln ("borrado con foreach");
    }
}

/**
 * borraTablasAPelo - trunca tablas en vez de borrar objetos, para acelerar el desarrollo
 * y forzar que se reseteen los ids.
 */
function borraTablasAPelo($tablas, $fuerza_borrado = false)
{
    if (!BD_BORRABLE && !$fuerza_borrado){
        throw new Exception ("Tratando de borrar " . $tablas . " en un servidor que no tiene pinta de desarrollo");
    }

    $connection = Propel::getConnection();
    $query = '';
    pintaln("Truncando tablas: ");
    if (!is_array($tablas)){
        $tablas = array($tablas);
    }
    foreach ($tablas as $tabla){
        pinta($tabla." ");
        $connection->executeUpdate('TRUNCATE TABLE '.$tabla);       
    }
    echo "\n\n";
}

/** 
 * Guarda un archivo CSV con sólo las categorías (nodoseries)
 */
function guardaCsvDesdeBD($nodo_uned)
{
    // $string_csv  = utf8_decode('"Raíz";"Nº Elementos";');
    $string_csv  ='"Raíz";"Nº Elementos";';
    for ($i=1; $i <= NUM_PASADAS; $i++){
        // $string_csv .= utf8_decode('"Categoría-NodoSerie nivel ' . $i . '";"Nº Elementos";');
        $string_csv .= '"Categoría-NodoSerie nivel ' . $i . '";"Nº Elementos";';
    }
    $string_csv .= "\n";
    $string_csv .= csvArbolEnteroDesdeBD($nodo_uned);
    // file_put_contents('arbol_bd_categorias.csv', utf8_encode($string_csv));
    file_put_contents('arbol_bd_categorias.csv', $string_csv);
}

function csvArbolEnteroDesdeBD(array $elemento)
{
    $l      = $elemento['level'] - 1; //descuenta root, 0 = UNED.
    $name   = $elemento['node']->getName();
    $num_mm = $elemento['node']->getNumMm();
    $inicio = str_repeat('"";"";', $l);
    $final  = str_repeat('"";"";', 2-$l) . "\n";

    $str_csv = $inicio . '"' . $name . '";"' . $num_mm . '";' . $final;
    foreach ($elemento['children'] as $sub_elemento){
        $str_csv .= csvArbolEnteroDesdeBD($sub_elemento);
    }

    return $str_csv;
}

/**
 * Guarda un CSV la lista de nodoseries y muestra el total de temáticas o unescos
 * del conjunto de los mm de la nodoserie.
 */
function guardaCsvNodoseriesTematicasDesdeBD($añadir_totales = "tematicas"){

    $tree_array = CategoryPeer::buildTreeArray();
    $nodo_raiz_nodoseries = encuentraNodoCategoriaEnTreeArray(RAIZ_NODOSERIES_UNED, $tree_array[0]);
    $nodo_raiz_tematicas = encuentraNodoCategoriaEnTreeArray(RAIZ_CATEGORIAS_TEMATICAS, $tree_array[0]);
    $nodo_raiz_unesco = encuentraNodoCategoriaEnTreeArray(RAIZ_CATEGORIAS_UNESCO, $tree_array[0]);

    if (strtolower($añadir_totales) == "tematicas") {
        $nodo_raiz_añadir_totales = $nodo_raiz_tematicas;
    } else if (strtolower($añadir_totales) == "unesco") {
        $nodo_raiz_añadir_totales = $nodo_raiz_unesco;
    } else throw new Exception("guardaCsvNodoseriesTematicas - arbol " . $añadir_totales . " inválido - usar tematcias o unesco");

    $string_csv  ='"Raíz";"Nº Elementos";';
    for ($i=1; $i <= NUM_PASADAS; $i++){
        $string_csv .= '"Categoría-NodoSerie nivel ' . $i . '";"Nº Elementos";';
    }
    $string_csv .= '"' . strtoupper($añadir_totales) . ' (multivaluado)";' . "\n";
    // $string_csv .= csvArbolNodoseriesTematicasDesdeBD($nodo_raiz_nodoseries, $nodo_raiz_tematicas);
    $string_csv .= csvArbolNodoseriesTematicasDesdeBD($nodo_raiz_nodoseries, $nodo_raiz_añadir_totales);
    file_put_contents('arbol_bd_categorias_tematicas.csv', $string_csv);
}

/**
 * Recorre árbol de nodoseries recursivamente y registra nodoseries y temáticas
 */
function csvArbolNodoseriesTematicasDesdeBD(array $elemento, array $nodo_raiz_tematicas)
{
    $l          = $elemento['level'] - 1; //descuenta root, 0 = UNED.
    $name       = $elemento['node']->getName();
    $num_mm     = $elemento['node']->getNumMm();
    $inicio     = str_repeat('"";"";', $l);
    $intermedio = str_repeat('"";"";', 2-$l);
    $tematicas  = (0 == $l) ? '' : listaTematicasDeEstaCategoria($elemento['node'], $nodo_raiz_tematicas['node']);// procesa tematicas
    $final      = "\";\n";

    $str_csv = $inicio . '"' . $name . '";"' . $num_mm . '";'. $intermedio . '"' . $tematicas . $final;
    foreach ($elemento['children'] as $sub_elemento){
     $str_csv .= csvArbolNodoseriesTematicasDesdeBD($sub_elemento, $nodo_raiz_tematicas);
    }

    return $str_csv;
}

function listaTematicasDeEstaCategoria($categoria, $cat_raiz_tematicas)
{
    pintaln("Tratando de encontrar las temáticas de ". $categoria->getName() 
        . " que son hijas de " . $cat_raiz_tematicas->getName() , "blanco", 3);
    $mms = $categoria->getMms();
        pintaln("Hay: " . count($mms) . " mms en esta nodoserie", "verde", 3);


    $array_tematicas = array();
    foreach ($mms as $mm) {
        pintaln("\tAnalizando mm id: " .$mm->getId(), "blanco", 4);
        $cat_tematicas = $mm->getCategories($cat_raiz_tematicas);
        foreach ($cat_tematicas as $cat_tematica){
            $array_tematicas = actualizaArray($array_tematicas, $cat_tematica->getName());
        }
    }

    arsort($array_tematicas);
    $string_tematicas = '';
    foreach ($array_tematicas as $k => $v){
        $string_tematicas .= $k . " (" . $v . "), ";       
    }
    
    return $string_tematicas;
}


// Ejemplo para sacar el árbol de etiquetas por consola.
function recorreTreeArray(array $elemento){
    $l      = $elemento['level'] - 1; //descuenta root, 0 = UNED.
    $name   = $elemento['node']->getName();
    $num_mm = $elemento['node']->getNumMm();
    pintaln($l . str_repeat("\t", $l) . $name . " (" . $num_mm . ")");
    foreach ($elemento['children'] as $sub_elemento){
        recorreTreeArray($sub_elemento);
    }
}

function encuentraNodoCategoriaEnTreeArray($nombre_categoria, array $elemento)
{
    foreach ($elemento['children'] as $sub_elemento){
        if ($sub_elemento['node']->getName() == $nombre_categoria){
            
            return $sub_elemento;
        }
    }
    
    return false;
}

function creaListaPersonas()
{   
    $array_people = array();

    $umos = retrieveUmosWithValidcolumn("autor");
    
    pintaln("umo_id" . "\t" ."Autores -----------------" ,"azul",3);
    foreach ($umos as $umo){
            pintaln($umo->getId() . "\t" . $umo->getAutor(),"blanco",3);

            foreach (separaVariosNombres($umo->getAutor()) as $person){
                if (!$parseada = parseaUnaPersona($person)){
                    continue;
                }

                $array_people = actualizaArray($array_people, $parseada);
                pintaln("\t" . $parseada,"verde", 3);
            }           
    }
    pintaln("\n-------------- Total uned_media_old con autor(es): " . count($umos)."-----------------------------\n", "azul");

    $umos = retrieveUmosWithValidcolumn("realizador");

    pintaln("umo_id" . "\t" ."Realizadores ------------" ,"azul",3);
    foreach ($umos as $umo){
            pintaln($umo->getId() . "\t" . $umo->getRealizador(),"blanco",3);

            foreach (separaVariosNombres($umo->getRealizador()) as $person){
                if (!$parseada = parseaUnaPersona($person)){
                    continue;
                }
                
                $array_people = actualizaArray($array_people, $parseada);
                pintaln("\t" . $parseada, "verde", 3);
            }
    }
    pintaln("\n-------------- Total uned_media_old con realizador(es): " . count($umos)."-----------------------------\n", "azul");
    
    // ------ Muestra personas
    // ksort($array_people, SORT_LOCALE_STRING );
    // foreach ($array_people as $person => $num){
    //     pintaln($person . "\t".$num);
    // }
    // ------

    pintaln("Total personas distintas entre autores y realizadores: ".count($array_people), "blanco", 3);

    return $array_people;
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

function wordCountutf8($string) {
    return preg_match_all("/\p{L}[\p{L}\p{Mn}\p{Pd}'\x{2019}]*/u",$string,$matches,PREG_PATTERN_ORDER);
}

/**
 *
 */
function persisteListaPersonas(array $lista_personas)
{
    if (count($lista_personas) < 2){
        throw new Exception ("La lista de personas a persistir está vacía " . var_dump($lista_personas));
    }

    foreach ($lista_personas as $nombre => $num){
        persistePerson($nombre);
    }

    pintaln ("Lista de personas persistida");
}

function splitRelevantWordsUtf8($string){
    // $string = utf8_decode($string);
    $num_words = preg_match_all("/\p{L}[\p{L}\p{Mn}\p{Pd}'\x{2019}]*/u", $string, $matches, PREG_OFFSET_CAPTURE);
    if (!$num_words || $num_words < 1){      
        pintaln("\tString original - número de ocurrencias - array ocurrencias:", "rojo");
        var_dump($string);
        var_dump($num_words);
        var_dump($matches);      

        return false;     
        // throw new Exception ("splitRelevantWordsUtf8 - Hay un problema con la expresión regular");
    }

    // elimina elementos del array
    // $array_sin_broza = array_merge(array_diff($matches[0], array("y")));

    return $matches[0];
}

/**
 * persistePerson persiste una persona
 */
function persistePerson($nombre)
{
    $c = new Criteria();
    $c->add(PersonPeer::NAME, $nombre);
    if ($person = PersonPeer::doSelectOne($c)){
        pintaln("Persona " . $nombre . " ya existe, recuperada de la BD", "amarillo", 3);

        return $person;
    }

    $person = new Person();
    $person->setName($nombre);
    $person->setWeb(WEB_PERSON);
    // Necesario para que cree entradas en person_i18n
    $person->setCulture('es');
    $person->setHonorific(' '); 
    
    $person->save();
    pintaln("Persona " . $nombre . " persistida", "verde", 4);

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

    $langs = (I18N)? sfConfig::get('app_lang_array', array('es')) : array('es');
    foreach($langs as $lang){
        $role->setCulture($lang);
        $role->setName($nombre);
    }

    $role->save();
    pintaln("Rol " . $nombre . " persistido");

    return $role;
}

function borraRolesImportados($roles = array (ROL_AUTOR, ROL_REALIZADOR)){
    foreach ($roles as $nombre){
        $c = new Criteria();
        $c->add(RoleI18nPeer::NAME, $nombre);
        $role = RolePeer::doSelectWithI18n($c, 'es');
        if (count($role) != 1){
            throw new Exception("Existen: ". count($role) . " roles con nombre " . $nombre);
        }
        $role[0]->Delete();
        pintaln("Rol " . $nombre . " borrado");
    }
    
}

function asignaMmPersonRole($mm, $person, $role)
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
    pintaln("mm: " . $mm->getId() ."\tRole: " . $role->getId() . "\tPerson: " . 
        $person->getId() . "\tCreado correctamente", "verde", 3);

    return $mpr;
}

function seleccionaMmImportados($limit = 0, $offset = 0)
{
    pintaln("---- Seleccionando los objetos mm importados ----","azul", 3);
    $c = new Criteria();
    $c->addJoin(MmPeer::ID, UnedMediaOldPeer::MM_ID);
    if (0 != $limit) $c->setLimit($limit);
    if (0 != $offset) $c->setOffset($offset);

    return MmPeer::doSelect($c);
}

function seleccionaPersonImportadas()
{
    pintaln("---- Seleccionando las personas mm importados ----","azul", 3);
    $c = new Criteria();
    $c->add(PersonPeer::WEB, WEB_PERSON);
    
    return PersonPeer::doSelect($c);
}

function borraPersonImportadas()
{
    $people = seleccionaPersonImportadas();
    foreach ($people as $person){
        $person->delete();
    }
}

function borraMmPersonImportados()
{
// El primer if falla, creo que al añadir el WEB_PERSON en el join
    // if (0 != count(seleccionaPersonImportadas())){
    //     $c = new Criteria();
    //     $c->addJoin(MmPersonPeer::PERSON_ID, PersonPeer::ID);
    //     $c->addJoin(PersonPeer::WEB, WEB_PERSON);
       
    // } else {
    //     $c = new Criteria();
    //     $c->addJoin(MmPersonPeer::MM_ID, MmPeer::ID);
    //     $c->addJoin(MmPeer::ID, UnedMediaOldPeer::MM_ID);
    // }

    $c = new Criteria();
    $c->addJoin(MmPersonPeer::MM_ID, MmPeer::ID);
    $c->addJoin(MmPeer::ID, UnedMediaOldPeer::MM_ID);

    $mmps = MmPersonPeer::doSelect($c);
    if (0 == count($mmps)){
        pintaln("Warning: No hay MmPerson que borrar. ¿Es la primera vez que se ejecuta el script?", "rojo");

        return false;
    }
    
    foreach ($mmps as $mmp){
        $mmp->delete();
    }
}


/**
 * asignaListaMmListaPersonas - parte de mm y person ya importados en la BD.
 * Recorre la lista de Mm de la base de datos que se corresponden a uned_media_old
 * Parsea umo para obtener las personas de cada uno.
 * Asigna esas personas a los roles autor y editor importados.
 */
function asignaListaMmListaPersonas()
{
    $role_autor      = persisteRole(ROL_AUTOR);
    $role_realizador = persisteRole(ROL_REALIZADOR);
    $mms             = seleccionaMmImportados();
    
    foreach ($mms as $mm){
        $mm->setCulture('es');
        $c = new Criteria();
        $c->add(UnedMediaOldPeer::MM_ID, $mm->getId());
        $umo = UnedMediaOldPeer::doSelectOne($c);
 
        // Sólo para probar cuanto tarda en recorrer la BD con este subset
        // pintaln($umo->getOriginalId());
        // continue;
        if ("" != $umo->getAutor()){
            foreach (separaVariosNombres($umo->getAutor()) as $person){
                if ($parseada = parseaUnaPersona($person)){
                    $c = new criteria();
                    $c->add(PersonPeer::NAME,$parseada);
                    if ($person = PersonPeer::doSelectOne($c)){
                        asignaMmPersonRole($mm, $person, $role_autor);
                    } else {
                        throw new Exception ("Persona ". $parseada . " no encontrada en la BD");
                    }
                } 
                // continue - $person está vacía
            }
        }

        if ("" != $umo->getRealizador()){
            foreach (separaVariosNombres($umo->getRealizador()) as $person){
                if ($parseada = parseaUnaPersona($person)){
                    $c = new criteria();
                    $c->add(PersonPeer::NAME,$parseada);
                    if ($person = PersonPeer::doSelectOne($c)){
                        asignaMmPersonRole($mm, $person, $role_realizador);
                    } else {
                        throw new Exception ("Persona ". $parseada . " no encontrada en la BD");
                    }
                }
                // continue - $person está vacía
            }
        }
    } // end foreach
}

function compruebaNumeroPersonasImportadas()
{
    pintaln("Comprobando el número de personas a objetos mm desde umo y person", "blanco", 3);
    $testOK = true;
    $array_people = creaListaPersonas();
    $total_asignaciones_umo_people = 0;
    foreach ($array_people as $k => $v){
        $total_asignaciones_umo_people += $v;
    }
    
    $total_people_BD = PersonPeer::doCount(new Criteria())  ;
    if (count($array_people) == $total_people_BD){
        pintaln("Número de personas distintas en la BD = personas distintas en umo = ". $total_people_BD, "verde", 1);
    } else {
        pintaln("Error en el número de personas distintas", "rojo");
        pintaln("En umo hay " . count($array_people) . " y en la BD " . $total_people_BD, "rojo");
        $testOK = false;
    }   

    $total_asignados = MmPersonPeer::doCount(new Criteria());
    if ($total_asignaciones_umo_people == $total_asignados){
        pintaln("Total umo people = total asignados = " . $total_asignados, "verde", 1);
    } else {
        pintaln("Total umo people =\t". $total_asignaciones_umo_people, "rojo");
        pintaln("Total asignados =\t". $total_asignados, "rojo");  
        $testOK = false;
    }

    return $testOK;
}

/*
 * compruebaPresetsCreaPerfilesFormatMimetypes - Recorre todos los umos con presets no vacíos
 * comprueba que hay coherencia entre las extensiones y categorías (Audios, Videos)
 * crea un perfil por preset y extensión, nombre tipo "Importado_Orig_Vid_mp4"
 */
function compruebaPresetsCreaPerfilesFormatMimetypes($presets = array('original', 'alta', 'media', 'baja'))
{ 
    foreach ($presets as $preset){
        $preset = ucfirst($preset);
        $getter = 'getPreset' . $preset;
        $umos   = retrieveUmosFromPreset($preset);
        
        pintaln ("\nProcesando preset_" . $preset . " para comprobar coherencia\n", "azul");
        pintaln("Total de umos con preset_" . $preset . " = " . count($umos));

        $files = array();
        
        // reviso repetidos y añado categorias de umo (Videos, Audios) 
        foreach ($umos as $umo){
            $file_preset_actual = strtolower($umo->$getter());
            if (isset($files[$file_preset_actual])){
                $files[$file_preset_actual]['num'] = $files[$file_preset_actual]['num'] + 1;
                pintaln("El preset " . $preset . " " . $file_preset_actual . " se repite " 
                    . $files[$file_preset_actual]['num'] . " veces", "amarillo") ;

                if ($umo->getCategorias() != $files[$file_preset_actual]['categorias']){
                    throw new Exception("Categorías distintas");
                }

            } else{
                $files[$file_preset_actual]['num'] = 1;
                $files[$file_preset_actual]['categorias'] = $umo->getCategorias();
            }
            $umo->clearAllReferences(true);       
        }
        pintaln("Total de files distintos = " . count($files) . "\n");

        unset($umos);

        // agrupo extensiones para contarlas y comprobar que se corresponden con 1 sola categoría
        $extensiones = array();
        foreach ($files as $file => $v){
            $ext = extraeExtension($file);
            if (isset($extensiones[$ext])){
                $extensiones[$ext]['num'] = $extensiones[$ext]['num'] + 1;
                if ($v['categorias'] != $extensiones[$ext]['categorias']){
                    throw new Exception("La extensión " . $ext . "tiene categorías " 
                        . $extensiones[$ext]['categorias'] . " y " . $v['categorias']);
                }
            } else{
                $extensiones[$ext]['num'] = 1;
                $extensiones[$ext]['categorias'] = $v['categorias'];
            }
        }

        unset($files);
        
        // Creo un perfil por cada preset y extensión
        // Creo un format por cada extension
        $array_ext_perfil = array();
        foreach ($extensiones as $ext => $v){
            $nombre_perfil = nombrePerfil($preset, $v['categorias'], $ext);
            pinta("Extensión:\t" . $ext . "\tcategorias:\t" . $v['categorias'] 
                . "\ttotal:\t" . $v['num'] , "blanco");

            $perfil = persistePerfil($nombre_perfil, $preset, $v['categorias'], $ext);
            $array_ext_perfil[$ext] = $perfil;
            pintaln("");
            persisteFormat($ext);
            persisteMimetype($ext);

        }
    }// end foreach preset
} //end compruebaPresetsCreaPerfilesFormatMimetypes

function extraeExtension($file){
    return substr(strrchr($file, '.'), 1);
}

function borraFilesImportados()
{
    pintaln("Borrando Files importados", "azul");
    $c = new Criteria();
    $c->addJoin(UnedMediaOldPeer::MM_ID, MmPeer::ID);
    $c->addJoin(FilePeer::MM_ID, MmPeer::ID);
    $files = FilePeer::doSelectWithI18n($c);

    if (0 == count($files)){
        pintaln("Warning: no hay files que borrar. ¿Es la primera vez que se ejecuta el script?", "rojo");

        return false;
    }

    foreach  ($files as $file){
        pintaln("borrando file_id: " . $file->getId(), "blanco",4);
        $file->delete();
    }
    pintaln("Files borrados","verde");
}

function importaPresetsCreaFiles($presets = array('original', 'alta', 'media', 'baja'))
{
    $c = new Criteria();
    $c->add(LanguagePeer::COD, "ES");
    $language =  LanguagePeer::doSelectOne($c);

    foreach ($presets as $preset){
        $umos   = retrieveUmosFromPreset($preset);
        $getter = 'getPreset' . ucfirst($preset);
        pintaln ("Importando los files del preset: " . $preset);

        foreach ($umos as $umo){
            $categoria     = $umo->getCategorias();
            $path          = (PUNTO_MONTAJE_FILES) . trim($umo->$getter());
            $ext           = extraeExtension($path);
            $mimetype      = persisteMimetype($ext);
            $nombre_perfil = nombrePerfil($preset, $categoria, $ext);
            $url           = trim($umo->$getter());

            $perfil = retrievePerfilByName($nombre_perfil);
            $format = retrieveFormatByName($ext);

            // To Do - un retrievefilebyalgo para no crear de nuevo files
            $file = new File();

            $file->setMmId($umo->getMmId());
            $file->setLanguage($language);
            $file->setUrl($url);
            $file->setFile($path);
            $file->setPerfilId($perfil->getId());
            $file->setFormatId($format->getId());
            $file->setAudio($perfil->getAudio());
            $file->setMimeTypeId($mimetype->getId());

            //  TO DO - establecer Display según algún campo de uned_media_old 
            // ("denegar descarga", "estado de publicación"...)
            
            // La description se muestra en la vista de series al lado de cada
            // vídeo, así que mejor dejarla en blanco.
            // $description = 'File importado - preset ' . $preset;
            $description = '';
            $langs = (I18N)? sfConfig::get('app_lang_array', array('es')) : array('es');
            foreach($langs as $lang){
                $file->setCulture($lang);
                $file->setDescription($description);
            }
            $file->save();

            pintaln ("File ". $file->getId() . "\t" . $preset . " " . $path . " grabado", "verde",4);
        }
    }

}

function retrieveUmosFromPreset($preset)
{
    $c = new Criteria();
    switch (ucfirst($preset)) {
        case 'Original':
            $c->add(UnedMediaOldPeer::PRESET_ORIGINAL, '',  Criteria::NOT_EQUAL);    
            break;
        case 'Alta':
            $c->add(UnedMediaOldPeer::PRESET_ALTA, '',  Criteria::NOT_EQUAL);    
            break;
        case 'Media':
            $c->add(UnedMediaOldPeer::PRESET_MEDIA, '',  Criteria::NOT_EQUAL);    
            break;
        case 'Baja':
            $c->add(UnedMediaOldPeer::PRESET_BAJA, '',  Criteria::NOT_EQUAL);    
            break;
        default:
            throw new Exception ("Preset " . $preset . " no válido");
    }
    return UnedMediaOldPeer::doSelect($c);
}

function retrieveUmosWithValidcolumn($columna)
{
    //$c = new Criteria();
    $c = criteriaUmosWithMultimedia();
    switch (strtolower($columna)) {
        case 'thumbs':
            $c->add(UnedMediaOldPeer::THUMBS, '',  Criteria::NOT_EQUAL);   
            break;
        case 'autor':
            $c->add(UnedMediaOldPeer::AUTOR, '',  Criteria::NOT_EQUAL);   
            break;
        case 'realizador':
            $c->add(UnedMediaOldPeer::REALIZADOR, '',  Criteria::NOT_EQUAL);   
            break;
        case 'tematicas':
            $c->add(UnedMediaOldPeer::TEMATICAS, '',  Criteria::NOT_EQUAL);   
            break;
        case 'enlaces':
            $c->add(UnedMediaOldPeer::ENLACES, '',  Criteria::NOT_EQUAL);   
            break;
        case 'documentos_adjuntos':
            $c->add(UnedMediaOldPeer::DOCUMENTOS_ADJUNTOS, '',  Criteria::NOT_EQUAL);   
            break;
        case 'subtitulos':
            $c->add(UnedMediaOldPeer::SUBTITULOS, '',  Criteria::NOT_EQUAL);   
            break;
        default:
            throw new Exception ("Columna " . $columna . " no válida");
    }

    return UnedMediaOldPeer::doSelect($c);
}

function persistePerfil($nombre, $preset, $categoria, $ext, $display = false)
{
    if ($perfil = retrievePerfilByName($nombre)){
        pinta("\tPerfil: " .$nombre . " ya existe, recuperado de la BD", "amarillo", 2);

        return $perfil;
    }

    $streamserver = persisteStreamServerParaImportados();

    $audio  = ('Audios' == $categoria)? 1 : 0;
    $link   = ('Audios' == $categoria)? 'Audio' : 'Vídeo';
    $perfil = new Perfil();
    $perfil->setName($nombre);
    $perfil->setDisplay($display);
    $perfil->setWizard(0);
    $perfil->setMaster(0);
    $perfil->setFormat($ext);
    $perfil->setMimetype(UnedDesbrozatorHardcoded::getMimeType($ext));
    $perfil->setExtension($ext);  
    $perfil->setAudio($audio);
    $perfil->setApp('uned_desbrozator');
    $perfil->setStreamserverId($streamserver->getId());


    $descripcion = 'Importado preset_' . strtolower($preset) . ' ' . $categoria . ' ' . $ext ;
    $langs = (I18N)? sfConfig::get('app_lang_array', array('es')) : array('es');
    foreach($langs as $lang){
        $perfil->setCulture($lang);
        $perfil->setDescription($descripcion);
        $perfil->setLink($link);
    }

    $perfil->save();
    pinta("\tPerfil: " . $nombre . "\tpersistido", "verde", 2);

    return $perfil;
}


function nombrePerfil($preset, $categoria, $ext)
{
    return 'Importado_' . substr(ucfirst($preset), 0, 4) . "_"
            . substr($categoria, 0, 3) . '_' . $ext;    
}

function retrievePerfilByName($name)
{
    $c = new Criteria();
    $c->add(PerfilPeer::NAME, $name);
    $perfil = PerfilPeer::doSelectWithI18n($c, 'es'); 
    
    return  ($perfil) ? $perfil[0] : $perfil;
}

function retrieveFormatByName($name){
    $c = new Criteria();
    $c->add(FormatPeer::NAME, $name);
    
    return FormatPeer::doSelectOne($c);
}

function persisteFormat($ext)
{

    $c = new Criteria();
    $c->add(FormatPeer::NAME, $ext);
    if ($format = FormatPeer::doSelectOne($c)){
        pintaln("\tFormat\t" . $ext . " ya existe, recuperado de la BD", "amarillo" , 4);
    } else {
        $format = new Format();
        $format->setName($ext);
        $format->save();
        pintaln("\tFormat\t" . $ext . " persistido", "verde", 4);
    }

    return $format;
}

function persisteMimetype($ext)
{
    $c = new Criteria();
    $c->add(MimeTypePeer::NAME, $ext);
    if ($mimetype = MimeTypePeer::doSelectOne($c)){
        pintaln("\tMimetype\t" . $ext . " ya existe, recuperado de la BD", "amarillo" , 4);
    } else {
        $mimetype = new MimeType();
        $mimetype->setName($ext);
        $mimetype->setType(UnedDesbrozatorHardcoded::getMimeType($ext));
        $mimetype->save();
        pintaln("\tMimetype\t" . $ext . "\t" . $mimetype->getType() . " persistido", "verde", 3);
    }

    return $mimetype;
}

function borraPerfilesImportados($app ='uned_desbrozator')
{
    $c = new Criteria();
    $c->add(PerfilPeer::APP, $app);
    $perfiles = PerfilPeer::doSelectWithI18n($c, 'es');

    foreach ($perfiles as $perfil){
        pintaln("Borrando perfil: " . $perfil->getName(), "blanco", 4);
        $perfil->delete();
    }
}

function importaThumbsCreaPicMms()
{
    $umos = retrieveUmosWithValidcolumn('thumbs');

    // $c = new Criteria();
    // $c->add(UnedMediaOldPeer::THUMBS, "", Criteria::NOT_EQUAL);
    // $umos = UnedMediaOldPeer::doSelect($c);
    foreach ($umos as $umo){
        $thumb_paths = explode(",", $umo->getThumbs());
        foreach ($thumb_paths as $thumb_path){
            // Ojo, monto y enlazo resources dentro de mi /web/
            $thumb_path = trim($thumb_path);
            
            $pic    = persistePic($thumb_path);
            $mm     = MmPeer::retrieveByPK ($umo->getMmId());
            $pic_mm = asignaPicMm($pic, $mm);
        }
    }
    pintaln('Thumbs parseadas, pics creadas, ic_mm asignadas. No faltaba ni un puntoycoma.', "verde");
}

function persistePic($url){
    $c = new Criteria();
    $c->add(PicPeer::URL, $url);
    if ($pic = PicPeer::doSelectOne($c)){
        pintaln("Pic " . $url . " ya existe, recuperada de la BD", "amarillo" , 3);
    } else {
        $pic = new Pic();
        $pic->setUrl($url);
        $pic->save();
        pintaln("Pic " . $url . " persistida", "verde", 3);
    }

    return $pic;
}

function asignaPicMm($pic, $mm){
    $c = new Criteria();
    $c->add(PicMmPeer::PIC_ID, $pic->getId());
    $c->add(PicMmPeer::OTHER_ID, $mm->getId());
    if ($pic_mm = PicMmPeer::doSelectOne($c)){
        pintaln("pic_mm " . $pic->getId() . " , " . $mm->getId() .
         " ya existe, recuperada de la BD", "amarillo" , 3);

        return $pic_mm;
    }
    $pic_mm = new PicMm();
    $pic_mm->setPicId($pic->getId());
    $pic_mm->setOtherId($mm->getId());
    $pic_mm->save();
    pintaln("pic_mm " . $pic->getId() . " , " . $mm->getId() .
         " persistido", "verde" , 3);
    return $pic_mm;
}

function borraPicsImportadas($app ='uned_desbrozator')
{
    $c = new Criteria();
    $c->addJoin(UnedMediaOldPeer::MM_ID, MmPeer::ID);
    $c->addJoin(PicMmPeer::OTHER_ID, MmPeer::ID);
    $c->addJoin(PicMmPeer::PIC_ID, PicPeer::ID);

    $pics = PicPeer::doSelect($c);
    $pic_mms = PicMmPeer::doSelect($c);

    foreach ($pics as $pic){
        pintaln("borrando pic " . $pic->getId(), "blanco",4);
        $pic->delete();

    }
    pintaln("Pics importadas borradas","verde");
    // pic_mm también se borran en cascada.
}

/**
 * creaArbolTematicas - crea un nuevo arbol con las temáticas de uned_media_old
 * Necesita los mms ya importados (el campo umo->mm_id establecido)
 */
function creaArbolTematicas()
{
    $cat_raiz_tematicas = creaSubRaiz(RAIZ_CATEGORIAS_TEMATICAS);
    $umos = retrieveUmosWithValidcolumn('tematicas');

    $array_arbol_tematicas = array();
    foreach ($umos as $umo){
        $tematicas = parseaTematicas( $umo->getTematicas());      
       
        foreach ($tematicas as $tematica){
            $array_arbol_tematicas = actualizaArray($array_arbol_tematicas, $tematica);
        }
    }
    ksort($array_arbol_tematicas, SORT_LOCALE_STRING );

    // persiste la lista ordenada de categorías en la BD y crea array de acceso rápido
    $cod_prefix_int = 1;
    $array_tematicas_persistidas = array();
    foreach ($array_arbol_tematicas as $k => $v){
        $cod_prefix     = "T_".sprintf("%02s",$cod_prefix_int);      
        $array_tematicas_persistidas[$k] = persisteCategoria($k, $cat_raiz_tematicas, $cod_prefix);
        $cod_prefix_int++;
    }

    // Asigna temáticas a cada mm
    pintaln("Hay ". count($umos) . " umos con temáticas asignadas¡");
    foreach ($umos as $umo){
        $mm_id = $umo->getMmId();
        pintaln("mm_id: " . $mm_id . "\tañadido a las categorías:","blanco", 4);
        foreach (parseaTematicas($umo->getTematicas()) as $tematica){
            $cat_tematica = $array_tematicas_persistidas[$tematica];            
            $array_cat = $cat_tematica->addMmIdAndUpdateCategoryTree($mm_id);
            pintaln("\t" . $cat_tematica->getId() . "\t" . $cat_tematica->getCod() 
                . "\t" . $tematica, "verde",4);
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



function persisteCategoria($nombre, $padre, $cod_prefix = '')
{
    $c = new Criteria();
    $c->add(CategoryI18nPeer::NAME, $nombre);
    $c->add(CategoryPeer::COD, $cod_prefix);
    $c->addJoin(CategoryI18nPeer::ID, CategoryPeer::ID);
    $category = CategoryPeer::doSelectWithI18n($c, 'es');
    if (!$category){
        $category = createCategory($nombre, $padre->getId(), $cod_prefix);
        
        return $category;       

    } else if (count($category) > 1) {
        throw new Exception ("Hay más de una category con el título \"".$nombre."\"");
    }

    pintaln ("existe la categoría " . $nombre, "amarillo");
    return $category[0];
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
    if (NIVEL_DEBUG > 3) echo "Fecha a comprobar: " . $dvalue . "\tFormato: " . $dformat . "\tResultado: " . $string_fecha_hora_valido. "\n";

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

// copiado de import_csv.php
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
                if (NIVEL_DEBUG > 3) { 
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
            if (NIVEL_DEBUG > 2) {
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


/**
 * Usa las funciones copiadas de import_csv e importa los archivos.
 */
function compruebaImportaCsv($fuerza_borrado_uned_media_old = false)
{
    if ($fuerza_borrado_uned_media_old){
        borraTablasAPelo(array('uned_media_old'), true);
    }
    
    $umo_count = UnedMediaOldPeer::doCount(new Criteria());
    if (0 == $umo_count) {
        pintaln("Tabla uned_media_old vacía - importando todos los ficheros .csv desde " . 
            IMPORT_CSV_FOLDER, "azul");
        $csv_files = glob(IMPORT_CSV_FOLDER . '/*.csv');

        if (0 === count($csv_files)){
            throw new Exception ("No se encuentran ficheros ?.csv en " . IMPORT_CSV_FOLDER);           
        }

        foreach ($csv_files as $csv_file){
            pintaln("Procesando fichero " . $csv_file, "azul");
            parseCsv($csv_file);
        }
    } else{
        pintaln("Tabla uned_media_old poblada con importados", "azul", 1);
        reseteaUmoMmId();
    }
}

function creaListaEnlaces()
{
    $umos = retrieveUmosWithValidcolumn('enlaces');
    $lista_mm_enlaces = array();
    foreach ($umos as $umo){
        $string_enlaces = $umo->getEnlaces();
        $lista_mm_enlaces[$umo->getMmId()] = parseaEnlaces($string_enlaces);
    }

    return $lista_mm_enlaces;
}

function importaEnlaces()
{
    $lista_mm_enlaces = creaListaEnlaces();
    foreach ($lista_mm_enlaces as $mm_id => $array_enlaces){
        foreach ($array_enlaces as $array_enlace){       
            foreach ($array_enlace as $descripcion => $url){
                persisteLink($mm_id, $descripcion, $url);
            }
        }
        
    }
}

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
        pintaln("Link mm_id: " . $mm_id . "\turl:" . $url . " ya existe, recuperada de la BD", "amarillo", 4);

    } else {

        $link = new Link();
        $link->setMmId($mm_id);
        $link->setUrl($url);

        $langs = (I18N)? sfConfig::get('app_lang_array', array('es')) : array('es');
        foreach($langs as $lang){
            $link->setCulture($lang);
            $link->setName($descripcion);
        }
        $link->save();
        pintaln("Link mm_id: " . $mm_id . "\t" . $url . "\t" . $descripcion . " persistido", "verde", 4);
    }
    
    return $link;
}

function borraLinksImportados()
{
    $lista_mm_enlaces = creaListaEnlaces();
    foreach ($lista_mm_enlaces as $mm_id => $array_enlaces){
        foreach ($array_enlaces as $array_enlace){       
            foreach ($array_enlace as $descripcion => $url){
                $c = new criteria();
                $c->add(LinkPeer::MM_ID, $mm_id);
                $c->add(LinkPeer::URL, $url);
                if ($link = LinkPeer::doSelectOne($c)){
                    $link->delete();
                    pintaln("Link mm_id: " . $mm_id . "\turl:" . $url . " borrado", "blanco", 4);
                } else {
                    pintaln("Enlace no importado previamente (no se puede borrar) mm_id: " . $mm_id . "\turl:" 
                        . $url . "\tDescr.: " . $descripcion,"blanco",2 );
                    // Comprueba url repetidas en los enlaces de un mm
                    if (compruebaLinkRepetido($array_enlaces, $url)){
                        pintaln("No hay problema - el enlace estaba repetido en la BD de (los paquetes de la) UNED", "amarillo");
                    }
                }
                
            }
        }     
    }
}

function compruebaLinkRepetido($array_enlaces, $test_url)
{
    $repe = 0;
    foreach ($array_enlaces as $array_enlace){
        foreach ($array_enlace as $descripcion => $enlace){
            if ($test_url == $enlace){
                $repe++;
            }
        }
    }

    return (bool) ($repe > 1);
}

function parseaAdjuntos($string_adjuntos)
{
    $adjuntos = superExplode(' , ', $string_adjuntos);
    $array_adjuntos = array();
    foreach ($adjuntos as $adjunto) {
        // 0 = todo; 1 = path; 2 = número entre paréntesis
        preg_match('/(.*) (\(.*\))$/', $adjunto, $result);       
        
        if (count($result) != 3){
            var_dump($result);
            throw new Exception ("Error en la expresión regular procesando el adjunto " . $string_adjuntos);
        }

        $path             = trim($result[1]);
        $numero           = $result[2];
        $array_adjuntos[$path] = $numero;
    }
    return $array_adjuntos;
}

function creaListaAdjuntos()
{
    $umos = retrieveUmosWithValidcolumn('documentos_adjuntos');
    $lista_mm_adjuntos = array();
    foreach ($umos as $umo){
        $string_adjuntos = $umo->getDocumentosAdjuntos();
        $lista_mm_adjuntos[$umo->getMmId()] = parseaAdjuntos($string_adjuntos);
        // $lista_mm_adjuntos[$umo->getMmId()] = $string_adjuntos;
    }

    return $lista_mm_adjuntos;
}

function importaDocumentosAdjuntos()
{
    pintaln("Importando documentos_adjuntos como materials visibles", "azul");
    $lista = creaListaAdjuntos();

    foreach ($lista as $mm_id => $array_adjuntos){
        foreach ($array_adjuntos as $url => $descripcion){
            $ext = extraeExtension($url);

            if (UnedDesbrozatorHardcoded::checkExtensionErronea($url)){
                pintaln("El mm_id: " . $mm_id . " tiene un documento_adjunto html estropeado:","rojo");
                pintaln("\t". $url . "\t- No se importará","rojo");
            } else { 
                persisteMaterial($mm_id, $descripcion, $url);
            }
        }       
    }
}

function persisteMaterial($mm_id, $descripcion, $url, $display = 1)
{   
    $c = new Criteria();
    $c->add(MaterialPeer::MM_ID, $mm_id);
    $c->add(MaterialPeer::URL, $url);
    if ($material = MaterialPeer::doSelectOne($c)){
        pintaln("Material mm_id: " . $mm_id . "\turl:" . $url . " ya existe, recuperado de la BD", "amarillo", 4);        
    
    } else {
        $material = new Material();
        $material->setMmId($mm_id);
        $material->setUrl($url);
        $material->setDisplay($display);

        $mt = persisteMatType(extraeExtension($url));
        $material->setMatTypeId($mt->getId());

        $langs = (I18N)? sfConfig::get('app_lang_array', array('es')) : array('es');
        foreach($langs as $lang){
            $material->setCulture($lang);
            $material->setName($descripcion);
        }
        $material->save();
        pintaln("Material mm_id: " . $mm_id . "\t" . $url . "\t" . $descripcion . " persistido", "verde", 4);
    }
    
    return $material;
}

function persisteMatType($ext)
{
    $ext = strtolower($ext);
    $c = new Criteria();
    $c->add(MatTypePeer::TYPE, $ext);
    if ($mt = MatTypePeer::doSelectWithI18n($c,'es')){
        pintaln("MatType " . $ext . " ya existe, recuperado de la BD", "amarillo", 4);
        
        return $mt[0];

    } else {
        $mt = new MatType();
        $mt->setType($ext);
        $mt->setDefaultSel(0);
        $mt->setMimeType(UnedDesbrozatorHardcoded::getMimeType($ext));

        $langs = (I18N)? sfConfig::get('app_lang_array', array('es')) : array('es');
        foreach($langs as $lang){
            $mt->setCulture($lang);
            $mt->setName(UnedDesbrozatorHardcoded::descripcionMimetype($ext));
        }

        $mt->save();
        pintaln("MatType " . $ext . "\tpersistido", "blanco", 2);
    }

    return $mt;
}

// TO DO - borrar subtitulos y otros materials que no sean documentos adjuntos
function borraMaterialsImportados()
{
    pintaln("Borrando materials importados de documentos_adjuntos si los hubiese","azul");
    $lista_adjuntos = creaListaAdjuntos();
    foreach ($lista_adjuntos as $mm_id => $array_adjuntos){
        foreach ($array_adjuntos as $url => $descripcion){
            if (UnedDesbrozatorHardcoded::checkExtensionErronea($url)){
                pintaln("Material erróneo no importado, saltando el borrado " . $url,"gris",4);
            } else {
                borraMaterial($mm_id, $url);
            } 
        }
    }

    pintaln("Borrando materials importados de subtitulos si los hubiese","azul");
    $lista_subtitulos = creaListaSubtitulos();
    foreach ($lista_subtitulos as $mm_id => $url){
        borraMaterial($mm_id, $url);        
    }
}

function borraMaterial($mm_id, $url)
{
    $c = new Criteria();
    $c->add(MaterialPeer::MM_ID, $mm_id);
    $c->add(MaterialPeer::URL, $url);
    if ($material = MaterialPeer::doSelectOne($c)){
        $material->delete();
        pintaln("Material mm_id: " . $mm_id . "\t" . $url . "\tborrado", "blanco", 4);

    } else {
        pintaln("No encuentro material mm_id: " . $mm_id . "\turl:" . $url ,"rojo",2);
    }
}

function importaSubtitulosComoMaterials()
{
    pintaln("Importando subtitulos como materials no visibles", "azul");
    $lista = creaListaSubtitulos();

    foreach ($lista as $mm_id => $url){

        if (UnedDesbrozatorHardcoded::checkExtensionErronea($url, "extension_subtitulos_erronea")){
            $descripcion = "Archivo pdf";
            $display     = 1;
            pintaln("Archivo " . $url . " incorrectamente catalogado como subtítulos","rojo");
            pintaln("Se persistirá como material convencional - " . $descripcion, "rojo");

        } else {
            $descripcion = "Subtítulos";
            $display     = 0;
        }
        persisteMaterial($mm_id, $descripcion, $url, $display);
    }

}

function creaListaSubtitulos()
{
    $umos = retrieveUmosWithValidcolumn('subtitulos');
    $lista_subtitulos = array();

    foreach ($umos as $umo){
        $url = str_replace('/deliverty/demo', '', $umo->getSubtitulos());
        $lista_subtitulos [$umo->getMmId()] = $url;
    }

    return $lista_subtitulos;
}

function pintaListaTematicas()
{
    $cat_raiz_tematicas = creaSubRaiz(RAIZ_CATEGORIAS_TEMATICAS);
    $categorias = $cat_raiz_tematicas->getChildren();
    pintaln("\nLista de temáticas\n", "blanco");
    foreach ($categorias as $categoria){
        $categoria->setCulture('es');
        pintaln($categoria->getName(), "blanco");
    }
}

/**
 * actualizaArray - recibe elementos y crea array elemento - nº de repeticiones
 */
function actualizaArray(array $array, $clave)
{
    if (isset($array[$clave])){
        $array[$clave] = $array[$clave] + 1;
    } else{
        $array[$clave] = 1;
    }

    return $array;
}

function asignaUnescoATematicas()
{
    $raiz_cat_tematicas = creaSubRaiz(RAIZ_CATEGORIAS_TEMATICAS);
    $raiz_cat_unesco    = creaSubRaiz(RAIZ_CATEGORIAS_UNESCO);

    $cat_tematicas    = $raiz_cat_tematicas->getChildren();
    $cat_unescos      = $raiz_cat_unesco->getChildren();
    // array de acceso rápido
    $array_cod_cat_unesco = array();
    foreach ($cat_unescos as $cu){
        $array_cod_cat_unesco [$cu->getCod()] = $cu;
    }

    foreach ($cat_tematicas as $cat_tematica){
        $tematica = $cat_tematica->getName();
        if ($array_cod_unesco = UnedDesbrozatorHardcoded::tematicaUnesco($tematica)){
            // set unesco

            pintaln("DEBUG - A: " . $tematica . " le corresponde: " . implode('-',$array_cod_unesco),"gris",4);
            foreach ($cat_tematica->getMms() as $mm){
                foreach ($array_cod_unesco as $cod_unesco){
                    $cat_unesco = $array_cod_cat_unesco[$cod_unesco];
                    pintaln("Asignando la categoría unesco " . $cat_unesco->getName() 
                        . " al mm_id: " . $mm->getId(), "blanco", 4);
                    $cat_unesco->addMmIdAndUpdateCategoryTree($mm->getId());
                }
            }

        } else {
            pintaln("\tTemática: ". $tematica . " no tiene unesco", "amarillo", 3);
        }
    }
}

function borraPubChannelMmImportados()
{
    pintaln("Borrando PubChannelMms", "azul");
    $c = new Criteria();
    $c->addJoin(UnedMediaOldPeer::MM_ID, MmPeer::ID);
    $c->addJoin(PubChannelMmPeer::MM_ID, MmPeer::ID);
    $pcmms = PubChannelMmPeer::doSelect($c);

    if (0 == count($pcmms)){
        pintaln("Warning: no hay PubChannelMms que borrar. ¿Es la primera vez que se ejecuta el script?", "rojo");

        return false;
    }

    foreach  ($pcmms as $pcmm){
        pintaln("borrando pubChannelMm pub_channel_id: " . $pcmm->getPubChannelId() 
            . "mm_id: " . $pcmm->getMmId() , "blanco",4);
        $pcmm->delete();
    }
    pintaln("PubChannelMms borrados","verde");
}

function publicaMmsImportados()
{
    $c = new Criteria();
    $c->add(PubChannelPeer::NAME, 'WebTV');
    $pc = PubChannelPeer::doSelectOne($c);

    $c= new Criteria();
    $c->add(BroadcastPeer::NAME, 'pub');
    $broadcast = BroadcastPeer::doSelectOne($c);

$i = 0;
    $mms = MmPeer::doSelect(new Criteria());

    // updatePubChannels($pub_channels_select) - usa un array como entrada

    foreach ($mms as $mm){
        $i++;
        $mm->setBroadcastId($broadcast->getId());
        (ACTUALIZA_INDICES_LUCENE)? $mm->save() : $mm->saveInDB();

        $pcm = new PubChannelMm();
        $pcm->setPubChannelId($pc->getId());
        $pcm->setMmId($mm->getId());
        $pcm->setStatusId(1);
        $pcm->save();
        if ($i > 19) break;
    }
}

function creaNodoseriesVariosParaMmsSinSubcategoria()
{
    $tree_array           = CategoryPeer::buildTreeArray();
    $nodo_raiz_nodoseries = encuentraNodoCategoriaEnTreeArray(RAIZ_NODOSERIES_UNED, $tree_array[0]);
    $cat_raiz_nodoseries  = creaSubRaiz(RAIZ_NODOSERIES_UNED);

    $total = 0;
    foreach ($nodo_raiz_nodoseries['children'] as $nodo_categoria){
        $categoria = $nodo_categoria['node']; 
        $nombre    = $nodo_categoria['node']->getName();     
        $num_mm    = $nodo_categoria['node']->getNumMm();     
        pintaln($num_mm . "\t" . $nombre, "verde", 4);

        $total2 = 0;
        $mms_con_subcat = array();
        foreach ($nodo_categoria['children'] as $nodo2){
            $nombre2   = $nodo2['node']->getName();     
            $num_mm2   = $nodo2['node']->getNumMm();
            $mms_nodo2 = $nodo2['node']->getMms();
            
            pintaln ("\t" . $num_mm2 . "\t" . $nombre2, "blanco", 4);
            
            $mms_con_subcat = array_merge($mms_con_subcat, $mms_nodo2);
            $total2 += $num_mm2;
        }

        if ($total2 == $num_mm || $total2 == 0){
            pintaln("\t\tTodos tienen nodoserie", "azul", 4);
        } else {
            pintaln("\t" . "Hay " . ($num_mm - $total2) . " mms sin categoría principal" , "amarillo",4);

// Como las categorias de nivel 1 tienen cod = NS_123 y las de nivel 2 empiezan en NS_123_01
// decido ponerle a las categorias de varios el código NS_123_00

            $nodoserie_varios = createCategory($categoria->getName(). NODOSERIE_VARIOS,
                                    $categoria->getId(), $categoria->getCod() . '_00') ;

            $mms_categoria1    = $nodo_categoria['node']->getMms();
            $medio_huerfanitos = super_array_diff($mms_categoria1, $mms_con_subcat);
            foreach ($medio_huerfanitos as $mm_huerfanito){

                pintaln($mm_huerfanito->getDescription() , "blanco",4);
                $nodoserie_varios->addMmId($mm_huerfanito->getId());
                // function createCategory($name, $id_parent, $cod_prefix = '')

            }
            
            // busca mms de $nodo_categoria['node'] que no tengan más categorías del arbol de nodoseries
        }


        $total += $num_mm;
    }
    pintaln("Total: ". $total, "verde", 4);

// NODOSERIE_VARIOS
}

// Ojo, el orden importa, el primer array tiene que contener al segundo.
function super_array_diff($array1, $array2){
    if (count($array1) < count($array2)){
        throw new Exception("super_array_diff con array1 < array2");
    }
    pintaln("array1: " . count($array1),"blanco",4);
    if (!is_array($array1) || !is_array($array2) || empty($array1) || empty($array2)){
        throw new exception ("Problema al comparar dos arrays de mms incorrectos");
    }

    foreach ($array2 as $k2 => $mm2){
        foreach ($array1 as $k1 => $mm1){
            if ($mm2->getId() == $mm1->getId()){
                unset($array1[$k1]);
            }
        }
    }

    return array_values($array1);
}

function pintaEstadisticasFormats()
{
    $formats = FormatPeer::doSelect(new Criteria());
    foreach ($formats as $format){
        $c = new Criteria();
        $c->add(FilePeer::FORMAT_ID, $format->getId());
        $num_files_format_actual = FilePeer::doCount($c);
        $c = new Criteria();
        $c->add(FilePeer::FORMAT_ID, $format->getId());
        $c->addGroupByColumn(FilePeer::MM_ID);
        $mms_format_actual = FilePeer::doSelect($c);
        $num_mms_format_actual = count($mms_format_actual);
        pintaln("Formato: " . $format->getName() . " - " . 
            sprintf("%4u", $num_mms_format_actual) . " mms y " .
            sprintf("%4u", $num_files_format_actual) . " files");
    }
}


function compruebaFechas()
{
    $csv_files = glob(IMPORT_CSV_FOLDER . '/?.csv');
    foreach ($csv_files as $csv_file){
        pintaln("Proceando fichero " . $csv_file, "azul");
        parseCSVFechas($csv_file);
    }
}

function parseCSVFechas($csv)
{
    $fila = 1;
    $array_fechas = array();
    if (($gestor = fopen($csv, "r")) !== FALSE) {
        while (($datos = fgetcsv($gestor, 0, ";")) !== FALSE) {
            $numero = count($datos);
            if ($numero == 36 ){
                if (trim($datos[0]) == "Id") {
                    continue;
                }

                $array_fecha = array(
                    $datos[1],
                    $datos[2],
                    $datos[11],
                    $datos[16],
                    $datos[17],
                    processDateTime($datos[1]),
                    processDateTime($datos[2]),
                    processDateTime($datos[11], array("d/m/Y")),
                    // Algunas fechas son "13/06/2011 20:00" y otras "13/06/2011"
                    processDateTime($datos[16], array("d/m/Y", "d/m/Y H:i")),
                    processDateTime($datos[17], array("d/m/Y", "d/m/Y H:i")) );                

                $array_fechas[] = $array_fecha;
        
            } else {
                echo "\n Última fila válida = \n". print_r($contenido_anterior) . "\n";
                echo "\n<error>ERROR: en la linea $fila, tiene $numero de elementos ($datos[0])</error>\n\n";
            var_dump($datos);
            }
            if ($fila % 100 == 0 ) echo "Fila " . $fila . "\n";
            $contenido_anterior = $datos;
            
            $fila++;        
        } // end while
        fclose($gestor);
    } else {
        echo "\n<error>ERROR: in fopen($csv)</error>\n\n";
    }

    pintaln("\n\n\nFecha creacion\t\tFecha actualizacion\tAño\t\tFechaInicio\t\tFechaFin");
    
    $i = 0;
    foreach ($array_fechas as $fecha){
        $cebreado = ($i&1) ? "blanco": "amarillo";
        if (strlen($fecha[3]) < 11) $fecha[3] .= "\t";
        if (in_array(209, $fecha)) {
            pintaln("Ojo, valor raro en el original_id=".$fecha[10], "rojo");
        // if (in_array(209, $fecha) || in_array(2020, $fecha)) pintaln("Ojo, valor raro en el original_id=".$fecha[10], "rojo");
        pintaln($fecha[0] . "\t" . $fecha[1] . "\t" . $fecha[2] . "\t\t" . $fecha[3] . "\t" . $fecha[4], $cebreado);
        pintaln($fecha[5] . "\t" . $fecha[6] . "\t" . $fecha[7] . "\t" . $fecha[8] . "\t" . $fecha[9], $cebreado);
        $i++;
        }
    }
}


function guardaCsvUnescos()
{
    $tree_array = CategoryPeer::buildTreeArray();
    $nodo_raiz_unesco = encuentraNodoCategoriaEnTreeArray(RAIZ_CATEGORIAS_UNESCO, $tree_array[0]);

    
    $string_csv  ='"Raíz";"Nº Elementos";';
    for ($i=1; $i <= NUM_PASADAS; $i++){
        $string_csv .= '"cód. Unesco";"Nombre Unesco ' . $i . '";"Nº Elementos";';
    }
    $string_csv .=  "\n";
        echo $string_csv;
    $string_csv .= csvArbolUnescos($nodo_raiz_unesco);
    echo $string_csv;
    file_put_contents('arbol_bd_unescos.csv', $string_csv);
}

function csvArbolUnescos(array $elemento)
{
    $l          = $elemento['level'] - 1; //descuenta root, 0 = UNED.
    $name       = $elemento['node']->getName();
    $cod        = $elemento['node']->getCod();
    $num_mm     = $elemento['node']->getNumMm();
    $inicio     = str_repeat('"";"";', $l);
    $intermedio = str_repeat('"";"";', 2-$l);
    $final      = "\n";

    $str_csv = $inicio . '"' . $cod . '";"' . $name . '";"' . $num_mm . '";'. $intermedio . $final;
    foreach ($elemento['children'] as $sub_elemento){
     $str_csv .= csvArbolUnescos($sub_elemento);
    }

    return $str_csv;
}

function pintaAsignacionTematicasUnescoGuardaCsv()
{
    $tematicas_unesco = UnedDesbrozatorHardcoded::$array_tematicas_unesco;
    $unesco_2         = UnedDesbrozatorHardcoded::$unesco_2;

    $string_csv      = '"Temática UNED";"cód. Unesco";"nombre Unesco";"cód. Unesco";"nombre Unesco";' . "\n";
    $string_terminal = '';

    foreach ($tematicas_unesco as $tematica => $unescos){
        $string_terminal .= $tematica;
        $string_csv      .= '"'.$tematica . '";';
        if (!is_array($unescos)) $unescos = array($unescos);
        if ('' == $unescos[0]){
            $string_terminal .= ': ------';
            $string_csv      .= '"-----";';
        } else {
            $string_terminal .= ':';
            foreach ($unescos as $cod_unesco){
                $string_terminal .= " " . $cod_unesco . " - " . $unesco_2[$cod_unesco] . ";";
                $string_csv      .= '"' . $cod_unesco . '";"' . $unesco_2[$cod_unesco] . '";';
            }
        }
            $string_terminal .= "\n";
            $string_csv      .= "\n"; 
    }
    echo $string_terminal;
    // echo $string_csv;
    file_put_contents("correspondencia_tematicas_uned_codigos_unesco.csv", $string_csv);
}

/**
 * creaPicAudioAsignaMms - 
 */
function creaPicAudioAsignaMms($audio_pic_url = '/images/sound_bn.png')
{

    pintaln("Asignando pic de audio " . $audio_pic_url . " a los mms con files de audio sin pic previa", "azul");
    $pic                     = persistePic($audio_pic_url);   
    $mms_con_audios_sin_pics = retrieveFilesWithoutPics(array('wav','mp3','wma'));
    
    pintaln("Pic " . $audio_pic_url . " persistido con id: " . $pic->getId());
    $i = 0;
    foreach ($mms_con_audios_sin_pics as $mm){
        if (NIVEL_DEBUG > 1 &&  $i % 100 == 0) pintaln("Añadiendo pic de audio para mm id ".$mm->getId(), "blanco");
        $pic_mm = asignaPicMm($pic, $mm);
        $i++;
    }
}

function borraPicYPicMms($pic_url = '/images/sound_bn.png')
{
    pintaln("Borrando pics de objetos de audio");
    $c = new Criteria();
    $c->add(PicPeer::URL, $pic_url);
    $pic = PicPeer::doSelect($c);
    if (count($pic) > 1){
        throw new Exception("Hay varias pics con url =" . $pic_url . " - en total: ". count($pic));
    } else if ($pic) {
        pintaln("Encontrada una pic con url = " . $pic_url);
        $pic = $pic[0];
    } else {
        pintaln("No encontrada ninguna pic con url =" . $pic_url , "amarillo");

        return;
    }

    $c->addJoin(PicMmPeer::PIC_ID, PicPeer::ID);
    $pic_mms = PicMmPeer::doSelect($c);

    pintaln("Borrando un total de: " . count($pic_mms) . "pic_mms");

    foreach ($pic_mms as $pic_mm){
        $pic_mm->delete();
    }

    $pic->delete();
}

/**
 * retrieveFilesWithoutPics devuelve todos los archivos de las extensiones (format)
 * pasadas por parámetro que no tengan pics asignadas.
 * No busca sólo los importados, devuelve todos.
 */
function retrieveFilesWithoutPics($formats = array('wav','mp3','wma'))
{
    if (!is_array($formats)) $formats = array($formats);
    $c = new Criteria();
    $c->add(FormatPeer::NAME, $formats, Criteria::IN);
    $c->addJoin(FilePeer::FORMAT_ID, FormatPeer::ID);
    $c->addJoin(MmPeer::ID, FilePeer::MM_ID);
    // Buscar los que no tengan pics, left join + null equivale a un minus
    $c->addJoin(MmPeer::ID, PicMmPeer::OTHER_ID, Criteria::LEFT_JOIN);
    $c->add(PicMmPeer::PIC_ID, null);

    $c->setDistinct();

    return MmPeer::doSelect($c);
}

function retrieveMmsWithoutPic()
{
    $total_mms = UnedMediaOldPeer::doCount(new Criteria());
    pintaln("Hay un total de: " . $total_mms . " mms");

    $c = new Criteria();
    $c->addJoin(MmPeer::ID, PicMmPeer::OTHER_ID, Criteria::LEFT_JOIN);
    $c->add(PicMmPeer::PIC_ID, null);
    $c->setDistinct();


    $mms_sin_pic = MmPeer::doSelect($c);
    pintaln("De esos, " . count($mms_sin_pic) . " no tienen foto");

    return $mms_sin_pic;
}

function persisteStreamServerParaImportados(){
    $ip = '127.0.0.1';
    $name = 'LocalhostUNED';
    $description = 'Archivos importados de UNED y montados por nfs';
    $dir_out = '/var/www/pumukitunedtv/web/resources';
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

function retrieveUmosWithMultimedia()
{
    $c = new Criteria();
    $c1 = $c->getNewCriterion(UnedMediaOldPeer::PRESET_ORIGINAL, '', Criteria::NOT_EQUAL);
    $c2 = $c->getNewCriterion(UnedMediaOldPeer::PRESET_ALTA, '',  Criteria::NOT_EQUAL);    
    $c3 = $c->getNewCriterion(UnedMediaOldPeer::PRESET_MEDIA, '',  Criteria::NOT_EQUAL); 
    $c4 = $c->getNewCriterion(UnedMediaOldPeer::PRESET_BAJA, '',  Criteria::NOT_EQUAL);
    $c1->addOr($c2);
    $c1->addOr($c3);
    $c1->addOr($c4);
    $c->add($c1);
    
    return UnedMediaOldPeer::doSelect($c);
}

function criteriaUmosWithMultimedia()
{
    $c = new Criteria();
    $c1 = $c->getNewCriterion(UnedMediaOldPeer::PRESET_ORIGINAL, '', Criteria::NOT_EQUAL);
    $c2 = $c->getNewCriterion(UnedMediaOldPeer::PRESET_ALTA, '',  Criteria::NOT_EQUAL);    
    $c3 = $c->getNewCriterion(UnedMediaOldPeer::PRESET_MEDIA, '',  Criteria::NOT_EQUAL); 
    $c4 = $c->getNewCriterion(UnedMediaOldPeer::PRESET_BAJA, '',  Criteria::NOT_EQUAL);
    $c1->addOr($c2);
    $c1->addOr($c3);
    $c1->addOr($c4);
    $c->add($c1);

    return $c;
}

function creaSeriesParaNodoseriesFinales()
{
    $tree_array = CategoryPeer::buildTreeArray();
    $nodo_raiz_nodoseries = encuentraNodoCategoriaEnTreeArray(RAIZ_NODOSERIES_UNED, $tree_array[0]);
    $array_cod_nombre_ns  = recorreTreeArrayBuscandoNodoseriesFinales($nodo_raiz_nodoseries, $padres_acumulados = '');
    foreach ($array_cod_nombre_ns as $cod => $nombre_ns){
        $serial   = creaSerie($nombre_ns);
        $category = CategoryPeer::retrieveByCode($cod);
        $mms      = $category->getMms();
        pintaln("Asignando " . count($mms) . " mms a serie " . $nombre_ns, "blanco");
        addMmsToSerial($mms, $serial); 
    }
}

/**
 * recorre recursivamente el treearray y pinta de manera distinta nodoseries padres y finales
 */
function recorreTreeArrayBuscandoNodoseriesFinales($elemento, $padres_acumulados = '')
{
    $name   = $elemento['node']->getName();
    $indenta = str_repeat("\t", $elemento['level']);
    if (count($elemento['children']) == 0){       
        pintaln($indenta. $padres_acumulados . $name . "] - NS final, mover hijos", "blanco", 2);
        // Ver creaNodoseriesVariosParaMmsSinSubcategoria()
        // Evitar nombre "Nodoserie 1 - Nodoserie 1 - Varios sin subcategoria"
        $nombre_padre_nombre_hijo = (str1TerminaEnStr2($name, NODOSERIE_VARIOS))? $name : $padres_acumulados . $name;

        return array( $elemento['node']->getCod() => $nombre_padre_nombre_hijo);
    } else {
        // pintaln($indenta . $padres_acumulados . $name . "- NS Padre", "blanco");
        $array_nodoseries_finales = array();
        foreach ($elemento['children'] as $child){
            $padres_acumulados = ($name == RAIZ_NODOSERIES_UNED)? '' : $name . " - ";
            $array_nodoseries_finales = array_merge($array_nodoseries_finales, recorreTreeArrayBuscandoNodoseriesFinales($child, $padres_acumulados));
        }

        return $array_nodoseries_finales;
    }
}

function str1TerminaEnStr2($str1, $str2)
{
    if (strlen($str1) < strlen($str2)) return false;

    return substr_compare($str1, $str2, -strlen($str2), strlen($str2)) === 0;
}
// Para corregir el que se haya creado originalmente con metacategory = true y no asignaba ni contabilizaba mms totales.
function asignaCategoriaRaizUnescoAMmms()
{
    $cat_unesco = creaSubRaiz(RAIZ_CATEGORIAS_UNESCO);
    $cat_unesco->setMetacategory(false);
    $cat_unesco->save();
    foreach ($cat_unesco->getChildren() as $subcat_unesco){
        pintaln("Procesando cateogría unesco: " . $subcat_unesco->getName());
        foreach ($subcat_unesco->getMms() as $mm){
            pintaln("\tAñadiendo el mm id: " . $mm->getId() . " a la raiz unesco", "blanco");
            $cat_unesco->addMmId($mm->getId());
        } 
    }
}

// SERIE_IMPORTADOS
function creaSerie($titulo)
{
    $c = new Criteria();
    $c->add(SerialI18nPeer::TITLE, $titulo);

    if (!$serial = SerialPeer::doSelectWithI18n($c, 'es')){
        // crea serie
        $serial = new Serial();
        $serial->setCulture('es');
        $serial->setPublicdate("now"); // de momento...
        $serial->setTitle($titulo);
        $serial->setSerialTypeId(SerialTypePeer::getDefaultSelId());
        $serial->setSerialTemplateId(1);
        $serial->save();

        echo "Creada serie " . $serial->getTitle() . "\n";
        
        return $serial;

    } else if (count($serial) > 1) {
        throw new Exception ("Hay más de una serie con el título " . $titulo);

    } else {
        echo"La serie " . $serial[0]->getTitle() . " ya existe, la recupero de la BD\n";

        return $serial[0];
    }   
}

function addMmsToSerial($mms, $serial)
{
    $serial_id = $serial->getId();
    foreach ($mms as $mm) {
        if ($mm->getSerialId() == $serial_id){
            pintaln("El mm con id: " . $mm->getId() . " ya estaba dentro de la serie " 
                . $serial->getTitle() . ", saltándolo." , "amarillo");

            continue;
        }

        $mm->setSerialId($serial_id);
        (ACTUALIZA_INDICES_LUCENE)? $mm->save() : $mm->saveInDB();
        pintaln("Asignado el mm con id: " . $mm->getId() . " a la serie " . $serial->getTitle());
    }
}

function retrieveMmsWithAnyCategoryNames ($names)
{
    if (!is_array($names)) $names = array($names);

    $c = new Criteria();
    $c->add(CategoryI18nPeer::NAME, $names, Criteria::IN);
    $c->addJoin(CategoryI18nPeer::ID, CategoryMmPeer::CATEGORY_ID);
    $c->addJoin(CategoryMmPeer::MM_ID, MmPeer::ID);

    return MmPeer::doSelect($c);
}

function creaNuevasSeriesMueveMms()
{
    $nuevas_series = array( 
        'UNED Editorial' => 'UNED EDITORIAL', // temática
        'INTECCA' => 'INTECCA', // Nodoserie
        'El Faro Emigrado' => 'EL FARO EMIGRADO', // Nodoserie
        'ASECIC - UNED' => 'ASECIC', // Nodoserie
        'Sala Virtual de Prensa - UNED' => 'SALA DE PRENSA VIRTUAL', // temática
        'I+D+i (Ciencia)' => 'I+D+I' // Nodoserie
        );
    
    foreach ($nuevas_series as $nombre_nueva_serie => $tematica_nueva_serie){
        pintaln("Recuperando los mms de la etiqueta " . $tematica_nueva_serie);
        $mms = retrieveMmsWithAnyCategoryNames($tematica_nueva_serie);
        pintaln("Creando serie " . $nombre_nueva_serie, "azul");
        $serial = creaSerie($nombre_nueva_serie);
        pintaln("Asignando mms a serie");
        addMmsToSerial($mms, $serial);
    }
}

function borraNuevasSeriesMueveMmsSerieUnica()
{
    $serial_unica = serieUnicaImportados();
    $serials = SerialPeer::doSelect(new Criteria());
    foreach ($serials as $serial){
        if ($serial->getId() != $serial_unica->getId()){
            $mms = $serial->getMms();
            pintaln("Moviendo " . count($mms) . "\tmms de la serie " . $serial->getTitle(), "blanco");
            addMmsToSerial($mms, $serial_unica);
            pintaln("Borrando serie " . $serial->getTitle());
            $serial->delete();
        }
    }  
}

        // ¿quitar mms que no estén en $mms y moverlos a otra serie?
/**
 * Subquery que devuelve los umos que no estén etiquetados con una categoría
 * Revisar que esté asignada a todos los objetos que queremos (p.ej. metacategory = false)
 */
function retrieveMmsWithoutCategory($category)
{
    $c = new Criteria();
    $subQuery = "uned_media_old.mm_id NOT IN (SELECT mm_id FROM category_mm WHERE category_id=" . $category->getId() . ")";
    $c->add(UnedMediaOldPeer::ID, $subQuery, Criteria::CUSTOM);
    $c->addJoin(MmPeer::ID, UnedMediaOldPeer::MM_ID);

    return MmPeer::doSelect($c);
}

function procesaMmsSinUnescoGeneraInformeCsv()
{
    $csv_filename     = "proporcion_tematicas_uned_sin_unesco.csv";
    $csv_filename_ns  = "proporcion_nodoseries_sin_unesco.csv";
    $cat_unesco       = creaSubRaiz(RAIZ_CATEGORIAS_UNESCO);
    $cat_tematicas    = creaSubRaiz(RAIZ_CATEGORIAS_TEMATICAS);
    $cat_nodoseries   = creaSubRaiz(RAIZ_NODOSERIES_UNED);
    $mms_sin_cat      = retrieveMmsWithoutCategory($cat_unesco);
    pintaln("Hay: " . count($mms_sin_cat) . " mms sin categoria");
    $sin_tematica     = 0;
    $sin_nodoserie    = 0;
    $array_tematicas  = array();
    $array_nodoseries = array();

    pintaln("\n Contando las temáticas y nodoseries de los objetos sin unesco\n", "blanco");
    foreach ($mms_sin_cat as $mm){
        $tematicas = $mm->getCategories($cat_tematicas);
        if (0 == count($tematicas)){
            $sin_tematica++;
        }
        foreach ($tematicas as $tematica){
            $array_tematicas = actualizaArray($array_tematicas, $tematica->getName());
        }

        $nodoseries = $mm->getCategories($cat_nodoseries);
        if (0 == count ($nodoseries)){
            $sin_nodoserie++;
        }
        foreach ($nodoseries as $ns){
            $array_nodoseries = actualizaArray($array_nodoseries, $ns->getCod());
        }
    }

    pintaln("\n Analizando las temáticas de los objetos sin unesco\n", "blanco");
    $fp = fopen($csv_filename, 'w');
    fputcsv($fp, array("Temática UNED", "Mms sin unesco", "Total mms"));
    foreach ($array_tematicas as $tematica => $num){
        $cat       = CategoryPeer::retrieveByName($tematica, $cat_tematicas);
        $total_cat = $cat->getNumMm();
        pintaln("La temática uned: " . $tematica . " tiene " .
            $num . " de " . $total_cat . " objetos sin unesco." );
        fputcsv($fp, array($tematica, $num, $total_cat));
    }
    pintaln("Hay " . $sin_tematica . " objetos válidos sin temática asignada");
    fputcsv ($fp, array("Sin temática", $sin_tematica, $sin_tematica));
    fclose($fp);

    pintaln("\n Analizando las nodoseries de los objetos sin unesco\n", "blanco");
    $fp = fopen($csv_filename_ns, 'w');
    fputcsv($fp, array("Nodoserie nivel 1", "Nodoserie nivel 2", "Mms sin unesco", "Total mms"));
    foreach ($array_nodoseries as $cod_ns => $num){
        $cat       = CategoryPeer::retrieveByCode($cod_ns);
        $total_cat = $cat->getNumMm();
        pintaln("La nodoserie: " . $cod_ns . " tiene " .
            $num . " de " . $total_cat . " objetos sin unesco." );

        $nombre_cat = $cat->getName();
        if (strlen($cod_ns) < 7){
            $ns1 = $cat->getName();
            $ns2 = '';
        } else {
            $ns1 = '';
            $ns2 = $cat->getName();
        }

        fputcsv($fp, array($ns1, $ns2, $num, $total_cat));
    }
    pintaln("Hay " . $sin_nodoserie . " objetos válidos sin nodoserie asignada");
    fputcsv ($fp, array("Sin nodoserie", "", $sin_nodoserie, ""));
    fclose($fp);


}

/**
 * borra el contenido de la columna uned_media_old.mm_id
 */
function reseteaUmoMmId()
{
    pintaln("Reseteando la columna uned_media_old.mm_id", "azul");
    $umos = UnedMediaOldPeer::doSelect(new Criteria());
    $i = 0;
    foreach ($umos as $umo){
        $i++;
        if ($i % 100 == 0 ) echo "Fila " . $i . "\n";
        $umo->setMmId(null);
        $umo->save();
    }
}

/**
 * Crea campos de i18n para cada person, para evitar problemas en pumukit
 */
function arreglaPersonI18n()
{
    $people = PersonPeer::doSelect(new Criteria());
    foreach ($people as $person){
        pintaln("Añadiendo campo i18n a la person: ". $person->getName());
        $person->setCulture('es');
        $person->setHonorific(' ');
        $person->save();
    }
}

/** 
 * Incomprensiblemente, no guarda bien $mm->setAudio($audio) así que lo arreglo a posteriori.
 * Tarda <10 segundos.
 */
function arreglaMmAudio()
{
    pintaln("Revisando la propiedad audio de los mm");
    $c = new Criteria();
    $c->add(UnedMediaOldPeer::CATEGORIAS, "Audios");
    $c->addJoin(UnedMediaOldPeer::MM_ID, MmPeer::ID);
    $c->add(MmPeer::AUDIO, 1, Criteria::NOT_EQUAL);
    $umos = UnedMediaOldPeer::doSelect($c);
    pintaln ("El total de audios sin establecer es: " . count($umos));
    $i = 1;
    foreach ($umos as $umo){
        $mm = $umo->getMm();
        $mm->setAudio(1);
        $mm->saveInDB();
        if ($i%100 == 0 && NIVEL_DEBUG > 1) pintaln($i);
        $i++;
    }

}

/** 
 * Para probar las cosas antes de ejecutar ninguna otra función del script,
 * aprovechando la BD poblada.
 */
function testNuevaFuncion()
{
    echo "\nEstas seguro?\n"; 
    echo "\nPor defecto borrará prácticamente todas las tablas relevantes\n";
    echo "y al final asignará una serie para cada nodoserie final\n";
    echo "\nComenta el primer exit en la última función del código\n";

    // exit;

    return; // Mantener descomentada esta línea para el funcionamiento normal del script 

    // TO DO: actualizar person quitando mail = www.canaluned.com
    // TO DO: buscarle sitio a publicaMmsImportados (y borraPubChannelMmImportados)
    // Importante: hay que elegir creaNuevasSeriesMueveMms() o creaSeriesParaNodoseriesFinales();

    $array_tiempos = array();
    cronometraEvento("Inicio del script", $array_tiempos);

// ESPACIO PARA PROBAR NUEVAS FUNCIONES SIN EJECUTAR EL SCRIPT ENTERO

    // exec("php ". dirname(__file__) . '/../timeframes/creacategoriasdestacadosradiotv.php');

    // creaIndicesLucene - tarda media hora
    // exec("php ". dirname(__file__) . '/../search/init_lucene.php');

    // borraTablasAPelo(array('serial', 'serial_i18n'));
    // creaSeriesParaNodoseriesFinales();

    // compruebaImportaCsv();

    cronometraEvento("Final de testNuevaFuncion", $array_tiempos);
    pintaTiempos($array_tiempos);
    exit;
}