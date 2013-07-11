<?php

/**
 * serials components.
 *
 * @package    fin
 * @subpackage serials
 * @author     Your name here
 * @version    SVN: $Id: components.class.php 2692 2006-11-15 21:03:55Z fabien $
 */
class serialsComponents extends sfComponents
{
  /**
   * Executes index component
   *
   */

  public function executePreview()
  {
    if ($this->getUser()->hasAttribute('id', 'new_admin/serials'))
    {
      $this->serial = SerialPeer::retrieveByPk($this->getUser()->getAttribute('id', null, 'new_admin/serials'));
    }
  }

  public function executeListrelation()
  {
      $this->persons = PersonPeer::doList($this->mm->getId(), $this->role->getId(), $this->getUser()->getCulture());
  }

  public function executeListrelationtemplate()
  {
    $this->persons = PersonPeer::doListTemplate($this->mm->getId(), $this->role->getId(), $this->getUser()->getCulture());
  }

  /**
   * Executes index component
   *
   */
  public function executeListFiles()
  {
    if (isset($this->mm)){
      $this->files = FilePeer::getFilesFromMm($this->mm, $this->getUser()->getCulture());
      $this->transcodings = TranscodingPeer::getTranscodingsFromMm($this->mm, false);
      $this->oc = MmMatterhornPeer::retrieveByPK($this->mm);
    }elseif ($this->hasRequestParameter('mm')){
      $this->mm = $this->getRequestParameter('mm');
      $this->files = FilePeer::getFilesFromMm($this->getRequestParameter('mm'), $this->getUser()->getCulture());
      $this->transcodings = TranscodingPeer::getTranscodingsFromMm($this->getRequestParameter('mm'), false);
      $this->oc = MmMatterhornPeer::retrieveByPK($this->mm);
    }else{
      $this->files = array();
      $this->transcodings = array();
      $this->oc = null;
    }
  }

  /**
   * Executes list component
   *
   */
  public function executeListPics()
  {
    if (isset($this->mm)){
      $this->object_id = $this->mm;
      $this->pics = PicPeer::getPicsFromMm($this->object_id);
      $this->que = 'mm';
    }elseif (isset($this->serial)){
      $this->object_id = $this->serial;
      $this->pics = PicPeer::getPicsFromSerial($this->object_id);
      $this->que = 'serial';
    }elseif (isset($this->channel)){
      $this->object_id = $this->channel;
      $this->pics = PicPeer::getPicsFromChannel($this->object_id);
      $this->que = 'channel';
    }elseif ($this->hasRequestParameter('mm')){
      $this->object_id = $this->getRequestParameter('mm');
      $this->pics = PicPeer::getPicsFromMm($this->object_id);
      $this->que = 'mm';
    }elseif ($this->hasRequestParameter('serial')){
      $this->object_id = $this->getRequestParameter('serial');
      $this->pics = PicPeer::getPicsFromSerial($this->object_id);
      $this->que = 'serial';
    }elseif ($this->hasRequestParameter('channel')){
      $this->object_id = $this->getRequestParameter('channel');
      $this->pics = PicPeer::getPicsFromChannel($this->object_id);
      $this->que = 'channel';
    }else{
      $this->pics = array();
      $this->object_id = 0;
      $this->que = 'nada';
    }
  }

  /**
   * COPIADO del modulo materials
   * Executes index component
   *
   */
  public function executeListMaterials()
  {
    if (isset($this->mm)){
      $this->materials = MaterialPeer::getMaterialsFromMm($this->mm, $this->getUser()->getCulture());
    }elseif ($this->hasRequestParameter('mm')){
      $this->mm = $this->getRequestParameter('mm');
      $this->materials = MaterialPeer::getMaterialsFromMm($this->getRequestParameter('mm'), $this->getUser()->getCulture());
    }else{
      $this->materials = array();
    }
  }

  /**
   * Executes index component
   *
   */
  public function executeListLinks()
  {
    if (isset($this->mm)){
      $this->links = LinkPeer::getLinksFromMm($this->mm, $this->getUser()->getCulture());
    }elseif ($this->hasRequestParameter('mm')){
      $this->mm = $this->getRequestParameter('mm');
      $this->links = LinkPeer::getLinksFromMm($this->getRequestParameter('mm'), $this->getUser()->getCulture());
    }else{
      $this->links = array();
    }
  }

