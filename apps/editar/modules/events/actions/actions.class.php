<?php
/**
 * MODULO EVENTS ACTIONS. 
 * Modulo de configuracion de los noticias y eventos que aparecen en el portal web.
 *
 * @package    pumukit
 * @subpackage events
 * @author     Ruben Gonzalez Gonzalez <rubenrua ar uvigo dot es>
 * @version    1.0
 **/

class eventsActions extends sfActions
{

  /**
   * --  INDEX -- /editar.php/events
   * Muestra el modulo de administracion de las noticias, con la vista previa, formulario
   * de fultrado, listado de noticias y acciones de nuevo...
   *
   * Accion por defecto en la aplicacion. Acceso publico. Layout: layout
   *
   */
  public function executeIndex()
  {
    sfConfig::set('tv_menu','active');
    $this->getUser()->setAttribute('sort', 'id', 'tv_admin/event');
    $this->getUser()->setAttribute('type', 'desc', 'tv_admin/event');
    if (!$this->getUser()->hasAttribute('page', 'tv_admin/event'))
      $this->getUser()->setAttribute('page', 1, 'tv_admin/event');      
    if (!$this->getUser()->hasAttribute('mes', 'tv_admin/event'))
      $this->getUser()->setAttribute('mes', date('m'), 1, 'tv_admin/event');      
    if (!$this->getUser()->hasAttribute('ano', 'tv_admin/event'))
      $this->getUser()->setAttribute('ano', date('Y'), 1, 'tv_admin/event');      
  }



  /**
   * --  LIST -- /editar.php/events/list
   * Muestra la tabla que lista de forma paginada y filtrada las noticias. Renderiza el componente
   * list para que sea accesibe como ajax.
   *
   * Accion asincrona. Acceso privado.
   *
   */
  public function executeList()
  {
    if($this->hasRequestParameter("cal")){
      return $this->renderComponent('events', 'calendar');
    }else{
      return $this->renderComponent('events', 'array');
    }
  }


  /**
   * --  PREVIEW -- /editar.php/events/preview
   * Muestra la una perqueña vista previa de la noticia
   *
   * Accion asincrona. Acceso privado. Paremetros id de la noticia
   *
   */
  public function executePreview()
  {
    if ($this->hasRequestParameter('id'))
    {
      $this->getUser()->setAttribute('id', $this->getRequestParameter('id'), 'tv_admin/event');
    }
    return $this->renderComponent('events', 'preview');
  }


  /**
   * --  CREATE -- /editar.php/events/create
   * Muesta el formulario de edicion de la noticia nueva.
   *
   * Accion asincrona. Acceso privado.
   *
   */
  public function executeCreate()
  {
    $this->event = new Event();
    $this->event->setDirect(DirectPeer::doSelectOne(new Criteria()));
    $serial = SerialPeer::createNew(false);

    $this->event->setDate('today');  //dejar esto a mysql

    $this->langs = sfConfig::get('app_lang_array', array('es'));
    foreach($this->langs as $lang){
      $this->event->setCulture($lang);
      $this->event->setTitle("Nuevo teleacto");
    }
    $this->event->setSerialId($serial->getId());
    $this->event->save();
    $this->getUser()->setAttribute('id', $this->event->getId(), 'tv_admin/event');

    $this->redirect('events/index');
  }



  /**
   * --  listAutoComplete -- /editar.php/events/listAutoComplete
   * Muesta el formulario de autocompletado de teleactos relacionados con series.
   *
   * Accion asincrona. Acceso publico.
   *
   */
  public function executeListAutoComplete()
  {
    if ($this->hasRequestParameter('edit')) {
      $this->edit = true;
      $this->event = $this->getRequestParameter('event');
    }
    else{ 
      $this->edit = false;
    }
  }


  /**
   * --  AUTOCOMPLETE -- /editar.php/events/autocomplete
   * Muestra una lista com los nombres de las peronas similares al que se esta campo nombre.
   *
   * Accion asincrona. Acceso privado. Parametros name por URL.
   *
   */
  public function executeAutoComplete()
  {      
    $c = new Criteria();
    $this->name = $this->getRequestParameter('name');
    $c->addJoin(SerialPeer::ID, SerialI18nPeer::ID);
    $c->add(SerialI18nPeer::TITLE, '%' . $this->name . '%', Criteria::LIKE);
    $this->serials = SerialPeer::doSelect($c);
  }



