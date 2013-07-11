<?php
/**
 * MODULO SERIALS ACTIONS. 
 * Modulo de administracion de las series de objetos multimedia. Permite 
 * modificar los metadatos de las series(tectinicos, de estilo y pics), y 
 * da acceso el modulo de administracion de objtos multimedia de casa serie. 
 *
 * @package    pumukit
 * @subpackage serials
 * @author     Ruben Gonzalez Gonzalez (rubenrua at uvigo dot com)
 * @version    1.0
 */
class newserialsActions extends sfActions
{

  /**
   * --  INDEX -- /editar.php/newserials
   * Muestra el modulo de administracion de las series, con la vista previa, formulario
   * de filtrado, listado de usuarios y formulario de edicion...
   *
   * Accion por defecto del modulo. Layout: layout
   *
   */
  public function executeIndex()
  {
    sfConfig::set('serial_menu','active');

    $this->getUser()->setAttribute('sort', 'publicDate', 'tv_admin/serial');
    $this->getUser()->setAttribute('type', 'desc', 'tv_admin/serial');
    if (!$this->getUser()->hasAttribute('page', 'tv_admin/serial'))
      $this->getUser()->setAttribute('page', 1, 'tv_admin/serial');
    $this->getUser()->setAttribute('id', 0, 'tv_admin/mm');

    $this->broadcasts = BroadcastPeer::doSelectWithI18n(new Criteria(), $this->getUser()->getCulture());
    $this->serialtypes = SerialTypePeer::doSelectWithI18n(new Criteria(), $this->getUser()->getCulture());
  }


  /**
   * --  LIST -- /editar.php/newserials/list
   *
   * Sin parametros
   *
   */
  public function executeList()
  {
    return $this->renderComponent('newserials', 'list');
  }

