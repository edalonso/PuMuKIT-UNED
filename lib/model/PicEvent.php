<?php

/**
 * Subclass for representing a row from the 'pic_event' table.
 *
 * 
 *
 * @package lib.model
 */ 
class PicEvent extends BasePicEvent
{
  public function getId()
  {   
    return $this->pic_id;
  }
}

sfPropelBehavior::add('PicEvent', array(
				  'sortableFk' => array('f_key' => 'other_id'),
				  ) );

