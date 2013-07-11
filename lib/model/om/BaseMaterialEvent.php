<?php

/**
 * Base class that represents a row from the 'material_event' table.
 *
 * 
 *
 * @package    lib.model.om
 */
abstract class BaseMaterialEvent extends BaseObject  implements Persistent {


	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        MaterialEventPeer
	 */
	protected static $peer;


	/**
	 * The value for the id field.
	 * @var        int
	 */
	protected $id;


	/**
	 * The value for the event_id field.
	 * @var        int
	 */
	protected $event_id;


	/**
	 * The value for the url field.
	 * @var        string
	 */
	protected $url;


	/**
	 * The value for the rank field.
	 * @var        int
	 */
	protected $rank = 1;


	/**
	 * The value for the mat_type_id field.
	 * @var        int
	 */
	protected $mat_type_id;


	/**
	 * The value for the display field.
	 * @var        boolean
	 */
	protected $display = true;


	/**
	 * The value for the size field.
	 * @var        string
	 */
	protected $size = '0';

	/**
	 * @var        Event
	 */
	protected $aEvent;

	/**
	 * @var        MatType
	 */
	protected $aMatType;

	/**
	 * Collection to store aggregation of collMaterialEventI18ns.
	 * @var        array
	 */
	protected $collMaterialEventI18ns;

	/**
	 * The criteria used to select the current contents of collMaterialEventI18ns.
	 * @var        Criteria
	 */
	protected $lastMaterialEventI18nCriteria = null;

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
   * The value for the culture field.
   * @var string
   */
  protected $culture;

	/**
	 * Get the [id] column value.
	 * 
	 * @return     int
	 */
	public function getId()
	{

		return $this->id;
	}

	/**
	 * Get the [event_id] column value.
	 * 
	 * @return     int
	 */
	public function getEventId()
	{

		return $this->event_id;
	}

	/**
	 * Get the [url] column value.
	 * 
	 * @return     string
	 */
	public function getUrl()
	{

		return $this->url;
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
	 * Get the [mat_type_id] column value.
	 * 
	 * @return     int
	 */
	public function getMatTypeId()
	{

		return $this->mat_type_id;
	}

	/**
	 * Get the [display] column value.
	 * 
	 * @return     boolean
	 */
	public function getDisplay()
	{

		return $this->display;
	}

	/**
	 * Get the [size] column value.
	 * 
	 * @return     string
	 */
	public function getSize()
	{

		return $this->size;
	}

	/**
	 * Set the value of [id] column.
	 * 
	 * @param      int $v new value
	 * @return     void
	 */
	public function setId($v)
	{

		// Since the native PHP type for this column is integer,
		// we will cast the input value to an int (if it is not).
		if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->id !== $v) {
			$this->id = $v;
			$this->modifiedColumns[] = MaterialEventPeer::ID;
		}

	} // setId()

	/**
	 * Set the value of [event_id] column.
	 * 
	 * @param      int $v new value
	 * @return     void
	 */
	public function setEventId($v)
	{

		// Since the native PHP type for this column is integer,
		// we will cast the input value to an int (if it is not).
		if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->event_id !== $v) {
			$this->event_id = $v;
			$this->modifiedColumns[] = MaterialEventPeer::EVENT_ID;
		}

