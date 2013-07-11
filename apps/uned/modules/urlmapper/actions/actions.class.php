<?php

/**
 * urlmapper actions.
 *
 * @package      pumukituvigo
 * @subpackage   urlmapper
 * @author       Ruben Glez <rubenrua at uvigo.es>
 * @version      SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 * @description  Modulo necesario para mantener en Pumukit las URLs antiguas de canaluned.
 */
class urlmapperActions extends sfActions
{
  public function preExecute()
  {
    sfConfig::set('sf_escaping_strategy', 'bc');
    set_time_limit(0);
    //ini_set("memory_limit","256M");
  } 


  /**
   * Executes index action
   *
   */
  public function executeIndex()
  {
    $this->forward('default', 'module');
  }


  /**
   * Execute URL Mapper for HTMLs.
   *
   * Example of URLs to map:
   * - http://www.canaluned.com/menu-principal/tele-uned/traduccion-cuando-el-verbo-se-hizo-puente-11640.html
   * - http://www.canaluned.com/menu-principal/mediateca/amen---hotep-iv-el-faraon-hereje-4525.html
   * - http://www.canaluned.com/menu-principal/teleactos/presentacion-del-caso-odyssey-11693.html
   * - http://www.canaluned.com/carreras/ciencias-de-la-educacion/la-tecnologia-para-el-desarrollo-emprendedor-11588.html
   * - http://www.canaluned.com/faro-emigrado/novedades/pinturas-y-poema-de-ivonne-sanchez-barea-hija-y-nieta-de-emigrantes-emigrante-espanola-retornada-7605.html
   */
  public function executeHtml()
  {
    $request = $this->getRequest();
    preg_match_all('/.*\/.*\/.*-(\d*)\.html/i', $request->getPathInfo(), $result);
    $id = intval($result[1][0]);
    if ($id !== 0) {
      $umo = UnedMediaOldPeer::retrieveByOriginalPK($id);
      if($umo && $umo->getMmId()) {
	$this->redirect("mmobj/index?id=" . $umo->getMmId());
      }
    }
    $this->forward404();
  }


  /**
   * Execute URL Mapper for MMEDIAs
   */
  public function executeMmedia()
  {
    $request = $this->getRequest();
    var_dump("MMEDIA");exit;
  }


  /**
   * Execute URL Mapper for MATERIALs
   */
  public function executeMaterial()
  {
    $request = $this->getRequest();
    var_dump("MATERIAL");exit;
  }


  /**
   * Execute URL Mapper for RSSs - Mediateca
   *
   * RSSs to map:
   * - http://www.canaluned.com/rss/F_RC-S_MEDIAT-FI_VIDEO.xml
   */
  public function executeRssmediateca()
  {
    $this->redirect('xml/lastnews');
  }


  /**
   * Execute URL Mapper for RSSs - Teleactos
   *
   * RSSs to map:
   * - http://www.canaluned.com/rss/F_RC-S_TELEAC-FI_VIDEO.xml
   */
  public function executeRssteleactos()
  {
    $this->getResponse()->setContentType('text/xml');
    $this->link = "urlmapper/rssaudio";

    //TODOUNED a la espera de terminar teleactos.
    $this->mms = array();

    $this->setTemplate('rss');
    $this->setLayout(false);
  }


  /**
   * Execute URL Mapper for RSSs - Destacados Audio
   *
   * RSSs to map:
   * - http://www.canaluned.com/rss/F_RC-S_RADUNE-FI_AUDIO.xml
   */
  public function executeRssaudio()
  {
    $this->getResponse()->setContentType('text/xml');
    $this->link = "urlmapper/rssaudio";

    //TODOUNED only public
    $cod = CategoryMmTimeframePeer::DESTACADOS_RADIO;
    $mms = CategoryMmTimeframePeer::doSelectDestacados($cod, true, null);

    $this->setTemplate('rss');
    $this->setLayout(false);
  }


  /**
   * Execute URL Mapper for RSSs - Destacados Television
   *
   * RSSs to map:
   * - http://www.canaluned.com/rss/F_RC-S_TELUNE-FI_VIDEO.xml
   */
  public function executeRsstelevision()
  {
    $this->getResponse()->setContentType('text/xml');
    $this->link = "urlmapper/rsstelevision";

    //TODOUNED only public
    $cod = CategoryMmTimeframePeer::DESTACADOS_TV;
    $mms = CategoryMmTimeframePeer::doSelectDestacados($cod, true, null);

    $this->setTemplate('rss');
    $this->setLayout(false);
  }


  /**
   * Execute URL Mapper for EMBEDs
   *
   * Las peticiones a responder necesarias en un player embed de Uned son:
   *
   * -  http://www.canaluned.com/swf/v2/CTVPlayer.swf?assetID=11641_es_videos&location=embed
   * -  http://www.canaluned.com/swf/data/es/videos/embed/1/4/6/1/11641.xml
   * -  http://www.canaluned.com/swf/data/ctv_commons.xml
   * -  http://www.canaluned.com/locale/es_ES/player.properties
   * -  http://www.canaluned.com/resources/jpg/3/8/1365062981483.jpg
   * -  http://www.canaluned.com/swf/v2/plugins/playicon.swf
   * -  http://www.canaluned.com/swf/v2/plugins/logoPlugin.swf
   * -  http://www.canaluned.com/swf/v2/plugins/controlbar.swf
   * -  http://www.canaluned.com/swf/v2/plugins/subtitles.swf
   * -  http://www.canaluned.com/swf/v2/plugins/LiveSessions.swf
   * -  http://www.canaluned.com/swf/v2/plugins/AuthoringPlugin.swf
   * -  http://www.canaluned.com/swf/v2/plugins/statistics.swf
   * -  http://www.canaluned.com/swf/v2/plugins/SUPlugin.swf
   * -  http://www.canaluned.com/assets/mosca.png
   * -  http://www.canaluned.com/resources/srt/0/0/1365062981300.srt
   * -  http://www.canaluned.com/xml/contents/es/sessions/1/4/6/1/sessions_11641.xml
   * -  http://www.canaluned.com/assets/teleactos.jpg
   *
   * Las archivos swf se almacenan una copia en /web/swf/v2/
   **/



  /**
   * 
   * Para mostrar solo una imagen este codigo no es necesario.
   * Se soluciona con png2swf -o CTVPlayer.swf image.png
   * y guardando la imagen en  web/swf/v2/CTVPlayer.swf
   *
   * Mas info ver web/swf/v2/info.php
   */
  /**
  public function executeEmbed()
  {
    $request = $this->getRequest();
    var_dump("EMBED");exit;
  }
  */
}