  /**
   * --  LIST -- /editar.php/newserials/list2
   *
   * Accion asincrona. Acceso publico.
   *
   */
  public function executeList2()
  {
      $this->category = new Category();

      $this->operation = $this->getRequestParameter('operation');
      $this->id = $this->getRequestParameter('id');
      $aux = CategoryPeer::buildTreeArray();
      $this->categories = $aux[0][CategoryPeer::TREE_ARRAY_CHILDREN];

      //Lista total de series
      $c = new Criteria();
      $this->processSort($c);
      $c->setLimit(11);
      $c->setOffset(0);
      $this->serials = SerialPeer::doList($c, $this->getUser()->getCulture());

      if ($this->operation == 'get_children'){
          $this->parent = $this->getRequestParameter('parent');
          if ($this->id == 1){
              $datosCategory = array();
              foreach($this->categories as $node){
                  $c = $node[CategoryPeer::TREE_ARRAY_NODE];
                  $datosCategory[] = array(
                                           "attr" => array(
                                                           "id"    => "node_" . $c->getId(),
                                                           "rel"   => "drive",
                                                           ),
                                           "data"  => $c->getCodName(),
                                           "state" => "closed"
                                           );
              }
              //Añadimos nodo inicial para la lista de series
              $datosCategory[] = array(
                                       "attr" => array(
                                                       "id"    => "node_99999999",
                                                       "rel"   => "drive",
                                                       ),
                                       "data"  => 'Lista de Series',
                                       "state" => "closed"
                                       );
              return $this->renderText(json_encode($datosCategory));
          } else {
              $datosCategory = false;
              foreach($this->categories as $node){
                  $category = $node[CategoryPeer::TREE_ARRAY_NODE];
                      while ($datosCategory === false){
                          $datosCategory = $this->searchId($this->id, $node);
                      }
              }
          }
          return $this->renderText(json_encode($datosCategory));
      } elseif ($this->operation == 'rename_node'){//TODO aplicar cambios para los diferentes idiomas
          $title = $this->getRequestParameter('title');
          if (!$this->getRequestParameter('id'))
              {
                  $category = new Category();
                  $parent_id = $this->getRequestParameter('parent_id');
                  $parent = CategoryPeer::retrieveByPk($parent_id);
                  $this->forward404Unless($parent);
                  $category->insertAsLastChildOf($parent);
              }
          else
              {
                  $category = CategoryPeer::retrieveByPk($this->id);
                  $this->forward404Unless($category);
              }
          
          $langs = sfConfig::get('app_lang_array', array('es'));
          $pos = strpos($title, '-');
          $realTitle = substr($title, $pos+2, strlen($title));
          foreach($langs as $lang){
              $category->setCulture($lang);
              $category->setName($realTitle);
          }
          try{
              $category->save();
              return $this->renderText(json_encode(array('status' => 1)));
          }catch(Exception $e){
              return $this->renderText(json_encode("Error al actualizar."));
          }
          
      } elseif ($this->operation == 'create_node'){//TODO crear nodo de series, diferencia entre categorías y series: el id es 99999999 o mayor que 1379 y menor que 2506
          if ($this->getRequestParameter('id'))
              {
                  $category = new Category();
                  $parent_id = $this->id;
                  $parent = CategoryPeer::retrieveByPk($parent_id);
                  $this->forward404Unless($parent);
                  $category->insertAsLastChildOf($parent);
              }
          $category->setMetacategory(0);//Parámetros básicos para las categorías
          $category->setDisplay(1);
          $category->setRequired(1);
          $category->setCod($this->getRequestParameter('cod', ' '));//TODO definir como se generan los códigos de las categorías
          
          $langs = sfConfig::get('app_lang_array', array('es'));
          foreach($langs as $lang){
              $category->setCulture($lang);
              $category->setName($this->getRequestParameter('title'));
          }
          try{
              $category->save();
              return $this->renderText(json_encode(array('status' => 1, 'id' => $category->getId())));
          }catch(Exception $e){
              return $this->renderText(json_encode("Error al actualizar."));
          }
      } elseif ($this->operation == 'remove_node'){
          if($this->hasRequestParameter('id')){
              $category = CategoryPeer::retrieveByPk($this->id);
              $category->delete();
          }

          return $this->renderText(json_encode(array('status' => 1)));
      } elseif ($this->operation == 'move_node'){//TODO mover la categoría en lugar de crear una nueva y borrar la existente
          if($this->hasRequestParameter('id')){
              $category = CategoryPeer::retrieveByPk($this->id);              
              $parent_id = $this->getRequestParameter('ref');
              $parent = CategoryPeer::retrieveByPk($parent_id);
              $this->forward404Unless($parent);
              $category->insertAsLastChildOf($parent);
              
              try{
                  $category->save();
                  return $this->renderText(json_encode(array('status' => 1, 'id' => $category->getId())));
              }catch(Exception $e){
                  return $this->renderText(json_encode("Error al actualizar."));
              }
          }
      }
  }

  /*
   * -- SEARCH --
   * Search an Id by recursive way in Category Tree
   *
   */
  public function searchId($id, $node){
      $has_children = count($node[CategoryPeer::TREE_ARRAY_CHILDREN]);
      $datosCategory = array();
      $find = false;
      $category = $node[CategoryPeer::TREE_ARRAY_NODE];
      if ($has_children == 0) return(false);
      foreach ($node[CategoryPeer::TREE_ARRAY_CHILDREN] as $children){
          $has_children_children = count($children[CategoryPeer::TREE_ARRAY_CHILDREN]);
          if ($category->getId() == $id){
              $find = true;
              $c = $children[CategoryPeer::TREE_ARRAY_NODE];
              $datosCategory[] = array(
                                       "attr" => array(
                                                       "id"    => "node_" . $c->getId(),
                                                       "rel"   => ($has_children_children==0) ? "default" : "folder",
                                                       ),
                                       "data"  => $c->getCodName(),
                                       "state" => ($has_children_children==0) ? "leaf" : "closed"
                                       );
          }
      }
      if (!$find && $has_children!=0){
          foreach ($node[CategoryPeer::TREE_ARRAY_CHILDREN] as $children){
              $category2 = $children[CategoryPeer::TREE_ARRAY_NODE];
              $datosCategory = $this->searchId($id, $children);
              if ($datosCategory!== false) break;
          }
      } elseif (!$find && $has_children==0){
          return(false);
      }

      return($datosCategory);
  }

