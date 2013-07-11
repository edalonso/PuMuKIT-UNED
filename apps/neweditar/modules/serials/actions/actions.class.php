<?php
/**
 * MODULO SERIALS ACTIONS. 
 * Modulo de administracion de las series de objetos multimedia. Permite 
 * modificar los metadatos de las series(tectinicos, de estilo y pics), y 
 * da acceso el modulo de administracion de objtos multimedia de casa serie. 
 *
 * @package    uned
 * @subpackage serials
 * @author     Ruben Gonzalez Gonzalez (rubenrua at uvigo dot com)
 * @version    1.0
 */
class serialsActions extends sfActions
{

  /**
   * --  INDEX -- /neweditar.php/serials
   * Muestra el modulo de administracion de las series, con la vista previa, formulario
   * de filtrado, listado de usuarios y formulario de edicion...
   *
   * Accion por defecto del modulo. Layout: layout
   *
   */
  public function executeIndex()
  {
    sfConfig::set('serial_menu','active');

    $this->getUser()->setAttribute('sort', 'publicDate', 'new_admin/serials');
    $this->getUser()->setAttribute('type', 'desc', 'new_admin/serials');
    if (!$this->getUser()->hasAttribute('page', 'new_admin/serials'))
      $this->getUser()->setAttribute('page', 1, 'new_admin/serials');
    if (!$this->getUser()->hasAttribute('cat_id', 'new_admin/serials'))
        $this->getUser()->setAttribute('cat_id', 0, 'new_admin/serials');

    $this->broadcasts = BroadcastPeer::doSelectWithI18n(new Criteria(), $this->getUser()->getCulture());
    $this->serialtypes = SerialTypePeer::doSelectWithI18n(new Criteria(), $this->getUser()->getCulture());
  }


  /**
   * --  LIST -- /editar.php/serials/list
   *
   * Sin parametros
   *
   */
  public function executeList()
  {
    return $this->renderComponent('serials', 'mmsList');
  }

  /**
   * COPIADO del modulo links
   * --  LIST -- /editar.php/serials/listLinks
   *
   * Parametros por URL: Identificador del objeto multimedia
   *
   */
  public function executeListLinks()
  {
    return $this->renderComponent('serials', 'listLinks');
  }

  /**
   * --  CREATE -- /editar.php/serials/createLinks
   *
   * Parametros por URL: Identificador del objeto multimedia
   *
   */
  public function executeCreateLinks()
  {
    $this->link = new Link();

    //$this->link->setCulture($this->getUser()->getCulture()); //No hace falta con hydrate
    $this->link->setMmId($this->getRequestParameter('mm'));
    $this->link->setUrl('...');
    
    $this->langs = sfConfig::get('app_lang_array', array('es'));

    $this->setTemplate('editLinks');
  }

  /**
   * --  DELETE -- /editar.php/linkss/delete
   *
   * Parametros por URL: Identificador del link
   *
   */
  public function executeDeleteLinks()
  {
    $link = LinkPeer::retrieveByPk($this->getRequestParameter('id'));
    $this->forward404Unless($link);
    $link->delete();
    
    return $this->renderComponent('serials', 'listLinks');
  }

  /**
   * --  EDIT -- /editar.php/linkss/edit
   *
   * Parametros por URL: Identificador del link
   *
   */
  public function executeEditLinks()
  {
    $this->link = LinkPeer::retrieveByPk($this->getRequestParameter('id'));
    $this->forward404Unless($this->link);
    $this->langs = sfConfig::get('app_lang_array', array('es'));
  }

   /**
   * Executes transcodificar
   *
   */
  public function executeEditTranscoders()
  {
    $this->mm = MmPeer::retrieveByPk($this->getRequestParameter('mm'));
    $this->forward404Unless($this->mm);
  
    $this->profiles = PerfilPeer::doSelectToWizard(false);
    $this->langs = sfConfig::get('app_lang_array', array('es'));
  }

  public function executeUploadTranscoders()
  {
    set_time_limit(0);
    $this->setLayout(false);

    if (($this->getRequest()->hasFiles())||($this->getRequestParameter('file_type', 'url') == 'file')){

      $mm = MmPeer::retrieveByPKWithI18n($this->getRequestParameter('num_video'), $this->getUser()->getCulture());
      $this->forward404Unless($mm);
      
      $lang = LanguagePeer::retrieveByPK($this->getRequestParameter('idioma'));
      $this->forward404Unless($lang);


      if($this->getRequestParameter('file_type', 'url') == 'url'){
	$file_name = $this->getRequest()->getFileName('video');
      }else{
	$file_name = basename($this->getRequestParameter('file'));
      }
      
      do{
        $path_video_tmp = sfConfig::get('app_transcoder_path_tmp').'/'.$mm->getId().'_';
        $path_video_tmp .= $lang->getCod().'_'.rand().'_'.$file_name;
      } while (file_exists($path_video_tmp));
      
      if($this->getRequestParameter('file_type') == 'url'){
	if(!$this->getRequest()->moveFile('video', $path_video_tmp)){
	  return sfView::ERROR;
	}
      }else{
	$aux = str_replace("\\", "/", $this->getRequestParameter('file'));
	$aux = str_replace(sfConfig::get('app_transcoder_path_win'), sfConfig::get('app_transcoder_path_unix'), $aux);

	if(file_exists($aux)){
	  //copy($aux, $path_video_tmp);
	  $path_video_tmp = $aux;
	}else{
	  return sfView::ERROR;
	}
      }

      //analizo archivo
      try {
	$duration = FilePeer::getDuration($path_video_tmp);
      }
      catch (Exception $e) {
	if($this->getRequestParameter('file_type') == 'url')
	  unlink($path_video_tmp);
        return sfView::ERROR; //MAL
      }

      if($duration == 0){
	if($this->getRequestParameter('file_type') == 'url')
	  unlink($path_video_tmp);
        return sfView::ERROR; //MAL
      }
      
      
      $c = new Criteria();
      $c->addAscendingOrderByColumn(PerfilPeer::RANK);
      $c->add(PerfilPeer::ID, $this->getRequestParameter('master')); 
      $profiles = PerfilPeer::doSelect($c); 
      
      foreach($profiles as $profile){
      	$trans = new Transcoding();
      	$trans->setPerfilId($profile->getId());
      	$trans->setStatusId(1);
      	$trans->setPriority($this->getRequestParameter('prioridad'));  
	if (strpos($profile->getName(), 'master') !== false){
	  $trans->setPriority($this->getRequestParameter('prioridad') - 1);  
	}
      	$trans->setTimeini('now');
      	$trans->setMmId($mm->getId());
	
      	$langs = sfConfig::get('app_lang_array', array('es'));
      	foreach($langs as $l){
      	  $trans->setCulture($l);
      	  $trans->setDescription($this->getRequestParameter('description_' . $l, ' '));
      	}
      	
      	$trans->save();
      
      	$trans->setName(substr($file_name, 0 , strlen($file_name)- 4));
      	$trans->setLanguage($lang);
      	$trans->setPriority($this->getRequestParameter('prioridad'));

      	$trans->setPid(0);
      	$user = UserPeer::retrieveByPK($this->getUser()->getAttribute('user_id'));
      	$trans->setEmail($user->getEmail());
      
      	$trans->setDuration($duration);
      	$trans->setPathsAuto($path_video_tmp);
      	$trans->setUrl($trans->getPathEnd());
      	$trans->save();
      
      	TranscodingPeer::execNext();
	$this->mm = $mm->getId();
      }
    }
  }


  /**
   * --  UPDATE -- /editar.php/linkss/update
   *
   * Parametros por POST
   *
   */
  public function executeUpdateLinks()
  {
    if (!$this->getRequestParameter('id'))
    {
      $link = new Link();
    }
    else
    {
      $link = LinkPeer::retrieveByPk($this->getRequestParameter('id'));
      $this->forward404Unless($link);
    }
    $link->setMmId($this->getRequestParameter('mm', 0));
    $link->setUrl($this->getRequestParameter('url', 0));

    $langs = sfConfig::get('app_lang_array', array('es'));
    foreach($langs as $lang){
      $link->setCulture($lang);
      $link->setName($this->getRequestParameter('name_' . $lang, ' '));
    }
    
    $link->save();

    return $this->renderComponent('serials', 'listLinks');
  }

  /**
   * --  UP -- /editar.php/linkss/up
   *
   * Parametros por URL: Identificador del link
   *
   */
  public function executeUpLinks()
  {
    $link = LinkPeer::retrieveByPk($this->getRequestParameter('id'));
    $this->forward404Unless($link);

    $link->moveUp();
    $link->save();

    return $this->renderComponent('serials', 'listLinks');
  }


  /**
   * --  DOWN -- /editar.php/linkss/down
   *
   * Parametros por URL: Identificador del link
   *
   */
  public function executeDownLinks()
  {
    $link = LinkPeer::retrieveByPk($this->getRequestParameter('id'));
    $this->forward404Unless($link);

    $link->moveDown();
    $link->save();

    return $this->renderComponent('serials', 'listLinks');
  }