  /**
   * --  CREATE -- /editar.php/events/create
   * Muesta el formulario de edicion de la noticia nueva.
   *
   * Accion asincrona. Acceso privado.
   *
   */
  public function executeCreateFromSerial()
  {
    $this->event = new Event();
    $this->event->setDirect(DirectPeer::doSelectOne(new Criteria()));
    $serial = SerialPeer::retrieveByPK($this->getRequestParameter('serial'));

    $this->event->setDate('today');  //dejar esto a mysql
    
    $this->langs = sfConfig::get('app_lang_array', array('es'));
    foreach($this->langs as $lang){
      $this->event->setCulture($lang);
      $this->event->setTitle($serial->getTitle());
      $this->event->setDescription($serial->getDescription());
    }
    
    $this->event->setSerialId($serial->getId());

    $this->event->save();

    // crea el pic copiando el de la serie
    $pic = new Pic();
    $pic->setUrl($serial->getFirstUrlPic());
    $pic->save();
    $pic_object = new PicEvent();
    $pic_object->setPicId($pic->getId());
    $pic_object->setOtherId($this->event->getId());
    $pic_object->save();

    $this->getUser()->setAttribute('id', $this->event->getId(), 'tv_admin/event');
    $this->redirect('events/index');
  }


  /**
   * --  CHANGESERIAL -- /editar.php/events/chageSerial
   * Muesta el formulario de edicion de la noticia nueva.
   *
   * Accion asincrona. Acceso privado.
   *
   */
  public function executeChangeSerial()
  {
    $this->serial = SerialPeer::retrieveByPK($this->getRequestParameter('serial'));
    $this->event = EventPeer::retrieveByPK($this->getRequestParameter('event'));
    $this->event->setSerialId($this->serial->getId());
    $this->event->save();

  }


  /**
   * --  EDIT -- /editar.php/events/edit
   * Muesta el formulario de edicion de una noticia.
   *
   * Accion asincrona. Acceso privado. Parametros identificador de la noticia
   *
   */
  public function executeEdit()
  {

    if ($this->hasRequestParameter('id'))
    {
      $this->getUser()->setAttribute('id', $this->getRequestParameter('id'), 'tv_admin/event');
    }

    if($this->hasRequestParameter("cal")) {
      $this->getUser()->setAttribute('cal', $this->getRequestParameter('cal'), 'tv_admin/event');
    }

    return $this->renderComponent('events', 'edit');
  }



  /**
   * --  copy -- /editar.php/events/copy
   * Muesta el formulario de edicion de una noticia.
   *
   * Accion asincrona. Acceso privado. Parametros identificador de la noticia
   *
   */
  public function executeCopy()
  {

    $event = EventPeer::retrieveByPK($this->getRequestParameter('id'));
    $this->forward404Unless($event);

    $event2 = $event->copy();
    
    $this->getUser()->setAttribute('id', $event2->getId(), 'tv_admin/event');

    if($this->hasRequestParameter("cal")){
      return $this->renderComponent('events', 'calendar');
    }else{
      return $this->renderComponent('events', 'array');
    }
  }


