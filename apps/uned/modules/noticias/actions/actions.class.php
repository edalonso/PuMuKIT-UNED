<?php

/**
 * noticias actions.
 *
 * @package    pumukituvigo
 * @subpackage noticias
 * @author     Ruben Glez <rubenrua at uvigo.es>
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */
class noticiasActions extends CanalUnedActions
{
  /**
   * Executes index action
   *
   */
  public function executeIndex()
  {
    $this->getUser()->resetPan();
    $this->getUser()->panNivelDos('Noticias', 'noticias');
    $this->title   = "Noticias";

    $this->error = '';
    $this->total = 0;

    $maxPerPage = $this->getRequestParameter('numPerPage', 20);

    $c = $this->getCriteria();
    $c->addJoin(MmPeer::GENRE_ID, GenreI18nPeer::ID);
    $c->add(GenreI18nPeer::NAME, "Noticias");
    $mms = array();
    try{
      $mms = $this->getMms($c, $maxPerPage);
    }catch(Exception $e){
      $this->error = $e->getMessage();
    }

    $this->years = $this->getYears();
    $this->unescos = $this->getCategories(CategoryPeer::retrieveByCod("UNESCO")); 
    $this->mms = $this->groupMms($mms);
    $this->setTemplate('displaymmsdate');
  }


  /**
   * Selecciona la nodoserie Noticias con más mms.
   * De momento hay varias: mayúsculas, minúsculas, dentro de programa de tv o independiente.
   * Las búsquedas por nombre son case-insensitive
   */
  private function getCategoryNoticias()
  {
    $cats  = CategoryPeer::doSelectByName("Noticias");
    $max_mms = 0;
    foreach ($cats as $cat){
      if ($cat->getNumMm() > $max_mms){
        $biggest_cat = $cat;
        $max_mms = $cat->getNumMm();
      }
    }
    
    return $biggest_cat;
  }

}