  /*
   * --  ANALYZE -- /editar.php/newserials/analyze
   * To analyze the tree and show errors
   *
   */
  public function executeAnalyze(){
      return $this->renderText(json_encode("Analizado"));
  }
  /*
   * --  ANALYZE -- /editar.php/newserials/reconstruct
   * To analyze the tree and show errors
   *
   */
  public function executeReconstruct(){
      //TODO reconstruir el árbol desde un árbol inicial y recargar el árbol en el template
      return $this->renderText(json_encode("Reconstruido"));
  }
  /*
   * --  SEARCH -- /editar.php/newserials/search
   * To analyze the tree and show errors
   *
   */
  public function executeSearch(){
      //TODO realizar función en CategoryPeer que realice una consulta sobre la base de datos sobre el nombre de categoría
      $name = $this->getRequestParameter('search_str');

      //$category = CategoryPeer::retrieveByName($name);
      return $this->renderText(json_encode(array('#node_4','#node_5')));
      return $this->renderText(json_encode('Buscado'));
  }
  

  /**
   * --  EDIT -- /editar.php/newserials/editmms/id/:id
   *
   * Parametros por URL: Identificador el objeto multimedia
   *
   */
  public function executeEditMms()
  {
      if ($this->hasRequestParameter('id'))
          {
              $this->getUser()->setAttribute('id', $this->getRequestParameter('id'), 'tv_admin/mm');
          }
      return $this->renderComponent('newserials', 'editmms');
  }


  /**
   * --  PREVIEW -- /editar.php/newserials/previewmms/id/:id
   *
   * Parametros por URL: Identificador el objeto multimedia
   *
   */
  public function executePreviewMms()
  {
      //session _request ID o 0 si no hay
      $this->roles = RolePeer::doSelectWithI18n(new Criteria());
      $this->mm = MmPeer::retrieveByPk($this->getRequestParameter('id'));
      $this->file = FilePeer::retrieveByPk($this->getRequestParameter('id'));

      return $this->renderPartial('previewmms');
  }


  /**
   * --  EDIT -- /editar.php/newserials/edit
   *
   * Parametros por URL: id de la serie
   *
   */
  public function executeEdit()
  {
    if ($this->hasRequestParameter('id'))
    {
      $this->getUser()->setAttribute('id', $this->getRequestParameter('id'), 'tv_admin/serial');
    }
    return $this->renderComponent('newserials', 'edit');
  }


  /**
   * --  PREVIEW -- /editar.php/newserials/preview
   *
   * Parametros por URL: id de la serie
   *
   */
  public function executePreview()
  {
    if ($this->hasRequestParameter('id'))
    {
      $this->getUser()->setAttribute('id', $this->getRequestParameter('id'), 'tv_admin/serial');
    }
    return $this->renderComponent('newserials', 'preview');
  }

  /**
   * --  UPDATE -- /editar.php/newserials/update
   *
   * Parametros por POST: parameteros del formulario
   *
   */
  public function executeUpdate()
  {
    if (!$this->getRequestParameter('id'))
    {
      $serial = new Serial();
    }
    else
    {
      $serial = SerialPeer::retrieveByPk($this->getRequestParameter('id'));
      $this->forward404Unless($serial);
    }

    if ($this->getRequestParameter('publicdate'))
    {
      $timestamp = sfI18N::getTimestampForCulture($this->getRequestParameter('publicdate'), $this->getUser()->getCulture());
      
      $serial->setPublicdate($timestamp);
    }

    $serial->setAnnounce($this->getRequestParameter('announce', 0));
    $serial->setCopyright($this->getRequestParameter('copyright', 0));
    $serial->setSerialTypeId($this->getRequestParameter('serial_type_id', 0));
    $serial->setSerialTemplateId($this->getRequestParameter('serial_template_id', 1));

    $langs = sfConfig::get('app_lang_array', array('es'));
    foreach($langs as $lang){
      $serial->setCulture($lang);
      $serial->setTitle($this->getRequestParameter('title_' . $lang, 0));
      $serial->setSubtitle($this->getRequestParameter('subtitle_' . $lang, 0));
      $serial->setKeyword($this->getRequestParameter('keyword_' . $lang, ' '));
      $serial->setDescription($this->getRequestParameter('description_' . $lang, ' '));
      $serial->setHeader($this->getRequestParameter('header_' . $lang, ' '));
      $serial->setFooter($this->getRequestParameter('footer_' . $lang, ' '));
      $serial->setLine2($this->getRequestParameter('line2_' . $lang, ' '));
    }
    
    $serial->save();
    $this->msg_alert = array('info', "Serie \"" . $serial->getTitle() . "\" guardada OK.");

    

    return $this->renderComponent('serials', 'list');
  }

