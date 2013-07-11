<?php

/**
 * Base class that represents a row from the 'session' table.
 *
 * 
 *
 * @package    lib.model.om
 */
abstract class BaseSession extends BaseObject  implements Persistent {


	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        SessionPeer
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
	 * The value for the direct_id field.
	 * @var        int
	 */
	protected $direct_id;


	/**
	 * The value for the init_date field.
	 * @var        int
	 */
	protected $init_date;


	/**
	 * The value for the end_date field.
	 * @var        int
	 */
	protected $end_date;


	/**
	 * The value for the notes field.
	 * @var        string
	 */
	protected $notes;

	/**
	 * @var        Event
	 */
	protected $aEvent;

	/**
	 * @var        Direct
	 */
	protected $aDirect;

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
	 * Get the [direct_id] column value.
	 * 
	 * @return     int
	 */
	public function getDirectId()
	{

		return $this->direct_id;
	}

	/**
	 * Get the [optionally formatted] [init_date] column value.
	 * 
	 * @param      string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the integer unix timestamp will be returned.
	 * @return     mixed Formatted date/time value as string or integer unix timestamp (if format is NULL).
	 * @throws     PropelException - if unable to convert the date/time to timestamp.
	 */
	public function getInitDate($format = 'Y-m-d H:i:s')
	{

		if ($this->init_date === null || $this->init_date === '') {
			return null;
		} elseif (!is_int($this->init_date)) {
			// a non-timestamp value was set externally, so we convert it
			$ts = strtotime($this->init_date);
			if ($ts === -1 || $ts === false) { // in PHP 5.1 return value changes to FALSE
				throw new PropelException("Unable to parse value of [init_date] as date/time value: " . var_export($this->init_date, true));
			}
		} else {
			$ts = $this->init_date;
		}
		if ($format === null) {
			return $ts;
		} elseif (strpos($format, '%') !== false) {
			return strftime($format, $ts);
		} else {
			return date($format, $ts);
		}
	}

	/**
	 * Get the [optionally formatted] [end_date] column value.
	 * 
	 * @param      string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the integer unix timestamp will be returned.
	 * @return     mixed Formatted date/time value as string or integer unix timestamp (if format is NULL).
	 * @throws     PropelException - if unable to convert the date/time to timestamp.
	 */
	public function getEndDate($format = 'Y-m-d H:i:s')
	{

		if ($this->end_date === null || $this->end_date === '') {
			return null;
		} elseif (!is_int($this->end_date)) {
			// a non-timestamp value was set externally, so we convert it
			$ts = strtotime($this->end_date);
			if ($ts === -1 || $ts === false) { // in PHP 5.1 return value changes to FALSE
				throw new PropelException("Unable to parse value of [end_date] as date/time value: " . var_export($this->end_date, true));
			}
		} else {
			$ts = $this->end_date;
		}
		if ($format === null) {
			return $ts;
		} elseif (strpos($format, '%') !== false) {
			return strftime($format, $ts);
		} else {
			return date($format, $ts);
		}
	}

	/**
	 * Get the [notes] column value.
	 * 
	 * @return     string
	 */
	public function getNotes()
	{

		return $this->notes;
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
			$this->modifiedColumns[] = SessionPeer::ID;
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
			$this->modifiedColumns[] = SessionPeer::EVENT_ID;
		}

