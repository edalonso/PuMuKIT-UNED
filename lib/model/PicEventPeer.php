<?php

/**
 * Subclass for performing query and update operations on the 'pic_event' table.
 *
 * 
 *
 * @package lib.model
 */ 
class PicEventPeer extends BasePicEventPeer
{
  /**
   * Sobrecarga la funcion doDelete para borrar, si existe,
   * el archivo alojado en el servidor.
   *
   * @param      mixed $values Criteria or Material object or primary key or array of primary keys
   *              which is used to create the DELETE statement
   * @param      Connection $con the connection to use
   */
  public static function doDelete($values, $con = null)
  {
    if ($values instanceof PicEvent){
      $pic = $values->getPic();
      if (($pic->countPicPersons() + $pic->countPicSerials() + $pic->countPicEvents()) == 1)
	$pic->noCascadeDelete();
    }
   
    elseif($values instanceof Criteria){
      $event_pics = PicEventPeer::doSelect($values);
      foreach($event_pics as $event_pic){
	$pic = $event_pic->getPic();
	if (($pic->countPicPersons() + $pic->countPicSerials() + $pic->countPicEvents()) == 1)
	  $pic->noCascadeDelete();
      }
    }
    

    parent::doDelete($values, $con);
  }

}