  /**
   * COPIADO del modulo files
   *
   * --  LIST -- /neweditar.php/serials/listFiles
   *
   * Parametros por URL: Identificador del objeto multimedia
   *
   */
  public function executeListFiles()
  {
    return $this->renderComponent('serials', 'listFiles');
  }

  /**
   * --  EDIT -- /neweditar.php/serials/editFiles
   *
   * Parametros por URL: Identificador del archivo multimedia
   *
   */
  public function executeEditFiles()
  {
    $this->file = FilePeer::retrieveByPk($this->getRequestParameter('id'));
    $this->forward404Unless($this->file);
    $this->langs = sfConfig::get('app_lang_array', array('es'));
  }

  /**
   * COPIADO del modulo files
   * --  DOWNLOAD -- /neweditar.php/serials/downloadFiles
   *
   * Parametros por URL: Identificador del archivo multimedia
   *
   */
  public function executeDownloadFiles(){
    set_time_limit(0);

    $file = FilePeer::retrieveByPk($this->getRequestParameter('id'));
    $this->forward404Unless($file);

    //-  A)   ***** SIN TICKET ln -s file->getUrl web/tickets *****
    //-  // Cabeceras HTTP
    //-  header('HTTP/1.1 200 OK');
    //-  if ($file->getSize() != 0) header('Content-Length: '.$file->getSize());
    //-  header ('Content-Disposition: attachment; filename='.basename($file->getFile())); 
    //-  header('Content-type: application/octet-stream');
    //-  
    //-  ob_end_clean();
    //-  //DATOS
    //-  $aux= readfile($file->getUrlMount());
    //-  
    //-  file_put_contents(sfConfig::get('sf_log_dir') . '/readfile.log', $aux . " \n", FILE_APPEND);
    //-  

    //-  B)   ***** CON TICKET ln -s file->getUrl web/tickets *****
    // Compruebo que acede desde pumukit.
    if (strpos($this->getRequest()->getReferer(), 'neweditar.php/mms/index/serial')){
      $ticket = TicketPeer::new_web($file);
      return $this->redirect($ticket->getUrl());
    }else{
      return $this->renderText("ERROR -1: Acceda desde PuMuKIT");
    }
  }

  /**
   *COPIADA del modulo files
   * --  RETRANSCODIFICAR -- /neweditar.php/serials/retransFiles
   *
   * Parametros por URL: 
   *   - Identificador del archivo multimedia
   *   - Identificador del perfil nuevo
   *
   */
  public function executeRetransFiles()
  {
    $file = FilePeer::retrieveByPk($this->getRequestParameter('id'));
    $this->forward404Unless($file);
    
    $profile = PerfilPeer::retrieveByPk($this->getRequestParameter('profile'));
    $this->forward404Unless($profile);

    //retranscodifico creando uno nuevo si es necesario.
    $file->retranscoding($profile->getId(), $this->getRequestParameter('prioridad', 2), $this->getUser()->getAttribute('user_id'), true);

    $this->msg_alert = array('info', "Creada nueva tarea para retranscodificar al nuevo formato.");
    return $this->renderComponent('serials', 'listFiles');
  }

  /**
   * --  PIC -- /neweditar.php/serials/picFiles
   *
   * Parametros por URL: Identificador del archivo multimedia y opcionalmente, numero de frame
   *
   */
  public function executePicFiles(){
  
    $file = FilePeer::retrieveByPk($this->getRequestParameter('id'));
    if ($file == null) return $this->renderText('K0');

    if(!file_exists($file->getUrlMount())){
      $this->msg_alert = array('error', "Error en autocompletado los datos del archivo multimedia.");
      return $this->renderComponent('pics', 'list');
    }

    $aux = $this->getRequestParameter('numframe', null);
    $num_frames = FilePeer::getFrameCountFfmpeg($file->getFile());
    if((is_null($aux)||($num_frames == 0))){
      $num = 125 * (count($file->getMm()->getPics())) + 1;
    }elseif(substr($aux, -1, 1) === '%'){
      $num = intval($aux)* $num_frames /100;
    }else{
      $num = intval($aux);
    }
	    //$num = count($file->getMm()->getPics());
	    //$num = $this->getRequestParameter('numframe', 125 * ($num +1));
    $file->createPic($num);

    $this->msg_alert = array('info', "Capturado el FRAME " .  $num. " como imagen.");
    return $this->renderComponent('serials', 'listPics');
  }

  /**
   * --  LIST -- /editar.php/materials/list
   *
   * Parametros por URL: Identificador del objeto multimedia
   *
   */
  public function executeListMaterials()
  {
    return $this->renderComponent('serials', 'listMaterials');
  }

  /**
   * --  CREATE -- /neweditar.php/serials/createMaterials
   *
   * Parametros por URL: Identificador del objeto multimedia
   *
   */
  public function executeCreateMaterials()
  {
    $this->material = new Material();

    $this->material->setMmId($this->getRequestParameter('mm'));
    $this->material->setMatTypeId(MatTypePeer::getDefaultSelId());
    
    $this->langs = sfConfig::get('app_lang_array', array('es'));

    $this->default_sel = 'file';
    $this->setTemplate('editMaterials');
  }

  /**
   * --  EDIT -- /neweditar.php/serials/editMaterials
   *
   * Parametros por URL: Identificador del material
   *
   */
  public function executeEditMaterials()
  {
    $this->material = MaterialPeer::retrieveByPk($this->getRequestParameter('id'));
    $this->forward404Unless($this->material);
    $this->langs = sfConfig::get('app_lang_array', array('es'));

    $this->default_sel = 'url';
  }


  /**
   * --  DELETE -- /editar.php/materials/delete
   *
   * Parametros por URL: Identificador del material
   *
   */
  public function executeDeleteMaterials()
  {
    $material = MaterialPeer::retrieveByPk($this->getRequestParameter('id'));
    $this->forward404Unless($material);
    $material->delete();
    
    return $this->renderComponent('serials', 'listMaterials');
  }


  /**
   * --  UPDATE -- /editar.php/materials/update
   *
   * Parametros por POST
   *
   */
  public function executeUpdateMaterials()
  {
    if (!$this->getRequestParameter('id'))
    {
      $material = new Material();
    }
    else
    {
      $material = MaterialPeer::retrieveByPk($this->getRequestParameter('id'));
      $this->forward404Unless($material);
    }
    $material->setMmId($this->getRequestParameter('mm', 0));
    $material->setDisplay($this->getRequestParameter('display', 0));
    $material->setMatTypeId($this->getRequestParameter('mat_type_id', 0));
    if ($material->isNew()) $material->setUrl($this->getRequestParameter('url', 0));

    $langs = sfConfig::get('app_lang_array', array('es'));
    foreach($langs as $lang){
      $material->setCulture($lang);
      $material->setName($this->getRequestParameter('name_' . $lang, ' '));
    }
    
    $material->save();

    return $this->renderComponent('serials', 'listMaterials'); 
  }


  /**
   * --  UPLOAD -- /editar.php/materials/upload
   *
   * Parametros por POST
   *
   */
  public function executeUploadMaterials()
  {
    if (!$this->getRequestParameter('id'))
    {
      $material = new Material();
    }
    else
    {
      $material = MaterialPeer::retrieveByPk($this->getRequestParameter('id'));
      $this->forward404Unless($material);
    }
    $material->setMmId($this->getRequestParameter('mm', 0));
    $material->setDisplay($this->getRequestParameter('display', 0));
    $material->setMatTypeId($this->getRequestParameter('mat_type_id', 0));


    $langs = sfConfig::get('app_lang_array', array('es'));
    foreach($langs as $lang){
      $material->setCulture($lang);
      $material->setName($this->getRequestParameter('name_' . $lang, ' '));
    }


    if($this->getRequestParameter('file_type', 'url') == 'url'){
      if ($material->isNew()) $material->setUrl($this->getRequestParameter('url', 0));
      $material->save();

      $this->msg_info = 'Nueva material modificado.';
    }elseif($this->getRequestParameter('file_type', 'url') == 'file'){
      $currentDir = 'Video/' . $material->getMmId();      
      $absCurrentDir = sfConfig::get('sf_upload_dir').'/material/' . $currentDir;
      $fileName = $this->sanitizeFile($this->getRequest()->getFileName('file'));
      $this->getRequest()->moveFile('file', $absCurrentDir . '/' . $fileName);
      
      $material->setUrl('/uploads/material/' . $currentDir . '/' .  $fileName);
      $material->save();

      $this->msg_info = 'Nueva material subido e insertado.';
    }

    $this->mm = $material->getMmId();
    $this->material = $material->getId();
  }

  /**
   * --  UP -- /editar.php/materials/up
   *
   * Parametros por URL: Identificador del material
   *
   */
  public function executeUpMaterials()
  {
    $material = MaterialPeer::retrieveByPk($this->getRequestParameter('id'));
    $this->forward404Unless($material);

    $material->moveUp();
    $material->save();

    return $this->renderComponent('serials', 'listMaterials');
  }