  /**
   * --  CREATE -- /editar.php/newserials/create
   *
   * Sin parametros
   *
   */
  public function executeCreate()
  {
    $serial = SerialPeer::createNew();
    
    $this->getUser()->setAttribute('serial', $serial->getId() );
    $this->getUser()->setAttribute('page', 1, 'tv_admin/serial');
    $this->getUser()->setAttribute('sort', 'publicDate', 'tv_admin/serial');
    $this->getUser()->setAttribute('type', 'desc', 'tv_admin/serial');

    $this->msg_alert = array('info', "Serie de id :" . $serial->getId() . " creada con un objeto multimedia.");
    return $this->renderComponent('serials', 'list');
  }


  /**
   * --  DELETE -- /editar.php/newserials/delete
   * OJO: Borra en cascada.
   *
   * Parametros por URL: identificador de la serie. o por POST: array en JSON de identificadores
   *
   */
  public function executeDelete()
  {
    if($this->hasRequestParameter('ids')){
      $serials = SerialPeer::retrieveByPKs(json_decode($this->getRequestParameter('ids')));

      foreach($serials as $serial){
	$serial->delete();
      }
      $this->msg_alert = array('info', "Series borradas.");

    }elseif($this->hasRequestParameter('id')){
      $serial = SerialPeer::retrieveByPk($this->getRequestParameter('id'));
      $this->msg_alert = array('info', "Serie \"" . $serial->getTitle() . "\" borrada.");
      $serial->delete();
    }

    $text = '<script type="text/javascript"> click_fila_edit("serial", null, -1)</script>';
    $this->getResponse()->setContent($this->getResponse()->getContent().$text);

    return $this->renderComponent('serials', 'list');
  }


  public function executeTwitter()
  {
    $serial = SerialPeer::retrieveByPk($this->getRequestParameter('id'));
    $this->forward404Unless($serial);

      $mensaje = 'Nuevo #UvigoTV "'.substr($serial->getTitle(), 0, 90).'" http://tv.uvigo.es/es/serial/' . $serial->getSerialId() . '.html#';

      define('_CONSUMER_KEY','aiUhqbftQ11nJddHzt6s5w');
      define('_CONSUMER_SECRET','d1ZQ9Yzb0K1ufMzn3cP7UE41bI6NWWFofHTqCwIrAo4');
      define('_OAUTH_TOKEN','107822829-pWTfMsEUkrZILtWOC4Qv6nm2eHl8ysy4TcX4A1Ye');
      define('_OAUTH_TOKEN_SECRET','q7cVu6G1CUGoKeyVKe0I9EuQEdiFjzfKw3Bbv39uYWE');
    

      $connection = new TwitterOAuth(_CONSUMER_KEY, _CONSUMER_SECRET,_OAUTH_TOKEN, _OAUTH_TOKEN_SECRET);
      $twitter=$connection->post('statuses/update', array('status' =>($mensaje)));
    return $this->renderComponent('serials', 'list');
  }


 /**
   * --  EPUB -- /editar.php/newserials/epub
   *
   * Parametros por URL: identificador de la serie.
   *
   */


