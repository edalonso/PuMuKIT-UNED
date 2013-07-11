<?php

/**
 * index components.
 *
 * @package    pumukit
 * @subpackage index
 * @author     Ruben Glez <rubenrua at uvigo.es>
 * @version    SVN: $Id: components.class.php 2692 2006-11-15 21:03:55Z fabien $
 */
class indexComponents extends sfComponents
{
  /**
   * Executes index component
   *
   */
  public function executeTabs()
  {
      $this->last = SerialPeer::getAnnounces($this->getUser()->getCulture(), 3, array('pub', 'cor')); 
      $this->popular = MmPeer::masVistos($this->getUser()->getCulture(), 0, 3);
  }
}