		if ($this->aEvent !== null && $this->aEvent->getId() !== $v) {
			$this->aEvent = null;
		}

	} // setEventId()

	/**
	 * Set the value of [url] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setUrl($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->url !== $v) {
			$this->url = $v;
			$this->modifiedColumns[] = MaterialEventPeer::URL;
		}

	} // setUrl()

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

		if ($this->rank !== $v || $v === 1) {
			$this->rank = $v;
			$this->modifiedColumns[] = MaterialEventPeer::RANK;
		}

	} // setRank()

	/**
	 * Set the value of [mat_type_id] column.
	 * 
	 * @param      int $v new value
	 * @return     void
	 */
	public function setMatTypeId($v)
	{

		// Since the native PHP type for this column is integer,
		// we will cast the input value to an int (if it is not).
		if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->mat_type_id !== $v) {
			$this->mat_type_id = $v;
			$this->modifiedColumns[] = MaterialEventPeer::MAT_TYPE_ID;
		}

		if ($this->aMatType !== null && $this->aMatType->getId() !== $v) {
			$this->aMatType = null;
		}

	} // setMatTypeId()

	/**
	 * Set the value of [display] column.
	 * 
	 * @param      boolean $v new value
	 * @return     void
	 */
	public function setDisplay($v)
	{

		if ($this->display !== $v || $v === true) {
			$this->display = $v;
			$this->modifiedColumns[] = MaterialEventPeer::DISPLAY;
		}

	} // setDisplay()

	/**
	 * Set the value of [size] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setSize($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->size !== $v || $v === '0') {
			$this->size = $v;
			$this->modifiedColumns[] = MaterialEventPeer::SIZE;
		}

	} // setSize()

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

			$this->id = $rs->getInt($startcol + 0);

			$this->event_id = $rs->getInt($startcol + 1);

			$this->url = $rs->getString($startcol + 2);

			$this->rank = $rs->getInt($startcol + 3);

			$this->mat_type_id = $rs->getInt($startcol + 4);

			$this->display = $rs->getBoolean($startcol + 5);

			$this->size = $rs->getString($startcol + 6);

			$this->resetModified();

			$this->setNew(false);
			$this->setCulture(sfContext::getInstance()->getUser()->getCulture());

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 7; // 7 = MaterialEventPeer::NUM_COLUMNS - MaterialEventPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating MaterialEvent object", $e);
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

    foreach (sfMixer::getCallables('BaseMaterialEvent:delete:pre') as $callable)
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
			$con = Propel::getConnection(MaterialEventPeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			MaterialEventPeer::doDelete($this, $con);
			$this->setDeleted(true);
			$con->commit();
		} catch (PropelException $e) {
			$con->rollback();
			throw $e;
		}
	

    foreach (sfMixer::getCallables('BaseMaterialEvent:delete:post') as $callable)
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

    foreach (sfMixer::getCallables('BaseMaterialEvent:save:pre') as $callable)
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
			$con = Propel::getConnection(MaterialEventPeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			$affectedRows = $this->doSave($con);
			$con->commit();
    foreach (sfMixer::getCallables('BaseMaterialEvent:save:post') as $callable)
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

			if ($this->aEvent !== null) {
				if ($this->aEvent->isModified() || $this->aEvent->getCurrentEventI18n()->isModified()) {
					$affectedRows += $this->aEvent->save($con);
				}
				$this->setEvent($this->aEvent);
			}

			if ($this->aMatType !== null) {
				if ($this->aMatType->isModified() || $this->aMatType->getCurrentMatTypeI18n()->isModified()) {
					$affectedRows += $this->aMatType->save($con);
				}
				$this->setMatType($this->aMatType);
			}


			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = MaterialEventPeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setId($pk);  //[IMV] update autoincrement primary key

					$this->setNew(false);
				} else {
					$affectedRows += MaterialEventPeer::doUpdate($this, $con);
				}
				$this->resetModified(); // [HL] After being saved an object is no longer 'modified'
			}

			if ($this->collMaterialEventI18ns !== null) {
				foreach($this->collMaterialEventI18ns as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
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

			if ($this->aEvent !== null) {
				if (!$this->aEvent->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aEvent->getValidationFailures());
				}
			}

			if ($this->aMatType !== null) {
				if (!$this->aMatType->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aMatType->getValidationFailures());
				}
			}


			if (($retval = MaterialEventPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}


				if ($this->collMaterialEventI18ns !== null) {
					foreach($this->collMaterialEventI18ns as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
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
		$pos = MaterialEventPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getId();
				break;
			case 1:
				return $this->getEventId();
				break;
			case 2:
				return $this->getUrl();
				break;
			case 3:
				return $this->getRank();
				break;
			case 4:
				return $this->getMatTypeId();
				break;
			case 5:
				return $this->getDisplay();
				break;
			case 6:
				return $this->getSize();
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
		$keys = MaterialEventPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getEventId(),
			$keys[2] => $this->getUrl(),
			$keys[3] => $this->getRank(),
			$keys[4] => $this->getMatTypeId(),
			$keys[5] => $this->getDisplay(),
			$keys[6] => $this->getSize(),
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
		$pos = MaterialEventPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				$this->setId($value);
				break;
			case 1:
				$this->setEventId($value);
				break;
			case 2:
				$this->setUrl($value);
				break;
			case 3:
				$this->setRank($value);
				break;
			case 4:
				$this->setMatTypeId($value);
				break;
			case 5:
				$this->setDisplay($value);
				break;
			case 6:
				$this->setSize($value);
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
		$keys = MaterialEventPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setEventId($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setUrl($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setRank($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setMatTypeId($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setDisplay($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setSize($arr[$keys[6]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(MaterialEventPeer::DATABASE_NAME);

		if ($this->isColumnModified(MaterialEventPeer::ID)) $criteria->add(MaterialEventPeer::ID, $this->id);
		if ($this->isColumnModified(MaterialEventPeer::EVENT_ID)) $criteria->add(MaterialEventPeer::EVENT_ID, $this->event_id);
		if ($this->isColumnModified(MaterialEventPeer::URL)) $criteria->add(MaterialEventPeer::URL, $this->url);
		if ($this->isColumnModified(MaterialEventPeer::RANK)) $criteria->add(MaterialEventPeer::RANK, $this->rank);
		if ($this->isColumnModified(MaterialEventPeer::MAT_TYPE_ID)) $criteria->add(MaterialEventPeer::MAT_TYPE_ID, $this->mat_type_id);
		if ($this->isColumnModified(MaterialEventPeer::DISPLAY)) $criteria->add(MaterialEventPeer::DISPLAY, $this->display);
		if ($this->isColumnModified(MaterialEventPeer::SIZE)) $criteria->add(MaterialEventPeer::SIZE, $this->size);

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
		$criteria = new Criteria(MaterialEventPeer::DATABASE_NAME);

		$criteria->add(MaterialEventPeer::ID, $this->id);

		return $criteria;
	}

	/**
	 * Returns the primary key for this object (row).
	 * @return     int
	 */
	public function getPrimaryKey()
	{
		return $this->getId();
	}

	/**
	 * Generic method to set the primary key (id column).
	 *
	 * @param      int $key Primary key.
	 * @return     void
	 */
	public function setPrimaryKey($key)
	{
		$this->setId($key);
	}

	/**
	 * Sets contents of passed object to values from current object.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param      object $copyObj An object of MaterialEvent (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setEventId($this->event_id);

		$copyObj->setUrl($this->url);

		$copyObj->setRank($this->rank);

		$copyObj->setMatTypeId($this->mat_type_id);

		$copyObj->setDisplay($this->display);

		$copyObj->setSize($this->size);


		if ($deepCopy) {
			// important: temporarily setNew(false) because this affects the behavior of
			// the getter/setter methods for fkey referrer objects.
			$copyObj->setNew(false);

			foreach($this->getMaterialEventI18ns() as $relObj) {
				$copyObj->addMaterialEventI18n($relObj->copy($deepCopy));
			}

		} // if ($deepCopy)


		$copyObj->setNew(true);

		$copyObj->setId(NULL); // this is a pkey column, so set to default value

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
	 * @return     MaterialEvent Clone of current object.
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
	 * @return     MaterialEventPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new MaterialEventPeer();
		}
		return self::$peer;
	}

	/**
	 * Declares an association between this object and a Event object.
	 *
	 * @param      Event $v
	 * @return     void
	 * @throws     PropelException
	 */
	public function setEvent($v)
	{


		if ($v === null) {
			$this->setEventId(NULL);
		} else {
			$this->setEventId($v->getId());
		}


		$this->aEvent = $v;
	}


	/**
	 * Get the associated Event object
	 *
	 * @param      Connection Optional Connection object.
	 * @return     Event The associated Event object.
	 * @throws     PropelException
	 */
	public function getEvent($con = null)
	{
		if ($this->aEvent === null && ($this->event_id !== null)) {
			// include the related Peer class
			include_once 'lib/model/om/BaseEventPeer.php';

			$this->aEvent = EventPeer::retrieveByPK($this->event_id, $con);

			/* The following can be used instead of the line above to
			   guarantee the related object contains a reference
			   to this object, but this level of coupling
			   may be undesirable in many circumstances.
			   As it can lead to a db query with many results that may
			   never be used.
			   $obj = EventPeer::retrieveByPK($this->event_id, $con);
			   $obj->addEvents($this);
			 */
		}
		return $this->aEvent;
	}


	/**
	 * Get the associated Event object
	 *
	 * @param      Connection Optional Connection object.
	 * @return     Event The associated Event object.
	 * @throws     PropelException
	 */
	public function getEventWithI18n($con = null)
	{
		if ($this->aEvent === null && ($this->event_id !== null)) {
			// include the related Peer class
			include_once 'lib/model/om/BaseEventPeer.php';

			$this->aEvent = EventPeer::retrieveByPKWithI18n($this->event_id, $this->getCulture(), $con);

			/* The following can be used instead of the line above to
			   guarantee the related object contains a reference
			   to this object, but this level of coupling
			   may be undesirable in many circumstances.
			   As it can lead to a db query with many results that may
			   never be used.
			   $obj = EventPeer::retrieveByPKWithI18n($this->event_id, $this->getCulture(), $con);
			   $obj->addEvents($this);
			 */
		}
		return $this->aEvent;
	}

	/**
	 * Declares an association between this object and a MatType object.
	 *
	 * @param      MatType $v
	 * @return     void
	 * @throws     PropelException
	 */
	public function setMatType($v)
	{


		if ($v === null) {
			$this->setMatTypeId(NULL);
		} else {
			$this->setMatTypeId($v->getId());
		}


		$this->aMatType = $v;
	}


	/**
	 * Get the associated MatType object
	 *
	 * @param      Connection Optional Connection object.
	 * @return     MatType The associated MatType object.
	 * @throws     PropelException
	 */
	public function getMatType($con = null)
	{
		if ($this->aMatType === null && ($this->mat_type_id !== null)) {
			// include the related Peer class
			include_once 'lib/model/om/BaseMatTypePeer.php';

			$this->aMatType = MatTypePeer::retrieveByPK($this->mat_type_id, $con);

			/* The following can be used instead of the line above to
			   guarantee the related object contains a reference
			   to this object, but this level of coupling
			   may be undesirable in many circumstances.
			   As it can lead to a db query with many results that may
			   never be used.
			   $obj = MatTypePeer::retrieveByPK($this->mat_type_id, $con);
			   $obj->addMatTypes($this);
			 */
		}
		return $this->aMatType;
	}


	/**
	 * Get the associated MatType object
	 *
	 * @param      Connection Optional Connection object.
	 * @return     MatType The associated MatType object.
	 * @throws     PropelException
	 */
	public function getMatTypeWithI18n($con = null)
	{
		if ($this->aMatType === null && ($this->mat_type_id !== null)) {
			// include the related Peer class
			include_once 'lib/model/om/BaseMatTypePeer.php';

			$this->aMatType = MatTypePeer::retrieveByPKWithI18n($this->mat_type_id, $this->getCulture(), $con);

			/* The following can be used instead of the line above to
			   guarantee the related object contains a reference
			   to this object, but this level of coupling
			   may be undesirable in many circumstances.
			   As it can lead to a db query with many results that may
			   never be used.
			   $obj = MatTypePeer::retrieveByPKWithI18n($this->mat_type_id, $this->getCulture(), $con);
			   $obj->addMatTypes($this);
			 */
		}
		return $this->aMatType;
	}

	/**
	 * Temporary storage of collMaterialEventI18ns to save a possible db hit in
	 * the event objects are add to the collection, but the
	 * complete collection is never requested.
	 * @return     void
	 */
	public function initMaterialEventI18ns()
	{
		if ($this->collMaterialEventI18ns === null) {
			$this->collMaterialEventI18ns = array();
		}
	}

	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this MaterialEvent has previously
	 * been saved, it will retrieve related MaterialEventI18ns from storage.
	 * If this MaterialEvent is new, it will return
	 * an empty collection or the current collection, the criteria
	 * is ignored on a new object.
	 *
	 * @param      Connection $con
	 * @param      Criteria $criteria
	 * @throws     PropelException
	 */
	public function getMaterialEventI18ns($criteria = null, $con = null)
	{
		// include the Peer class
		include_once 'lib/model/om/BaseMaterialEventI18nPeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collMaterialEventI18ns === null) {
			if ($this->isNew()) {
			   $this->collMaterialEventI18ns = array();
			} else {

				$criteria->add(MaterialEventI18nPeer::ID, $this->getId());

				MaterialEventI18nPeer::addSelectColumns($criteria);
				$this->collMaterialEventI18ns = MaterialEventI18nPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(MaterialEventI18nPeer::ID, $this->getId());

				MaterialEventI18nPeer::addSelectColumns($criteria);
				if (!isset($this->lastMaterialEventI18nCriteria) || !$this->lastMaterialEventI18nCriteria->equals($criteria)) {
					$this->collMaterialEventI18ns = MaterialEventI18nPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastMaterialEventI18nCriteria = $criteria;
		return $this->collMaterialEventI18ns;
	}

	/**
	 * Returns the number of related MaterialEventI18ns.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      Connection $con
	 * @throws     PropelException
	 */
	public function countMaterialEventI18ns($criteria = null, $distinct = false, $con = null)
	{
		// include the Peer class
		include_once 'lib/model/om/BaseMaterialEventI18nPeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		$criteria->add(MaterialEventI18nPeer::ID, $this->getId());

		return MaterialEventI18nPeer::doCount($criteria, $distinct, $con);
	}

	/**
	 * Method called to associate a MaterialEventI18n object to this object
	 * through the MaterialEventI18n foreign key attribute
	 *
	 * @param      MaterialEventI18n $l MaterialEventI18n
	 * @return     void
	 * @throws     PropelException
	 */
	public function addMaterialEventI18n(MaterialEventI18n $l)
	{
		$this->collMaterialEventI18ns[] = $l;
		$l->setMaterialEvent($this);
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
			if ($this->collMaterialEventI18ns) {
				foreach ((array) $this->collMaterialEventI18ns as $o) {
					$o->clearAllReferences($deep);
				}
			}
		} // if ($deep)

		$this->collMaterialEventI18ns = null;
		$this->aEvent = null;
		$this->aMatType = null;
	}

  public function getCulture()
  {
    return $this->culture;
  }

  public function setCulture($culture)
  {
    $this->culture = $culture;
  }

  public function getName()
  {
    $obj = $this->getCurrentMaterialEventI18n();

    return ($obj ? $obj->getName() : null);
  }

  public function setName($value)
  {
    $this->getCurrentMaterialEventI18n()->setName($value);
  }

  protected $current_i18n = array();

  public function getCurrentMaterialEventI18n()
  {
    if (!isset($this->current_i18n[$this->culture]))
    {
      $obj = MaterialEventI18nPeer::retrieveByPK($this->getId(), $this->culture);
      if ($obj)
      {
        $this->setMaterialEventI18nForCulture($obj, $this->culture);
      }
      else
      {
        $this->setMaterialEventI18nForCulture(new MaterialEventI18n(), $this->culture);
        $this->current_i18n[$this->culture]->setCulture($this->culture);
      }
    }

    return $this->current_i18n[$this->culture];
  }

  public function setMaterialEventI18nForCulture($object, $culture)
  {
    $this->current_i18n[$culture] = $object;
    $this->addMaterialEventI18n($object);
  }


  public function __call($method, $arguments)
  {
    if (!$callable = sfMixer::getCallable('BaseMaterialEvent:'.$method))
    {
      throw new sfException(sprintf('Call to undefined method BaseMaterialEvent::%s', $method));
    }

    array_unshift($arguments, $this);

    return call_user_func_array($callable, $arguments);
  }


} // BaseMaterialEvent