  public function executePreviewmms()
  {
      $this->roles = RolePeer::doSelectWithI18n(new Criteria());
      if ($this->getUser()->getAttribute('id', 0, 'new_admin/serials') != 0){
          $this->mm = MmPeer::retrieveByPk($this->getUser()->getAttribute('id', null, 'new_admin/serials'));
      }
  }


  public function executeList()
  {
    $limit  = 11;
    $offset = 0;

    $c = new Criteria();

    $this->processSort($c);
    $this->processFilters($c);

    $cTotal = clone $c;

    if ($this->hasRequestParameter('page'))
    {
      $this->getUser()->setAttribute('page', $this->getRequestParameter('page'), 'new_admin/serials');
    }

    if ($this->getUser()->hasAttribute('page', 'new_admin/serials') )
    {
      $this->page = $this->getUser()->getAttribute('page', null, 'new_admin/serials');
      $offset = ($this->page - 1) * $limit;
      $c->setLimit($limit);
      $c->setOffset($offset);
    }

    $this->total_serial_all = SerialPeer::doCount(new Criteria());
    $this->total_serial = SerialPeer::doCount($cTotal);
    $this->total = ceil($this->total_serial / $limit); 

    if ($this->total < $this->page)
    {
      $this->getUser()->setAttribute('page',1);
      $this->page = 1;
      $c->setOffset(0);
    }

    $this->serials = SerialPeer::doList($c, $this->getUser()->getCulture());
    //$this->serials = SerialPeer::doSelectWithI18n($c, $this->getUser()->getCulture());
  }

  public function executeMmsList()
  {
      $limit = 7;
      $page = $this->getUser()->getAttribute('page', 1, 'new_admin/serials');
      $id = $this->getUser()->getAttribute('cat_id', 1, 'new_admin/serials');

      
      if ($id == 0) {
          $this->cat = CategoryPeer::retrieveByPk(1);
          $this->mms = $this->getMms(null, $limit, $limit * ($page - 1));
          $this->total_mms = $this->countMms(null);
      }else {
          $this->cat = CategoryPeer::retrieveByPk($id);
          $this->mms = $this->getMms($this->cat->getId(), $limit, $limit * ($page - 1));
          $this->total_mms = $this->countMms($this->cat->getId());
      }
      //TODO
      if(!$this->cat) {
          return "Empty";
      }

      $this->cat_raiz_unesco = CategoryPeer::retrieveByCode("UNESCO");
      $this->cat_raiz_uned = CategoryPeer::retrieveByCode("Tematicas UNED");
      
      
      $this->limit = $limit;
      $this->page  = $page;
      $this->pages = ceil($this->total_mms / $limit);
  }

  public function executeEditmms()
  {
      if ($this->getUser()->getAttribute('id', 0, 'new_admin/serials') != 0){
          $this->mm = MmPeer::retrieveByPk($this->getUser()->getAttribute('id', null, 'new_admin/serials'));
      }else{
          $c = new Criteria;
          $c->add(MmPeer::SERIAL_ID, $this->getUser()->getAttribute('serial'));
          $c->addAscendingOrderByColumn(MmPeer::RANK);
          $this->mm = MmPeer::doSelectOne($c);
      }

      if (!isset($this->mm)) return;

      $this->langs = sfConfig::get('app_lang_array', array('es'));
      $this->grounds_sel = $this->mm->getGrounds();
      $cg = new Criteria();
      $cg->addAscendingOrderByColumn(GroundPeer::COD);
      $this->grounds = GroundPeer::doSelectWithI18n($cg, $this->getUser()->getCulture());

      $c = new Criteria();
      $c->add(GroundTypePeer::DISPLAY, true);
      $c->addAscendingOrderByColumn(GroundTypePeer::RANK);
      $this->groundtypes = GroundTypePeer::doSelectWithI18n($c, 'es');

      $c = new Criteria();
      $c->addAscendingOrderByColumn(RolePeer::RANK);
      $this->roles = RolePeer::doSelectWithI18n($c, $this->getUser()->getCulture()); //ORDER
  }


