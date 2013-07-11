<?php

/**
 * createMm batch script
 *
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


/*******************************************************
 * START                                               *
 *******************************************************/
set_time_limit(0);

$default_profile = 17;

//procesa parametros
if (3 != count($argv)) {
  //TODO inbox Create log function
  file_put_contents(sfConfig::get('sf_log_dir') . '/inbox.log', date('r')."  createMm   Wrong number of parameters \n\n", FILE_APPEND);
  exit(-1);
 }

//si no se ha cerrado el fichero salimos
if ($argv[2] != 'IN_CLOSE_WRITE') exit (0);

$serial_title = "Prueba Inbox II";
$path = $argv[1];

if ((($pos = strpos($path, ".filepart")) !== FALSE) || (($pos = strpos($path, ".part")) !== FALSE)) {
  $path = substr($path, 0, $pos);
  sleep(2);
 }

$title = substr(basename($path), 0, -4);

//$path = "\"".$path."\"";

//analizo archivo
// si no es video o su duracion es 0 no hace nada
try {
  $duration = FilePeer::getDuration($path);
}
catch (Exception $e) {
  file_put_contents(sfConfig::get('sf_log_dir') . '/inbox.log', date('r')."  createMm   Error getting video duration: " . $path." \n\n", FILE_APPEND);
  exit;
}

if($duration == 0){
  file_put_contents(sfConfig::get('sf_log_dir') . '/inbox.log', date('r')."  createMm   Video duration: 0 from " . $path." \n\n", FILE_APPEND);
  exit;
}




if ( ($serial = SerialPeer::retrieveByTitle($serial_title))  == null) {
  //  file_put_contents(sfConfig::get('sf_log_dir') . '/inbox.log', date('r')."  createMm   Serial: " . $serial_title. " does not exist \n\n", FILE_APPEND);
  //No existe serie y la crea
  SerialPeer::createNew(false, $serial_title);
 }



//crea el objeto multimedia
$mm = MmPeer::createNewMm($serial->getId(), $title);
$mm->save();




//genera master y a continuacion por workflow el mp4

$file_name = basename($path);


do{
  $path_video_tmp = sfConfig::get('app_transcoder_path_tmp').'/'.$mm->getId().'_';
  $path_video_tmp .= 'ES_'.rand().'_'.$file_name;
 } while (file_exists($path_video_tmp));


if (!rename($path, $path_video_tmp)) {
  file_put_contents(sfConfig::get('sf_log_dir') . '/inbox.log', date('r')."  createMm   Error to copy: " . $path. " to ".$path_video_tmp." \n\n", FILE_APPEND);
 }




//pone a hacer el mp4 al acabar el master
//status id = 3

$pcm = new PubChannelMm();
$pcm->setMmId($mm->getId());
$pcm->setPubChannelId(1);
$pcm->setStatusId(3);  
$pcm->save();
  

// Ojo que el perfil debe depender del directorio en el que se deje el fichero
$profile = PerfilPeer::retrieveByPK($default_profile);
      

$trans = new Transcoding();
$trans->setPerfilId($profile->getId());
$trans->setStatusId(1);
$trans->setPriority(2);  

$trans->setTimeini('now');
$trans->setMmId($mm->getId());

$langs = sfConfig::get('app_lang_array', array('es'));
foreach($langs as $l){
  $trans->setCulture($l);
  $trans->setDescription('');
}

$trans->save();

//TODO INBOX - Poner una referencia a que fue crado por el inbox
$trans->setName(substr($file_name, 0 , strlen($file_name)- 4));

$lang = LanguagePeer::retrieveByPK(4);
$trans->setLanguage($lang);

$trans->setPid(0);
$user = UserPeer::retrieveByPK(sfConfig::get('app_transcoder_default_user_id'));
$trans->setEmail($user->getEmail());

$trans->setDuration($duration);
$trans->setPathsAuto($path_video_tmp);
$trans->setUrl($trans->getPathEnd());
$trans->save();

TranscodingPeer::execNext();



