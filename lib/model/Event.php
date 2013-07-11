<?php

/**
 * Subclass for representing a row from the 'event' table.
 *
 * 
 *
 * @package lib.model
 */ 
class Event extends BaseEvent
{

  /**
   * Devuelve una referencia al propio evento
   *
   *
   */
  public function getEvent()
  {
    return $this;
  }
  /**
   * Sobreescribe la funcion copy.
   *
   *
   */
  public function copy($bool = false)
  {
    $event2 = new Event();

    $event2->setAuthor($this->getAuthor());
    $event2->setDirectId($this->getDirectId());
    //apanhar serie
    $serial1 = $this->getSerial();
    $serial2 = $serial1->copy();
    $event2->setSerialId($serial2->getId());

    $event2->setDisplay($this->getDisplay());
    $event2->setCreateSerial($this->getCreateSerial());
    $event2->setDate($this->getDate());
    $event2->setEnableQuery($this->getEnableQuery());
    $event2->setEmailQuery($this->getEmailQuery());
    $event2->setProducer($this->getProducer());

    $langs = sfConfig::get('app_lang_array', array('es'));
    foreach($langs as $lang){
      $event2->setCulture($lang);
      $this->setCulture($lang);
      $event2->setTitle($this->getTitle());
      $event2->setDescription($this->getDescription());
    }

    $event2->save();

    $sessions = SessionPeer::getFromEvent($this->getId());
   
    foreach ($sessions as $session){
      $session2 = $session->copy(true);
      $session2->setEventId($event2->getId());
      $session2->save();
    }
  
    return($event2);
  }


  /**
   * Obtiene la sesion mas reciente de un evento
   *
   */
  public function getFirstSession(){
    $c = new Criteria();
    $c->add(SessionPeer::EVENT_ID, $this->getId());
    $c->addAscendingOrderByColumn(SessionPeer::INIT_DATE);

    return SessionPeer::doSelectOne($c);
  }


  /**
   * Obtiene la primera sesion de hoy de un evento
   *
   */
  public function getTodaysFirstSession(){
    $c = new Criteria();
    $c->add(SessionPeer::EVENT_ID, $this->getId());

    $criterion = $c->getNewCriterion(SessionPeer::INIT_DATE , date("Y-m-d H:i:s", mktime(0, 0, 0, date("m"),date("d"),date("Y"))), Criteria::GREATER_THAN);
    $criterion->addAnd($c->getNewCriterion(SessionPeer::END_DATE , date("Y-m-d H:i:s", mktime(23, 59, 59, date("m"),date("d"), date("Y"))), Criteria::LESS_THAN));    
    $c->add($criterion);

    $c->addAscendingOrderByColumn(SessionPeer::INIT_DATE);

    return SessionPeer::doSelectOne($c);
  }


  /**
   * Obtiene las sesiones futuras del dia de hoy de un evento
   *
   */
  public function getTodaysFutureSession(){
    $c = new Criteria();
    $c->add(SessionPeer::EVENT_ID, $this->getId());

    $criterion =  $c->getNewCriterion(SessionPeer::INIT_DATE, date("Y-m-d H:i", strtotime("now")), Criteria::GREATER_THAN);
    $criterion->addAnd($c->getNewCriterion(SessionPeer::END_DATE , date("Y-m-d H:i:s", mktime(23, 59, 59, date("m"),date("d"), date("Y"))), Criteria::LESS_THAN));    
    $c->add($criterion);

    $c->addAscendingOrderByColumn(SessionPeer::INIT_DATE);

    return SessionPeer::doSelectOne($c);
  }


  /**
   * Obtiene la sesion mas cercana a la fecha actual del evento actual
   * 
   *
   */
    public function getSimilarSessionDate(){
    $c = new Criteria();
    $c->add(SessionPeer::EVENT_ID, $this->getId());
    $criterion = $c->getNewCriterion(SessionPeer::INIT_DATE , date("Y-m-d", mktime(0, 0, 0, date("m"),date("d"),date("Y"))), Criteria::LESS_EQUAL);
    $criterion->addAnd($c->getNewCriterion(SessionPeer::END_DATE , date("Y-m-d", mktime(0, 0, 0, date("m"),date("d"),date("Y"))), Criteria::GREATER_EQUAL ));
    $c->add($criterion);

    return SessionPeer::doSelectOne($c);
    } 



  /**
   * Obtiene la sesion que se esta celebrando ahora
   * 
   *
   */
    public function getSessionNow(){
      $c = new Criteria();
      $c->add(SessionPeer::EVENT_ID, $this->getId());
      $c->add(SessionPeer::INIT_DATE, date("Y-m-d H:i", mktime(date("H"), date("i"), 0, date("m"),date("d"),date("Y"))), Criteria::LESS_EQUAL);
      $c->add(SessionPeer::END_DATE, date("Y-m-d H:i", mktime(date("H"), date("i"), 0, date("m"),date("d"),date("Y"))), Criteria::GREATER_EQUAL);

      return SessionPeer::doSelectOne($c);
    }


  /**
   * Obtiene las sesiones futuras de un evento
   *
   */
    public function getFutureSessions(){
      $c = new Criteria();
      $c->add(SessionPeer::EVENT_ID, $this->getId());
      $c->add(SessionPeer::INIT_DATE, date("Y-m-d H:i", strtotime("now")), Criteria::GREATER_THAN);

      $c->addAscendingOrderByColumn(SessionPeer::INIT_DATE);
      
      return SessionPeer::doSelect($c);
  }


  /**
   * Obtiene la sesion futura mas reciente de un evento
   * que no pertenezca al dia de hoy
   *
   */
    public function getFutureSession(){
      $c = new Criteria();
      $c->add(SessionPeer::EVENT_ID, $this->getId());
      $c->add(SessionPeer::INIT_DATE, date("Y-m-d H:i:s", mktime(23, 59, 59, date("m"),date("d"),date("Y"))), Criteria::GREATER_THAN);

      $c->addAscendingOrderByColumn(SessionPeer::INIT_DATE);
      
      return SessionPeer::doSelectOne($c);
  }


 /** 
   * Devuelve el los materiales que tienen perfil publico.    
   * 
   * @access public 
   * @return Array de Files   
   */
  public function getMaterialsPublic()
  {
    $c = new Criteria();
    $c->add(MaterialEventPeer::EVENT_ID, $this->getId());
    $c->add(MaterialEventPeer::DISPLAY, true);
    $c->addAscendingOrderByColumn(MaterialEventPeer::RANK);

    return MaterialEventPeer::doSelectWithI18n($c, $this->getCulture());
  }



  /**
   * Usado en PicBehavior
   */
  public function isSerial(){
    return false;
  }

  public function getDefaultPic(){
    return '/images/uned/thumbnails_teleacto.jpg';
  }

}

sfPropelBehavior::add('Event', array('pic') );