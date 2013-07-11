<?php

/**
 * Subclass for performing query and update operations on the 'material_event' table.
 *
 * 
 *
 * @package lib.model
 */ 
class MaterialEventPeer extends BaseMaterialEventPeer
{

  /**
   * Sobrecarga la funcion doDelete para borrar, si existe,
   * el archivo alojado en el servidor.
   *
   */
  public static function doDelete($values, $con = null)
  {
    if ($values instanceof MaterialEvent){
      //strstr($values->getUrl(), '/uploads/material/') and unlink(sfConfig::get('sf_upload_dir').'/..'.$values->getUrl());
    }
    parent::doDelete($values, $con);
  }


  /**
   * Devuelve todos los materiales asociados al objeto
   * multimedia cuya id se pasa por parametro.
   *
   * @param integer $id id del objeto multimedia
   * @param string $culture cultura en la que se desea completar los materiales
   * @return ResulSet de objetos Material.
   */
  public static function getMaterialsFromEvent($id, $culture)
  {
    $criteria = new Criteria();
    $criteria->add(MaterialEventPeer::EVENT_ID, $id);
    $criteria->addAscendingOrderByColumn(MaterialEventPeer::RANK);

    return MaterialEventPeer::doSelectWithI18n($criteria, $culture);
  }

}
