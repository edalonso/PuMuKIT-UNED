<?php

/**
 * Subclass for representing a row from the 'material_event' table.
 *
 * 
 *
 * @package lib.model
 */ 
class MaterialEvent extends BaseMaterialEvent
{

  /**
   * Devuelve la representacion textual de la columna.
   *
   * @access public
   * @return string representacion textual del objeto.
   */
  public function __toString()
  {
    return $this->getName();
  }


  /**
   * Acceso directo al MimeType del material
   *
   * @access public
   * @return string del mimeType 
   */
  public function getMimeType()
  {
    $this->getMatType()->getMimeType();
  }


  /**
   *  Modifica funcion url para poder obligar a que la url devuelta sea absoluta.
   *
   * @access public
   * @param boolean $absolute (default true) indica si la url devuesta es absoluta.
   * @return string url
   *
   **/
  public function getUrl($absolute = false)
  {
    $url = parent::getUrl();
    if (($absolute)&&(!strstr($url, 'http://'))) $url = sfConfig::get('app_info_link'). $url;
    
    return $url;
  }

  /**
   * 
   * To init size
   */
  public function save($con = null)
  {
    $tamano = filesize(sfConfig::get('sf_web_dir') . $this->getUrl());
    if ($tamano) $this->setSize($tamano);
    parent::save($con);
  }
}
