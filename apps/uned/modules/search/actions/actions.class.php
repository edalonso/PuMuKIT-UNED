<?php

/**
 * search actions.
 *
 * @package    pumukit
 * @subpackage search
 * @author     Ruben Glez <rubenrua at uvigo.es>
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */
class searchActions extends sfActions
{
  /**
   * Executes index action
   *
   */
  public function executeIndex()
  {
    $this->getUser()->resetPan();
    $this->getUser()->panNivelDos('Buscar', 'search/index');

    $this->search = preg_replace('/[^a-z0-9_\.,;:_-]/i', ' ', $this->getRequestParameter('q'));
  }
}
