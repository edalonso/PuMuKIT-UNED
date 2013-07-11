<?php
/**
 * MODULO XML. 
 * Este modulo es el encargado de generar los diferentes FEEDs
 * con los datos que se tienen en el servidor. Los feed generados son:
 *    -ARCA FEED
 *    -VIDEOSITEMAP
 *
 * @package    pumukit
 * @subpackage xml
 * @author     Ruben Gonzalez Gonzalez (rubenrua at uvigo dot es)
 * @version    1.0
 */
class xmlActions extends sfActions
{

  /**
   * Se configura el modulo para:
   *  - Sin limite de tiempo de ejecucion.
   *  - Estrategia de escape BC.
   *  - Content-type: application/rss+xml; charset=utf-8. (view.yml)
   *  - Sin Layout. (view.yml)
   *
   */
  public function preExecute()
  {
    sfConfig::set('sf_escaping_strategy', 'bc');
    set_time_limit(0);
    //ini_set("memory_limit","256M");
  } 


  /**
   * OLD Se quita de Routing por sobrecarga, ver executeArcaByYear y 
   * execute arcaOPML
   * --  ARCA -- /arca.xml
   * Genera FEED arca (http://arca.rediris.es/doc.php?dmod=tech).
   * se puede validar en http://validator.w3.org/feed/
   *
   */
  public function executeArca()
  {
    $c = new Criteria();
    SerialPeer::addPublicCriteria($c);
    SerialPeer::addBroadcastCriteria($c, array('pub'));
    //$c->add(SerialPeer::PUBLICDATE, '2010-01-01', Criteria::GREATER_THAN);
    //$c->addAnd(SerialPeer::PUBLICDATE, '2010-10-01', Criteria::LESS_THAN);
    $this->serials = SerialPeer::doSelectWithI18n($c, 'es');

    $cr = new Criteria();
    $cr->add(RolePeer::DISPLAY, true);
    $this->roles = RolePeer::doSelectWithI18n($cr, 'es');
    $this->cat_raiz_unesco = CategoryPeer::retrieveByCode("UNESCO");
  }

  /**
   * --  ARCA -- /YYYY/arca.xml
   * Genera FEED arca (http://arca.rediris.es/doc.php?dmod=tech).
   * se puede validar en http://validator.w3.org/feed/
   *
   */
  public function executeArcaByYear()
  {
    $year = $this->getRequestParameter("year");

    $c = new Criteria();
    $c->add(MmPeer::RECORDDATE, $year . '-01-01', Criteria::GREATER_THAN);
    $c->addAnd(MmPeer::RECORDDATE, ($year + 1) . '-01-01', Criteria::LESS_THAN);
    $c->addJoin(PubChannelMmPeer::MM_ID, MmPeer::ID);
    $c->add(PubChannelMmPeer::PUB_CHANNEL_ID, 2);
    $c->add(PubChannelMmPeer::STATUS_ID, 1);
    $c->add(MmPeer::STATUS_ID, MmPeer::STATUS_NORMAL);
    $this->mms = MmPeer::doSelectWithI18n($c);
    
    $cr = new Criteria();
    $cr->add(RolePeer::DISPLAY, true);
    $this->roles = RolePeer::doSelectWithI18n($cr, 'es');
    $this->cat_raiz_unesco = CategoryPeer::retrieveByCode("UNESCO");

    $this->setTemplate("arca");
  }

  /**
   * --  ARCA -- /arca.xml
   * Genera FEED arca (http://arca.rediris.es/doc.php?dmod=tech).
   * se puede validar en http://validator.w3.org/feed/
   *
   */
  public function executeArcaOPML()
  {
    $conexion = Propel::getConnection();
    $consulta = 'select distinct YEAR(%s) as year from %s, %s WHERE %s = %s AND %s = %s';
    $consulta = sprintf($consulta, MmPeer::RECORDDATE, SerialPeer::TABLE_NAME, MmPeer::TABLE_NAME, 
			SerialPeer::ID, MmPeer::SERIAL_ID, MmPeer::STATUS_ID, MmPeer::STATUS_NORMAL);

    $sentencia = $conexion->prepareStatement($consulta);
    $resultset = $sentencia->executeQuery();
    $this->years = array();
    while($resultset->next()){
      $this->years[] = $resultset->getInt('year');
    }

  }

