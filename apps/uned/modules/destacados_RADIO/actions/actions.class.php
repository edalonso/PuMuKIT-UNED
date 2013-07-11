<?php

/**
 * destacados_RADIO actions.
 *
 * @package    pumukituvigo
 * @subpackage destacados_RADIO
 * @author     Ruben Glez <rubenrua at uvigo.es>
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */
class destacados_RADIOActions extends sfActions
{
  /**
   * Executes index action
   *
   */
  public function executeIndex()
  {
    $this->getUser()->resetPan();
    $this->getUser()->panNivelDos('Destacados Radio', 'destacados_RADIO');
    $this->title   = "Destacados Radio";

    $cod = CategoryMmTimeframePeer::DESTACADOS_RADIO;
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

