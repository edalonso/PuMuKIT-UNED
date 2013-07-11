<?php

/**
 * Subclass for performing query and update operations on the 'event' table.
 *
 * 
 *
 * @package lib.model
 */ 
class EventPeer extends BaseEventPeer
{

  /**
   * Crea un nuevo evento con directo por defecto.
   *
   * @access public
   * @return Event
   */

  //TODOUNED hablar con Nacho.
  static public function createNew()
  {
    $event = new Event();
    return $event;
  }

  /**
   *
   */
  static public function getByDate($y, $m, $d){
    $c = new Criteria();
    $c->add(EventPeer::DATE, mktime(0,0,0,$m, $d, $y), Criteria::GREATER_EQUAL);
    $c->addAnd(EventPeer::DATE, mktime(23, 59, 59, $m, $d, $y), Criteria::LESS_EQUAL);

    return EventPeer::doSelect($c);
  }


  /**
   *
   * obtiene todos los teleactos que tienen sesiones a partir de hoy
   */
  static public function getFutureEvents($limit = 0, $offset = 0){
    $c = new Criteria();
    $c->addJoin(SessionPeer::EVENT_ID, EventPeer::ID);
    $c->add(SessionPeer::INIT_DATE, date("Y-m-d H:i:s", mktime(23, 59, 59, date("m"), date("d"), date("Y"))) ,Criteria::GREATER_THAN);
    $c->add(EventPeer::DISPLAY, true);
    $c->setDistinct(true);

    $c_count = clone($c);
    $out['total'] = EventPeer::doCount($c);
    
    $c->setLimit($limit);
    $c->setOffset($offset);
    $c->addAscendingOrderByColumn(SessionPeer::INIT_DATE);

    $out['events'] = EventPeer::doSelect($c);

    return $out;
  }



  /**
   *
   * obtiene todos los teleactos que tienen sesiones ahora
   */
  static public function getRNEvents($limit = 0, $offset = 0){
    $c = new Criteria();
    $c->addJoin(SessionPeer::EVENT_ID, EventPeer::ID);
    $c->add(SessionPeer::INIT_DATE, date("Y-m-d H:i", mktime(date("H"), date("i"), 0, date("m"),date("d"),date("Y"))), Criteria::LESS_EQUAL);
    $c->add(SessionPeer::END_DATE, date("Y-m-d H:i", mktime(date("H"), date("i"), 0, date("m"),date("d"),date("Y"))), Criteria::GREATER_EQUAL);
    
    $c->add(EventPeer::DISPLAY, true);
    $c->setDistinct(true);
    
    $c->setLimit($limit);
    $c->setOffset($offset);
    $c->addAscendingOrderByColumn(SessionPeer::INIT_DATE);
    
    $out = EventPeer::doSelect($c);
    
    return $out;
  }



  /**
   *
   * obtiene todos los teleactos que tienen sesiones hoy
   */
  static public function getToDayEvents($limit = 0, $offset = 0){
    $c = new Criteria();
    $c->addJoin(SessionPeer::EVENT_ID, EventPeer::ID);
    $criterion = $c->getNewCriterion(SessionPeer::INIT_DATE , date("Y-m-d H:i:s", mktime(0, 0, 0, date("m"),date("d"),date("Y"))), Criteria::GREATER_THAN);
    $criterion->addAnd($c->getNewCriterion(SessionPeer::END_DATE , date("Y-m-d H:i:s", mktime(23, 59, 59, date("m"),date("d"), date("Y"))), Criteria::LESS_THAN));
    
    $c->add($criterion);
    
    $c->add(EventPeer::DISPLAY, true);
    $c->setDistinct(true);
    
    $c->setLimit($limit);
    $c->setOffset($offset);
    $c->addAscendingOrderByColumn(SessionPeer::INIT_DATE);
    
    $out = EventPeer::doSelect($c);
    
    return $out;
  }

}