 public function executeNuevoarca()
  {
    $c = new Criteria();
    SerialPeer::addPublicCriteria($c);
    SerialPeer::addBroadcastCriteria($c, array('pub'));
    $c->addDescendingOrderByColumn(SerialPeer::PUBLICDATE);
    MmPeer::doSelectWithI18n($c, 'es');
    $c->setLimit(10);
    $this->mms = MmPeer::doSelectWithI18n($c, 'es');

    $cs = new Criteria();
    SerialPeer::addPublicCriteria($cs);
    SerialPeer::addBroadcastCriteria($cs, array('pub'));
    $cs->addDescendingOrderByColumn(SerialPeer::PUBLICDATE);
    $cs->setLimit(10);
    $this->serials = SerialPeer::doSelectWithI18n($cs, 'es');

    $cr = new Criteria();
    $cr->add(RolePeer::DISPLAY, true);
    $this->roles = RolePeer::doSelectWithI18n($cr, 'es');
  }


 public function executeNuevorss()
  {
    $c = new Criteria();
    SerialPeer::addPublicCriteria($c, 1);
    SerialPeer::addBroadcastCriteria($c, array('pub'));
    $c->addDescendingOrderByColumn(SerialPeer::PUBLICDATE);
 //   $c->add(SerialPeer::PUBLICDATE,'1800-01-01', Criteria::GREATER_THAN);
    $this->serials = SerialPeer::doSelectWithI18n($c, 'es');

    $cr = new Criteria();
    $cr->add(RolePeer::DISPLAY, true);
    $this->roles = RolePeer::doSelectWithI18n($cr);
  }

  public function executeNovedades()
  {
    $this->announces = SerialPeer::getAnnounces( 'es', 'pub');

    $cr = new Criteria();
    $cr->add(RolePeer::DISPLAY, true);
    $this->roles = RolePeer::doSelectWithI18n($cr);
  }


  public function executeLastnews()
  {    
    $this->getResponse()->setContentType('text/xml');
    $this->link = "xml/lastnews";

    $c = new Criteria();
    $c->add(MmPeer::ANNOUNCE, true);
    $c->addJoin(PubChannelMmPeer::MM_ID, MmPeer::ID);
    $c->add(PubChannelMmPeer::PUB_CHANNEL_ID, 1);
    $c->add(PubChannelMmPeer::STATUS_ID, 1);
    $c->add(MmPeer::STATUS_ID, MmPeer::STATUS_NORMAL);

    $c->addJoin(MmPeer::BROADCAST_ID, BroadcastPeer::ID);
    $c->addJoin(BroadcastPeer::BROADCAST_TYPE_ID, BroadcastTypePeer::ID);
    $c->add(BroadcastTypePeer::NAME, array('pub', 'cor'), Criteria::IN);
    $c->setDistinct(true);

    $c->addDescendingOrderByColumn(MmPeer::RECORDDATE);
    $c->setLimit(10);
    $this->mms = MmPeer::doSelect($c);
  }


  /**
   * --  VIDEOSITEMAP -- /videositemap.xml
   * Genera FEED Google VideoSiteMap
   * mas info en http://google.es/support/webmaster/bin/topic.py?topic=10079
   *
   */
  public function executeVideositemap()
  {
    $c = new Criteria();
    SerialPeer::addPublicCriteria($c);
    SerialPeer::addBroadcastCriteria($c);
    
    $this->serials = SerialPeer::doSelectWithI18n($c, 'es');
  }