  /**
   * --  DOWN -- /editar.php/materials/down
   *
   * Parametros por URL: Identificador del material
   *
   */
  public function executeDownMaterials()
  {
    $material = MaterialPeer::retrieveByPk($this->getRequestParameter('id'));
    $this->forward404Unless($material);

    $material->moveDown();
    $material->save();

    return $this->renderComponent('serials', 'listMaterials');
  }

  /**
   * COPIADA del modulo pics
   * --  CREATE -- /editar.php/pics/create
   *
   * Parametros: id de mm, serial o channel
   *
   */
  public function executeCreatePics()
  {
    $limit = 12; //3x4

    $this->inicialize();

    $c = new Criteria();

    $this->page = $this->getRequestParameter('page', 1);
    $offset = ($this->page - 1) * $limit;
    $c->setLimit($limit);
    $c->setOffset($offset);

    $c->addDescendingOrderByColumn(PicPeer::ID);
    $this->pics = PicPeer::doSelect($c);

    $this->total_pic = PicPeer::doCount(new Criteria());
    $this->total = ceil($this->total_pic / $limit); 
  }

  /**
   * COPIADA del modulo pics
   * --  UPDATE -- /neweditar.php/serials/updatePics
   *
   * Parametros: 
   *     -ID de mm, serial o channel
   *     -TYPE: url o file
   *
   */
  public function executeUpdatePics()
  {
    $limit = 12; //3x4

    $this->inicialize();

    if ($this->getRequestParameter('type') == 'url'){
      $aux = 'Pic'.ucfirst($this->que);
      $pic = new Pic();
      $pic->setUrl($this->getRequestParameter('url'));
      $pic->save();
      $pic_object = new $aux;
      $pic_object->setPicId($pic->getId());
      $pic_object->setOtherId($this->object_id);
      $pic_object->save();
    }elseif($this->getRequestParameter('type') == 'pic'){
      $aux = 'Pic'.ucfirst($this->que);
      if (call_user_func(array($auxPeer, 'retrieveByPk'), $this->getRequestParameter('id'), $this->object_id) == null){
	$pic_object = new $aux;
	$pic_object->setPicId($this->getRequestParameter('id'));
	$pic_object->setOtherId($this->object_id);
	$pic_object->save();
      }
    }

    $this->preview = true;
    $this->msg_alert = array('info', "Nueva imagen insertada.");
    return $this->renderComponent('serials', 'listPics');
  }

  /**
   * COPIADA del modulo pics
   * --  UPLOAD -- /neweditar.php/serials/uploadPics
   *
   * Parametros: 
   *    -id de mm, serial o channel (tipo de objeto)
   *    -type: url o file (metodo de subida)
   *
   */
  public function executeUploadPics()
  {
    $limit = 12; //3x4

    $currentDir = $this->inicialize(true);

    if ($this->getRequestParameter('type') == 'url'){
      $absCurrentDir = sfConfig::get('sf_upload_dir').'/pic/' . $currentDir;
      
      $fileName = $this->sanitizeFile($this->getRequest()->getFileName('file'));
      
      if ( strstr($this->getRequest()->getFileType('file'), 'image') == false ){
	return 'Fail';
      }
     
	
      $absFile = $absCurrentDir .'/' . $fileName;
	
      while(file_exists($absFile)){
	$r = rand ();
	$absFile = $absCurrentDir .'/' . $r . $fileName;
      }


      //copiar archivo
      //$this->getRequest()->moveFile('file', $absCurrentDir . '/' . $fileName);
      //Crear miniatura.
      try{
	$thumbnail = new sfThumbnail(sfConfig::get('app_thumbnail_hor'), sfConfig::get('app_thumbnail_ver'));
	$thumbnail->loadFile($this->getRequest()->getFilePath('file'));
	@mkdir($absCurrentDir, 0777, true);

	$thumbnail->save($absFile, 'image/jpeg');
      }catch(Exception $e){
	$this->getRequest()->moveFile('file', $absFile);
      }
      

      
      $aux = 'Pic'.ucfirst($this->que);
    
      $pic = new Pic();
      $pic->setUrl('/uploads/pic/' . $currentDir . '/' . $r . $fileName);
      $pic->save();
      $pic_object = new $aux;
      $pic_object->setPicId($pic->getId());
      $pic_object->setOtherId($this->object_id);
      $pic_object->save();
    }

    $this->mm_id = $this->getRequestParameter('mm');
    $this->msg_alert = array('info', "Nueva imagen insertada.");
  }



  /**
   * --  LIST -- /editar.php/serials/list
   *
   * Sin parametros
   *
   */
  public function executeListPics()
  {
    return $this->renderComponent('serials', 'listPics');
  }

  /**
   * COPIADA del modulo pics
   * --  DELETE -- /neweditar.php/serialss/deletepics
   *
   * Parametros: id de mm, serial o channel
   *
   */
  public function executeDeletePics()
  {
    if ($this->hasRequestParameter('serial')){
      $pic_relation = PicSerialPeer::retrieveByPk($this->getRequestParameter('id'), $this->getRequestParameter('serial'));
    }elseif($this->hasRequestParameter('mm')){
      $pic_relation = PicMmPeer::retrieveByPk($this->getRequestParameter('id'), $this->getRequestParameter('mm'));
    }
    $this->forward404Unless($pic_relation);

    $pic_relation->delete();

    $this->preview = true;
    $this->msg_alert = array('info', "Imagen borrada.");
    return $this->renderComponent('serials', 'listPics');
  }


  /**
   * --  UP -- /editar.php/pics/up
   *
   * Parametros: id de mm, serial o channel; id del pic
   *
   */
  public function executeUpPics()
  {
    if ($this->hasRequestParameter('serial')){
      $pic = PicSerialPeer::retrieveByPk($this->getRequestParameter('id'), $this->getRequestParameter('serial'));
    }elseif($this->hasRequestParameter('mm')){
      $pic = PicMmPeer::retrieveByPk($this->getRequestParameter('id'), $this->getRequestParameter('mm'));
    }
    $this->forward404Unless($pic);

    $pic->moveUp();
    $pic->save();

    $this->preview = true;
    return $this->renderComponent('serials', 'listPics');
  }

  /**
   * --  DOWN -- /editar.php/pics/down
   *
   * Parametros: id de mm, serial o channel; id del pic
   *
   */
  public function executeDownPics()
  {
    if ($this->hasRequestParameter('serial')){
      $pic = PicSerialPeer::retrieveByPk($this->getRequestParameter('id'), $this->getRequestParameter('serial'));
    }elseif($this->hasRequestParameter('mm')){
      $pic = PicMmPeer::retrieveByPk($this->getRequestParameter('id'), $this->getRequestParameter('mm'));
    }
    $this->forward404Unless($pic);

    $pic->moveDown();
    $pic->save();

    $this->preview = true;
    return $this->renderComponent('serials', 'listPics');
  }

  /**
   * --  LIST -- /editar.php/serials/list2
   *
   * Accion asincrona. Acceso privado.
   *
   */

  public function executeList3()
  {
    $this->operation = $this->getRequestParameter('operation');
    $id = $this->getRequestParameter('id');
    $cat = CategoryPeer::retrieveByPk($id);
    $datosCategory = array();

    if ($this->operation == 'get_children'){
      foreach($cat->getChildren() as $c){
	if ($id == 1){
	  $datosCategory[] = array(
				   "attr" => array(
						   "id"    => "node_" . $c->getId(),
						   "rel"   => "drive",
						   ),
				   "data"  => $c->getCodName() . ' (' .$c->getNumMm() . ')',
				   "state" => "closed"
				   );
	} else {
	  $datosCategory[] = array(
				   "attr" => array(
						   "id"    => "node_" . $c->getId(),
						   "rel"   => "folder",
						   ),
				   "data"  => $c->getCodName() . ' (' .$c->getNumMm() . ')',
				   "state" => "closed"
				   );
	  
	}
      }
    }
    return $this->renderText(json_encode($datosCategory));
  }

