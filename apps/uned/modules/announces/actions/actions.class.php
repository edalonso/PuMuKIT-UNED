<?php

/**
 * announces actions.
 *
 * @package    fin
 * @subpackage announces
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */
class announcesActions extends sfActions
{
  /**
   * Executes index action
   *
   */
  public function executeIndex()
  {
    $this->getUser()->resetPan();
    $this->getUser()->panNivelDos('Mediateca por meses', 'announces');

    $this->date = date("Y"). "/" . date("m") . "/1";
    $i = 0;

    $this->announces = MmPeer::getAnnouncesByDate($this->date, $this->getUser()->getCulture());
    while( count($this->announces) == 0 && $i < 24 ) {
      $aux_date = strtotime($this->date);
      $pre_month = strtotime('-1 month', $aux_date);
      $this->date = date('Y/m/d', $pre_month);

      $this->announces = MmPeer::getAnnouncesByDate($this->date, $this->getUser()->getCulture());
      $i++;
    }
  }


  /**
   * Executes part action
   *
   */
  public function executePart()
  {
    $request = $this->getRequest();

    $this->date = date('Y', strtotime($request->getParameter("fecha") . "/1")) . "/" . date('m', strtotime($request->getParameter("fecha") . "/1")) . "/1";
    $this->setLayout(false);

    $this->announces = MmPeer::getAnnouncesByDate($this->date, $this->getUser()->getCulture());

    $i = 0;

    while( count($this->announces) == 0 && $i < 24 ) {
      $aux_date = strtotime($this->date);
      $pre_month = strtotime('-1 month', $aux_date);

      $this->date = date('Y/m/d', $pre_month);

      $this->announces = MmPeer::getAnnouncesByDate($this->date, $this->getUser()->getCulture());
      $i++;
    }
    $this->getResponse()->setHttpHeader('returnDate', date('m-Y', strtotime($this->date)));
  }
}
