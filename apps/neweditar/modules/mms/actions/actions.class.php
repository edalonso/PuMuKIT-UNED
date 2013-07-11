<?php
/**
 * MODULO MMS ACTIONS. 
 * Modulo de administracion de los objetos multimedia. Permite administrar
 * los objetos multimedia de una serie. Su formulario de edicion se divide en 
 * cuatro pestanas:
 *   -Metadatos
 *   -Areas de conocimiento
 *   -Personas
 *   -Multimedia 
 *
 * @package    uned
 * @subpackage mms
 * @author     Ruben Gonzalez Gonzalez (rubenrua at uvigo dot com)
 * @version    1.0
 */
class mmsActions extends sfActions
{
  /**
   * --  INDEX -- /editar.php/mms/index/serial/:id
   *
   * Accion por defecto del modulo. Layout: layout
   * Parametros por URL: identificador de la serie.
   *
   */
  public function executeIndex()
  {
    $this->serial = SerialPeer::retrieveByPKWithI18n($this->getRequestParameter('serial'), $this->getUser()->getCulture());
    $this->forward404Unless($this->serial);

    sfConfig::set('serial_menu','active');
   
    $this->getUser()->setAttribute('serial', $this->getRequestParameter('serial'));

    //si cambias de serie tienes que cambiar de pagina
    if((!$this->getUser()->hasAttribute('page', 'tv_admin/mm'))||
       (($this->getUser()->getAttribute('serial') != $this->getRequestParameter('serial'))))
      $this->getUser()->setAttribute('page', 1, 'tv_admin/mm');
    if(!$this->getUser()->hasAttribute('id', 'tv_admin/mm'))
      $this->getUser()->setAttribute('id', 0, 'tv_admin/mm');
    if ($this->hasRequestParameter('id'))
      $this->getUser()->setAttribute('id', $this->getRequestParameter('id'), 'tv_admin/mm');
  }


  /**
   * --  LIST -- /editar.php/mms/list
   *
   * Sin parametros
   *
   */
  public function executeList()
  {
    return $this->renderComponent('mms', 'list');
  }


  /**
   * --  EDIT -- /editar.php/mms/edit/id/:id
   *
   * Parametros por URL: Identificador el objeto multimedia
   *
   */
  public function executeEdit()
  {
    if ($this->hasRequestParameter('id'))
    {
      $this->getUser()->setAttribute('id', $this->getRequestParameter('id'), 'tv_admin/mm');
    }
    return $this->renderComponent('mms', 'edit');
  }


  /**
   * --  PREVIEW -- /editar.php/mms/preview/id/:id
   *
   * Parametros por URL: Identificador el objeto multimedia
   *
   */
  public function executePreview()
  {
    //session _request ID o 0 si no hay
    $this->roles = RolePeer::doSelectWithI18n(new Criteria());
    $this->mm = MmPeer::retrieveByPk($this->getRequestParameter('id'));
    return $this->renderPartial('mms/preview');
  }