  public function executeList2()
  {
      $this->category = new Category();

      $this->operation = $this->getRequestParameter('operation');
      $id = $this->getRequestParameter('id');
      $this->categories = CategoryPeer::retrieveByPk($id);
      $datosCategory = array();
      //Lista total de series

      //Lista total de series
      /*$c = new Criteria();

      $this->processSort($c);
      $c->setLimit(11);
      $c->setOffset(0);
      $this->serials = SerialPeer::doList($c, $this->getUser()->getCulture());*/

      if ($id == 1){
          $datosCategory[] = array(
              "attr" => array(
                              "id"    => 0,
                              "rel"   => "drive",
                              ),
              "data"  => 'Todos',
              "state" => "leaf"
           );
      }

      if ($this->operation == 'get_children'){
          $this->parent = $this->getRequestParameter('parent');
          foreach($this->categories->getChildren() as $c){
              if ($id == 1){
                  $datosCategory[] = array(
                                           "attr" => array(
                                                           "id"    => "node_" . $c->getId(),
                                                           "rel"   => "drive",
                                                           ),
                                           "data"  => $c->getCodName(),
                                           "state" => "closed"
                                           );
              } else {
                  $datosCategory[] = array(
                                           "attr" => array(
                                                           "id"    => "node_" . $c->getId(),
                                                           "rel"   => "folder",
                                                           ),
                                           "data"  => $c->getCodName() . ' (' .$c->getNumMm() . ')',
                                           "state" => ((count($c->getChildren()) == 0)?"leaf":"closed")
                                           );
              }
          }
              return $this->renderText(json_encode($datosCategory));
      }
              /*} else {
              $datosCategory = false;
              foreach($this->categories as $node){
                  $category = $node[CategoryPeer::TREE_ARRAY_NODE];
		  if ($this->id > 542){
                      while ($datosCategory === false){
                          $datosCategory = $this->searchId($this->id, $node);
                      }
		  } elseif ($this->id > 29){
                      while ($datosCategory === false){
                          $datosCategory = $this->searchId($this->id, $node);
                      }
                  } elseif ($this->id > 1){
		    if ($category->getId()==2){
		      while ($datosCategory === false){
			$datosCategory = $this->searchId($this->id, $node);
		      }
		    }
                  }
              }
          }
      }
      elseif ($this->operation == 'rename_node'){//TODO aplicar cambios para los diferentes idiomas
          $title = $this->getRequestParameter('title');
          if (!$this->getRequestParameter('id'))
              {
                  $category = new Category();
                  $parent_id = $this->getRequestParameter('parent_id');
                  $parent = CategoryPeer::retrieveByPk($parent_id);
                  $this->forward404Unless($parent);
                  $category->insertAsLastChildOf($parent);
              }
          else
              {
                  $category = CategoryPeer::retrieveByPk($this->id);
                  $this->forward404Unless($category);
              }
          
          $langs = sfConfig::get('app_lang_array', array('es'));
          $pos = strpos($title, '-');
          $realTitle = substr($title, $pos+2, strlen($title));
          foreach($langs as $lang){
              $category->setCulture($lang);
              $category->setName($realTitle);
          }
          try{
              $category->save();
              return $this->renderText(json_encode(array('status' => 1)));
          }catch(Exception $e){
              return $this->renderText(json_encode("Error al actualizar."));
          }
          
      } elseif ($this->operation == 'create_node'){//TODO crear nodo de series, diferencia entre categorías y series: el id es 99999999 o mayor que 1379 y menor que 2506
          if ($this->getRequestParameter('id'))
              {
                  $category = new Category();
                  $parent_id = $this->id;
                  $parent = CategoryPeer::retrieveByPk($parent_id);
                  $this->forward404Unless($parent);
                  $category->insertAsLastChildOf($parent);
              }
          $category->setMetacategory(0);//Parámetros básicos para las categorías
          $category->setDisplay(1);
          $category->setRequired(1);
          $category->setCod($this->getRequestParameter('cod', ' '));//TODO definir como se generan los códigos de las categorías
          
          $langs = sfConfig::get('app_lang_array', array('es'));
          foreach($langs as $lang){
              $category->setCulture($lang);
              $category->setName($this->getRequestParameter('title'));
          }
          try{
              $category->save();
              return $this->renderText(json_encode(array('status' => 1, 'id' => $category->getId())));
          }catch(Exception $e){
              return $this->renderText(json_encode("Error al actualizar."));
          }
      } elseif ($this->operation == 'remove_node'){
          if($this->hasRequestParameter('id')){
              $category = CategoryPeer::retrieveByPk($this->id);
              $category->delete();
          }

          return $this->renderText(json_encode(array('status' => 1)));
          }*/ elseif ($this->operation == 'move_node'){//TODO mover la categoría en lugar de crear una nueva y borrar la existente
          if($this->hasRequestParameter('id')){
              $category = CategoryPeer::retrieveByPk($id);              
              $parent_id = $this->getRequestParameter('ref');
              $parent = CategoryPeer::retrieveByPk($parent_id);
              $this->forward404Unless($parent);
              $category->insertAsLastChildOf($parent);
              
              try{
                  $category->save();
                  return $this->renderText(json_encode(array('status' => 1, 'id' => $category->getId())));
              }catch(Exception $e){
                  return $this->renderText(json_encode("Error al actualizar."));
              }
          }
      }
      return $this->renderText(json_encode($datosCategory));
  }

  public function executeMmsListCategories()
  {
      
      $id = $this->getRequestParameter('id');
      
      $parent = CategoryPeer::retrieveByPk(543);//Tematicas UNED

      $cat_raiz_unesco = CategoryPeer::retrieveByCode("UNESCO");
      $cat_raiz_uned = CategoryPeer::retrieveByCode("Tematicas UNED");

      $this->mm = MmPeer::retrieveByPk($id);

      $this->cats = array();
      $this->cats[] = $this->mm->getCategories($cat_raiz_unesco);
      $this->cats[] = $this->mm->getCategories($cat_raiz_uned);
  }

  public function executeMmsList()
  {
      $id = $this->getRequestParameter('id');

      $this->getUser()->setAttribute('cat_id', $id, 'new_admin/serials');

      if ($this->hasRequestParameter('page')){
              $page = $this->getRequestParameter('page', 1);
              if ($page < 1) {
                  $page = 1;
              }
              $this->getUser()->setAttribute('page', $page, 'new_admin/serials');
      }
      
      return $this->renderComponent('serials', 'mmsList');
  }

  public function executeAddCategory()
  {

      if($this->hasRequestParameter('category_id')){
          $cat_id = $this->getRequestParameter('category_id');
          $cat = CategoryPeer::retrieveByPk($cat_id);
          $this->cat = CategoryPeer::retrieveByPk($this->getRequestParameter('parent_id'));

          $mm = MmPeer::retrieveByPk($this->getRequestParameter('mms_id'));
         
          
          if ($mm->hasCategoryId($cat->getId())){
              //return $this->renderComponent('serials', 'mmsList');
              //return $this->renderText(json_encode(array('status' => 1)));
          }
          
          $cat->addMmId($mm->getId());

          $limit = 7;
          $page = $this->getRequestParameter('page', 1);
          
          if ($page < 1) {
              $page = 1;
          }
          if(!$this->cat) {
              return "Empty";
          }
          
          $this->cat_raiz_unesco = CategoryPeer::retrieveByCode("UNESCO");
          $this->cat_raiz_uned = CategoryPeer::retrieveByCode("Tematicas UNED");
          
          $this->mms = $this->getMms($this->getRequestParameter('parent_id'), $limit, $limit * ($page - 1));
          
          $this->total_mms = $this->countMms($this->getRequestParameter('parent_id'));
          $this->limit = $limit;
          $this->page  = $page;
          $this->pages = ceil($this->total_mms / $limit);
          
          return $this->renderComponent('serials', 'mmsList');
      }
  }

  /**
   * --  AUTOCOMPLETE -- /editar.php/files/autocomplete
   *
   * Parametros por URL: Identificador del archivo multimedia
   *
   */
  public function executeAutocompleteFiles(){
  
    $file = FilePeer::retrieveByPk($this->getRequestParameter('id'));
    if ($file == null) return $this->renderText('K0');

    if(!file_exists($file->getUrlMount())){
      $this->msg_alert = array('error', "Error en autocompletado los datos del archivo multimedia.");
      return $this->renderComponent('files', 'list');
    }

    $file->setDuration(intval(FilePeer::getDuration($file->getUrlMount())));
    $file->setSize(filesize($file->getUrlMount()));

    //Autocmpletar resolution
    $movie = new ffmpeg_movie($file->getFile());
    if (!is_null($movie)){
      $file->setResolutionVer($movie->getFrameHeight());
      $file->setResolutionHor($movie->getFrameWidth());
    }
    
    if (file_exists($file->getUrlMount())) $file->setFile($file->getUrlMount());
    $file->save();

    $this->msg_alert = array('info', "Autocompletados los datos del archivo multimedia.");
    return $this->renderComponent('serials', 'listFiles');
  }

  /** FUNCION COPIADA del MODULO persons
   * --  LISTAUTOCOMPLETE -- /editar.php/serials/listautocomplete
   * Muestra el formulario de asociacion de una perona a un objeto multimedia. A traves de ese 
   * formulario se puede asociar una persona ya existente o una nueva a un objeto multimedia com un determinado rol.
   * Mientas se completa el nombre de la perona se muestras personas ya existentes con dicho nombre.
   *
   * Accion asincrona. Acceso privado. Parametros role y mm por URL.
   *
   */
  public function executeListAutoComplete()
  {
    $this->role_id = $this->getRequestParameter('role');
    $this->mm_id = $this->getRequestParameter('mm');  //OJO SI NO E
    $this->template = ($this->hasRequestParameter("template"))?'/template/true':'';
  }

  /**
   * Copiado de persons actions
   * --  AUTOCOMPLETE -- /editar.php/serials/autocomplete
   * Muestra una lista com los nombres de las peronas similares al que se esta campo nombre.
   *
   * Accion asincrona. Acceso privado. Parametros name por URL.
   *
   */
  public function executeAutoComplete()
  {
    $c = new Criteria();
    $name = $this->getRequestParameter('term');
    $c->add(PersonPeer::NAME, '%' . $name . '%', Criteria::LIKE);
    $persons = PersonPeer::doSelect($c);

    $res = array();

    foreach ($persons as $person){
      $res[] = array("id" => $person->getId(), "name" => $person->getName(), "info" => $person->getInfo());
    }
    return $this->renderText(json_encode($res));
  }