 public function executeEpub()
  {
    $serial = SerialPeer::retrieveByPk($this->getRequestParameter('id'));
    $this->forward404Unless($serial);
    
    
    //debemos crear los videos mp4 de la serie y copiarlos en la carpeta OEBPS
    
    $epub = new epubPumukit($serial);
    $listo= $epub->crea_mp4();
    
    //no deberíamos seguir hasta que estuvieran todos los videos de la serie codificados. 
    //Una vez que esto ocurra, los copiamos en la carpeta OEBPS y seguimos con el proceso normal
    
    if ($listo === false) return $this->renderText('<span style="font-weight:bolder; color:red">Error: No hay objetos multimedia p&uacute;blicos o no existe el master</span>');
  
    if ($listo==1) $epub->fin_epub();

    //mandamos un mail para avisar de que el .epub ya se ha creado (falta cambiar la persona a la cual se le manda el mail)
    //$func="epub";
    //$serial->envia_mail($func);
    
    return $this->renderComponent('serials', 'list');
  }


  /**
   * --  DVD -- /editar.php/newserials/dvd
   *
   * Parametros por URL: identificador de la serie.
   *
   */

  public function executeDvd()
  {
    $serial = SerialPeer::retrieveByPk($this->getRequestParameter('id'));
    $this->forward404Unless($serial);

    DvdPumukit::dvdmaker($serial);
    

    return $this->renderComponent('serials', 'list');
  }




  /**
   * --  COPY -- /editar.php/newserials/copy
   *
   * Parametros por URL: identificador de la serie.
   *
   */
  public function executeCopy()
  {
    $serial = SerialPeer::retrieveByPk($this->getRequestParameter('id'));
    $this->forward404Unless($serial);

    $serial2 = $serial->copy();
    $this->getUser()->setAttribute('serial', $serial2->getId() ); //selecione el nuevo                                                                                                
    return $this->renderComponent('serials', 'list');
  }


  /**
   * --  ANNOUNCE -- /editar.php/newserials/announce
   *
   * Parametros por URL: identificador de la serie.
   *
   */
  public function executeAnnounce()
  {
    if($this->hasRequestParameter('ids')){
      $serials = SerialPeer::retrieveByPKs(json_decode($this->getRequestParameter('ids')));

      foreach($serials as $serial){
	$serial->setAnnounce(!$serial->getAnnounce());
	$serial->save();
      }
    $this->msg_alert = array('info', "Series anunciadas/desanunciada correctamente.");

    }elseif($this->hasRequestParameter('id')){
      $serial = SerialPeer::retrieveByPk($this->getRequestParameter('id'));
      $serial->setAnnounce(!$serial->getAnnounce());
      $serial->save();
      $this->msg_alert = array('info', "Serie \"" . $serial->getTitle() . "\" anunciada/desanunciada.");
    }

    return $this->renderComponent('serials', 'list');
  }


  /**
   * --  PREVIEWALL -- /editar.php/newserials/previewall
   * Muestra  una vista previa de la representacion de la serie. De como se va a mostrar, 
   * si todos lo videos son publicos, una vez publicado.
   *
   * Parametros por URL: identificador de la serie. Layout: tvlayout
   *
   */
  public function executePreviewall()
  {
    $this->setLayout('tvlayout');
    //ADD CSS

    //$this->

    $c = new Criteria();
    $c->add(SerialPeer::ID, $this->getRequestParameter('id', $this->getUser()->getAttribute('serial')));
    list($aux) = SerialPeer::doSelectWithI18n($c, $this->getUser()->getCulture());
    $this->serial = $aux;
    $this->forward404Unless($this->serial);

    $this->mms = $this->serial->getMms();
    $this->roles = RolePeer::doSelectWithI18n(new Criteria(), $this->getUser()->getCulture());

    $this->getResponse()->setTitle($this->serial->getTitle());
    $this->getResponse()->addMeta('keywords', $this->serial->getKeyword());
  }


