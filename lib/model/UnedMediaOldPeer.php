<?php

/**
 * Subclass for performing query and update operations on the 'uned_media_old' table.
 *
 * 
 *
 * @package lib.model
 */ 
class UnedMediaOldPeer extends BaseUnedMediaOldPeer
{
  public static function retrieveByOriginalPK($pk, $con = null)
  {
    if ($con === null) {
      $con = Propel::getConnection(self::DATABASE_NAME);
    }

    $criteria = new Criteria(UnedMediaOldPeer::DATABASE_NAME);
    $criteria->add(UnedMediaOldPeer::ORIGINAL_ID, $pk);

    $v = UnedMediaOldPeer::doSelect($criteria, $con);

    return !empty($v) > 0 ? $v[0] : null;
  }
}