  public function executeEdit()
  {
    if ($this->getUser()->hasAttribute('id', 'new_admin/serials'))
    {
      $this->serial = SerialPeer::retrieveByPk($this->getUser()->getAttribute('id', null, 'new_admin/serials'));
    }
    $this->langs = sfConfig::get('app_lang_array', array('es'));
  }

  protected function processFilters(Criteria $c)
  {
    $c->setDistinct(true);
    if ($this->getRequest()->hasParameter('filter')){
      $filters = $this->getRequestParameter('filters');

      $this->getUser()->getAttributeHolder()->removeNamespace('new_admin/serial/filters');
      $this->getUser()->getAttributeHolder()->add($filters, 'new_admin/serial/filters');
    }

    $filters = $this->getUser()->getAttributeHolder()->getAll('new_admin/serial/filters');

    if (isset($filters['title']) && $filters['title'] !== ''){
      if(0 != intval($filters['title'])){
	$c->add(SerialPeer::ID, intval($filters['title']));
      }else{
	$c->addJoin(SerialPeer::ID, SerialI18nPeer::ID);
	$c->add(SerialI18nPeer::CULTURE, $this->getUser()->getCulture());

        $c->addJoin(SerialPeer::ID, MmPeer::SERIAL_ID);
	$c->addJoin(MmPeer::ID, MmI18nPeer::ID);
	$c->add(MmI18nPeer::CULTURE, $this->getUser()->getCulture());

	$c1 = $c->getNewCriterion(MmI18nPeer::TITLE, '%' . str_replace(' ', '%', $filters['title']). '%', Criteria::LIKE);
	$c2 = $c->getNewCriterion(SerialI18nPeer::TITLE, '%' . str_replace(' ', '%', $filters['title']). '%', Criteria::LIKE);
	$c1->addOr($c2);
	$c->add($c1);
      }
    }

    if (isset($filters['person']) && $filters['person'] !== ''){
      $c->addJoin(SerialPeer::ID, MmPeer::SERIAL_ID);
      $c->addJoin(MmPeer::ID, MmPersonPeer::MM_ID);
      $c->addJoin(PersonPeer::ID, MmPersonPeer::PERSON_ID);
      $c->add(PersonPeer::NAME, '%' . $filters['person']. '%', Criteria::LIKE);
      $c->setDistinct(true);
    }

    if (isset($filters['place']) && $filters['place'] != 0){
      $c->addJoin(SerialPeer::ID, MmPeer::SERIAL_ID);
      $c->addJoin(MmPeer::PRECINCT_ID, PrecinctPeer::ID);
      $c->add(PrecinctPeer::PLACE_ID, $filters['place']);
    }

    if (isset($filters['serialtype'])){
      $c->add(SerialPeer::SERIAL_TYPE_ID, array_keys($filters['serialtype']), Criteria::IN);
    }


    if (isset($filters['announce']) && ($filters['announce'] === 'true' || $filters['announce'] === 'false')){
      $c->add(SerialPeer::ANNOUNCE, $filters['announce'] === 'true');
    }

    if (isset($filters['status'])&&($filters['status'] != 'diff')){
      $c->addJoin(SerialPeer::ID, MmPeer::SERIAL_ID);
      $c->add(MmPeer::STATUS_ID, $filters['status']);
    }

    if (isset($filters['broadcast'])){
      $c->addJoin(SerialPeer::ID, MmPeer::SERIAL_ID);
      $c->add(MmPeer::BROADCAST_ID, array_keys($filters['broadcast']), Criteria::IN);
    }

    if (isset($filters['date'])){
      if (isset($filters['date']['from']) && $filters['date']['from'] !== ''){
        list($d, $m, $y) = sfI18N::getDateForCulture($filters['date']['from'], $this->getUser()->getCulture());
        $criterion = $c->getNewCriterion(MmPeer::RECORDDATE, "$y-$m-$d", Criteria::GREATER_EQUAL);
      }

      if (isset($filters['date']['to']) && $filters['date']['to'] !== ''){
        if (isset($criterion)){
          list($d, $m, $y) = sfI18N::getDateForCulture($filters['date']['to'], $this->getUser()->getCulture());
          $criterion->addAnd($c->getNewCriterion(MmPeer::RECORDDATE, "$y-$m-$d", Criteria::LESS_EQUAL));
        }else{
          list($d, $m, $y) = sfI18N::getDateForCulture($filters['date']['to'], $this->getUser()->getCulture());
          $criterion = $c->getNewCriterion(MmPeer::RECORDDATE, "$y-$m-$d", Criteria::LESS_EQUAL);
        }
      }

      if (isset($criterion)){
	$c->add($criterion);
	$c->addJoin(SerialPeer::ID, MmPeer::SERIAL_ID);
      }
    }
  }