  /**
   * --  UPDATE -- /editar.php/mms/update
   *
   * Parametros por POST: Serializacion de formulario
   *
   */
  public function executeUpdate()
  {
    if (!$this->getRequestParameter('id'))
    {
      $mm = new Mm();
    }
    else
    {
      $mm = MmPeer::retrieveByPk($this->getRequestParameter('id'));
      $this->forward404Unless($mm);
    }

    
    /* STATUS */
    /*if ($this->getUser()->getAttribute('user_type_id', 1) == 0){
      $mm->setStatusId($this->getRequestParameter('status', 0));
    }*/

    /* DATES */
    if ($this->getRequestParameter('publicdate'))
    {
      $timestamp = sfI18N::getTimestampForCulture($this->getRequestParameter('publicdate'), $this->getUser()->getCulture());      
      $mm->setPublicdate($timestamp);
    }

    if ($this->getRequestParameter('recorddate'))
    {
      $timestamp = sfI18N::getTimestampForCulture($this->getRequestParameter('recorddate'), $this->getUser()->getCulture());      
      $mm->setRecorddate($timestamp);
    }
    
    /* METADATA */
    $mm->setAnnounce($this->getRequestParameter('announce', 0));
    $mm->setImportant($this->getRequestParameter('important', 0));
    $mm->setSubserial($this->getRequestParameter('subserial', 0));
    $mm->setCopyright($this->getRequestParameter('copyright', 0));
    $mm->setPrecinctId($this->getRequestParameter('precinct_id', 0));
    $mm->setGenreId($this->getRequestParameter('genre_id', 0));
    $mm->setBroadcastId($this->getRequestParameter('broadcast_id', 0));

    $langs = sfConfig::get('app_lang_array', array('es'));
    foreach($langs as $lang){
      $mm->setCulture($lang);
      $mm->setTitle($this->getRequestParameter('title_' . $lang, 0));
      $mm->setSubtitle($this->getRequestParameter('subtitle_' . $lang, 0));
      $mm->setKeyword($this->getRequestParameter('keyword_' . $lang, ' '));
      $mm->setDescription($this->getRequestParameter('description_' . $lang, ' '));
      $mm->setLine2($this->getRequestParameter('line2_' . $lang, ' '));
      $mm->setSubserialTitle($this->getRequestParameter('subserial_title_' . $lang, ' '));
    }
    
    $mm->save();

    //actualiza metadatos en youtube
    if (($mmYt = $mm->getMmYoutube()) != null){
      $yt = new YoutubePumukit();
      $yt->updateMetadata($mm, $mmYt);
    }


    return $this->renderComponent('mms', 'list');
  }



  /**
   * --  UPDATE_PUB -- /editar.php/mms/update_pub
   *
   * Parametros por GET: MM_ID y STATUS
   *
   */
  public function executeUpdate_pub()
  {
    $mm = MmPeer::retrieveByPk($this->getRequestParameter('id'));
    $this->forward404Unless($mm);

    $status_id = $this->getRequestParameter('status', 0);
    $return_js = '<script type="text/javascript">$("filters_anounce").setValue('.$mm->getStatusId().')</script>'; 
    $return_js_ok = '<script type="text/javascript">$("table_mms_status_'.$mm->getId().'").src="/images/admin/bbuttons/'.$status_id.'_inline.gif"</script>';
    
    $error = EncoderWorkflow::changeStatus($mm, $status_id, $this->getUser()->getAttribute('user_type_id'));
    
    switch($error){
    case -1:
      return $this->renderText('<span style="font-weight:bolder; color:red">Error</span> Error de permisos.' . $return_js);
    case -2:
      return $this->renderText('<span style="font-weight:bolder; color:red">Error</span> El Objeto Multimedia no tiene master.' . $return_js);
    case -3:
      return $this->renderText('<span style="font-weight:bolder; color:red">Error</span> El Objeto Multimedia no tiene archivos de video. <a href="#mediaMmHash" onclick="menuTab.select(\'mediaMm\'); update_file.stop(); return false;"> Se genera automaticamente</a>' . $return_js);
    case -4:
      return $this->renderText('<span style="font-weight:bolder; color:red">Error</span> El Objeto Multimedia no tiene archivo con perfil <em>podcast_video</em><a href="#mediaMmHash" onclick="menuTab.select(\'mediaMm\'); update_file.stop(); return false;"> Se genera automaticamente</a>' . $return_js);
    case -5:
      return $this->renderText('<span style="font-weight:bolder; color:red">Error</span> El Objeto Multimedia no tiene archivo con perfil <em>podcast_audio.</em><a href="#mediaMmHash" onclick="menuTab.select(\'mediaMm\'); update_file.stop(); return false;"> Se genera automaticamente</a>' . $return_js);
    case -6:
      return $this->renderText('<span style="font-weight:bolder; color:red">Error</span> El Objeto Multimedia no esta catalogado en iTunesU' . $return_js);
    default:
      return $this->renderText("Estado actualizado" . $return_js_ok);
    }
  }


