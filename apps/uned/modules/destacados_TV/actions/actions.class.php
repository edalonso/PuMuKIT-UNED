<?php

/**
 * destacados_TV actions.
 *
 * @package    pumukituvigo
 * @subpackage destacados_TV
 * @author     Ruben Glez <rubenrua at uvigo.es>
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */
class destacados_TVActions extends sfActions
{
  /**
   * Executes index action
   *
   */
  public function executeIndex()
  {
    $this->getUser()->resetPan();
    $this->getUser()->panNivelDos('Destacados TV', 'destacados_TV');
    $this->title = "Destacados TV";

    $cod = CategoryMmTimeframePeer::DESTACADOS_TV;
    $mms = CategoryMmTimeframePeer::doSelectDestacados($cod, true, null);

    $aux = array();
    foreach($mms as $mm){
      if (!array_key_exists($mm->getRecorddate('Y'), $aux)) {
	$aux[$mm->getRecorddate('Y')] = array();
      }
      $aux[$mm->getRecorddate('Y')][] = $mm;
    }
    $this->mms = $aux;

    $this->setTemplate('displaymmsdate');
  }
}
