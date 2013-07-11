<?php

/**
 * directo actions.
 *
 * @package    fin
 * @subpackage directo
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */
class directoActions extends sfActions
{
  /**
   * Executes index action
   *
   */
  public function executeIndex()
  {
    $event_id = $this->getRequestParameter('id', 1);

    $this->event = EventPeer::retrieveByPk($event_id);
    $this->forward404Unless($this->event);

    $this->getUser()->resetPan();
    $this->getUser()->panNivelDos('Teleactos', 'teleactos');
    $this->getUser()->panNivelDosYMedio('Directo', 'directo/index?id='.$event_id);

    $this->title   = "Teleacto: ";
    

    //obtiene 10 teleactos cuyas sesione se producen hoy
    $c = new Criteria();

    $c->addJoin(SessionPeer::EVENT_ID, EventPeer::ID);
    $c->add(SessionPeer::INIT_DATE, date("Y-m-d H:i", mktime(date("H"), date("i"), 0, date("m"),date("d"),date("Y"))), Criteria::LESS_EQUAL);
    $c->add(SessionPeer::END_DATE, date("Y-m-d H:i", mktime(date("H"), date("i"), 0, date("m"),date("d"),date("Y"))), Criteria::GREATER_EQUAL);

    $c->add(EventPeer::DISPLAY, true);
    $c->addAscendingOrderByColumn(SessionPeer::INIT_DATE);

    $c->setLimit(10);
    $c->setDistinct(true);
    
    $this->events = EventPeer::doSelect($c);

    if (($this->event->getSessionNow() == null) && ($this->event->getFutureSessions() == null)){
      if (!$this->event->getSerial()->isWorking()) {
	if (PubChannelPeer::countMmsFromSerial(1, $this->event->getSerialId()) > 1) {
	  return $this->redirect('serial/index?id='.$this->event->getSerialId());
	}
	if (PubChannelPeer::countMmsFromSerial(1, $this->event->getSerialId()) == 1) {
	  $mms = $this->event->getSerial()->getMmsPublic();
          $mm = $mms[0];
	  return $this->redirect('mmobj/index?id='.$mm->getId());
	}
      }
      return "Past";
    } 

    elseif (($this->event->getSessionNow() == null) && ($this->event->getTodaysFirstSession() != null) && ($this->event->getTodaysFutureSession() == null)) {
      return "Past";
    }

    if ($this->hasRequestParameter('passwd')) {
      if ($this->event->getPassword() != $this->getRequestParameter('passwd')) {
	$this->setTemplate('passwd');
      }
    }
    elseif ($this->event->getSecured()) {
      $this->setTemplate('passwd');
    }

    if ($this->event->getEnableQuery()) {
      $g = new Captcha();
      $this->getUser()->setAttribute('captcha', $g->generate());
    }

  }


  public function handleErrorMail(){
    $this->event = EventPeer::retrieveByPk($this->getRequestParameter('event')); 
    $this->name = $this->getRequestParameter('name');
    $this->reply = $this->getRequestParameter('mail');
    $this->content = $this->getRequestParameter('content');
    $g = new Captcha();
    $this->getUser()->setAttribute('captcha', $g->generate());
  }


  public function executeMail() {
    $this->forward404If(!$this->hasRequestParameter('event'));
    $this->event = EventPeer::retrieveByPk($this->getRequestParameter('event'));
    $name = $this->getRequestParameter('name');
    $reply = $this->getRequestParameter('mail');
    $content = $this->getRequestParameter('content');
 
    $mail = new sfMail();
    $mail->setCharset('utf-8');
    $mail->setSender($reply);
    $mail->setMailer('mail');
    $mail->setFrom($reply, $name);

    $mail->addAddresses($this->event->getEmailQuery());

    $mail->setSubject("UnedTV - Nueva consulta teleacto: ".$this->event->getTitle());
    $mail->setBody($content);

    if ($this->event->getEmailQuery() != null) $mail->send();
    $this->setFlash('enviado', 'enviado');
  }
}
