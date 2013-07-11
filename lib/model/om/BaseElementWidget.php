<?php

/**
 * Base class that represents a row from the 'element_widget' table.
 *
 * 
 *
 * @package    lib.model.om
 */
abstract class BaseElementWidget extends BaseObject  implements Persistent {


	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        ElementWidgetPeer
	 */
	protected static $peer;


	/**
	 * The value for the bar_widget_id field.
	 * @var        int
	 */
	protected $bar_widget_id;


	/**
	 * The value for the widget_id field.
	 * @var        int
	 */
	protected $widget_id;


	/**
	 * The value for the rank field.
	 * @var        int
	 */
	protected $rank = 0;

	/**
	 * @var        BarWidget
	 */
	protected $aBarWidget;

	/**
	 * @var        Widget
	 */
	protected $aWidget;

	/**
	 * Flag to prevent endless save loop, if this object is referenced
	 * by another object which falls in this transaction.
	 * @var        boolean
	 */
	protected $alreadyInSave = false;

	/**
	 * Flag to prevent endless validation loop, if this object is referenced
	 * by another object which falls in this transaction.
	 * @var        boolean
	 */
	protected $alreadyInValidation = false;

	/**
	 * Get the [bar_widget_id] column value.
	 * 
	 * @return     int
	 */
	public function getBarWidgetId()
	{

		return $this->bar_widget_id;
	}

	/**
	 * Get the [widget_id] column value.
	 * 
	 * @return     int
	 */
	public function getWidgetId()
	{

		return $this->widget_id;
	}

	/**
	 * Get the [rank] column value.
	 * 
	 * @return     int
	 */
	public function getRank()
	{

		return $this->rank;
	}

	/**
	 * Set the value of [bar_widget_id] column.
	 * 
	 * @param      int $v new value
	 * @return     void
	 */
	public function setBarWidgetId($v)
	{

		// Since the native PHP type for this column is integer,
		// we will cast the input value to an int (if it is not).
		if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->bar_widget_id !== $v) {
			$this->bar_widget_id = $v;
			$this->modifiedColumns[] = ElementWidgetPeer::BAR_WIDGET_ID;
		}

