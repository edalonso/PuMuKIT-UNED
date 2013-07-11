<?php
/**
 * MODULO MATERIALEVENTS ACTIONS. 
 * Pseudomodulo usado por el modulo de objeto multimedia para administrar
 * los materiales de un objeto multimedia. 
 *
 * @package    pumukit
 * @subpackage materialevents
 * @author     Ruben Gonzalez Gonzalez (rubenrua at uvigo dot com)
 * @version    1.0
 */
class materialeventsComponents extends sfComponents
{
  /**
   * Executes index component
   *
   */
  public function executeList()
  {
    if (isset($this->event)){
      $this->materials = MaterialEventPeer::getMaterialsFromEvent($this->event, $this->getUser()->getCulture());
    }elseif ($this->hasRequestParameter('event')){
      $this->event = $this->getRequestParameter('event');
      $this->materials = MaterialEventPeer::getMaterialsFromEvent($this->getRequestParameter('event'), $this->getUser()->getCulture());
    }else{
      $this->materials = array();
    }
  }
}
