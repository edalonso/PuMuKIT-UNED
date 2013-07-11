<?php
/**
 * MODULO MATERIALEVENTS ACTIONS. 
 * Pseudomodulo usado por el modulo de objeto multimedia para administrar
 * los materiales de un objeto multimedia. 
 *
 * @package    pumukit
 * @subpackage materialevents
 * @author     Ruben Gonzalez Gonzalez (rubenrua at uvigo dot com)
 * @version    1.0
 */
class materialeventsActions extends sfActions
{
  /**
   * --  LIST -- /editar.php/materialevents/list
   *
   * Parametros por URL: Identificador del objeto multimedia
   *
   */
  public function executeList()
  {
    return $this->renderComponent('materialevents', 'list');
  }

  /**
   * --  CREATE -- /editar.php/materialevents/create
   *
   * Parametros por URL: Identificador del objeto multimedia
   *
   */
  public function executeCreate()
  {
    $this->material = new MaterialEvent();

    $this->material->setEventId($this->getRequestParameter('event'));
    $this->material->setMatTypeId(MatTypePeer::getDefaultSelId());
    
    $this->langs = sfConfig::get('app_lang_array', array('es'));

    $this->default_sel = 'file';
    $this->setTemplate('edit');
  }

  /**
   * --  EDIT -- /editar.php/materialevents/edit
   *
   * Parametros por URL: Identificador del material
   *
   */
  public function executeEdit()
  {
    $this->material = MaterialEventPeer::retrieveByPk($this->getRequestParameter('id'));
    $this->forward404Unless($this->material);
    $this->langs = sfConfig::get('app_lang_array', array('es'));

    $this->default_sel = 'url';
  }


  /**
   * --  DELETE -- /editar.php/materialevents/delete
   *
   * Parametros por URL: Identificador del material
   *
   */
  public function executeDelete()
  {
    $material = MaterialEventPeer::retrieveByPk($this->getRequestParameter('id'));
    $this->forward404Unless($material);
    $material->delete();
    
    return $this->renderComponent('materialevents', 'list');
  }


  /**
   * --  UPDATE -- /editar.php/materialevents/update
   *
   * Parametros por POST
   *
   */
  public function executeUpdate()
  {
    if (!$this->getRequestParameter('id'))
    {
      $material = new MaterialEvent();
    }
    else
    {
      $material = MaterialEventPeer::retrieveByPk($this->getRequestParameter('id'));
      $this->forward404Unless($material);
    }
    $material->setEventId($this->getRequestParameter('event', 0));
    $material->setDisplay($this->getRequestParameter('display', 0));
    $material->setMatTypeId($this->getRequestParameter('mat_type_id', 0));
    if ($material->isNew()) $material->setUrl($this->getRequestParameter('url', 0));

    $langs = sfConfig::get('app_lang_array', array('es'));
    foreach($langs as $lang){
      $material->setCulture($lang);
      $material->setName($this->getRequestParameter('name_' . $lang, ' '));
    }
    
    $material->save();

    return $this->renderComponent('materialevents', 'list'); 
  }


  /**
   * --  UPLOAD -- /editar.php/materialevents/upload
   *
   * Parametros por POST
   *
   */
  public function executeUpload()
  {
    if (!$this->getRequestParameter('id'))
    {
      $material = new MaterialEvent();
    }
    else
    {
      $material = MaterialEventPeer::retrieveByPk($this->getRequestParameter('id'));
      $this->forward404Unless($material);
    }
    $material->setEventId($this->getRequestParameter('event', 0));
    $material->setDisplay($this->getRequestParameter('display', 0));
    $material->setMatTypeId($this->getRequestParameter('mat_type_id', 0));


    $langs = sfConfig::get('app_lang_array', array('es'));
    foreach($langs as $lang){
      $material->setCulture($lang);
      $material->setName($this->getRequestParameter('name_' . $lang, ' '));
    }


    if($this->getRequestParameter('file_type', 'url') == 'url'){
      if ($material->isNew()) $material->setUrl($this->getRequestParameter('url', 0));
      $material->save();

      $this->msg_info = 'Nueva material modificado.';
    }elseif($this->getRequestParameter('file_type', 'url') == 'file'){
      $currentDir = 'Video/' . $material->getEventId();      
      $absCurrentDir = sfConfig::get('sf_upload_dir').'/materialevent/' . $currentDir;
      $fileName = $this->sanitizeFile($this->getRequest()->getFileName('file'));
      $this->getRequest()->moveFile('file', $absCurrentDir . '/' . $fileName);
      
      $material->setUrl('/uploads/materialevent/' . $currentDir . '/' .  $fileName);
      $material->save();

      $this->msg_info = 'Nueva material subido e insertado.';
    }

    $this->event = $material->getEventId();
  }

  /**
   * --  UP -- /editar.php/materialevents/up
   *
   * Parametros por URL: Identificador del material
   *
   */
  public function executeUp()
  {
    $material = MaterialEventPeer::retrieveByPk($this->getRequestParameter('id'));
    $this->forward404Unless($material);

    $material->moveUp();
    $material->save();

    return $this->renderComponent('materialevents', 'list');
  }

  /**
   * --  DOWN -- /editar.php/materialevents/down
   *
   * Parametros por URL: Identificador del material
   *
   */
  public function executeDown()
  {
    $material = MaterialEventPeer::retrieveByPk($this->getRequestParameter('id'));
    $this->forward404Unless($material);

    $material->moveDown();
    $material->save();

    return $this->renderComponent('materialevents', 'list');
  }


  /*
   *
   */
  protected function sanitizeDir($dir)
  {
    return preg_replace('/[^a-z0-9_-]/i', '_', $dir);
  }

  protected function sanitizeFile($file)
  {
    return preg_replace('/[^a-z0-9_\.-]/i', '_', $file);
  }
}