  /**
   * --  PODCAST -- /podcast/:id.xml
   * Genera FEED podcast (http://www.apple.com/support/itunes_u/).
   *
   */
  public function executePodcast()
  {
    if(in_array($this->getRequestParameter('culture', 'es'), array('es','gl'))){
      $this->culture = $this->getRequestParameter('culture', 'es');
    }else{
      $this->culture = "es";
    }

    $this->serial = SerialPeer::retrieveByPKWithI18n($this->getRequestParameter('id'), $this->culture);
    $this->forward404Unless($this->serial);
    $this->forward404If($this->serial->isWorking());
    $this->forward404If((0 === $this->serial->countFileByPerfil(array(22, 34, 48))));

    $this->onlyi18n = ($this->getRequestParameter('culture') == 'en')?true:false;

    if($this->getRequestParameter('only') == 'only') $this->setTemplate('podcastonly');
    //CHAPUZA
    if($this->getRequestParameter('culture') == 'en') $this->setTemplate('podcasten');
  }


  /**
   * --  VIDEOCAST -- /videocast/:id.xml
   * Genera FEED videocast (http://www.apple.com/support/itunes_u/).
   *
   */
  public function executeVideocast()
  {
    if(in_array($this->getRequestParameter('culture', 'es'), array('es','gl'))){
      $this->culture = $this->getRequestParameter('culture', 'es');
    }else{
      $this->culture = "es";
    }

    $this->serial = SerialPeer::retrieveByPKWithI18n($this->getRequestParameter('id'), $this->culture);
    $this->forward404Unless($this->serial);
    $this->forward404If($this->serial->isWorking());
    $this->forward404If((0 === $this->serial->countFileByPerfil(array(20, 21, 32, 33))));

    $this->onlyi18n = ($this->getRequestParameter('culture') == 'en')?true:false;

    if($this->getRequestParameter('only') == 'only') $this->setTemplate('videocastonly');
    //CHAPUZA
    if($this->getRequestParameter('culture') == 'en') $this->setTemplate('videocasten');
  }

  /**
   * --  PODCAST -- /podcast/:id.xml
   * Genera FEED podcast (http://www.apple.com/support/itunes_u/).
   *
   */
  public function executePodcastuvigo()
  {
    if(in_array($this->getRequestParameter('culture', 'es'), array('es','gl'))){
      $this->culture = $this->getRequestParameter('culture', 'es');
    }else{
      $this->culture = "es";
    }

    $this->serial = SerialPeer::retrieveByPKWithI18n($this->getRequestParameter('id'), $this->culture);
    $this->forward404Unless($this->serial);
    $this->forward404If($this->serial->isWorking());
    $this->forward404If((0 === $this->serial->countFileByPerfil(array(22, 34, 48))));

    $this->onlyi18n = ($this->getRequestParameter('culture') == 'en')?true:false;
  }


  /**
   * --  VIDEOCAST -- /videocast/:id.xml
   * Genera FEED videocast (http://www.apple.com/support/itunes_u/).
   *
   */
  public function executeVideocastuvigo()
  {

    if(in_array($this->getRequestParameter('culture', 'es'), array('es','gl'))){
      $this->culture = $this->getRequestParameter('culture', 'es');
    }else{
      $this->culture = "es";
    }

    $this->serial = SerialPeer::retrieveByPKWithI18n($this->getRequestParameter('id'), $this->culture);
    $this->forward404Unless($this->serial);
    $this->forward404If($this->serial->isWorking());
    $this->forward404If((0 === $this->serial->countFileByPerfil(array(20, 21, 32, 33))));
    $this->onlyi18n = ($this->getRequestParameter('culture') == 'en')?true:false;
  }

  /**
   * --  EPUB -- /podcastepub/:id.xml
   * 
   *
   */



  public function executePodcastepub(){

  if(in_array($this->getRequestParameter('culture', 'es'), array('es','gl'))){
      $this->culture = $this->getRequestParameter('culture', 'es');
    }else{
      $this->culture = "es";
    }

    $this->serial = SerialPeer::retrieveByPKWithI18n($this->getRequestParameter('id'), $this->culture);	
    $mms = $this->serial->getMms();
    $this->mm=$mms[0];



  }



}
