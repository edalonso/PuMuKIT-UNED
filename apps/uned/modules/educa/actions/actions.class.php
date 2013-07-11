<?php

/**
 * educa actions.
 *
 * @package    pumukituvigo
 * @subpackage educa
 * @author     Ruben Glez <rubenrua at uvigo.es>
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */
class educaActions extends CanalUnedActions
{
  /**
   * Executes index action
   *
   */
  public function executeIndex()
  {

    $this->getUser()->resetPan();
    $this->getUser()->panNivelDos('Recursos educativos', 'educa');

    //$id = $this->getRequestParameter('id');
    //$this->cat = CategoryPeer::retrieveByPk($id);

    $this->salud = array();//Ciencias de la vida y la salud
    $this->salud[] = CategoryPeer::retrieveByCode("U310000");
    $this->salud[] = CategoryPeer::retrieveByCode("U320000");
    $this->salud[] = CategoryPeer::retrieveByCode("U610000");
    $this->salud[] = CategoryPeer::retrieveByCode("U240000");
    $this->salud[] = CategoryPeer::retrieveByCode("U510000");
    $this->tecnologias = array();
    $this->tecnologias[] = CategoryPeer::retrieveByCode("U330000");
    $this->ciencias = array();
    $this->ciencias[] = CategoryPeer::retrieveByCode("U210000");
    $this->ciencias[] = CategoryPeer::retrieveByCode("U220000");
    $this->ciencias[] = CategoryPeer::retrieveByCode("U120000");
    $this->ciencias[] = CategoryPeer::retrieveByCode("U230000");
    $this->ciencias[] = CategoryPeer::retrieveByCode("U250000");
    $this->juridicas = array();
    $this->juridicas[] = CategoryPeer::retrieveByCode("U520000");
    $this->juridicas[] = CategoryPeer::retrieveByCode("U530000");
    $this->juridicas[] = CategoryPeer::retrieveByCode("U540000");
    $this->juridicas[] = CategoryPeer::retrieveByCode("U560000");
    $this->juridicas[] = CategoryPeer::retrieveByCode("U590000");
    $this->juridicas[] = CategoryPeer::retrieveByCode("U630000");
    $this->humanidades = array();
    $this->humanidades[] = CategoryPeer::retrieveByCode("U110000");
    $this->humanidades[] = CategoryPeer::retrieveByCode("U580000");
    $this->humanidades[] = CategoryPeer::retrieveByCode("U550000");
    $this->humanidades[] = CategoryPeer::retrieveByCode("U570000");
    $this->humanidades[] = CategoryPeer::retrieveByCode("U620000");
    $this->humanidades[] = CategoryPeer::retrieveByCode("U710000");
    $this->humanidades[] = CategoryPeer::retrieveByCode("U720000");

    if( $this->hasRequestParameter('oldtemplate') && ( $this->getRequestParameter('oldtemplate')=='true' || $this->getRequestParameter('oldtemplate')=='') )
      return ("oldPics");
  }

  /**
   * Executes objetos multimedia por fecha action
   *
   */
  public function executeAllMmsByDate()
  {

    $this->error = '';
    $this->total = 0;

    $id = $this->getRequestParameter('id');
    $maxPerPage = $this->getRequestParameter('numPerPage', 20);
    $this->page = $this->getRequestParameter('page', 1);
    $this->cat = CategoryPeer::retrieveByPk($id);
    $this->title = $this->cat->getName();

    $c = $this->getCriteria();
    $c->addJoin(MmPeer::ID, CategoryMmPeer::MM_ID);
    $c->add(CategoryMmPeer::CATEGORY_ID, $this->cat->getId());

    $mms = array();
    try{
      $mms = $this->getMms($c, $maxPerPage);
    }catch(Exception $e){
      $hits = array();
      $this->error = $e->getMessage();
    }

    $this->getUser()->panNivelDos('Recursos educativos', 'educa');
    $this->getUser()->panNivelDosYMedio($this->cat->getName(), 'educa/allMmsByDate?id=' . $id, $this->cat->getCod());

    $aux = array();

    foreach($mms as $mm){
      if (!array_key_exists($mm->getRecorddate('Y'), $aux)) {
	$aux[$mm->getRecorddate('Y')] = array();
      }
      $aux[$mm->getRecorddate('Y')][] = $mm;
    }

    $this->years = $this->getYears();
    $this->genres  = $this->getGenres();
    $this->mms = $aux;
    $this->setTemplate('displaymmsdate');
  }

}
