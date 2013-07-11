<?php
/**
 * MODULO EVENTS COMPONENTS. 
 * Modulo de configuracion de los noticias y eventos que aparecen en el portal web.
 *
 * @package    pumukit
 * @subpackage events
 * @author     Ruben Gonzalez Gonzalez <rubenrua ar uvigo dot es>
 * @version    1.0
 **/
class eventsComponents extends sfComponents
{
  /**
   * Executes index component
   *
   */

  public function executePreview()
  {
    if ($this->getUser()->hasAttribute('id', 'tv_admin/event')){
      $this->event = EventPeer::retrieveByPk($this->getUser()->getAttribute('id', null, 'tv_admin/event'));
    } else {
      //obtiene el primero el mas futuro
      $c = new Criteria;
      $this->processSort($c);
      $this->event = EventPeer::doSelectOne($c);
    }
  }

  public function executeArray()
  {
    $limit  = 11;
    $offset = 0;

    $c = new Criteria();

    $this->processSort($c);
    $this->processFilters($c);

    $cTotal = clone $c;

    if ($this->hasRequestParameter('page'))
    {
      $this->getUser()->setAttribute('page', $this->getRequestParameter('page'), 'tv_admin/event');
    }

    if ($this->getUser()->hasAttribute('page', 'tv_admin/event') )
    {
      $this->page = $this->getUser()->getAttribute('page', null, 'tv_admin/event');
      $offset = ($this->page - 1) * $limit;
      $c->setLimit($limit);
      $c->setOffset($offset);
    }

    $this->total_event_all = EventPeer::doCount(new Criteria());
    $this->total_event = EventPeer::doCount($cTotal);
    $this->total = ceil($this->total_event / $limit); 

    if ($this->total < $this->page)
    {
      $this->getUser()->setAttribute('page',1);
      $this->page = 1;
      $c->setOffset(0);
    }
    
    $this->events = EventPeer::doSelectWithI18n($c, $this->getUser()->getCulture());

    //Marco el primero si no esta seleccionado ningun teleacto.
    if(count($this->events) > 0) {
      $f = create_function('$a', 'return $a->getId();');
      if (!in_array($this->getUser()->getAttribute('id', 0, 'tv_admin/event'), array_map($f, $this->events))){
	$this->getUser()->setAttribute('id', $this->events[0]->getId(), 'tv_admin/event');
      }
    }
  }
  

  public function executeListSessions()
  {
    if ($this->getUser()->hasAttribute('id', 'tv_admin/event')){
      $this->event = EventPeer::retrieveByPK($this->getUser()->getAttribute('id', null, 'tv_admin/event'));
    }
  }


 public function executeEdit()
  {
    if ($this->getUser()->hasAttribute('id', 'tv_admin/event'))
    {
      $this->event = EventPeer::retrieveByPk($this->getUser()->getAttribute('id', null, 'tv_admin/event'));
    }
    
    if ($this->getUser()->hasAttribute('cal', 'tv_admin/event'))
      {
	$this->div = '?cal=cal';
      }
    else{
      $this->div = '';
    }
     
    $this->langs = sfConfig::get('app_lang_array', array('es'));
  }


  public function executeCalendar()
  {
    $this->total_event_all = EventPeer::doCount(new Criteria());


    if ($this->getRequestParameter('mes') == "mas")
    {
      $m = $this->getUser()->getAttribute('mes', date('m'), 'tv_admin/event');
      $y = $this->getUser()->getAttribute('ano', date('Y'), 'tv_admin/event');
      $fecha_cambiada = mktime(0,0,0,$m+1,1,$y);
      $this->getUser()->setAttribute('ano', date("Y", $fecha_cambiada), 'tv_admin/event');
      $this->getUser()->setAttribute('mes', date("m", $fecha_cambiada), 'tv_admin/event');
    }elseif ($this->getRequestParameter('mes') == "menos"){
      $m = $this->getUser()->getAttribute('mes', date('m'), 'tv_admin/event');
      $y = $this->getUser()->getAttribute('ano', date('Y'), 'tv_admin/event');
      $fecha_cambiada = mktime(0,0,0,$m-1,1,$y);
      $this->getUser()->setAttribute('ano', date("Y", $fecha_cambiada), 'tv_admin/event');
      $this->getUser()->setAttribute('mes', date("m", $fecha_cambiada), 'tv_admin/event');
    }elseif ($this->getRequestParameter('mes') == "hoy"){
      $this->getUser()->setAttribute('ano', date("Y"), 'tv_admin/event');
      $this->getUser()->setAttribute('mes', date("m"), 'tv_admin/event');
    }


    $this->m = $this->getUser()->getAttribute('mes', date('m'), 'tv_admin/event');
    $this->y = $this->getUser()->getAttribute('ano', date('Y'), 'tv_admin/event');
    $this->cal = calendar::generate_array($this->m, $this->y);
  }



