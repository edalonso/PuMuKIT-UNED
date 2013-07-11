<?php

/**
 * asx actions.
 *
 * @package    pumukit
 * @subpackage asx
 * @author     Ruben Glez <rubenrua at uvigo.es>
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */
class asxActions extends sfActions
{
  /**
   * Executes index action
   *
   */
  public function executeIndex()
  {
    $this->setLayout(false);

    $this->file = FilePeer::retrieveByPk($this->getRequestParameter('id'));
    $this->forward404Unless($this->file);

  }
}
