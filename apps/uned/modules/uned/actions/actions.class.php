<?php

/**
 * uned actions.
 *
 * @package    pumukituvigo
 * @subpackage uned
 * @author     Ruben Glez <rubenrua at uvigo.es>
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */
class unedActions extends CanalUnedActions
{
  public function executeCultural()
  {
    $unedCultural = CategoryPeer::retrieveByCode("U990200"); //UNESCO propios de UNED
    $this->getUser()->panNivelDos($unedCultural->getName(), 'uned/cultural');
    $this->module = "uned/cultural";

    $this->myExecute($unedCultural);
  }

  public function executeInstitucional()
  {
    $unedInstitucional = CategoryPeer::retrieveByCode("U990100"); //UNESCO propios de UNED
    $this->getUser()->panNivelDos($unedInstitucional->getName(), 'uned/institucional');
    $this->module = "uned/institucional";

    $this->myExecute($unedInstitucional);
  }


  public function myExecute($cat)
  {
    $this->error = '';
    $this->total = 0;
    
    $this->cat = $cat;
    $this->title = $this->cat->getName();

    $c = $this->getCriteria();
    $c->addJoin(MmPeer::ID, CategoryMmPeer::MM_ID);
    $c->add(CategoryMmPeer::CATEGORY_ID, $this->cat->getId());

    $maxPerPage = $this->getRequestParameter('numPerPage', 20);
    $mms = array();
    try{
      $mms = $this->getMms($c, $maxPerPage);
    }catch(Exception $e){
      $this->error = $e->getMessage();
    }

    $this->years = $this->getYears();
    $this->genres  = $this->getGenres();
    $this->unescos = $this->getCategories($cat);
    $this->mms = $this->groupMms($mms);
    $this->setTemplate('displaymmsdate');
    
  }
}
