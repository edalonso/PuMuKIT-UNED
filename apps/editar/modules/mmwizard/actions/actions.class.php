<?php
/**
 * MODULO WIZARD ACTIONS. 
 * Pseudomodulo de administracion de los objetos multimedia a traves de wizard.
 * Se genera un wizard en modalbox con los siguintes pasos.
 *
 * @package    pumukit
 * @subpackage wizards
 * @author     Ruben Gonzalez Gonzalez (rubenrua at uvigo dot com)
 * @version    1.5
 */
class mmwizardActions extends sfActions
{
  /**
   * Executes index action
   * Step ONE
   *
   */
  public function executeIndex()
  {
    $this->serial_id = $this->getRequestParameter("serial_id");
    $this->mod = $this->getRequestParameter("mod", "mms");
  }

  /**
   * Executes two action
   * Step two
   *
   */
  public function executeTwo()
  {
    if($this->getRequestParameter("type") == "one") {
      $this->forward("mmwizard", "mms");
    }else{
      $this->forward("mmwizard", "several");
    }
  }

  /**
   * Executes mms action
   *
   */
  public function executeMms()
  {
    $this->serial_id = $this->getRequestParameter("serial_id");
    $this->mod = $this->getRequestParameter("mod", "mms");
    
    $this->langs = sfConfig::get('app_lang_array', array('es'));
    $this->mm = new Mm();
  }


  public function executeFile()
  {
    $this->langs = sfConfig::get('app_lang_array', array('es'));
    $this->serial_id = $this->getRequestParameter("serial_id");
    $this->mod = $this->getRequestParameter("mod", "mms");

    $this->profiles = PerfilPeer::doSelectToWizard(true);
    $this->pub_channels = PubChannelPeer::doSelect(new Criteria());

    $this->mm = new Mm();
    foreach($this->langs as $lang){
      $this->mm->setCulture($lang);
      $this->mm->setTitle($this->getRequestParameter('title_' . $lang));
      $this->mm->setSubtitle($this->getRequestParameter('subtitle_' . $lang));
      $this->mm->setDescription($this->getRequestParameter('description_' . $lang));
    }
    
  }



  public function executeEndfile()
  {
    set_time_limit(0);
    $language = LanguagePeer::retrieveByPK($this->getRequestParameter('idioma'));
    $priority = $this->getRequestParameter('prioridad');
    $this->mod = $this->getRequestParameter('mod', 'mms');
    $this->forward404Unless($language);

    //PERIL PARA MASTER
    $master = PerfilPeer::retrieveByPk($this->getRequestParameter('master'));
    
    //TODO: Mirar que el perfil es de master
    if($master == null){
      return $this->renderText("ERROR- No hay selecionado ningun perfil para master");
      //return $this->forward($div, 'list');
    }

    $c = new Criteria();
    $c->add(PubChannelPeer::ID, $this->getRequestParameter('pub_channel'), Criteria::IN); 
    $pub_channels = PubChannelPeer::doSelect($c); 



    switch ($this->getRequestParameter('file_type', 0)) {
    case "file":
      $file_name = $this->getRequest()->getFileName('video');
      do{
        $path_video_tmp = sfConfig::get('app_transcoder_path_tmp').'/';
        $path_video_tmp .= $language->getCod().'_'.rand().'_'.$file_name;
      } while (file_exists($path_video_tmp));
      
      if(!$this->getRequest()->moveFile('video', $path_video_tmp)){
	return $this->renderText("ERROR- [file] error haciendo un move file");
	//return $this->forward($div, 'list');
      }
      $files = array($path_video_tmp);
      break;
    case "url":
      $aux = str_replace("\\", "/", $this->getRequestParameter('file'));
      $aux = str_replace(sfConfig::get('app_transcoder_path_win'), sfConfig::get('app_transcoder_path_unix'), $aux);
      if(file_exists($aux)){
	$files = array($aux);
      }else{
	return $this->renderText("ERROR - [url] No files");
	//return $this->forward($div, 'list');
      }
      break;
    default:
      return $this->renderText("ERROR - [default] ");
      //return $this->forward($div, 'list');
    }

    $serial = SerialPeer::retrieveByPk($this->getRequestParameter('serial_id'));
    $this->forward404Unless($serial);

    $this->new_mms = $this->createFiles($files, $master, $pub_channels, $serial, $language, $priority); 
  }

