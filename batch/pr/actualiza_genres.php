<?php

/**
 * Script que borra la lista de generos y la crea de nuevo con los suministrados
 * por el personal de la UNED - tarea #58 del redmine de soporte
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

echo "Se borrarán las tablas genre y genre_i18n.\n";
echo "Se establecerá el por defecto 'Sin Género' (1) a todos los mms\n";
echo " ¿estás seguro? (edita el código)\n";
exit;

// batch process here
$array_generos = array( '-- Sin Género --',
                        'Jornada',
                        'Noticias',
                        'Congreso',
                        'Debate',
                        'Documental / Reportaje',
                        'Entrevista / Testimonio',
                        'Coloquio',
                        'Curso',
                        'Conferencia',
                        'Seminarios'
                        );
$por_defecto = array (  'nombre' => '-- Sin Género --',
                        'id' => 1);

borraTablasAPelo(array('genre', 'genre_i18n'));
creaGeneros($array_generos, $por_defecto);
setGeneros($por_defecto);

//-------------------------------------------------------------------------------
/**
 * borraTablasAPelo - trunca tablas en vez de borrar objetos, para acelerar el desarrollo
 * y forzar que se reseteen los ids.
 */
function borraTablasAPelo($tablas)
{
    $connection = Propel::getConnection();
    $query = '';
    echo("Truncando tablas: \n");
    if (!is_array($tablas)){
        $tablas = array($tablas);
    }
    foreach ($tablas as $tabla){
        echo $tabla." ";
        $connection->executeUpdate('TRUNCATE TABLE '.$tabla);       
    }
    echo "\n\n";
}

function creaGeneros($array_generos, $por_defecto)
{

    $i = 1;
    foreach ($array_generos as $genero){
        echo"Creando el género: " . $genero;
        $g = new Genre();
        $g->setCulture('es');
        if ($por_defecto['nombre'] == $genero ){
            $g->setDefaultSel(1);
            echo " - Default";
        }
        echo "\n";
        $g->setName($genero);
        $g->setCod($i);
        $i++;
        $g->save();
    }
}

function setGeneros($por_defecto)
{
    $c = new Criteria();
    // $c->add(MmPeer::GENRE_ID, null);
    $mms = MmPeer::doSelect($c);
    $i = 1;
    echo "Estableciendo todos los géneros al valor por defecto\n";
    foreach ($mms as $mm){
        $mm->setGenreId($por_defecto['id']);
        $mm->saveInDB();
        if ($i%100 == 0) echo "Grabando mm: " . $i . "\n";
        $i++;
    }
}