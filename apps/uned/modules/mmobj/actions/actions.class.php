<?php

/**
 * mmobj actions.
 *
 * @package    pumukitvigo
 * @subpackage mmobj
 * @author     Ruben Glez <rubenrua at uvigo.es>
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */
class mmobjActions extends sfActions
{
    /**
     * Executes index action
     *
     */
    public function executeIndex()
    {
        if($this->hasRequestParameter('file_id')){
            $this->file = FilePeer::retrieveByPK($this->getRequestParameter('file_id'));
            $this->forward404Unless($this->file);

            $this->m = $this->file->getMm();
        }else{
            $this->m = MmPeer::retrieveByPKWithI18n($this->getRequestParameter('id'));
            $this->forward404Unless($this->m);

            $this->file = $this->m->getFirstFile();
	    $this->file->setMm($this->m); //To update cache.
        }
        $this->forward404Unless($this->file);

        // CHECK STATUS
	$status = array(MmPeer::STATUS_NORMAL, MmPeer::STATUS_HIDE);
        $this->forward404Unless($this->file);
        $this->forward404If($this->file->isMaster());
	if($this->getUser()->isAuthenticated() && $this->getUser()->hasCredential('admin')) {
	  $status[] = MmPeer::STATUS_BLOQ;
	} else {
	  $this->forward404Unless($this->m->hasPubChannelId(1));
	  $this->forward404Unless($this->file->getDisplay());
	}

	$this->forward404Unless(in_array($this->m->getStatusId(), $status));

        //$this->forward404Unless($this->m->hasPubChannelId(1));

        $this->getUser()->panNivelCuatro($this->m);


        $this->roles = RolePeer::doSelectWithI18n(new Criteria(), $this->getUser()->getCulture());

        $this->getResponse()->addMeta('keywords', $this->m->getKeyword());

	if ($this->hasRequestParameter('Contraseña')) {
	  $broadcast = BroadcastPeer::retrieveByPk($this->m->getBroadcastId());
	  if ( $broadcast->getPasswd() != $this->getRequestParameter('Contraseña') ) {
	    $this->passwd();
	  }
        } else if ( $this->m->getBroadcastId() != 1 ) {
	  $this->passwd();
	}
    }


    /**
     * --  DOWNLOAD -- /uned.php/mmobj/download
     *
     * Parametros por URL: Identificador del archivo multimedia
     *
     */
    public function executeDownload(){
      set_time_limit(0);
      
      $file = FilePeer::retrieveByPk($this->getRequestParameter('id'));
      $this->forward404Unless($file);
      $this->forward404Unless($file->getDownload());

      $ticket = TicketPeer::new_web($file);
      return $this->redirect($ticket->getUrl());
    }
   
    
    /**
     * --  IFRAME -- /uned.php/mmobj/iframe
     *
     * Parametros por URL: Identificador del archivo multimedia
     *
     */
    public function executeIframe()
    {
      $this->setLayout('simplelayout');
      $this->mm = MmPeer::retrieveByPkWithI18n($this->getRequestParameter('id'));
      $this->forward404Unless($this->mm);
      $this->file = $this->mm->getFirstFile();
      $this->roles = RolePeer::doSelectWithI18n(new Criteria());
    }

    protected function passwd()
    {
      //$this->getResponse()->setHttpHeader('WWW-Authenticate', 'Basic realm="'.sfConfig::get('app_info_title').'"');
      //$this->getResponse()->setStatusCode(401);

      //$this->setLayout(false);
      $this->setTemplate('passwd');
    }

}