		if ($this->aEvent !== null && $this->aEvent->getId() !== $v) {
			$this->aEvent = null;
		}

	} // setEventId()

	/**
	 * Set the value of [direct_id] column.
	 * 
	 * @param      int $v new value
	 * @return     void
	 */
	public function setDirectId($v)
	{

		// Since the native PHP type for this column is integer,
		// we will cast the input value to an int (if it is not).
		if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->direct_id !== $v) {
			$this->direct_id = $v;
			$this->modifiedColumns[] = SessionPeer::DIRECT_ID;
		}

		if ($this->aDirect !== null && $this->aDirect->getId() !== $v) {
			$this->aDirect = null;
		}

	} // setDirectId()

	/**
	 * Set the value of [init_date] column.
	 * 
	 * @param      int $v new value
	 * @return     void
	 */
	public function setInitDate($v)
	{

		if ($v !== null && !is_int($v)) {
			$ts = strtotime($v);
			if ($ts === -1 || $ts === false) { // in PHP 5.1 return value changes to FALSE
				throw new PropelException("Unable to parse date/time value for [init_date] from input: " . var_export($v, true));
			}
		} else {
			$ts = $v;
		}
		if ($this->init_date !== $ts) {
			$this->init_date = $ts;
			$this->modifiedColumns[] = SessionPeer::INIT_DATE;
		}

	} // setInitDate()

	/**
	 * Set the value of [end_date] column.
	 * 
	 * @param      int $v new value
	 * @return     void
	 */
	public function setEndDate($v)
	{

		if ($v !== null && !is_int($v)) {
			$ts = strtotime($v);
			if ($ts === -1 || $ts === false) { // in PHP 5.1 return value changes to FALSE
				throw new PropelException("Unable to parse date/time value for [end_date] from input: " . var_export($v, true));
			}
		} else {
			$ts = $v;
		}
		if ($this->end_date !== $ts) {
			$this->end_date = $ts;
			$this->modifiedColumns[] = SessionPeer::END_DATE;
		}

	} // setEndDate()

	/**
	 * Set the value of [notes] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setNotes($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->notes !== $v) {
			$this->notes = $v;
			$this->modifiedColumns[] = SessionPeer::NOTES;
		}

	} // setNotes()

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

			$this->direct_id = $rs->getInt($startcol + 2);

			$this->init_date = $rs->getTimestamp($startcol + 3, null);

			$this->end_date = $rs->getTimestamp($startcol + 4, null);

			$this->notes = $rs->getString($startcol + 5);

			$this->resetModified();

			$this->setNew(false);

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 6; // 6 = SessionPeer::NUM_COLUMNS - SessionPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating Session object", $e);
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

    foreach (sfMixer::getCallables('BaseSession:delete:pre') as $callable)
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
			$con = Propel::getConnection(SessionPeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			SessionPeer::doDelete($this, $con);
			$this->setDeleted(true);
			$con->commit();
		} catch (PropelException $e) {
			$con->rollback();
			throw $e;
		}
	

    foreach (sfMixer::getCallables('BaseSession:delete:post') as $callable)
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

    foreach (sfMixer::getCallables('BaseSession:save:pre') as $callable)
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
			$con = Propel::getConnection(SessionPeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			$affectedRows = $this->doSave($con);
			$con->commit();
    foreach (sfMixer::getCallables('BaseSession:save:post') as $callable)
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

			if ($this->aDirect !== null) {
				if ($this->aDirect->isModified() || $this->aDirect->getCurrentDirectI18n()->isModified()) {
					$affectedRows += $this->aDirect->save($con);
				}
				$this->setDirect($this->aDirect);
			}


			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = SessionPeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setId($pk);  //[IMV] update autoincrement primary key

					$this->setNew(false);
				} else {
					$affectedRows += SessionPeer::doUpdate($this, $con);
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

			if ($this->aEvent !== null) {
				if (!$this->aEvent->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aEvent->getValidationFailures());
				}
			}

			if ($this->aDirect !== null) {
				if (!$this->aDirect->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aDirect->getValidationFailures());
				}
			}


			if (($retval = SessionPeer::doValidate($this, $columns)) !== true) {
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
		$pos = SessionPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getDirectId();
				break;
			case 3:
				return $this->getInitDate();
				break;
			case 4:
				return $this->getEndDate();
				break;
			case 5:
				return $this->getNotes();
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
		$keys = SessionPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getEventId(),
			$keys[2] => $this->getDirectId(),
			$keys[3] => $this->getInitDate(),
			$keys[4] => $this->getEndDate(),
			$keys[5] => $this->getNotes(),
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
		$pos = SessionPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				$this->setDirectId($value);
				break;
			case 3:
				$this->setInitDate($value);
				break;
			case 4:
				$this->setEndDate($value);
				break;
			case 5:
				$this->setNotes($value);
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
		$keys = SessionPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setEventId($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setDirectId($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setInitDate($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setEndDate($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setNotes($arr[$keys[5]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(SessionPeer::DATABASE_NAME);

		if ($this->isColumnModified(SessionPeer::ID)) $criteria->add(SessionPeer::ID, $this->id);
		if ($this->isColumnModified(SessionPeer::EVENT_ID)) $criteria->add(SessionPeer::EVENT_ID, $this->event_id);
		if ($this->isColumnModified(SessionPeer::DIRECT_ID)) $criteria->add(SessionPeer::DIRECT_ID, $this->direct_id);
		if ($this->isColumnModified(SessionPeer::INIT_DATE)) $criteria->add(SessionPeer::INIT_DATE, $this->init_date);
		if ($this->isColumnModified(SessionPeer::END_DATE)) $criteria->add(SessionPeer::END_DATE, $this->end_date);
		if ($this->isColumnModified(SessionPeer::NOTES)) $criteria->add(SessionPeer::NOTES, $this->notes);

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
		$criteria = new Criteria(SessionPeer::DATABASE_NAME);

		$criteria->add(SessionPeer::ID, $this->id);

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
	 * @param      object $copyObj An object of Session (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setEventId($this->event_id);

		$copyObj->setDirectId($this->direct_id);

		$copyObj->setInitDate($this->init_date);

		$copyObj->setEndDate($this->end_date);

		$copyObj->setNotes($this->notes);


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
	 * @return     Session Clone of current object.
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
	 * @return     SessionPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new SessionPeer();
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
	 * Declares an association between this object and a Direct object.
	 *
	 * @param      Direct $v
	 * @return     void
	 * @throws     PropelException
	 */
	public function setDirect($v)
	{


		if ($v === null) {
			$this->setDirectId(NULL);
		} else {
			$this->setDirectId($v->getId());
		}


		$this->aDirect = $v;
	}


	/**
	 * Get the associated Direct object
	 *
	 * @param      Connection Optional Connection object.
	 * @return     Direct The associated Direct object.
	 * @throws     PropelException
	 */
	public function getDirect($con = null)
	{
		if ($this->aDirect === null && ($this->direct_id !== null)) {
			// include the related Peer class
			include_once 'lib/model/om/BaseDirectPeer.php';

			$this->aDirect = DirectPeer::retrieveByPK($this->direct_id, $con);

			/* The following can be used instead of the line above to
			   guarantee the related object contains a reference
			   to this object, but this level of coupling
			   may be undesirable in many circumstances.
			   As it can lead to a db query with many results that may
			   never be used.
			   $obj = DirectPeer::retrieveByPK($this->direct_id, $con);
			   $obj->addDirects($this);
			 */
		}
		return $this->aDirect;
	}


	/**
	 * Get the associated Direct object
	 *
	 * @param      Connection Optional Connection object.
	 * @return     Direct The associated Direct object.
	 * @throws     PropelException
	 */
	public function getDirectWithI18n($con = null)
	{
		if ($this->aDirect === null && ($this->direct_id !== null)) {
			// include the related Peer class
			include_once 'lib/model/om/BaseDirectPeer.php';

			$this->aDirect = DirectPeer::retrieveByPKWithI18n($this->direct_id, $this->getCulture(), $con);

			/* The following can be used instead of the line above to
			   guarantee the related object contains a reference
			   to this object, but this level of coupling
			   may be undesirable in many circumstances.
			   As it can lead to a db query with many results that may
			   never be used.
			   $obj = DirectPeer::retrieveByPKWithI18n($this->direct_id, $this->getCulture(), $con);
			   $obj->addDirects($this);
			 */
		}
		return $this->aDirect;
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

		$this->aEvent = null;
		$this->aDirect = null;
	}


  public function __call($method, $arguments)
  {
    if (!$callable = sfMixer::getCallable('BaseSession:'.$method))
    {
      throw new sfException(sprintf('Call to undefined method BaseSession::%s', $method));
    }

    array_unshift($arguments, $this);

    return call_user_func_array($callable, $arguments);
  }


} // BaseSession