  public function executeUpdate_yt()
  {
    $mm = MmPeer::retrieveByPk($this->getRequestParameter('id'));
    $this->forward404Unless($mm);
    $mmYt = $mm->getMmYoutube();
    $lista = $this->getRequestParameter('list');
    //    $return_js_ok = '<script type="text/javascript">$("table_mms_status_'.$mm->getId().'").src="/images/admin/bbuttons/'.$status_id.'_inline.gif"</script>';


    if($this->getRequestParameter('youtube') == 'true') {
      //viene marcado el checkbox de yt
      if ($mmYt == null) {
	$command = 'nohup php ' . sfConfig::get('sf_bin_dir') . '/youtube/youtube_sube.php ' . $mm->getId().' "'.$lista.'" 1> /tmp/1 2> /tmp/2 & echo $!';
        $salida = shell_exec($command);
	return  $this->renderText("Va a subir el v&iacute;deo a YouTube");
      }
      else {
	if ($lista != $mmYt->getYoutubePlaylist()){
	  $yt = new YoutubePumukit();
	  $result = $yt->moveFromListToList($mm, $mmYt->getYoutubePlaylist(), $lista);
	}
	return  $this->renderText("El video va ser cambiado de lista de reproducci&oacute;n");
	/*
	 Si ya hay objeto creado hay video en youtube, no tiene que volver a subir
	*/
      }
       
    }

    else {
      //no viene marcado el checbox de yt
      if ($mmYt != null) {
	$yt = new YoutubePumukit();
	$yt->delete($mm);
	return $this->renderText("El v&iacute;deo ha sido eliminado de YouTube");
      }
	return $this->renderText("El v&iacute;deo no se puede borrar de Youtube porque no se ha subido");      
      //si el objeto es null no se hace nada porque no hay video en youtube
    }
  }


  /**
   * --  ITUNES_ON -- /editar.php/mms/ituneson
   *
   * Parametros por GET: MM_ID 
   *
   */
  public function executeItuneson()
  {
    $mm = MmPeer::retrieveByPk($this->getRequestParameter('id'));
    $this->forward404Unless($mm);

    /* Asegurarme que esta en la serie ok */
    if($mm->getStatusId() != 4){
      return $this->renderText('<span style="font-weight:bolder; color:red">Error</span> El estado del objeto multimedia tiene que incluir ItunesU.');
    }
    
    /* Si estado en mayor que (iTunes) compruebo que esiste podcast_audio** */
    if(count($mm->getGrounds(3)) == 0){
      return $this->renderText('<span style="font-weight:bolder; color:red">Error</span> El Objeto Multimedia no esta catalogado en iTunesU');
    }

    /*Ver si la serie esta publicada */
    $this->itunes = $mm->getSerial()->getSerialItuness(); 
    if(!empty($this->itunes)){
      return $this->renderPartial('mms/itunes_list');
    }

    itunes::AddCourse($mm->getSerialId());

    /*TEST */
    $this->itunes = $mm->getSerial()->getSerialItuness();
    return $this->renderPartial('mms/itunes_list');
    /*FIXME LISTAR ENLACES BIEN*/
    //return $this->renderText("Publicacion correcta");
  }



  /**
   * --  ITUNES_OFF -- /editar.php/mms/itunesoff
   *
   * Parametros por GET: MM_ID 
   *
   */
  public function executeItunesoff()
  {
    $mm = MmPeer::retrieveByPk($this->getRequestParameter('id'));
    $this->forward404Unless($mm);

    /* Asegurarme que esta en la serie ok */
    if($mm->getStatusId() != 4){
      return $this->renderText('<span style="font-weight:bolder; color:red">Error</span> El estado del objeto multimedia tiene que incluir ItunesU.');
    }
    
    /*Ver si la serie esta publicada */
    $this->itunes = $mm->getSerial()->getSerialItuness(); 
    if(!empty($this->itunes)){
      foreach($this->itunes as $hi){
	itunes::DeleteCourse($hi->getItunesId());
      }
    }
    

    return $this->renderText("Publicacion correcta");
  }


