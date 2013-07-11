<?php

/**
 * index actions.
 *
 * @package    pumukituvigo
 * @subpackage index
 * @author     Ruben Glez <rubenrua at uvigo.es>
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */
class indexActions extends sfActions
{
  public function executeIndex()
  {
    $this->setLayout("nobodylayout");
  }


  public function executeIndexpanel()
  {
    $this->getResponse()->addJavascript('/js/jquery-1.9.0.js');
    $this->getResponse()->addJavascript('/js/jquery-ui-1.10.0.custom.min.js');
    $this->getResponse()->addJavascript('/js/jquery.jstree.js');
    $this->getResponse()->addJavascript('/js/jquery.cookie.js');
    $this->getResponse()->addJavascript('/js/jquery.hotkeys.js');
    $this->getResponse()->addStylesheet('/css/admin/js-tree.css');

    if(!$this->getUser()->hasAttribute('only', 'explore'))
      $this->getUser()->setAttribute('only', 'all', 'explore');
  }



  public function executeCattree()
  {
    $this->operation = $this->getRequestParameter('operation');
    $id = $this->getRequestParameter('id');
    $cat = CategoryPeer::retrieveByPk($id);
    $datosCategory = array();
    if ($this->operation == 'get_children'){
      foreach($cat->getChildren() as $c){
	if ($id == 1){
	  $datosCategory[] = array(
				   "attr" => array(
						   "id"    => "node_" . $c->getId(),
						   "rel"   => "drive",
						   ),
				   "data"  => $c->getCodName() . ' (' .$c->getNumMm() . ')',
				   "state" => "closed"
				   );
	} else {
	  $datosCategory[] = array(
				   "attr" => array(
						   "id"    => "node_" . $c->getId(),
						   "rel"   => "folder",
						   ),
				   "data"  => $c->getCodName() . ' (' .$c->getNumMm() . ')',
				   "state" => ((count($c->getChildren()) == 0)?"leaf":"closed")
				   );
	  
	}
      }
    }
    return $this->renderText(json_encode($datosCategory));
  }


  public function executeMainpanel()
  {
    if ($this->hasRequestParameter('only') && (in_array($this->getRequestParameter('only'), array('audio', 'video', 'all'))))
    {
      $this->getUser()->setAttribute('only', $this->getRequestParameter('only'), 'explore');
    }

    $limit = 15;
    $page = $this->getRequestParameter('page', 1);

    if ($page < 1) {
      $page = 1;
    }

    $this->setLayout("bootstraplayout");

    $id = $this->getRequestParameter('id');
    $this->cat = CategoryPeer::retrieveByPk($id);
    if(!$this->cat) {
      return "Empty";
    }
    
    $this->mms = $this->getMms($this->cat->getId(), $limit, $limit * ($page - 1));

    $this->total_mms = $this->countMms($this->cat->getId());
    $this->limit = $limit;
    $this->page  = $page;
    $this->pages = ceil($this->total_mms / $limit);

    $this->cat_raiz_unesco = CategoryPeer::retrieveByCode("UNESCO");
    $this->cat_raiz_uned = CategoryPeer::retrieveByCode("Tematicas UNED");
  }


  private function getMms($cat_id, $limit, $offset, $parent = null)
  {
    $c = new Criteria();

    $c->addJoin(MmPeer::ID, CategoryMmPeer::MM_ID);
    $c->add(CategoryMmPeer::CATEGORY_ID, $cat_id);
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
    $c->setLimit($limit);
    $c->setOffset($offset);

    return MmPeer::doSelect($c);
  }

  private function countMms($cat_id, $parent = null)
  {
    $c = new Criteria();

    $c->addJoin(MmPeer::ID, CategoryMmPeer::MM_ID);
    $c->add(CategoryMmPeer::CATEGORY_ID, $cat_id);
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
