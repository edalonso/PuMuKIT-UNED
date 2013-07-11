<?php

/**
 * index actions.
 *
 * @package    pumukituvigo
 * @subpackage index
 * @author     Ruben Glez <rubenrua at uvigo.es>
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */
class indexActions extends sfActions
{
  /**
   * Executes index action
   *
   */
  public function executeIndex()
  {
      $this->getUser()->panNivelUno();
      if(!$this->getUser()->hasAttribute('only', 'explore'))
	$this->getUser()->setAttribute('only', 'all', 'explore');

      if( $this->hasRequestParameter('oldtemplate') && ( $this->getRequestParameter('oldtemplate')=='true' || $this->getRequestParameter('oldtemplate')=='') )
      	  return ("oldPics");
  }

  public function executeError404()
  {
      $this->template = TemplatePeer::get('error404', $this->getUser()->getCulture());
  }
}
