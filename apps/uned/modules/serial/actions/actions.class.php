<?php

/**
 * serial actions.
 *
 * @package    pumukituvigo
 * @subpackage serial
 * @author     Ruben Glez <rubenrua at uvigo.es>
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */
class serialActions extends sfActions
{
  /**
   * Executes index action
   *
   */
  public function executeIndex()
  {
    $status = array(MmPeer::STATUS_NORMAL);

    if($this->hasRequestParameter('hash')){
      $hash = SerialHashPeer::retrieveByHash($this->getRequestParameter('hash'));
      $this->forward404Unless($hash);
      
      $this->serial = $hash->getSerial();
      $status[] = MmPeer::STATUS_HIDE;
      
      if(($this->getRequestParameter('preview')  == 'true')
         && $this->getUser()->isAuthenticated()
         && $this->getUser()->hasCredential('admin')) {
        $status[] = MmPeer::STATUS_BLOQ;
      }
    } else {
      $this->serial = SerialPeer::retrieveByPKWithI18n($this->getRequestParameter('id'), $this->getUser()->getCulture());
      $this->forward404Unless($this->serial);
      $this->forward404If(PubChannelPeer::countMmsFromSerial(1, $this->serial->getId()) == 0);
      $this->forward404Unless($this->serial->getDisplay());
      //$this->forward404If($this->serial->isWorking());
    }
    $this->getUser()->panNivelTres($this->serial);
    //
    // Si es privado y no tiene credenciales error 404
    //
    
    //$this->forward404If(($this->serial->getBroadcastMax()->getBroadcastTypeId() == 3) && (!$this->getUser()->hasCredential('pri')));
    $this->mms = PubChannelPeer::getMmsFromSerialByStatus(1, $this->serial->getId(), $status);
    //$this->mms = $this->serial->getMmsPublic(true); //Se muestran los mm ocultos ya que conoce la url.
    
    $this->roles = RolePeer::doSelectWithI18n(new Criteria(), $this->getUser()->getCulture());
    
    //
    // Metadatos tecnicos
    //
    
    $this->getResponse()->setTitle($this->serial->getTitle());
    $this->getResponse()->addMeta('keywords', $this->serial->getKeyword());

  }
}