  protected function processFilters($c)
  {

    if ($this->getRequest()->hasParameter('filter')){
      $filters = $this->getRequestParameter('filters');

      $this->getUser()->getAttributeHolder()->removeNamespace('tv_admin/event/filters');
      $this->getUser()->getAttributeHolder()->add($filters, 'tv_admin/event/filters');
    }

    $filters = $this->getUser()->getAttributeHolder()->getAll('tv_admin/event/filters');

    if (isset($filters['title']) && $filters['title'] !== ''){
      $c->addJoin(EventI18nPeer::ID, EventPeer::ID);
      $c->add(EventI18nPeer::TITLE, '%' . $filters['title']. '%', Criteria::LIKE);
    }

    if (isset($filters['date'])){
      if (isset($filters['date']['from']) && $filters['date']['from'] !== ''){
        list($d, $m, $y) = sfI18N::getDateForCulture($filters['date']['from'], $this->getUser()->getCulture());
	//para obtener sesiones en esas fechas
	$c->addJoin(EventPeer::ID, SessionPeer::EVENT_ID);
        $criterion = $c->getNewCriterion(SessionPeer::INIT_DATE, "$y-$m-$d", Criteria::GREATER_EQUAL);
	$c->setDistinct(); 
      }

      if (isset($filters['date']['to']) && $filters['date']['to'] !== ''){
        if (isset($criterion)){
          list($d, $m, $y) = sfI18N::getDateForCulture($filters['date']['to'], $this->getUser()->getCulture());
	  $d = $d+1;
	  // filtra por sesiones
	  $criterion->addAnd($c->getNewCriterion(SessionPeer::END_DATE, "$y-$m-$d", Criteria::LESS_THAN));
        }else{
          list($d, $m, $y) = sfI18N::getDateForCulture($filters['date']['to'], $this->getUser()->getCulture());
	  //Para obtener sesiones en esas fechas
	  $c->addJoin(EventPeer::ID, SessionPeer::EVENT_ID);
	  $criterion = $c->getNewCriterion(SessionPeer::END_DATE, "$y-$m-$d", Criteria::LESS_EQUAL); 	 
        }
      }

      if (isset($criterion))
        {
          $c->add($criterion);
        }
    }

  }

  protected function processSort(Criteria $c)
  {
    if ($this->getRequestParameter('sort')){
      $this->getUser()->setAttribute('sort', $this->getRequestParameter('sort'), 'tv_admin/event');
      $this->getUser()->setAttribute('type', $this->getRequestParameter('type', 'asc'), 'tv_admin/event');
    }


    if ($sort_column = $this->getUser()->getAttribute('sort', null, 'tv_admin/event')){
      try{
	$sort_column = EventPeer::translateFieldName($sort_column, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_COLNAME);
      }catch(Exception $e){
	try{
	  $sort_column = EventI18nPeer::translateFieldName($sort_column, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_COLNAME);
	}catch(Exception $e){
	}
      }
      if ($this->getUser()->getAttribute('type', 'asc', 'tv_admin/event') == 'asc'){
	$c->addAscendingOrderByColumn($sort_column);
      }else{
	$c->addDescendingOrderByColumn($sort_column);
      }
    }
  }

}
