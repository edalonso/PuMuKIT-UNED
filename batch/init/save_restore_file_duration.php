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

// desactiva output buffering para que muestre por pantalla los echos en tiempo real
ob_implicit_flush(true);
ob_end_flush();

csv2file();

function file2csv($csv_filename = 'filename_duration_filesize.csv'){
    $files = FilePeer::doSelect(new Criteria());
    $fp = fopen($csv_filename, 'w');
    echo "Grabando duración de files\n";   

    foreach($files as $file){
        fputcsv($fp, array( $file->getfile(), $file->getDuration(), $file->getSize()));
    }

    fclose($fp);
}

function csv2file($csv_filename = 'filename_duration_filesize.csv')
{
    $fp = fopen($csv_filename, 'r');
    $array_duration_filesize = array();
    echo "Procesando datos guardados en el csv\n";
    while (($fila = fgetcsv($fp, 100, ",")) !== FALSE) {
        $array_duration_filesize[$fila[0]] = array();
        $array_duration_filesize[$fila[0]]['duration'] = $fila[1];
        $array_duration_filesize[$fila[0]]['filesize'] = $fila[2];
    }

    $files = FilePeer::doSelect(new Criteria());
    echo "Persistiendo duración y tamaño en la base de datos\n\n";
    foreach ($files as $file){
        $v = $array_duration_filesize[$file->getFile()];
        $file->setDuration($v['duration']);
        $file->setSize($v['filesize']);
        $file->save();
        echo $file->getFile() . "\t" . $v['duration'] . "\t" . $v['filesize'] . "\n";
    }


  fclose($fp);
}

