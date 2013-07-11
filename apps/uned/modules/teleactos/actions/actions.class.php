<?php

/**
 * teleactos actions.
 *
 * @package    pumukituvigo
 * @subpackage teleactos
 * @author     Ruben Glez <rubenrua at uvigo.es>
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */
class teleactosActions extends sfActions
{
  /**
   * Executes index action
   *
   */
  public function executeIndex()
  {
    $this->getUser()->resetPan();    
    $this->getUser()->panNivelDos('Teleactos', 'teleactos');

    $this->title   = "Teleactos: ";
    
    $limit = 10;
    $page = $this->getRequestParameter('page', 1);
    if ($page < 1) {
      $page = 1;
    }
    $offset = ($page - 1) * $limit;

    $out = EventPeer::getFutureEvents($limit, $offset);
    $out_td = EventPeer::getToDayEvents(0, $offset); 
    $out_rn = EventPeer::getRNEvents(0, $offset); 


    //para obtener la fecha en el formato correcto
    //      setlocale(LC_TIME, $this->getUser()->getCulture().'_ES.utf-8');  
      

    //los futuros ordenados por la fecha del evento mas cercano
    $aux = array();
    foreach($out['events'] as $event){
      if ($event->getFutureSession() != null) {
	if (!array_key_exists(date('d M'), $aux)) {
	  $aux[date('d M')] = array();
	}
	$aux[date('d M')][] = $event;
      }
    }
      

    //los de hoy ordenados por fecha inicial del evento de hoy
    //primero elimina de los de hoy los que ya estan en directo
    $aux_td = array();
    foreach($out_td as $event){
      if (!in_array($event, $out_rn)) {
	if (!array_key_exists(date('d M'), $aux_td)) {
	  $aux_td[date('d M')] = array();
	}
	$aux_td[date('d M')][] = $event;
      }
    } 
      

    //los de ahora
    $aux_rn = array();
    foreach($out_rn as $event){
      if (!array_key_exists(date('d M'), $aux_rn)) {
	$aux_rn[date('d M')] = array();
      }
      $aux_rn[date('d M')][] = $event;
    }
      
      
    $this->events = $aux;
    $this->toDayEvents = $aux_td;
    $this->rnEvents = $aux_rn;
    $this->page    = $page;
    $this->pages   = ceil($out['total'] / $limit);  
  }

}