  /*
   * -- SEARCH --
   * Search an Id by recursive way in Category Tree
   *
   */
  /*public function searchId($id, $node){
      $has_children = count($node[CategoryPeer::TREE_ARRAY_CHILDREN]);
      $datosCategory = array();
      $find = false;
      $category = $node[CategoryPeer::TREE_ARRAY_NODE];
      if ($has_children == 0) return(false);
      foreach ($node[CategoryPeer::TREE_ARRAY_CHILDREN] as $children){
          $has_children_children = count($children[CategoryPeer::TREE_ARRAY_CHILDREN]);
          if ($category->getId() == $id){
              $find = true;
              $c = $children[CategoryPeer::TREE_ARRAY_NODE];
              $datosCategory[] = array(
                                       "attr" => array(
                                                       "id"    => "node_" . $c->getId(),
                                                       "rel"   => ($has_children_children==0) ? "default" : "folder",
                                                       ),
                                       "data"  => $c->getCodName(),
                                       "state" => ($has_children_children==0) ? "leaf" : "closed"
                                       );
          }
      }
      if (!$find && $has_children!=0){
          foreach ($node[CategoryPeer::TREE_ARRAY_CHILDREN] as $children){
              $category2 = $children[CategoryPeer::TREE_ARRAY_NODE];
              $datosCategory = $this->searchId($id, $children);
              if ($datosCategory!== false) break;
          }
      } elseif (!$find && $has_children==0){
          return(false);
      }

      return($datosCategory);
      }*/

  /*
   * --  ANALYZE -- /editar.php/serials/analyze
   * To analyze the tree and show errors
   *
   */
  public function executeAnalyze(){
      return $this->renderText(json_encode("Analizado"));
  }
  /*
   * --  ANALYZE -- /editar.php/serials/reconstruct
   * To analyze the tree and show errors
   *
   */
  public function executeReconstruct(){
      //TODO reconstruir el árbol desde un árbol inicial y recargar el árbol en el template
      return $this->renderText(json_encode("Reconstruido"));
  }
  /*
   * --  SEARCH -- /editar.php/serials/search
   * To analyze the tree and show errors
   *
   */
  public function executeSearch(){
      //TODO realizar función en CategoryPeer que realice una consulta sobre la base de datos sobre el nombre de categoría
      $name = $this->getRequestParameter('search_str');

      //$category = CategoryPeer::retrieveByName($name);
      return $this->renderText(json_encode(array('#node_4','#node_5')));
      return $this->renderText(json_encode('Buscado'));
  }
  

  /**
   * --  EDIT -- /editar.php/serials/editmms/id/:id
   *
   * Parametros por URL: Identificador el objeto multimedia
   *
   */
  public function executeEditMms()
  {
      if ($this->hasRequestParameter('id'))
          {
              $this->getUser()->setAttribute('id', $this->getRequestParameter('id'), 'new_admin/serials');
          }
      return $this->renderComponent('serials', 'editmms');
  }

  /**
   * --  PREVIEW -- /editar.php/serials/previewmms/id/:id
   *
   * Parametros por URL: Identificador el objeto multimedia
   *
   */
  public function executePreviewMms()
  {
      //session _request ID o 0 si no hay
      $this->roles = RolePeer::doSelectWithI18n(new Criteria());
      $this->mm = MmPeer::retrieveByPk($this->getRequestParameter('id'));
      $this->file = FilePeer::retrieveByPk($this->getRequestParameter('id'));

      return $this->renderPartial('previewmms');
  }


  /**
   * --  EDIT -- /editar.php/serials/edit
   *
   * Parametros por URL: id de la serie
   *
   */
  public function executeEdit()
  {
    if ($this->hasRequestParameter('id'))
    {
      $this->getUser()->setAttribute('id', $this->getRequestParameter('id'), 'new_admin/serials');
    }
    return $this->renderComponent('serials', 'edit');
  }


  /**
   * COPIADO del modulo persons
   * --  EDIT -- /editar.php/persons/edit/id/?
   * Muesta el formulario de edicion de la persona cuyo identificador se pada como paremetro.
   *
   * Accion asincrona. Acceso privado. Parametros id por URL, role y mm opcionales, (para conocer que template usar)
   *
   */
  public function executeEditPersons()
  {
    $this->person = PersonPeer::retrieveByPk($this->getRequestParameter('id'));
    $this->forward404Unless($this->person);

    /*De donde se accede persons o video ?*/
    if($this->hasRequestParameter('template')){
      $this->url = 'serials/updaterelation?template=true&mm='.$this->getRequestParameter('mm').'&role='.$this->getRequestParameter('role');
      $this->update = $this->getRequestParameter('role').'_person_mms';
      $this->role_id = $this->getRequestParameter('role');
    }elseif ($this->hasRequestParameter('role')){
      $this->url = 'serials/updaterelation?mm='.$this->getRequestParameter('mm').'&role='.$this->getRequestParameter('role');
      $this->update = $this->getRequestParameter('role').'_person_mms';
      $this->role_id = $this->getRequestParameter('role');
      //$this->preview = true;
    }else{
      $this->url = 'serials/update';
      $this->update = 'list_persons';

    }

    $this->langs = sfConfig::get('app_lang_array', array('es')); 
  }

  /**
   * COPIADO del modulo persons
   * --  UPDATERELATION -- /editar.php/serials/updaterelation
   * Actualiza el contenido de una persona con el resultado del formulario de modificacion.
   * Si no existe persona con id dado se crea uno nuevo y se realizan validacion de email en 
   * el servidor. Tras eso la asocia a un objeto multimedia con un rol determinado.
   *
   * Accion asincrona. Acceso privado. Parametros por POST resultado de formulario de edicion
   *
   */
  public function executeUpdaterelation()
  {
    $person = $this->update();
    if($this->hasRequestParameter('template')){
      $this->mm   = MmTemplatePeer::retrieveByPk($this->getRequestParameter('mm'));
      $this->role = RolePeer::retrieveByPk($this->getRequestParameter('role'));

      $aux = new MmTemplatePerson();
      $aux->setMmTemplateId($this->mm->getId());
      $aux->setRoleId($this->role->getId());
      $aux->setPersonId($person);
      try{
	$aux->save();
      }catch(Exception $e){
      }
      $component = 'listrelationtemplate';
    }else{
      $this->mm   = MmPeer::retrieveByPk($this->getRequestParameter('mm'));
      $this->role = RolePeer::retrieveByPk($this->getRequestParameter('role'));
      
      $aux = new MmPerson();
      $aux->setMmId($this->mm->getId());
      $aux->setRoleId($this->role->getId());
      $aux->setPersonId($person);
      try{
	$aux->save();
      }catch(Exception $e){
      }
      $component = 'listrelation';
    }

    $this->msg_alert = array('info', "Persona asociada correctamente al objeto multimedia \"" . $this->mm->getTitle()."\" con el rol " . $this->role->getName(). ". ");
    $this->preview = true;
    return $this->renderComponent('serials', $component);
  }

  /**
   * --  CREATERELATION -- /editar.php/serials/createrelation
   * Muesta el formulario de edicion de la persona nueva, que se asociara a un
   * determinado objeto multimedia con un rol determindo
   *
   * Accion asincrona. Acceso privado.
   *
   */
  public function executeCreaterelation()
  {
    $this->person = new Person();

    $this->person->setName(preg_replace('/\d+ - /', '', $this->getRequestParameter('name')));

    /*De donde se accede persons o video ?*/
    if($this->hasRequestParameter('template')){
      $this->url = 'serials/updaterelation?template=true&mm='.$this->getRequestParameter('mm').'&role='.$this->getRequestParameter('role');
      $this->update = $this->getRequestParameter('role').'_person_mms'; //EL MISMO QUE EN MM:(
      $this->role_id = $this->getRequestParameter('role');
    }else{
      $this->url = 'serials/updaterelation?mm='.$this->getRequestParameter('mm').'&role='.$this->getRequestParameter('role');
      $this->update = $this->getRequestParameter('role').'_person_mms';
      $this->role_id = $this->getRequestParameter('role');
    }

    $this->langs = sfConfig::get('app_lang_array', array('es'));

    $this->setTemplate('editPersons');
  }

  /**
   * COPIADA del modulo person
   * --  UP -- /editar.php/serials/up
   * Las personas asociadas a un determinado objeto multimedia con un rol especifico, tienen un orden determinado.
   * Esta accion permite ascender a una determinada persona en dicha lista
   *
   * Accion asincrona. Acceso privado. Parametros id, role y mm por URL.
   *
   */
  public function executeUp()
  {
    $this->mm = MmPeer::retrieveByPk($this->getRequestParameter('mm')); 
    $this->role = RolePeer::retrieveByPk($this->getRequestParameter('role'));
    
    if($this->hasRequestParameter('template')){
      $mmper = MmTemplatePersonPeer::retrieveByPK($this->mm->getId(), $this->getRequestParameter('id'), $this->role->getId());
      $this->forward404Unless($mmper);
      $template = 'listrelationtemplate';
    }else{
      $mmper = MmPersonPeer::retrieveByPK($this->mm->getId(), $this->getRequestParameter('id'), $this->role->getId());
      $this->forward404Unless($mmper);
      $template = 'listrelation';      
    }

    $mmper->moveUp();
    $mmper->save();

    return $this->renderComponent('serials', $template);
  }