  /**
   * --  CREATE -- /editar.php/mms/create/serial/:serial
   * Al contrario que en otros modulos, no se muestra el formulario vacio para crear
   * despues el objeto, sino que se crea directametne con los valores por defecto
   *
   * Parametros por URL: Identificador de la serie.
   *
   */
  public function executeCreate()
  {
    MmPeer::createNew($this->getUser()->getAttribute('serial'));

    return $this->renderComponent('mms', 'list');
  }


  /**
   * --  DELETE -- /editar.php/mms/delete
   * OJO: Borra en cascada.
   *
   * Parametros por URL: identificador del obj. multimedia. o por POST: array en JSON de identificadores
   *
   */
  public function executeDelete()
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

    $text = '<script type="text/javascript"> click_fila_edit("mm", null, -1)</script>';
    $this->getResponse()->setContent($this->getResponse()->getContent().$text);
    return $this->renderComponent('mms', 'list');
  }


  /**
   * --  COPY -- /editar.php/mms/copy
   *
   * Parametros por URL: identificador del objeto multimedia
   *
   */
  public function executeCopy()
  {
    $mm = MmPeer::retrieveByPk($this->getRequestParameter('id'));
    $this->forward404Unless($mm);

    $mm2 = $mm->copy();
    $this->getUser()->setAttribute('mm', $mm2->getId() ); //selecione el nuevo                                                                                                
    return $this->renderComponent('mms', 'list');
  }


  /**
   * --  ANNOUNCE -- /editar.php/mms/announce
   *
   * Parametros por POST: array en JOSN de identificadores de objeto multimedia  
   *
   */
  public function executeAnnounce()
  {
    if($this->hasRequestParameter('ids')){
      $mms = MmPeer::retrieveByPKs(json_decode($this->getRequestParameter('ids')));

      foreach($mms as $mm){
	$mm->setAnnounce(!$mm->getAnnounce());
	$mm->save();
      }

    }elseif($this->hasRequestParameter('id')){
      $mm = MmPeer::retrieveByPk($this->getRequestParameter('id'));
      $mm->setAnnounce(!$mm->getAnnounce());
      $mm->save();
    }

    return $this->renderComponent('mms', 'list');
  }




  /**
   * --  ORDERBY -- /editar.php/serials/orderby
   *
   * Parametros por URL: identificador de la serie y tipo de ordenacion (rec_asc, rec_des, pub_asc, pub_des).
   *
   */
  public function executeOrderby()
  {
    $serial = SerialPeer::retrieveByPKWithI18n($this->getRequestParameter('serial'), $this->getUser()->getCulture());
    $this->forward404Unless($serial);
    
    $c = new Criteria();
    $c->add(MmPeer::SERIAL_ID, $serial->getId());

    if('rec_asc' == $this->getRequestParameter('type')){
      $c->addAscendingOrderByColumn(MmPeer::RECORDDATE);
      $this->msg_alert = array('info', "Serie \"" . $serial->getTitle() . "\" reordenada por fecha de grabacion de modo ascendiente.");
    }elseif('rec_des' == $this->getRequestParameter('type')){
      $c->addDescendingOrderByColumn(MmPeer::RECORDDATE);
      $this->msg_alert = array('info', "Serie \"" . $serial->getTitle() . "\" reordenada por fecha de grabacion de modo descendiente.");
    }elseif('pub_asc' == $this->getRequestParameter('type')){
      $c->addAscendingOrderByColumn(MmPeer::PUBLICDATE);
      $this->msg_alert = array('info', "Serie \"" . $serial->getTitle() . "\" reordenada por fecha de publicacion de modo ascendiente.");
    }elseif('pub_des' == $this->getRequestParameter('type')){
      $c->addDescendingOrderByColumn(MmPeer::PUBLICDATE);
      $this->msg_alert = array('info', "Serie \"" . $serial->getTitle() . "\" reordenada por fecha de publicacion de modo ascendiente.");
    }

    $mms = MmPeer::doSelect($c);
    $rank = 1;
    foreach($mms as $mm){
      $mm->setRank($rank++);
      $mm->save();
    }

    return $this->renderComponent('mms', 'list');
  }


  /**
   * --  CHANGE -- /editar.php/mms/change
   *
   * Parametros por POST: array en JOSN de ids y nuevo estado
   *
   */
  public function executeChange()
  {
    $status = intval($this->getRequestParameter('status'));
    if(($status < -1)||($status > 3)) return $this->renderComponent('mms', 'list');
    if ($this->getUser()->getAttribute('user_type_id', 1) != 0) return $this->renderComponent('mms', 'list');


    if($this->hasRequestParameter('ids')){
      $mms = MmPeer::retrieveByPKs(json_decode($this->getRequestParameter('ids')));


      foreach($mms as $mm){
	$mm->setStatusId($status);
	$mm->save();
      }
      

    }elseif($this->hasRequestParameter('id')){
      $mm = MmPeer::retrieveByPk($this->getRequestParameter('id'));
      $mm->setStatusId($status);
      $mm->save();

    }elseif($this->getRequestParameter('all')){
      $mms = MmPeer::doSelect(new Criteria());
	    
      foreach($mms as $mm){
	$mm->setStatusId($status);
	$mm->save();
      }
    }

    return $this->renderComponent('mms', 'list');
  }

  /**
   * --  UP -- /editar.php/mms/up
   *
   * Parametros por URL: identificador del objeto mulimedia
   *
   */
  public function executeUp()
  {
    $mm = MmPeer::retrieveByPk($this->getRequestParameter('id'));
    $this->forward404Unless($mm);

    $mm->moveUp();
    $mm->save();

    //return $this->redirect('mms/list');
    return $this->renderComponent('mms', 'list');
  }


  /**
   * --  DOWN -- /editar.php/mms/down
   *
   * Parametros por URL: identificador del objeto mulimedia
   *
   */
  public function executeDown()
  {
    $mm = MmPeer::retrieveByPk($this->getRequestParameter('id'));
    $this->forward404Unless($mm);

    $mm->moveDown();
    $mm->save();

    //return $this->redirect('mms/list');
    return $this->renderComponent('mms', 'list');
  }


  /**
   * --  TOP -- /editar.php/mms/top
   *
   * Parametros por URL: identificador del objeto mulimedia
   *
   */
  public function executeTop()
  {
    $mm = MmPeer::retrieveByPk($this->getRequestParameter('id'));
    $this->forward404Unless($mm);

    $mm->moveToTop();
    $mm->save();

    return $this->renderComponent('mms', 'list');
  }


  /**
   * --  BUTTOM -- /editar.php/mms/buttom
   *
   * Parametros por URL: identificador del objeto mulimedia
   *
   */
  public function executeBottom()
  {
    $mm = MmPeer::retrieveByPk($this->getRequestParameter('id'));
    $this->forward404Unless($mm);

    $mm->moveToBottom();
    $mm->save();

    //return $this->redirect('mms/list');
    return $this->renderComponent('mms', 'list');
  }

  /**
   * --  ADDGROUND -- /editar.php/mms/addground
   *
   * Parametros por URL: identificador del objeto mulimedia e identificador del area de con.
   *
   */
  public function executeAddGround()
  {
    $mm_id = $this->getRequestParameter('id', 0);  //OJO SI NO EXISTEN
    $ground_id = $this->getRequestParameter('ground', 0);  //OJO SI NO EXISTEN
    
    $this->mm = MmPeer::retrieveByPk($mm_id); 
    $this->mm->setGroundId($ground_id);
    
    $c = new Criteria();
    $c->add(GroundTypePeer::DISPLAY, true);
    $c->addAscendingOrderByColumn(GroundTypePeer::RANK);
    $this->groundtypes = GroundTypePeer::doSelectWithI18n($c, 'es'); 
    foreach ($this->groundtypes as $groundt){
      if ($groundt->getName()== 'Itunes Hijo'){
      	 $type_children=$groundt->getId();
	 }
	 }
    
    $cg = new Criteria();
    $cg->addAscendingOrderByColumn(GroundPeer::COD);
    $this->grounds = GroundPeer::doSelectWithI18n($cg, 'es');
    
    $this->grounds_sel = $this->mm->getGrounds();
    
  

    
    $cc = new Criteria();
    $cc->addAscendingOrderByColumn(GroundPeer::COD);
    $cc=GroundPeer::doSelectWithI18n($cc, 'es');
    foreach ($cc as $cx){
            $ids=$cx->getRelationsId();
            foreach($ids as $id){
                   if ($id==$ground_id){
               $ground_padre=$cx->getId();
               }
            }
    }

    $caux = GroundPeer::doSelectRelationsWithI18n($ground_padre, 'es');	
    foreach ($caux as $aux){
    if ($aux->getGroundTypeId()==$type_children){
       $this->children=$caux;
    }	
    }
  
    return $this->renderPartial('edit_ground');
  }





  /**
   * --  DELETEGROUND -- /editar.php/mms/deleteground
   *
   * Parametros por URL: identificador del objeto mulimedia e identificador del area de con.
   *
   */
  public function executeDeleteGround()
  {
    $mm_id = $this->getRequestParameter('id', 0);  //OJO SI NO EXISTEN
    $ground_id = $this->getRequestParameter('ground', 0);  //OJO SI NO EXISTEN

    $gv = GroundMmPeer::retrieveByPK($ground_id, $mm_id);
    if (isset($gv)) $gv->delete();

    
    $c = new Criteria();
    $c->add(GroundTypePeer::DISPLAY, true);
    $c->addAscendingOrderByColumn(GroundTypePeer::RANK);
    $this->groundtypes = GroundTypePeer::doSelectWithI18n($c, 'es'); 
    foreach ($this->groundtypes as $groundt){
      if ($groundt->getName()== 'Itunes Hijo'){
         $type_children=$groundt->getId();
         }
         }


    $this->mm = MmPeer::retrieveByPk($mm_id); 
    $this->grounds_sel = $this->mm->getGrounds();
    $this->grounds = GroundPeer::doSelectWithI18n(new Criteria(), 'es');
    $cc = new Criteria();
    $cc->addAscendingOrderByColumn(GroundPeer::COD);
    $cc=GroundPeer::doSelectWithI18n($cc, 'es');
    foreach ($cc as $cx){
            $ids=$cx->getRelationsId();
            foreach($ids as $id){
               if ($id==$ground_id){
               $ground_padre=$cx->getId();
               }
            }
    }

    $caux = GroundPeer::doSelectRelationsWithI18n($ground_padre, 'es');
    foreach ($caux as $aux){
    if ($aux->getGroundTypeId()==$type_children){
       $this->children=$caux;
      }
    }

    
    return $this->renderPartial('edit_ground');
  }


  /**
   * --  RELAIONGROUND -- /editar.php/mms/relaionground
   *
   * Parametros por URL: 
   *          -identificador del objeto mulimedia 
   *          -identificadores de las areas de conociemiento
   *          -verbo que indoca la accion (incluir o eliminar)
   *
   */
  public function executeRelationgrounds()
  {
    $mm_id = $this->getRequestParameter('id', 0);  //OJO SI NO EXISTEN
    $ground_ids = $this->getRequestParameter('ground_ids');

    if('incluir' == $this->getRequestParameter('function', 0)){
      foreach($ground_ids as $ground_id){
	$gv =  GroundMmPeer::retrieveByPK($ground_id, $mm_id);
	if (!$gv){
	  $gv = new GroundMm();
	  $gv->setMmId($mm_id);
	  $gv->setGroundId($ground_id);
	  $gv->save();
	}
      } 
    }elseif('eliminar' == $this->getRequestParameter('function', 0)){
      foreach($ground_ids as $ground_id){
	$gv = GroundMmPeer::retrieveByPK($ground_id, $mm_id);
	if (isset($gv)) $gv->delete();
      }
    }

    $c = new Criteria();
    $c->add(GroundTypePeer::DISPLAY, true);
    $c->addAscendingOrderByColumn(GroundTypePeer::RANK);
    $this->groundtypes = GroundTypePeer::doSelectWithI18n($c, 'es'); 

    $this->mm = MmPeer::retrieveByPk($mm_id); 
    $this->grounds = GroundPeer::doSelectWithI18n(new Criteria(), 'es');
    $this->grounds_sel = $this->mm->getGrounds();

    return $this->renderPartial('edit_ground');    
  }

  /**
   * Deprecated
   * --  ADDGROUNDS -- /editar.php/mms/addgrounds
   *
   * Parametros por URL: identificador del objeto mulimedia e identificador del area de con.
   *
   */
  public function executeAddGrounds()
  {
    $mm_id = $this->getRequestParameter('id', 0);  //OJO SI NO EXISTEN
    $ground_ids = explode('_', $this->getRequestParameter('grounds'));  //OJO SI NO EXISTEN

    foreach($ground_ids as $ground_id){
      $gv =  GroundMmPeer::retrieveByPK($ground_id, $mm_id);
      if (!$gv){
	$gv = new GroundMm();
	$gv->setMmId($mm_id);
	$gv->setGroundId($ground_id);
	$gv->save();
      }
    }

    $c = new Criteria();
    $c->add(GroundTypePeer::DISPLAY, true);
    $c->addAscendingOrderByColumn(GroundTypePeer::RANK);
    $this->groundtypes = GroundTypePeer::doSelectWithI18n($c, 'es'); 

    $this->mm = MmPeer::retrieveByPk($mm_id); 
    $this->grounds = GroundPeer::doSelectWithI18n(new Criteria(), 'es');
    $this->grounds_sel = $this->mm->getGrounds();
   

    return $this->renderPartial('edit_ground');
  }


  /**
   * Deprecated
   * --  DELETEGROUNDS -- /editar.php/mms/deletegrounds
   *
   * Parametros por URL: identificador del objeto mulimedia e identificador del area de con.
   *
   */
  public function executeDeleteGrounds()
  {
    $mm_id = $this->getRequestParameter('id', 0);  //OJO SI NO EXISTEN
    $ground_ids = explode('_', $this->getRequestParameter('grounds'));  //OJO SI NO EXISTEN

    foreach($ground_ids as $ground_id){
      $gv = GroundMmPeer::retrieveByPK($ground_id, $mm_id);
      if (isset($gv)) $gv->delete();
    }
    
    $c = new Criteria();
    $c->add(GroundTypePeer::DISPLAY, true);
    $c->addAscendingOrderByColumn(GroundTypePeer::RANK);
    $this->groundtypes = GroundTypePeer::doSelectWithI18n($c, 'es'); 

    $this->mm = MmPeer::retrieveByPk($mm_id); 
    $this->grounds_sel = $this->mm->getGrounds();
    $this->grounds = GroundPeer::doSelectWithI18n(new Criteria(), 'es');
    
    return $this->renderPartial('edit_ground');
  }


  /**
   * --  ADDCATEGORY -- /editar.php/mms/addcategory
   *
   * Parametros por URL: identificador del objeto mulimedia e identificador del area de con.
   *
   */
  public function executeAddCategory()
  {
    $mm = MmPeer::retrieveByPKWithI18n($this->getRequestParameter('id'), $this->getUser()->getCulture());
    $this->forward404Unless($mm);

    $category = CategoryPeer::retrieveByPKWithI18n($this->getRequestParameter('category'), $this->getUser()->getCulture());
    $this->forward404Unless($category);

    $add_cats = array();
    
    foreach($category->getPath() as $p){
      if($p->addMmId($mm->getId())){
	$add_cats[] = $p;
      }
    }
    if($category->addMmId($mm->getId())){
      $add_cats[] = $category;
    }

    
    foreach($category->getRequiredWithI18n() as $p){
      if($p->addMmId($mm->getId())){
	$add_cats[] = $p;
      }
    }

    $json = array('added' => array(), 'recommended' => array());
    foreach($add_cats as $n){
      $json['added'][] = array(
          'id' => $n->getId(), 
	  'cod' => $n->getCod(), 
	  'name' => $n->getName()
      );
    }

    //Add recommended. Si mm no lo tiene.

    $this->getResponse()->setContentType('application/json');
    return $this->renderText(json_encode($json));
  }


  /**
   * --  DELCATEGORY -- /editar.php/mms/delcategory
   *
   * Parametros por URL: identificador del objeto mulimedia e identificador del area de con.
   *
   */
  public function executeDelCategory()
  {
    $mm = MmPeer::retrieveByPKWithI18n($this->getRequestParameter('id'), $this->getUser()->getCulture());
    $this->forward404Unless($mm);

    $category = CategoryPeer::retrieveByPKWithI18n($this->getRequestParameter('category'), $this->getUser()->getCulture());
    $this->forward404Unless($category);

    $del_cats = array();
    
    /*
    //TODO seria mejor quitar los hijos
    foreach($category->getPath() as $p){
      if($p->delMmId($mm->getId())){
	$del_cats[] = $p;
      }
    }
    */

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



  /**
   * --  PASTE -- /editar.php/mms/paste
   *
   * Parametros por URL: identificador de la serie donde se pegan
   *
   */
  public function executePaste()
  {
    $serial = SerialPeer::retrieveByPKWithI18n($this->getRequestParameter('serial'), $this->getUser()->getCulture());
    $this->forward404Unless($serial);

    $mms = MmPeer::retrieveByPKs($this->getUser()->getAttribute('cut_mms'));
    foreach($mms as $mm){
      $mm->setSerial($serial);
      $mm->setRank($serial->countMms() + 1);
      $mm->save();
    }
      
    $this->msg_alert = array('info', "Objetos multimedia pegados en las serie.");

    return $this->renderComponent('mms', 'list');
  }

  /**
   * --  CUT -- /editar.php/mms/cut
   *
   * Parametros por POST: lista en json de los identificadores de los mm a cortar
   *
   */
  public function executeCut()
  {
    $ids = json_decode($this->getRequestParameter('ids'));
    if(count($ids) == 0){
      $this->getUser()->setAttribute('cut_mms', array());
      $this->msg_alert = array('error', "Ningun Objeto multimedia seleccionados.");
    }else{
      $this->getUser()->setAttribute('cut_mms', $ids);
      $this->msg_alert = array('info', "Objetos multimedia cortados, pegelos en las serie de desee.");
    }

    return $this->renderComponent('mms', 'list');
  }

  
  /**
   *   /editar.php/mms/getGroundChildren
   *
   */
  public function executeGetGroundChildren()  {
    $mm_id = $this->getRequestParameter('id', 0);
    $ground_id = $this->getRequestParameter('ground', 0);
    
  
    $c = new Criteria();
    $c->add(GroundTypePeer::DISPLAY, true);
    $c->addAscendingOrderByColumn(GroundTypePeer::RANK);
    $this->groundtypes = GroundTypePeer::doSelectWithI18n($c, 'es');
    $this->mm = MmPeer::retrieveByPk($mm_id);
    $cg = new Criteria();
    $cg->addAscendingOrderByColumn(GroundPeer::COD);
    $this->grounds = GroundPeer::doSelectWithI18n($cg, 'es');
    $this->grounds_sel = $this->mm->getGrounds();
    
    $this->children = GroundPeer::doSelectRelationsWithI18n($ground_id, 'es');
    
    return $this->renderPartial('edit_ground');
  }


  /**
   * --  Inv -- /editar.php/mms/inv
   *
   * Parametros por URL: identificador del campo del objeto multimedia
   * Parametros por POST: array en JSON de identificadores
   *
   */
  public function executeInv()
  {
    $field = $this->getRequestParameter('field');

    if($this->hasRequestParameter('ids')){
      $mms = array_reverse(MmPeer::retrieveByPKs(json_decode($this->getRequestParameter('ids'))));
      
      foreach($mms as $mm){
	$save = false;
	if ($field == "announce") {
	  $mm->setAnnounce(!$mm->getAnnounce());
	  $save = true;
	}else if ($field == "important") {
	  $mm->setImportant(!$mm->getImportant());
	  $save = true;
	}
	if ($save){
	  $mm->save();
	}
      }

    }
    return $this->renderComponent('mms', 'list');
  }

}