		if ($this->aBarWidget !== null && $this->aBarWidget->getId() !== $v) {
			$this->aBarWidget = null;
		}

	} // setBarWidgetId()

	/**
	 * Set the value of [widget_id] column.
	 * 
	 * @param      int $v new value
	 * @return     void
	 */
	public function setWidgetId($v)
	{

		// Since the native PHP type for this column is integer,
		// we will cast the input value to an int (if it is not).
		if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->widget_id !== $v) {
			$this->widget_id = $v;
			$this->modifiedColumns[] = ElementWidgetPeer::WIDGET_ID;
		}

		if ($this->aWidget !== null && $this->aWidget->getId() !== $v) {
			$this->aWidget = null;
		}

	} // setWidgetId()

	/**
	 * Set the value of [rank] column.
	 * 
	 * @param      int $v new value
	 * @return     void
	 */
	public function setRank($v)
	{

		// Since the native PHP type for this column is integer,
		// we will cast the input value to an int (if it is not).
		if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->rank !== $v || $v === 0) {
			$this->rank = $v;
			$this->modifiedColumns[] = ElementWidgetPeer::RANK;
		}

	} // setRank()

	/**
	 * Hydrates (populates) the object variables with values from the database resultset.
	 *
	 * An offset (1-based "start column") is specified so that objects can be hydrated
	 * with a subset of the columns in the resultset rows.  This is needed, for example,
	 * for results of JOIN queries where the resultset row includes columns from two or
	 * more tables.
	 *
	 * @param      ResultSet $rs The ResultSet class with cursor advanced to desired record pos.
	 * @param      int $startcol 1-based offset column which indicates which restultset column to start with.
	 * @return     int next starting column
	 * @throws     PropelException  - Any caught Exception will be rewrapped as a PropelException.
	 */
	public function hydrate(ResultSet $rs, $startcol = 1)
	{
		try {

			$this->bar_widget_id = $rs->getInt($startcol + 0);

			$this->widget_id = $rs->getInt($startcol + 1);

			$this->rank = $rs->getInt($startcol + 2);

			$this->resetModified();

			$this->setNew(false);

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 3; // 3 = ElementWidgetPeer::NUM_COLUMNS - ElementWidgetPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating ElementWidget object", $e);
		}
	}

	/**
	 * Removes this object from datastore and sets delete attribute.
	 *
	 * @param      Connection $con
	 * @return     void
	 * @throws     PropelException
	 * @see        BaseObject::setDeleted()
	 * @see        BaseObject::isDeleted()
	 */
	public function delete($con = null)
	{

    foreach (sfMixer::getCallables('BaseElementWidget:delete:pre') as $callable)
    {
      $ret = call_user_func($callable, $this, $con);
      if ($ret)
      {
        return;
      }
    }


		if ($this->isDeleted()) {
			throw new PropelException("This object has already been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(ElementWidgetPeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			ElementWidgetPeer::doDelete($this, $con);
			$this->setDeleted(true);
			$con->commit();
		} catch (PropelException $e) {
			$con->rollback();
			throw $e;
		}
	

    foreach (sfMixer::getCallables('BaseElementWidget:delete:post') as $callable)
    {
      call_user_func($callable, $this, $con);
    }

  }
	/**
	 * Stores the object in the database.  If the object is new,
	 * it inserts it; otherwise an update is performed.  This method
	 * wraps the doSave() worker method in a transaction.
	 *
	 * @param      Connection $con
	 * @return     int The number of rows affected by this insert/update and any referring fk objects' save() operations.
	 * @throws     PropelException
	 * @see        doSave()
	 */
	public function save($con = null)
	{

    foreach (sfMixer::getCallables('BaseElementWidget:save:pre') as $callable)
    {
      $affectedRows = call_user_func($callable, $this, $con);
      if (is_int($affectedRows))
      {
        return $affectedRows;
      }
    }


		if ($this->isDeleted()) {
			throw new PropelException("You cannot save an object that has been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(ElementWidgetPeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			$affectedRows = $this->doSave($con);
			$con->commit();
    foreach (sfMixer::getCallables('BaseElementWidget:save:post') as $callable)
    {
      call_user_func($callable, $this, $con, $affectedRows);
    }

			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollback();
			throw $e;
		}
	}

	/**
	 * Stores the object in the database.
	 *
	 * If the object is new, it inserts it; otherwise an update is performed.
	 * All related objects are also updated in this method.
	 *
	 * @param      Connection $con
	 * @return     int The number of rows affected by this insert/update and any referring fk objects' save() operations.
	 * @throws     PropelException
	 * @see        save()
	 */
	protected function doSave($con)
	{
		$affectedRows = 0; // initialize var to track total num of affected rows
		if (!$this->alreadyInSave) {
			$this->alreadyInSave = true;


			// We call the save method on the following object(s) if they
			// were passed to this object by their coresponding set
			// method.  This object relates to these object(s) by a
			// foreign key reference.

			if ($this->aBarWidget !== null) {
				if ($this->aBarWidget->isModified()) {
					$affectedRows += $this->aBarWidget->save($con);
				}
				$this->setBarWidget($this->aBarWidget);
			}

			if ($this->aWidget !== null) {
				if ($this->aWidget->isModified() || $this->aWidget->getCurrentWidgetI18n()->isModified()) {
					$affectedRows += $this->aWidget->save($con);
				}
				$this->setWidget($this->aWidget);
			}


			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = ElementWidgetPeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setNew(false);
				} else {
					$affectedRows += ElementWidgetPeer::doUpdate($this, $con);
				}
				$this->resetModified(); // [HL] After being saved an object is no longer 'modified'
			}

			$this->alreadyInSave = false;
		}
		return $affectedRows;
	} // doSave()

	/**
	 * Array of ValidationFailed objects.
	 * @var        array ValidationFailed[]
	 */
	protected $validationFailures = array();

	/**
	 * Gets any ValidationFailed objects that resulted from last call to validate().
	 *
	 *
	 * @return     array ValidationFailed[]
	 * @see        validate()
	 */
	public function getValidationFailures()
	{
		return $this->validationFailures;
	}

	/**
	 * Validates the objects modified field values and all objects related to this table.
	 *
	 * If $columns is either a column name or an array of column names
	 * only those columns are validated.
	 *
	 * @param      mixed $columns Column name or an array of column names.
	 * @return     boolean Whether all columns pass validation.
	 * @see        doValidate()
	 * @see        getValidationFailures()
	 */
	public function validate($columns = null)
	{
		$res = $this->doValidate($columns);
		if ($res === true) {
			$this->validationFailures = array();
			return true;
		} else {
			$this->validationFailures = $res;
			return false;
		}
	}

	/**
	 * This function performs the validation work for complex object models.
	 *
	 * In addition to checking the current object, all related objects will
	 * also be validated.  If all pass then <code>true</code> is returned; otherwise
	 * an aggreagated array of ValidationFailed objects will be returned.
	 *
	 * @param      array $columns Array of column names to validate.
	 * @return     mixed <code>true</code> if all validations pass; array of <code>ValidationFailed</code> objets otherwise.
	 */
	protected function doValidate($columns = null)
	{
		if (!$this->alreadyInValidation) {
			$this->alreadyInValidation = true;
			$retval = null;

			$failureMap = array();


			// We call the validate method on the following object(s) if they
			// were passed to this object by their coresponding set
			// method.  This object relates to these object(s) by a
			// foreign key reference.

			if ($this->aBarWidget !== null) {
				if (!$this->aBarWidget->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aBarWidget->getValidationFailures());
				}
			}

			if ($this->aWidget !== null) {
				if (!$this->aWidget->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aWidget->getValidationFailures());
				}
			}


			if (($retval = ElementWidgetPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}



			$this->alreadyInValidation = false;
		}

		return (!empty($failureMap) ? $failureMap : true);
	}

	/**
	 * Retrieves a field from the object by name passed in as a string.
	 *
	 * @param      string $name name
	 * @param      string $type The type of fieldname the $name is of:
	 *                     one of the class type constants TYPE_PHPNAME,
	 *                     TYPE_COLNAME, TYPE_FIELDNAME, TYPE_NUM
	 * @return     mixed Value of field.
	 */
	public function getByName($name, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = ElementWidgetPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		return $this->getByPosition($pos);
	}

	/**
	 * Retrieves a field from the object by Position as specified in the xml schema.
	 * Zero-based.
	 *
	 * @param      int $pos position in xml schema
	 * @return     mixed Value of field at $pos
	 */
	public function getByPosition($pos)
	{
		switch($pos) {
			case 0:
				return $this->getBarWidgetId();
				break;
			case 1:
				return $this->getWidgetId();
				break;
			case 2:
				return $this->getRank();
				break;
			default:
				return null;
				break;
		} // switch()
	}

	/**
	 * Exports the object as an array.
	 *
	 * You can specify the key type of the array by passing one of the class
	 * type constants.
	 *
	 * @param      string $keyType One of the class type constants TYPE_PHPNAME,
	 *                        TYPE_COLNAME, TYPE_FIELDNAME, TYPE_NUM
	 * @return     an associative array containing the field names (as keys) and field values
	 */
	public function toArray($keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = ElementWidgetPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getBarWidgetId(),
			$keys[1] => $this->getWidgetId(),
			$keys[2] => $this->getRank(),
		);
		return $result;
	}

	/**
	 * Sets a field from the object by name passed in as a string.
	 *
	 * @param      string $name peer name
	 * @param      mixed $value field value
	 * @param      string $type The type of fieldname the $name is of:
	 *                     one of the class type constants TYPE_PHPNAME,
	 *                     TYPE_COLNAME, TYPE_FIELDNAME, TYPE_NUM
	 * @return     void
	 */
	public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = ElementWidgetPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		return $this->setByPosition($pos, $value);
	}

	/**
	 * Sets a field from the object by Position as specified in the xml schema.
	 * Zero-based.
	 *
	 * @param      int $pos position in xml schema
	 * @param      mixed $value field value
	 * @return     void
	 */
	public function setByPosition($pos, $value)
	{
		switch($pos) {
			case 0:
				$this->setBarWidgetId($value);
				break;
			case 1:
				$this->setWidgetId($value);
				break;
			case 2:
				$this->setRank($value);
				break;
		} // switch()
	}

	/**
	 * Populates the object using an array.
	 *
	 * This is particularly useful when populating an object from one of the
	 * request arrays (e.g. $_POST).  This method goes through the column
	 * names, checking to see whether a matching key exists in populated
	 * array. If so the setByName() method is called for that column.
	 *
	 * You can specify the key type of the array by additionally passing one
	 * of the class type constants TYPE_PHPNAME, TYPE_COLNAME, TYPE_FIELDNAME,
	 * TYPE_NUM. The default key type is the column's phpname (e.g. 'authorId')
	 *
	 * @param      array  $arr     An array to populate the object from.
	 * @param      string $keyType The type of keys the array uses.
	 * @return     void
	 */
	public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = ElementWidgetPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setBarWidgetId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setWidgetId($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setRank($arr[$keys[2]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(ElementWidgetPeer::DATABASE_NAME);

		if ($this->isColumnModified(ElementWidgetPeer::BAR_WIDGET_ID)) $criteria->add(ElementWidgetPeer::BAR_WIDGET_ID, $this->bar_widget_id);
		if ($this->isColumnModified(ElementWidgetPeer::WIDGET_ID)) $criteria->add(ElementWidgetPeer::WIDGET_ID, $this->widget_id);
		if ($this->isColumnModified(ElementWidgetPeer::RANK)) $criteria->add(ElementWidgetPeer::RANK, $this->rank);

		return $criteria;
	}

	/**
	 * Builds a Criteria object containing the primary key for this object.
	 *
	 * Unlike buildCriteria() this method includes the primary key values regardless
	 * of whether or not they have been modified.
	 *
	 * @return     Criteria The Criteria object containing value(s) for primary key(s).
	 */
	public function buildPkeyCriteria()
	{
		$criteria = new Criteria(ElementWidgetPeer::DATABASE_NAME);

		$criteria->add(ElementWidgetPeer::BAR_WIDGET_ID, $this->bar_widget_id);
		$criteria->add(ElementWidgetPeer::WIDGET_ID, $this->widget_id);

		return $criteria;
	}

	/**
	 * Returns the composite primary key for this object.
	 * The array elements will be in same order as specified in XML.
	 * @return     array
	 */
	public function getPrimaryKey()
	{
		$pks = array();

		$pks[0] = $this->getBarWidgetId();

		$pks[1] = $this->getWidgetId();

		return $pks;
	}

	/**
	 * Set the [composite] primary key.
	 *
	 * @param      array $keys The elements of the composite key (order must match the order in XML file).
	 * @return     void
	 */
	public function setPrimaryKey($keys)
	{

		$this->setBarWidgetId($keys[0]);

		$this->setWidgetId($keys[1]);

	}

	/**
	 * Sets contents of passed object to values from current object.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param      object $copyObj An object of ElementWidget (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setRank($this->rank);


		$copyObj->setNew(true);

		$copyObj->setBarWidgetId(NULL); // this is a pkey column, so set to default value

		$copyObj->setWidgetId(NULL); // this is a pkey column, so set to default value

	}

	/**
	 * Makes a copy of this object that will be inserted as a new row in table when saved.
	 * It creates a new object filling in the simple attributes, but skipping any primary
	 * keys that are defined for the table.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @return     ElementWidget Clone of current object.
	 * @throws     PropelException
	 */
	public function copy($deepCopy = false)
	{
		// we use get_class(), because this might be a subclass
		$clazz = get_class($this);
		$copyObj = new $clazz();
		$this->copyInto($copyObj, $deepCopy);
		return $copyObj;
	}

	/**
	 * Returns a peer instance associated with this om.
	 *
	 * Since Peer classes are not to have any instance attributes, this method returns the
	 * same instance for all member of this class. The method could therefore
	 * be static, but this would prevent one from overriding the behavior.
	 *
	 * @return     ElementWidgetPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new ElementWidgetPeer();
		}
		return self::$peer;
	}

	/**
	 * Declares an association between this object and a BarWidget object.
	 *
	 * @param      BarWidget $v
	 * @return     void
	 * @throws     PropelException
	 */
	public function setBarWidget($v)
	{


		if ($v === null) {
			$this->setBarWidgetId(NULL);
		} else {
			$this->setBarWidgetId($v->getId());
		}


		$this->aBarWidget = $v;
	}


	/**
	 * Get the associated BarWidget object
	 *
	 * @param      Connection Optional Connection object.
	 * @return     BarWidget The associated BarWidget object.
	 * @throws     PropelException
	 */
	public function getBarWidget($con = null)
	{
		if ($this->aBarWidget === null && ($this->bar_widget_id !== null)) {
			// include the related Peer class
			include_once 'lib/model/om/BaseBarWidgetPeer.php';

			$this->aBarWidget = BarWidgetPeer::retrieveByPK($this->bar_widget_id, $con);

			/* The following can be used instead of the line above to
			   guarantee the related object contains a reference
			   to this object, but this level of coupling
			   may be undesirable in many circumstances.
			   As it can lead to a db query with many results that may
			   never be used.
			   $obj = BarWidgetPeer::retrieveByPK($this->bar_widget_id, $con);
			   $obj->addBarWidgets($this);
			 */
		}
		return $this->aBarWidget;
	}

	/**
	 * Declares an association between this object and a Widget object.
	 *
	 * @param      Widget $v
	 * @return     void
	 * @throws     PropelException
	 */
	public function setWidget($v)
	{


		if ($v === null) {
			$this->setWidgetId(NULL);
		} else {
			$this->setWidgetId($v->getId());
		}


		$this->aWidget = $v;
	}


	/**
	 * Get the associated Widget object
	 *
	 * @param      Connection Optional Connection object.
	 * @return     Widget The associated Widget object.
	 * @throws     PropelException
	 */
	public function getWidget($con = null)
	{
		if ($this->aWidget === null && ($this->widget_id !== null)) {
			// include the related Peer class
			include_once 'lib/model/om/BaseWidgetPeer.php';

			$this->aWidget = WidgetPeer::retrieveByPK($this->widget_id, $con);

			/* The following can be used instead of the line above to
			   guarantee the related object contains a reference
			   to this object, but this level of coupling
			   may be undesirable in many circumstances.
			   As it can lead to a db query with many results that may
			   never be used.
			   $obj = WidgetPeer::retrieveByPK($this->widget_id, $con);
			   $obj->addWidgets($this);
			 */
		}
		return $this->aWidget;
	}


	/**
	 * Get the associated Widget object
	 *
	 * @param      Connection Optional Connection object.
	 * @return     Widget The associated Widget object.
	 * @throws     PropelException
	 */
	public function getWidgetWithI18n($con = null)
	{
		if ($this->aWidget === null && ($this->widget_id !== null)) {
			// include the related Peer class
			include_once 'lib/model/om/BaseWidgetPeer.php';

			$this->aWidget = WidgetPeer::retrieveByPKWithI18n($this->widget_id, $this->getCulture(), $con);

			/* The following can be used instead of the line above to
			   guarantee the related object contains a reference
			   to this object, but this level of coupling
			   may be undesirable in many circumstances.
			   As it can lead to a db query with many results that may
			   never be used.
			   $obj = WidgetPeer::retrieveByPKWithI18n($this->widget_id, $this->getCulture(), $con);
			   $obj->addWidgets($this);
			 */
		}
		return $this->aWidget;
	}

	/**
	 * Resets all collections of referencing foreign keys.
	 *
	 * This method is a user-space workaround for PHP's inability to garbage collect objects
	 * with circular references.  This is currently necessary when using Propel in certain
	 * daemon or large-volumne/high-memory operations.
	 *
	 * @param      boolean $deep Whether to also clear the references on all associated objects.
	 */
	public function clearAllReferences($deep = false)
	{
		if ($deep) {
		} // if ($deep)

		$this->aBarWidget = null;
		$this->aWidget = null;
	}


  public function __call($method, $arguments)
  {
    if (!$callable = sfMixer::getCallable('BaseElementWidget:'.$method))
    {
      throw new sfException(sprintf('Call to undefined method BaseElementWidget::%s', $method));
    }

    array_unshift($arguments, $this);

    return call_user_func_array($callable, $arguments);
  }


} // BaseElementWidget