  /**
   * COPIADA del modulo person
   * --  DOWN -- /editar.php/serials/down
   * Las personas asociadas a un determinado objeto multimedia con un rol especifico, tienen un orden determinado.
   * Esta accion permite descender a una determinada persona en dicha lista
   *
   * Accion asincrona. Acceso privado. Parametros id, role y mm por URL.
   *
   */
  public function executeDown()
  {
    $this->mm = MmPeer::retrieveByPk($this->getRequestParameter('mm')); 
    $this->role = RolePeer::retrieveByPk($this->getRequestParameter('role'));
    
    if($this->hasRequestParameter('template')){
      $mmper = MmTemplatePersonPeer::retrieveByPK($this->mm->getId(), $this->getRequestParameter('id'), $this->role->getId());
      $this->forward404Unless($mmper);
      $template = 'listrelationtemplate';
    }else{
      $mmper = MmPersonPeer::retrieveByPK($this->mm->getId(), $this->getRequestParameter('id'), $this->role->getId());
      $this->forward404Unless($mmper);
      $template = 'listrelation';      
    }

    $mmper->moveDown();
    $mmper->save();

    return $this->renderComponent('serials', $template);
  }


  /**
   * --  EDIT -- /editar.php/serials/edit
   *
   * Parametros por URL: id de la serie
   *
   */
  public function executeUnesco()
  {
    if ($this->hasRequestParameter('id'))
    {
        $category = CategoryPeer::retrieveByPK($this->getRequestParameter('id'));
        $this->mms = $category->getMms();
        
        $this->getUser()->setAttribute('serial', $this->getRequestParameter('id'));

        //si cambias de serie tienes que cambiar de pagina
        if((!$this->getUser()->hasAttribute('page', 'new_admin/serials'))|| (($this->getUser()->getAttribute('serial') != $this->getRequestParameter('serial'))))
            $this->getUser()->setAttribute('page', 1, 'new_admin/serials');
        if(!$this->getUser()->hasAttribute('id', 'new_admin/serials'))
            $this->getUser()->setAttribute('id', 0, 'new_admin/serials');
        if ($this->hasRequestParameter('id'))
            $this->getUser()->setAttribute('id', $this->getRequestParameter('id'), 'new_admin/serials');
        
        $this->getUser()->setAttribute('id', $this->getRequestParameter('id'), 'new_admin/serials');
    }
    return $this->renderComponent('mms', 'list');
  }

  /**
   * --  PREVIEW -- /editar.php/serials/preview
   *
   * Parametros por URL: id de la serie
   *
   */
  public function executePreview()
  {
    if ($this->hasRequestParameter('id'))
    {
      $this->getUser()->setAttribute('id', $this->getRequestParameter('id'), 'new_admin/serials');
    }
    return $this->renderComponent('serials', 'preview');
  }

  /**
   * --  UPDATE -- /editar.php/serials/update
   *
   * Parametros por POST: parameteros del formulario
   *
   */
  public function executeUpdate()
  {
    if (!$this->getRequestParameter('id'))
    {
      $serial = new Serial();
    }
    else
    {
      $serial = SerialPeer::retrieveByPk($this->getRequestParameter('id'));
      $this->forward404Unless($serial);
    }

    if ($this->getRequestParameter('publicdate'))
    {
      $timestamp = sfI18N::getTimestampForCulture($this->getRequestParameter('publicdate'), $this->getUser()->getCulture());
      
      $serial->setPublicdate($timestamp);
    }

    $serial->setAnnounce($this->getRequestParameter('announce', 0));
    $serial->setCopyright($this->getRequestParameter('copyright', 0));
    $serial->setSerialTypeId($this->getRequestParameter('serial_type_id', 0));
    $serial->setSerialTemplateId($this->getRequestParameter('serial_template_id', 1));

    $langs = sfConfig::get('app_lang_array', array('es'));
    foreach($langs as $lang){
      $serial->setCulture($lang);
      $serial->setTitle($this->getRequestParameter('title_' . $lang, 0));
      $serial->setSubtitle($this->getRequestParameter('subtitle_' . $lang, 0));
      $serial->setKeyword($this->getRequestParameter('keyword_' . $lang, ' '));
      $serial->setDescription($this->getRequestParameter('description_' . $lang, ' '));
      $serial->setHeader($this->getRequestParameter('header_' . $lang, ' '));
      $serial->setFooter($this->getRequestParameter('footer_' . $lang, ' '));
      $serial->setLine2($this->getRequestParameter('line2_' . $lang, ' '));
    }
    
    $serial->save();
    $this->msg_alert = array('info', "Serie \"" . $serial->getTitle() . "\" guardada OK.");

    

    return $this->renderComponent('serials', 'list');
  }


  /**
   * COPIADO de moduluo person
   * --  LINK -- /neweditar.php/serials/link
   * Asocia una persona ya creada a un objeto multimedia con un rol determinado.
   *
   * Accion asincrona. Acceso privado. Parametros por URL: ids de la persona y objeto multimedia a asociar e id del rol.
   *
   */
  public function executeLink()
  {
    if($this->hasRequestParameter("template")){
      $this->mm   = MmTemplatePeer::retrieveByPk($this->getRequestParameter('mm'));
      $this->role = RolePeer::retrieveByPk($this->getRequestParameter('role'));
      
      $aux = new MmTemplatePerson();
      $aux->setMmTemplateId($this->mm->getId());
      $aux->setRoleId($this->role->getId());
      $aux->setPersonId($this->getRequestParameter('person'));
      try{
	$aux->save();
	$this->msg_alert = array('info', 
			  "Persona asociada correctamente a la platilla con el rol " . $this->role->getName(). ". ");
      }catch(Exception $e){
	$this->msg_alert = array('error', 
			  "Persona ya asociada a la plantilla con el rol " . $this->role->getName(). ". ");
      } 
      
      return $this->renderComponent('serials', 'listrelationtemplate');
    }else{
      $this->mm   = MmPeer::retrieveByPk($this->getRequestParameter('mm'));
      $this->role = RolePeer::retrieveByPk($this->getRequestParameter('role'));
      
      $aux = new MmPerson();
      $aux->setMmId($this->mm->getId());
      $aux->setRoleId($this->role->getId());
      $aux->setPersonId($this->getRequestParameter('person'));

      try{
	$aux->save();
	$this->msg_alert = array('info', 
			   "Persona asociada correctamente al objeto multimedia \"" . $this->mm->getTitle()."\" con el rol " . $this->role->getName(). ". ");
      }catch(Exception $e){
	$this->msg_alert = array('error', 
			   "Persona ya asociada al objeto multimedia \"" . $this->mm->getTitle()."\" con el rol " . $this->role->getName(). ". ");
      } 
      var_dump($this->msg_alert);exit;
      return $this->renderComponent('serials', 'listrelation');
    }
}

  /**
   * --  CREATE -- /editar.php/serials/create
   *
   * Sin parametros
   *
   */
  public function executeCreate()
  {
    $serial = SerialPeer::createNew();
    
    $this->getUser()->setAttribute('serial', $serial->getId() );
    $this->getUser()->setAttribute('page', 1, 'new_admin/serials');
    $this->getUser()->setAttribute('sort', 'publicDate', 'new_admin/serials');
    $this->getUser()->setAttribute('type', 'desc', 'new_admin/serials');

    $this->msg_alert = array('info', "Serie de id :" . $serial->getId() . " creada con un objeto multimedia.");
    return $this->renderComponent('serials', 'list');
  }


  /**
   * --  DELETE -- /neweditar.php/serials/delete
   * OJO: Borra en cascada.
   *
   * Parametros por URL: identificador de la serie. o por POST: array en JSON de identificadores
   *
   */
  public function executeDelete()
  {
      if($this->hasRequestParameter('ids')){
          $serials = SerialPeer::retrieveByPKs(json_decode($this->getRequestParameter('ids')));
          
          foreach($serials as $serial){
              $serial->delete();
          }
          $this->msg_alert = array('info', "Series borradas.");
          
      }elseif($this->hasRequestParameter('id')){
          $serial = SerialPeer::retrieveByPk($this->getRequestParameter('id'));
          $this->msg_alert = array('info', "Serie \"" . $serial->getTitle() . "\" borrada.");
          $serial->delete();
      }
      
      $text = '<script type="text/javascript"> click_fila_edit_ruben("serial", null, -1)</script>';
      $this->getResponse()->setContent($this->getResponse()->getContent().$text);
      
      return $this->renderComponent('serials', 'list');
  }

