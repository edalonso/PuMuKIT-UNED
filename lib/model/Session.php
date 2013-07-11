<?php

/**
 * Subclass for representing a row from the 'session' table.
 *
 * 
 *
 * @package lib.model
 */ 
class Session extends BaseSession
{

  /**
   * funcion para crear una nueva sesion 
   * con la fecha del dia siguiente a la copiada
   *
   */
  public function copyTM($bool = false)
  {
    $session2 = new Session();
    $session2->setEventId($this->getEventId());
    $session2->setDirectId($this->getDirectId());
    $session2->setNotes($this->getNotes());
    $session2->setInitDate(date("Y-m-d H:i", mktime($this->getInitDate("H"), $this->getInitDate("i"), 0, $this->getInitDate("m"), $this->getInitDate("d") + 1, $this->getInitDate("Y"))));
    $session2->setEndDate(date("Y-m-d H:i", mktime($this->getEndDate("H"), $this->getEndDate("i"), 0, $this->getEndDate("m"), $this->getEndDate("d") + 1, $this->getEndDate("Y"))));
    $session2->save();
    return ($session2);
  }
}