  /**
   * Executes serveral action
   *
   */
  public function executeSeveral()
  {
    $this->serial_id = $this->getRequestParameter("serial_id");
    $this->mod = $this->getRequestParameter("mod", "mms");

    $this->profiles = PerfilPeer::doSelectToWizard(true);
    $this->pub_channels = PubChannelPeer::doSelect(new Criteria());
  }


  public function executeEndseveral()
  {
    set_time_limit(0);
    $language = LanguagePeer::retrieveByPK($this->getRequestParameter('idioma'));
    $priority = $this->getRequestParameter('prioridad');
    $mod = $this->getRequestParameter('mod', 'mms');
    $this->forward404Unless($language);

    //PERIL PARA MASTER
    $master = PerfilPeer::retrieveByPk($this->getRequestParameter('master'));
    
    //TODO: Mirar que el perfil es de master
    if($master == null){
      return $this->renderText("ERROR- No hay selecionado ningun perfil para master");
      //return $this->forward($div, 'list');
    }

    $c = new Criteria();
    $c->add(PubChannelPeer::ID, $this->getRequestParameter('pub_channel'), Criteria::IN); 
    $pub_channels = PubChannelPeer::doSelect($c); 

    $path = $this->getRequestParameter('url');
    $files = sfFinder::type('file')->maxdepth(0)->prune('.*')->in($path);

    $serial = SerialPeer::retrieveByPk($this->getRequestParameter('serial_id'));
    $this->forward404Unless($serial);

    $new_mms = $this->createFiles($files, $master, $pub_channels, $serial, $language, $priority); 
    $new_mm_id = (count($new_mms) == 0)?'':'&mm_id=' . $new_mms[0];
    return $this->redirect($mod. '/list?page=last' . $new_mm_id);
  }



  private function createFiles($files, $master, $pub_channels, $serial, $language, $priority)
  {
    $new_mms = array();
    $langs = sfConfig::get('app_lang_array', array('es'));
    foreach($files as $file) {
      $file_name = basename($file);
      $path_video_tmp = $file;

      //analizo archivo
      try {
	$duration = FilePeer::getDuration($path_video_tmp);
      }
      catch (Exception $e) {
	//unlink($path_video_tmp);
	continue; 
      }

      if($duration == 0){
	continue;
      }

      //creo objeto multimedia OJO NOMBRE DE FORMULARIO????
      $mm = MmPeer::createNew($serial->getId());
      if(ereg('^([0-9]{6})_([0-9]+_)*(.*)\.(.{3})', basename($file), $out) != false){
	list($y_mm, $m_mm, $d_mm) = str_split($out[1], 2);
	$title_mm = $out[3];
	
	$mm->setRecordDate(mktime(0, 0, 0, $m_mm, $d_mm, $y_mm));
      }else{
	$title_mm = basename($file);
      }
      //$mm->setPublicDate('now');

      foreach($langs as $l){
      	$mm->setCulture($l);
	$mm->setTitle($this->getRequestParameter("mm_title_" . $l, $file_name)); 
      	$mm->setSubtitle($this->getRequestParameter("mm_subtitle_" . $l));
      	if(strlen(trim($this->getRequestParameter("mm_description_" . $l))) > 0){
      	  $mm->setDescription($this->getRequestParameter("mm_description_" . $l));
      	}
      }
      $mm->save();
      $new_mms[] = $mm->getId();

      //Relaciono en estado tres (si tengo dos masters)
      foreach($pub_channels as $p_ch){
	$aux = new PubChannelMm();
	$aux->setMm($mm);
	$aux->setPubChannel($p_ch);
	$aux->setStatusId(3);
	$aux->save();
      }

      //echo "  -Process profile: " . $profile->getName() . "\n";
      $trans = new Transcoding();
      $trans->setPerfilId($master->getId());
      $trans->setStatusId(1);
      $trans->setPriority($priority);

      $trans->setTimeini('now');
      $trans->setMmId($mm->getId());
      
      foreach($langs as $l){
	$trans->setCulture($l);
	$trans->setDescription("");
      }
      
      $trans->save();
      
      //COMPLETO TAREA
      $trans->setName(substr($file_name, 0 , strlen($file_name)- 4));
      $trans->setLanguage($language);
      
      $trans->setPid(0);
      $trans->setEmail($this->getUser()->getAttribute('email'));
      
      $trans->setDuration($duration);
      $trans->setPathsAuto($path_video_tmp);
      $trans->setUrl($trans->getPathEnd());
      
      $trans->save();
      
      TranscodingPeer::execNext();
    }

    return $new_mms;
  }

}