  /**
   * --  DELETERELATION -- /editar.php/serials/deleterelation
   * Desasocia una persona ya creada a un objeto multimedia con un rol determinado.
   *
   * Accion asincrona. Acceso privado. Parametros por URL: ids de la persona y objeto multimedia a asociar e id del rol.
   *
   */
  public function executeDeleterelation()
  {
    $person = PersonPeer::retrieveByPk($this->getRequestParameter('id'));
    $this->mm = MmPeer::retrieveByPk($this->getRequestParameter('mm')); 
    $this->role = RolePeer::retrieveByPk($this->getRequestParameter('role'));
    $template = 'listrelation';

    if ($this->hasRequestParameter('role')){
      if($this->hasRequestParameter('template')){
	$mmPerson = MmTemplatePersonPeer::retrieveByPK( $this->mm->getId(), $person->getId(), $this->role->getId());
	$mmPerson->delete();
	$msg_c = "Persona desasocionada correctamente";
	$template = 'listrelationtemplate';
      }else{
	$mmPerson = MmPersonPeer::retrieveByPK( $this->mm->getId(), $person->getId(), $this->role->getId());
	$mmPerson->delete();
	$msg_c = "Persona desasocionada correctamente";
      }
    }

    //Solo Borro si no hay mas
    if (($person->countMmPersons() == 0)&&($person->countMmTemplatePersons() == 0)){
      $person->delete();
      $msg_c = "Persona ademas de desasociarse con el objeto multimedia se borro por no estar relacionada a nada mas";
    }

    $this->msg_alert = array('info', $msg_c);
    return $this->renderComponent('serials', $template);
  }
  
  /**
   * --  DELETE -- /neweditar.php/mms/deleteMms
   * OJO: Borra en cascada.
   *
   * Parametros por URL: identificador del obj. multimedia. o por POST: array en JSON de identificadores
   *
   */
  public function executeDeleteMms()
  {
      if($this->hasRequestParameter('ids')){
          $mms = array_reverse(MmPeer::retrieveByPKs(json_decode($this->getRequestParameter('ids'))));
          
          foreach($mms as $mm){
              $mm->delete();
          }
          
      }elseif($this->hasRequestParameter('id')){
          $mm = MmPeer::retrieveByPk($this->getRequestParameter('id'));
          $mm->delete();
      }

      $limit = 7;
      $page = $this->getRequestParameter('page', 1);
      
      if ($page < 1) {
          $page = 1;
      }
      
      
      $id = $this->getRequestParameter('id');
      $this->cat = CategoryPeer::retrieveByPk($id);
      if(!$this->cat) {
          return "Empty";
      }
      
      $this->mms = $this->getMms($this->cat->getId(), $limit, $limit * ($page - 1));
      
      $this->total_mms = $this->countMms($this->cat->getId());
      $this->limit = $limit;
      $this->page  = $page;
      $this->pages = ceil($this->total_mms / $limit);

      
      $text = '<script type="text/javascript"> click_fila_edit_ruben("mm", null, -1)</script>';
      $this->getResponse()->setContent($this->getResponse()->getContent().$text);
      return $this->renderComponent('serials', 'mmsList');
  }

  /**
   * --  DELETE -- /neweditar.php/serials/deleteCategory
   * OJO: Borra en cascada.
   *
   * Parametros por URL: identificador del obj. multimedia. y categoria a borrar de dicho objeto
   *
   */
  public function executeDelCategory()
  {
      $mm = MmPeer::retrieveByPKWithI18n($this->getRequestParameter('id'), $this->getUser()->getCulture());
      $this->forward404Unless($mm);

      $category = CategoryPeer::retrieveByPKWithI18n($this->getRequestParameter('category'), $this->getUser()->getCulture());
      $this->forward404Unless($category);

      $del_cats = array();

      if($category->delMmId($mm->getId())){
          $del_cats[] = $category;
      }

      $json = array('deleted' => array(), 'recommended' => array());
      foreach($del_cats as $n){
          $json['deleted'][] = array(
                                     'id' => $n->getId(),
                                     'cod' => $n->getCod(),
                                     'name' => $n->getName()
                                     );
      }

      $this->getResponse()->setContentType('application/json');
      return $this->renderText(json_encode($json));
  }
  
  public function executeTwitter()
  {
    $serial = SerialPeer::retrieveByPk($this->getRequestParameter('id'));
    $this->forward404Unless($serial);

      $mensaje = 'Nuevo #UvigoTV "'.substr($serial->getTitle(), 0, 90).'" http://tv.uvigo.es/es/serial/' . $serial->getSerialId() . '.html#';

      define('_CONSUMER_KEY','aiUhqbftQ11nJddHzt6s5w');
      define('_CONSUMER_SECRET','d1ZQ9Yzb0K1ufMzn3cP7UE41bI6NWWFofHTqCwIrAo4');
      define('_OAUTH_TOKEN','107822829-pWTfMsEUkrZILtWOC4Qv6nm2eHl8ysy4TcX4A1Ye');
      define('_OAUTH_TOKEN_SECRET','q7cVu6G1CUGoKeyVKe0I9EuQEdiFjzfKw3Bbv39uYWE');
    

      $connection = new TwitterOAuth(_CONSUMER_KEY, _CONSUMER_SECRET,_OAUTH_TOKEN, _OAUTH_TOKEN_SECRET);
      $twitter=$connection->post('statuses/update', array('status' =>($mensaje)));
    return $this->renderComponent('serials', 'list');
  }


 /**
   * --  EPUB -- /editar.php/serials/epub
   *
   * Parametros por URL: identificador de la serie.
   *
   */


 public function executeEpub()
  {
    $serial = SerialPeer::retrieveByPk($this->getRequestParameter('id'));
    $this->forward404Unless($serial);
    
    
    //debemos crear los videos mp4 de la serie y copiarlos en la carpeta OEBPS
    
    $epub = new epubPumukit($serial);
    $listo= $epub->crea_mp4();
    
    //no deberíamos seguir hasta que estuvieran todos los videos de la serie codificados. 
    //Una vez que esto ocurra, los copiamos en la carpeta OEBPS y seguimos con el proceso normal
    
    if ($listo === false) return $this->renderText('<span style="font-weight:bolder; color:red">Error: No hay objetos multimedia p&uacute;blicos o no existe el master</span>');
  
    if ($listo==1) $epub->fin_epub();

    //mandamos un mail para avisar de que el .epub ya se ha creado (falta cambiar la persona a la cual se le manda el mail)
    //$func="epub";
    //$serial->envia_mail($func);
    
    return $this->renderComponent('serials', 'list');
  }


  /**
   * --  DVD -- /editar.php/serials/dvd
   *
   * Parametros por URL: identificador de la serie.
   *
   */

  public function executeDvd()
  {
    $serial = SerialPeer::retrieveByPk($this->getRequestParameter('id'));
    $this->forward404Unless($serial);

    DvdPumukit::dvdmaker($serial);
    

    return $this->renderComponent('serials', 'list');
  }




  /**
   * --  COPY -- /editar.php/serials/copy
   *
   * Parametros por URL: identificador de la serie.
   *
   */
  public function executeCopy()
  {
    $serial = SerialPeer::retrieveByPk($this->getRequestParameter('id'));
    $this->forward404Unless($serial);

    $serial2 = $serial->copy();
    $this->getUser()->setAttribute('serial', $serial2->getId() ); //selecione el nuevo                                                                                                
    return $this->renderComponent('serials', 'list');
  }


  /**
   * --  ANNOUNCE -- /editar.php/serials/announce
   *
   * Parametros por URL: identificador de la serie.
   *
   */
  public function executeAnnounce()
  {
    if($this->hasRequestParameter('ids')){
      $serials = SerialPeer::retrieveByPKs(json_decode($this->getRequestParameter('ids')));

      foreach($serials as $serial){
	$serial->setAnnounce(!$serial->getAnnounce());
	$serial->save();
      }
    $this->msg_alert = array('info', "Series anunciadas/desanunciada correctamente.");

    }elseif($this->hasRequestParameter('id')){
      $serial = SerialPeer::retrieveByPk($this->getRequestParameter('id'));
      $serial->setAnnounce(!$serial->getAnnounce());
      $serial->save();
      $this->msg_alert = array('info', "Serie \"" . $serial->getTitle() . "\" anunciada/desanunciada.");
    }

    return $this->renderComponent('serials', 'list');
  }


  /**
   * --  PREVIEWALL -- /editar.php/serials/previewall
   * Muestra  una vista previa de la representacion de la serie. De como se va a mostrar, 
   * si todos lo videos son publicos, una vez publicado.
   *
   * Parametros por URL: identificador de la serie. Layout: tvlayout
   *
   */
  public function executePreviewall()
  {
    $this->setLayout('tvlayout');
    //ADD CSS

    //$this->

    $c = new Criteria();
    $c->add(SerialPeer::ID, $this->getRequestParameter('id', $this->getUser()->getAttribute('serial')));
    list($aux) = SerialPeer::doSelectWithI18n($c, $this->getUser()->getCulture());
    $this->serial = $aux;
    $this->forward404Unless($this->serial);

    $this->mms = $this->serial->getMms();
    $this->roles = RolePeer::doSelectWithI18n(new Criteria(), $this->getUser()->getCulture());

    $this->getResponse()->setTitle($this->serial->getTitle());
    $this->getResponse()->addMeta('keywords', $this->serial->getKeyword());
  }