  /**
   * --  UPDATE -- /editar.php/events/update
   * Actualiza el contenido de una noticia con el resultado del formulario de modificacion.
   * Si no existe noticia con id dado se crea uno nuevo y se realizan validacion de email en 
   * el servidor. 
   *
   * Accion asincrona. Acceso privado. Parametros por POST resultado de formulario de edicion
   *
   */
  public function executeUpdate()
  {
    if (!$this->getRequestParameter('id'))
    {
      $this->forward404();
    }
    else
    {
      $event = EventPeer::retrieveByPk($this->getRequestParameter('id'));
      $this->forward404Unless($event);
    }

    $serial = $event->getSerial();


    if ($this->hasRequestParameter('date'))
    {
      $timestamp = sfI18N::getTimestampForCulture($this->getRequestParameter('date'), $this->getUser()->getCulture());
      $event->setDate($timestamp);
    }

    $event->setDisplay($this->getRequestParameter('display', 0));
    $langs = sfConfig::get('app_lang_array', array('es'));
    foreach($langs as $lang){
      $event->setCulture($lang);
      $event->setTitle($this->getRequestParameter('title', 0));
      $event->setDescription($this->getRequestParameter('description', 0));
      if ($serial == null) continue;
      if (($serial->getTitle() == "Nuevo") && ($serial->getDescription() == null)){
	$serial->setTitle($event->getTitle());
	$serial->setSubtitle($event->getTitle());
	$serial->setDescription($event->getDescription());
	$serial->save();
      }

    }
    $event->setAuthor($this->getRequestParameter('author', 0));
    $event->setProducer($this->getRequestParameter('producer', 0));
    $event->setDirectId($this->getRequestParameter('direct_id', 0));
    $event->setEnableQuery($this->getRequestParameter('enable_query', 0));
    $event->setEmailQuery($this->getRequestParameter('emailQuery', ''));
    $event->setExternal($this->getRequestParameter('external', 0));
    $event->setUrl($this->getRequestParameter('url', ''));
    $event->setSecured($this->getRequestParameter('secured', 0));
    $event->setPassword($this->getRequestParameter('password', ''));


    $event->save();

    
    $this->getUser()->setAttribute('id', $event->getId(), 'tv_admin/event');

    if($this->hasRequestParameter("cal")){
      return $this->renderComponent('events', 'calendar');
    }else{
      return $this->renderComponent('events', 'array');
    }
  }



  /**
   * --  DELETE -- /editar.php/events/delete
   * Borrar una noticia de la base de datos si el parametro id se introduce en la URL, se 
   * pueden borrar varios noticias si existe por POST un array de ids codificado en JSON.
   *
   * Accion asincrona. Acceso privado. Parametros id por URL o ids JSON por POST.
   *
   */
  public function executeDelete()
  {
    if($this->hasRequestParameter('ids')){
      $events = EventPeer::retrieveByPKs(json_decode($this->getRequestParameter('ids')));

      foreach($events as $event){
	$event->delete();
      }
      //$this->msg_alert = array('info', "Noticias borradas.");

    }elseif($this->hasRequestParameter('id')){
      $event = EventPeer::retrieveByPk($this->getRequestParameter('id'));
      $event->delete();
      //$this->msg_alert = array('info', "Noticia borrada.");
    }

    $this->getUser()->setAttribute('id', null, 'tv_admin/event');
    $this->redirect('events/index');
  }


 /**
   * --  DELETE -- /editar.php/events/delete
   * Borrar una noticia de la base de datos si el parametro id se introduce en la URL, se 
   * pueden borrar varios noticias si existe por POST un array de ids codificado en JSON.
   *
   * Accion asincrona. Acceso privado. Parametros id por URL o ids JSON por POST.
   *
   */
  public function executeDeleteS()
  {
    if($this->hasRequestParameter('ids')){
      $events = EventPeer::retrieveByPKs(json_decode($this->getRequestParameter('ids')));

      foreach($events as $event){
	$event->delete();
      }
      //$this->msg_alert = array('info', "Noticias borradas.");

    }elseif($this->hasRequestParameter('id')){
      $event = EventPeer::retrieveByPk($this->getRequestParameter('id'));
      $event->delete();
    }

    $serial = $event->getSerial();
    $serial->delete();

    $this->getUser()->setAttribute('id', null, 'tv_admin/event');
    $this->redirect('events/index');
  }



  /**
   * --  WORKING -- /editar.php/events/working
   *
   *
   */
  public function executeWorking()
  {
    if($this->hasRequestParameterking('ids')){
      $events = EventPeer::retrieveByPKs(json_decode($this->getRequestParameter('ids')));

      foreach($events as $event){
	$event->setDisplay(!$event->getDisplay());
	$event->save();
      }
      //$this->msg_alert = array('info', "Noticias ocultadas/desocultadas correctamente.");

    }elseif($this->hasRequestParameter('id')){
      $event = EventPeer::retrieveByPk($this->getRequestParameter('id'));
      $event->setDisplay(!$event->getDisplay());
      $event->save();
      //$this->msg_alert = array('info', "Noticia ocultada/desocultada correctamente.");
    }

    if($this->hasRequestParameter("cal")){
      return $this->renderComponent('events', 'calendar');
    }else{
      return $this->renderComponent('events', 'array');
    }
  }


