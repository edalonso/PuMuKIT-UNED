<?php

/**
 * Subclass for performing query and update operations on the 'session' table.
 *
 * 
 *
 * @package lib.model
 */ 
class SessionPeer extends BaseSessionPeer
{

  /**
   *
   * Get Sessions from a event ID
   *
   **/
  public static function getFromEvent($id) {
    $c = new Criteria();
    $c->add(SessionPeer::EVENT_ID, $id);
    return SessionPeer::doSelect($c);
  }
}