  /**
   * --  CHANGE PUB -- /editar.php/serials/changePub
   *
   * Parametros por URL: identificador de la serie. Layout: none
   *
   */
  public function executeChangePub()
  {
    $this->serial = SerialPeer::retrieveByPk($this->getRequestParameter('serial'));
    $this->forward404Unless($this->serial);
  }


  /**
   * --  UPDATE PUB -- /editar.php/serials/updatePub
   *
   * Parametros por URL: identificadoes de los objeos multimedia y nuevo estado. Layout: none
   *
   */
  public function executeUpdatePub()
  {
    $mms = array_reverse(MmPeer::retrieveByPKs(json_decode($this->getRequestParameter('ids'))));
    
    $status_id = $this->getRequestParameter('status', 0);
    //$return_js = '<script type="text/javascript">$("filters_anounce").setValue('.$mm->getStatusId().')</script>'; 
    //$return_js = "<script type=\"text/javascript\"></script>";

    $error = -1000;

    foreach($mms as $mm){
      $aux = EncoderWorkflow::changeStatus($mm, $status_id, $this->getUser()->getAttribute('user_type_id'));      
      if($aux < 0){
	$error = max($aux, $error);
      }
    }

      $new_status = $mm->getSerial()->getMmStatus();
      $return_js = '<script type="text/javascript">$("table_serials_status_'.$mm->getSerial()->getId().'").src="/images/admin/bbuttons/'.$new_status['min'].$new_status['max'].'_inline.gif"; Modalbox.resizeToContent();</script>';

    switch($error){
    case -1:
      return $this->renderText('<span style="font-weight:bolder; color:red">Error</span> Error de permisos.'. $return_js);
    case -2:
      return $this->renderText('<span style="font-weight:bolder; color:red">Error</span> El Objeto Multimedia no tiene master.'. $return_js);
    case -3:
      return $this->renderText('<span style="font-weight:bolder; color:red">Error</span> El Objeto Multimedia no tiene archivos de video. <a href="#mediaMmHash" onclick="menuTab.select(\'mediaMm\'); update_file.stop(); return false;"> Se genera automaticamente</a>'. $return_js);
    case -4:
      return $this->renderText('<span style="font-weight:bolder; color:red">Error</span> El Objeto Multimedia no tiene archivo con perfil <em>podcast_video</em><a href="#mediaMmHash" onclick="menuTab.select(\'mediaMm\'); update_file.stop(); return false;"> Se genera automaticamente</a>'. $return_js);
    case -5:
      return $this->renderText('<span style="font-weight:bolder; color:red">Error</span> El Objeto Multimedia no tiene archivo con perfil <em>podcast_audio.</em><a href="#mediaMmHash" onclick="menuTab.select(\'mediaMm\'); update_file.stop(); return false;"> Se genera automaticamente</a>'. $return_js);
    case -6:
      return $this->renderText('<span style="font-weight:bolder; color:red">Error</span> El Objeto Multimedia no esta catalogado en iTunesU'. $return_js);
    default:
      return $this->renderText("Estado actualizado" . $return_js);
    }
  }

  protected function processSort(Criteria $c)
  {
      if ($this->getRequestParameter('sort')){
          $this->getUser()->setAttribute('sort', $this->getRequestParameter('sort'), 'new_admin/serials');
          $this->getUser()->setAttribute('type', $this->getRequestParameter('type', 'asc'), 'new_admin/serials');
      }

      if ($sort_column = $this->getUser()->getAttribute('sort', null, 'new_admin/serials')){
          try{
              $sort_column = SerialPeer::translateFieldName($sort_column, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_COLNAME);
          }catch(Exception $e){
              try{
                  $sort_column = SerialI18nPeer::translateFieldName($sort_column, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_COLNAME);
              }catch(Exception $e){
              }
          }
          if ($this->getUser()->getAttribute('type', 'asc', 'new_admin/serials') == 'asc'){
              $c->addAscendingOrderByColumn($sort_column);
          }else{
              $c->addDescendingOrderByColumn($sort_column);
          }
      }
  }

  private function getMms($cat_id, $limit, $offset, $parent = null)
  {
      $c = new Criteria();
      if ($cat_id){
          $c->addJoin(MmPeer::ID, CategoryMmPeer::MM_ID);
          $c->add(CategoryMmPeer::CATEGORY_ID, $cat_id);
      }
      $c->addAscendingOrderByColumn(MmPeer::ID);
      if($parent) {
          $c->addAnd(CategoryPeer::TREE_LEFT, $parent->getLeftValue(), Criteria::GREATER_THAN);
          $c->addAnd(CategoryPeer::TREE_RIGHT, $parent->getRightValue(), Criteria::LESS_THAN);
          $c->addAnd(CategoryPeer::SCOPE, $parent->getScopeIdValue(), Criteria::EQUAL);

      }

      $c->setDistinct(true);
      $c->setLimit($limit);
      $c->setOffset($offset);

      return MmPeer::doSelect($c);
  }
  private function countMms($cat_id, $parent = null)
  {
      $c = new Criteria();

      if ($cat_id){
          $c->addJoin(MmPeer::ID, CategoryMmPeer::MM_ID);
          $c->add(CategoryMmPeer::CATEGORY_ID, $cat_id);
      }
      $c->addAscendingOrderByColumn(MmPeer::ID);
      if($parent) {
          $c->addAnd(CategoryPeer::TREE_LEFT, $parent->getLeftValue(), Criteria::GREATER_THAN);
          $c->addAnd(CategoryPeer::TREE_RIGHT, $parent->getRightValue(), Criteria::LESS_THAN);
          $c->addAnd(CategoryPeer::SCOPE, $parent->getScopeIdValue(), Criteria::EQUAL);

      }

      if($this->getUser()->getAttribute('only', null, 'explore') == 'audio') {
          $c->addJoin(MmPeer::ID, FilePeer::MM_ID);
          $c->add(FilePeer::AUDIO, 1);
      }

      if($this->getUser()->getAttribute('only', null, 'explore') == 'video') {
          $c->addJoin(MmPeer::ID, FilePeer::MM_ID);
          $c->add(FilePeer::AUDIO, 0);
      }

      $c->setDistinct(true);

      return MmPeer::doCount($c);
  }
  /**
   *  Funcion de actualizar COPIADA de modulo persons
   **/
  private function update()
  {
    if (!$this->getRequestParameter('id'))
    {
      $person = new Person();
    }
    else
    {
      $person = PersonPeer::retrieveByPk($this->getRequestParameter('id'));
      $this->forward404Unless($person);
    }

    $person->setName($this->getRequestParameter('name', ' '));
    $person->setEmail($this->getRequestParameter('email', ' '));
    $person->setWeb($this->getRequestParameter('web', ' '));
    $person->setPhone($this->getRequestParameter('phone', ' '));

    $langs = sfConfig::get('app_lang_array', array('es'));
    foreach($langs as $lang){
      $person->setCulture($lang);
      $person->setHonorific($this->getRequestParameter('honorific_'. $lang, ' '));
      $person->setFirm($this->getRequestParameter('firm_'. $lang, ' '));
      $person->setPost($this->getRequestParameter('post_'. $lang, ' '));
      $person->setBio($this->getRequestParameter('bio_'. $lang, ' '));
    }
    $person->save();

    $this->getUser()->setAttribute('person_id', $person->getId(), 'new_admin/person');
    return $person->getId();
  }

  /*
   *COPIADA del modulo pics
   *
   */
  protected function inicialize($dir = false)
  {
    if ($this->hasRequestParameter('mm')){ 
      $this->object_id = $this->getRequestParameter('mm');      
      $this->url= 'serials/listPics?preview=true&mm='.$this->object_id;
      $this->upload = 'pic_mms';
      $this->que = 'mm';
      if($dir){
	$aux = MmPeer::retrieveByPk($this->object_id);
	$currentDir = 'Serial/' . $aux->getSerialId() . '/Video/' . $this->object_id;  
      }else{
	$currentDir = 'Video/' . $this->object_id;  
      }
    }elseif ($this->hasRequestParameter('serial')){
      $this->object_id = $this->getRequestParameter('serial');      
      $this->url= 'serials/listPics?preview=true&serial='.$this->object_id;
      $this->upload = 'pic_serials';
      $this->que = 'serial';
      $currentDir = 'Serial/' . $this->object_id;
    }elseif ($this->hasRequestParameter('channel')){ 
      $this->object_id = $this->getRequestParameter('channel');      
      $this->url= 'serials/listPics?channel='.$this->object_id;
      $this->upload = 'pic_channels';
      $this->que = 'channel';
      $currentDir = 'Channel/' . $this->object_id;  
    }else{
      $this->forward404();
    }
    
    return $currentDir;
  }

  /*
   *
   */
  protected function sanitizeDir($dir)
  {
    return preg_replace('/[^a-z0-9_-]/i', '_', $dir);
  }

  protected function sanitizeFile($file)
  {
    return preg_replace('/[^a-z0-9_\.-]/i', '_', $file);
  }

}