  /**
   * --  INFO -- /editar.php/events/info
   *
   * Parametros por URL: Identificador del archivo multimedia
   *
   */
  public function executeInfo(){
    $this->event = EventPeer::retrieveByPk($this->getRequestParameter('id'));
    $this->forward404Unless($this->event);
  }


  /**
   * --  CREATESESSION -- /editar.php/events/createSession/id
   * Muesta el formulario de edicion de la noticia nueva.
   *
   * Accion asincrona. Acceso privado.
   *
   */
  public function executeCreateSession()
  {
    $this->forward404Unless($this->getRequest()->hasParameter('id'));
    $event = EventPeer::retrieveByPK($this->getRequestParameter('id'));
    $this->forward404Unless($event);
   
    $this->session = new Session();
    $this->session->setInitDate('today');  //dejar esto a mysql
    $this->session->setEndDate('today');  //dejar esto a mysql
    $this->session->setEventId($event->getId());
    $this->session->setDirectId($event->getDirectId());
    $this->session->setNotes("");
  }



  /**
   * --  UPDATESESSION -- /editar.php/events/updateSession/id/event_id
   * Muesta el formulario de edicion de la noticia nueva.
   *
   * Accion asincrona. Acceso privado.
   *
   */
  public function executeUpdateSession()
  {
    $this->forward404Unless($this->getRequest()->hasParameter('id'));
    $this->session = SessionPeer::retrieveByPK($this->getRequestParameter('id'));

    if ($this->session == null) {
      $event = EventPeer::retrieveByPK($this->getRequestParameter('event_id'));
      $this->forward404Unless($event);
      $this->session = new Session();
      $this->session->setEventId($event->getId());
      $this->session->setDirectId($event->getDirectId());
    }

    if ($this->hasRequestParameter('init_date'))
      {
	$timestamp = sfI18N::getTimestampForCulture($this->getRequestParameter('init_date'), $this->getUser()->getCulture());
	$this->session->setInitDate($timestamp);
      }

    if ($this->hasRequestParameter('end_date'))
      {
	$timestamp = sfI18N::getTimestampForCulture($this->getRequestParameter('end_date'), $this->getUser()->getCulture());
	$this->session->setEndDate($timestamp);
      }

    $this->session->setNotes($this->getRequestParameter('notes'));

    $this->session->save();
    return $this->renderComponent('events', 'listSessions');
  }



  /**
   * --  CREATESESSION -- /editar.php/events/createSession/id
   * Muesta el formulario de edicion de la noticia nueva.
   *
   * Accion asincrona. Acceso privado.
   *
   */
  public function executeDeleteSession()
  {
    $this->forward404Unless($this->getRequest()->hasParameter('id'));
    $session = SessionPeer::retrieveByPK($this->getRequestParameter('id'));
    $this->forward404Unless($session);

    $this->getUser()->setAttribute('id', $session->getEventId(), 'tv_admin/event');

    $session->delete();
        
    return $this->renderComponent('events', 'listSessions');
  }



  /**
   * --  EDITSESSION -- /editar.php/events/createSession/id
   * Muesta el formulario de edicion de la sesion.
   *
   * Accion asincrona. Acceso privado.
   *
   */
  public function executeEditSession()
  {
    $this->forward404Unless($this->getRequest()->hasParameter('id'));
    $this->session = SessionPeer::retrieveByPK($this->getRequestParameter('id'));
    $this->forward404Unless($this->session);
  }



/**
   * --  CopySESSION -- /editar.php/events/createSession/id
   * Copia la sesion que se le pasa por parametro creando una nueva al dia siguiente
   *
   * Accion sincrona. Acceso publico
   *
   */
 public function executeCopySession()
  {

    $session = SessionPeer::retrieveByPK($this->getRequestParameter('id'));
    $this->forward404Unless($session);

    $session2 = $session->copyTM();

    return $this->renderComponent('events', 'listSessions');
  }



  /**
   * --  UPDATE -- /editar.php/events/updateSerial
   *
   * Parametros por POST: parameteros del formulario
   *
   */
  public function executeUpdateSerial()
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
    $serial->setDisplay($this->getRequestParameter('hide', 0) != 1);
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

    return $this->renderComponent('events', 'array');
  }


}