  /**
   * --  CHANGE PUB -- /editar.php/newserials/changePub
   *
   * Parametros por URL: identificador de la serie. Layout: none
   *
   */
  public function executeChangePub()
  {
    $this->serial = SerialPeer::retrieveByPk($this->getRequestParameter('serial'));
    $this->forward404Unless($this->serial);
  }


  /**
   * --  UPDATE PUB -- /editar.php/newserials/updatePub
   *
   * Parametros por URL: identificadoes de los objeos multimedia y nuevo estado. Layout: none
   *
   */
  public function executeUpdatePub()
  {
    $mms = array_reverse(MmPeer::retrieveByPKs(json_decode($this->getRequestParameter('ids'))));
    
    $status_id = $this->getRequestParameter('status', 0);
    //$return_js = '<script type="text/javascript">$("filters_anounce").setValue('.$mm->getStatusId().')</script>'; 
    //$return_js = "<script type=\"text/javascript\"></script>";

    $error = -1000;

    foreach($mms as $mm){
      $aux = EncoderWorkflow::changeStatus($mm, $status_id, $this->getUser()->getAttribute('user_type_id'));      
      if($aux < 0){
	$error = max($aux, $error);
      }
    }

      $new_status = $mm->getSerial()->getMmStatus();
      $return_js = '<script type="text/javascript">$("table_serials_status_'.$mm->getSerial()->getId().'").src="/images/admin/bbuttons/'.$new_status['min'].$new_status['max'].'_inline.gif"; Modalbox.resizeToContent();</script>';

    switch($error){
    case -1:
      return $this->renderText('<span style="font-weight:bolder; color:red">Error</span> Error de permisos.'. $return_js);
    case -2:
      return $this->renderText('<span style="font-weight:bolder; color:red">Error</span> El Objeto Multimedia no tiene master.'. $return_js);
    case -3:
      return $this->renderText('<span style="font-weight:bolder; color:red">Error</span> El Objeto Multimedia no tiene archivos de video. <a href="#mediaMmHash" onclick="menuTab.select(\'mediaMm\'); update_file.stop(); return false;"> Se genera automaticamente</a>'. $return_js);
    case -4:
      return $this->renderText('<span style="font-weight:bolder; color:red">Error</span> El Objeto Multimedia no tiene archivo con perfil <em>podcast_video</em><a href="#mediaMmHash" onclick="menuTab.select(\'mediaMm\'); update_file.stop(); return false;"> Se genera automaticamente</a>'. $return_js);
    case -5:
      return $this->renderText('<span style="font-weight:bolder; color:red">Error</span> El Objeto Multimedia no tiene archivo con perfil <em>podcast_audio.</em><a href="#mediaMmHash" onclick="menuTab.select(\'mediaMm\'); update_file.stop(); return false;"> Se genera automaticamente</a>'. $return_js);
    case -6:
      return $this->renderText('<span style="font-weight:bolder; color:red">Error</span> El Objeto Multimedia no esta catalogado en iTunesU'. $return_js);
    default:
      return $this->renderText("Estado actualizado" . $return_js);
    }
  }

  protected function processSort(Criteria $c)
  {
      if ($this->getRequestParameter('sort')){
          $this->getUser()->setAttribute('sort', $this->getRequestParameter('sort'), 'tv_admin/serial');
          $this->getUser()->setAttribute('type', $this->getRequestParameter('type', 'asc'), 'tv_admin/serial');
      }

      if ($sort_column = $this->getUser()->getAttribute('sort', null, 'tv_admin/serial')){
          try{
              $sort_column = SerialPeer::translateFieldName($sort_column, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_COLNAME);
          }catch(Exception $e){
              try{
                  $sort_column = SerialI18nPeer::translateFieldName($sort_column, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_COLNAME);
              }catch(Exception $e){
              }
          }
          if ($this->getUser()->getAttribute('type', 'asc', 'tv_admin/serial') == 'asc'){
              $c->addAscendingOrderByColumn($sort_column);
          }else{
              $c->addDescendingOrderByColumn($sort_column);
          }
      }
  }

}