  protected function processSort(Criteria $c)
  {
    if ($this->getRequestParameter('sort')){
      $this->getUser()->setAttribute('sort', $this->getRequestParameter('sort'), 'new_admin/serials');
      $this->getUser()->setAttribute('type', $this->getRequestParameter('type', 'asc'), 'new_admin/serials');
    }


    if ($sort_column = $this->getUser()->getAttribute('sort', null, 'new_admin/serials')){
      try{
	$sort_column = SerialPeer::translateFieldName($sort_column, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_COLNAME);
      }catch(Exception $e){
	try{
	  $sort_column = SerialI18nPeer::translateFieldName($sort_column, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_COLNAME);
	}catch(Exception $e){
	}
      }
      if ($this->getUser()->getAttribute('type', 'asc', 'new_admin/serials') == 'asc'){
	$c->addAscendingOrderByColumn($sort_column);
      }else{
	$c->addDescendingOrderByColumn($sort_column);
      }
    }
  }

  private function getMms($cat_id, $limit, $offset, $parent = null)
  {
      $c = new Criteria();
      if ($cat_id){
          $c->addJoin(MmPeer::ID, CategoryMmPeer::MM_ID);
          $c->add(CategoryMmPeer::CATEGORY_ID, $cat_id);
      }
      $c->addAscendingOrderByColumn(MmPeer::ID);
      if($parent) {
          $c->addAnd(CategoryPeer::TREE_LEFT, $parent->getLeftValue(), Criteria::GREATER_THAN);
          $c->addAnd(CategoryPeer::TREE_RIGHT, $parent->getRightValue(), Criteria::LESS_THAN);
          $c->addAnd(CategoryPeer::SCOPE, $parent->getScopeIdValue(), Criteria::EQUAL);

      }

      $c->setDistinct(true);
      $c->setLimit($limit);
      $c->setOffset($offset);

      return MmPeer::doSelect($c);
  }
  private function countMms($cat_id, $parent = null)
  {
      $c = new Criteria();

      if ($cat_id){
          $c->addJoin(MmPeer::ID, CategoryMmPeer::MM_ID);
          $c->add(CategoryMmPeer::CATEGORY_ID, $cat_id);
      }
      $c->addAscendingOrderByColumn(MmPeer::ID);
      if($parent) {
          $c->addAnd(CategoryPeer::TREE_LEFT, $parent->getLeftValue(), Criteria::GREATER_THAN);
          $c->addAnd(CategoryPeer::TREE_RIGHT, $parent->getRightValue(), Criteria::LESS_THAN);
          $c->addAnd(CategoryPeer::SCOPE, $parent->getScopeIdValue(), Criteria::EQUAL);

      }

      if($this->getUser()->getAttribute('only', null, 'explore') == 'audio') {
          $c->addJoin(MmPeer::ID, FilePeer::MM_ID);
          $c->add(FilePeer::AUDIO, 1);
      }

      if($this->getUser()->getAttribute('only', null, 'explore') == 'video') {
          $c->addJoin(MmPeer::ID, FilePeer::MM_ID);
          $c->add(FilePeer::AUDIO, 0);
      }

      $c->setDistinct(true);

      return MmPeer::doCount($c);
  }

}

/*  LocalWords:  serialsComponents
 */
