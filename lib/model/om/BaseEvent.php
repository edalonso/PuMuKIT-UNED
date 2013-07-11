<?php

/**
 * Base class that represents a row from the 'event' table.
 *
 * 
 *
 * @package    lib.model.om
 */
abstract class BaseEvent extends BaseObject  implements Persistent {


	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        EventPeer
	 */
	protected static $peer;


	/**
	 * The value for the id field.
	 * @var        int
	 */
	protected $id;


	/**
	 * The value for the direct_id field.
	 * @var        int
	 */
	protected $direct_id;


	/**
	 * The value for the serial_id field.
	 * @var        int
	 */
	protected $serial_id;


	/**
	 * The value for the date field.
	 * @var        int
	 */
	protected $date;


	/**
	 * The value for the display field.
	 * @var        boolean
	 */
	protected $display = true;


	/**
	 * The value for the create_serial field.
	 * @var        boolean
	 */
	protected $create_serial = true;


	/**
	 * The value for the enable_query field.
	 * @var        boolean
	 */
	protected $enable_query = false;


	/**
	 * The value for the email_query field.
	 * @var        string
	 */
	protected $email_query;


	/**
	 * The value for the author field.
	 * @var        string
	 */
	protected $author;


	/**
	 * The value for the producer field.
	 * @var        string
	 */
	protected $producer;


	/**
	 * The value for the external field.
	 * @var        boolean
	 */
	protected $external = false;


	/**
	 * The value for the url field.
	 * @var        string
	 */
	protected $url;


	/**
	 * The value for the secured field.
	 * @var        boolean
	 */
	protected $secured = false;


	/**
	 * The value for the password field.
	 * @var        string
	 */
	protected $password;

	/**
	 * @var        Direct
	 */
	protected $aDirect;

	/**
	 * @var        Serial
	 */
	protected $aSerial;

	/**
	 * Collection to store aggregation of collPicEvents.
	 * @var        array
	 */
	protected $collPicEvents;

	/**
	 * The criteria used to select the current contents of collPicEvents.
	 * @var        Criteria
	 */
	protected $lastPicEventCriteria = null;

	/**
	 * Collection to store aggregation of collMaterialEvents.
	 * @var        array
	 */
	protected $collMaterialEvents;

	/**
	 * The criteria used to select the current contents of collMaterialEvents.
	 * @var        Criteria
	 */
	protected $lastMaterialEventCriteria = null;

	/**
	 * Collection to store aggregation of collEventI18ns.
	 * @var        array
	 */
	protected $collEventI18ns;

	/**
	 * The criteria used to select the current contents of collEventI18ns.
	 * @var        Criteria
	 */
	protected $lastEventI18nCriteria = null;

	/**
	 * Collection to store aggregation of collSessions.
	 * @var        array
	 */
	protected $collSessions;

	/**
	 * The criteria used to select the current contents of collSessions.
	 * @var        Criteria
	 */
	protected $lastSessionCriteria = null;

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
	 * Get the [direct_id] column value.
	 * 
	 * @return     int
	 */
	public function getDirectId()
	{

		return $this->direct_id;
	}

	/**
	 * Get the [serial_id] column value.
	 * 
	 * @return     int
	 */
	public function getSerialId()
	{

		return $this->serial_id;
	}

	/**
	 * Get the [optionally formatted] [date] column value.
	 * 
	 * @param      string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the integer unix timestamp will be returned.
	 * @return     mixed Formatted date/time value as string or integer unix timestamp (if format is NULL).
	 * @throws     PropelException - if unable to convert the date/time to timestamp.
	 */
	public function getDate($format = 'Y-m-d H:i:s')
	{

		if ($this->date === null || $this->date === '') {
			return null;
		} elseif (!is_int($this->date)) {
			// a non-timestamp value was set externally, so we convert it
			$ts = strtotime($this->date);
			if ($ts === -1 || $ts === false) { // in PHP 5.1 return value changes to FALSE
				throw new PropelException("Unable to parse value of [date] as date/time value: " . var_export($this->date, true));
			}
		} else {
			$ts = $this->date;
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
	 * Get the [display] column value.
	 * 
	 * @return     boolean
	 */
	public function getDisplay()
	{

		return $this->display;
	}

	/**
	 * Get the [create_serial] column value.
	 * 
	 * @return     boolean
	 */
	public function getCreateSerial()
	{

		return $this->create_serial;
	}

	/**
	 * Get the [enable_query] column value.
	 * 
	 * @return     boolean
	 */
	public function getEnableQuery()
	{

		return $this->enable_query;
	}

	/**
	 * Get the [email_query] column value.
	 * 
	 * @return     string
	 */
	public function getEmailQuery()
	{

		return $this->email_query;
	}

	/**
	 * Get the [author] column value.
	 * 
	 * @return     string
	 */
	public function getAuthor()
	{

		return $this->author;
	}

	/**
	 * Get the [producer] column value.
	 * 
	 * @return     string
	 */
	public function getProducer()
	{

		return $this->producer;
	}

	/**
	 * Get the [external] column value.
	 * 
	 * @return     boolean
	 */
	public function getExternal()
	{

		return $this->external;
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
	 * Get the [secured] column value.
	 * 
	 * @return     boolean
	 */
	public function getSecured()
	{

		return $this->secured;
	}

	/**
	 * Get the [password] column value.
	 * 
	 * @return     string
	 */
	public function getPassword()
	{

		return $this->password;
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
			$this->modifiedColumns[] = EventPeer::ID;
		}

	} // setId()

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
			$this->modifiedColumns[] = EventPeer::DIRECT_ID;
		}

		if ($this->aDirect !== null && $this->aDirect->getId() !== $v) {
			$this->aDirect = null;
		}

	} // setDirectId()

	/**
	 * Set the value of [serial_id] column.
	 * 
	 * @param      int $v new value
	 * @return     void
	 */
	public function setSerialId($v)
	{

		// Since the native PHP type for this column is integer,
		// we will cast the input value to an int (if it is not).
		if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->serial_id !== $v) {
			$this->serial_id = $v;
			$this->modifiedColumns[] = EventPeer::SERIAL_ID;
		}

		if ($this->aSerial !== null && $this->aSerial->getId() !== $v) {
			$this->aSerial = null;
		}

	} // setSerialId()

	/**
	 * Set the value of [date] column.
	 * 
	 * @param      int $v new value
	 * @return     void
	 */
	public function setDate($v)
	{

		if ($v !== null && !is_int($v)) {
			$ts = strtotime($v);
			if ($ts === -1 || $ts === false) { // in PHP 5.1 return value changes to FALSE
				throw new PropelException("Unable to parse date/time value for [date] from input: " . var_export($v, true));
			}
		} else {
			$ts = $v;
		}
		if ($this->date !== $ts) {
			$this->date = $ts;
			$this->modifiedColumns[] = EventPeer::DATE;
		}

	} // setDate()

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
			$this->modifiedColumns[] = EventPeer::DISPLAY;
		}

	} // setDisplay()

	/**
	 * Set the value of [create_serial] column.
	 * 
	 * @param      boolean $v new value
	 * @return     void
	 */
	public function setCreateSerial($v)
	{

		if ($this->create_serial !== $v || $v === true) {
			$this->create_serial = $v;
			$this->modifiedColumns[] = EventPeer::CREATE_SERIAL;
		}

	} // setCreateSerial()

	/**
	 * Set the value of [enable_query] column.
	 * 
	 * @param      boolean $v new value
	 * @return     void
	 */
	public function setEnableQuery($v)
	{

		if ($this->enable_query !== $v || $v === false) {
			$this->enable_query = $v;
			$this->modifiedColumns[] = EventPeer::ENABLE_QUERY;
		}

	} // setEnableQuery()

	/**
	 * Set the value of [email_query] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setEmailQuery($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->email_query !== $v) {
			$this->email_query = $v;
			$this->modifiedColumns[] = EventPeer::EMAIL_QUERY;
		}

	} // setEmailQuery()

	/**
	 * Set the value of [author] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setAuthor($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->author !== $v) {
			$this->author = $v;
			$this->modifiedColumns[] = EventPeer::AUTHOR;
		}

	} // setAuthor()

	/**
	 * Set the value of [producer] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setProducer($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->producer !== $v) {
			$this->producer = $v;
			$this->modifiedColumns[] = EventPeer::PRODUCER;
		}

	} // setProducer()

	/**
	 * Set the value of [external] column.
	 * 
	 * @param      boolean $v new value
	 * @return     void
	 */
	public function setExternal($v)
	{

		if ($this->external !== $v || $v === false) {
			$this->external = $v;
			$this->modifiedColumns[] = EventPeer::EXTERNAL;
		}

	} // setExternal()

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
			$this->modifiedColumns[] = EventPeer::URL;
		}

	} // setUrl()

	/**
	 * Set the value of [secured] column.
	 * 
	 * @param      boolean $v new value
	 * @return     void
	 */
	public function setSecured($v)
	{

		if ($this->secured !== $v || $v === false) {
			$this->secured = $v;
			$this->modifiedColumns[] = EventPeer::SECURED;
		}

	} // setSecured()

	/**
	 * Set the value of [password] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setPassword($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->password !== $v) {
			$this->password = $v;
			$this->modifiedColumns[] = EventPeer::PASSWORD;
		}

	} // setPassword()

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

			$this->direct_id = $rs->getInt($startcol + 1);

			$this->serial_id = $rs->getInt($startcol + 2);

			$this->date = $rs->getTimestamp($startcol + 3, null);

			$this->display = $rs->getBoolean($startcol + 4);

			$this->create_serial = $rs->getBoolean($startcol + 5);

			$this->enable_query = $rs->getBoolean($startcol + 6);

			$this->email_query = $rs->getString($startcol + 7);

			$this->author = $rs->getString($startcol + 8);

			$this->producer = $rs->getString($startcol + 9);

			$this->external = $rs->getBoolean($startcol + 10);

			$this->url = $rs->getString($startcol + 11);

			$this->secured = $rs->getBoolean($startcol + 12);

			$this->password = $rs->getString($startcol + 13);

			$this->resetModified();

			$this->setNew(false);
			$this->setCulture(sfContext::getInstance()->getUser()->getCulture());

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 14; // 14 = EventPeer::NUM_COLUMNS - EventPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating Event object", $e);
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

    foreach (sfMixer::getCallables('BaseEvent:delete:pre') as $callable)
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
			$con = Propel::getConnection(EventPeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			EventPeer::doDelete($this, $con);
			$this->setDeleted(true);
			$con->commit();
		} catch (PropelException $e) {
			$con->rollback();
			throw $e;
		}
	

    foreach (sfMixer::getCallables('BaseEvent:delete:post') as $callable)
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

    foreach (sfMixer::getCallables('BaseEvent:save:pre') as $callable)
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
			$con = Propel::getConnection(EventPeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			$affectedRows = $this->doSave($con);
			$con->commit();
    foreach (sfMixer::getCallables('BaseEvent:save:post') as $callable)
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

			if ($this->aDirect !== null) {
				if ($this->aDirect->isModified() || $this->aDirect->getCurrentDirectI18n()->isModified()) {
					$affectedRows += $this->aDirect->save($con);
				}
				$this->setDirect($this->aDirect);
			}

			if ($this->aSerial !== null) {
				if ($this->aSerial->isModified() || $this->aSerial->getCurrentSerialI18n()->isModified()) {
					$affectedRows += $this->aSerial->save($con);
				}
				$this->setSerial($this->aSerial);
			}


			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = EventPeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setId($pk);  //[IMV] update autoincrement primary key

					$this->setNew(false);
				} else {
					$affectedRows += EventPeer::doUpdate($this, $con);
				}
				$this->resetModified(); // [HL] After being saved an object is no longer 'modified'
			}

			if ($this->collPicEvents !== null) {
				foreach($this->collPicEvents as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collMaterialEvents !== null) {
				foreach($this->collMaterialEvents as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collEventI18ns !== null) {
				foreach($this->collEventI18ns as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collSessions !== null) {
				foreach($this->collSessions as $referrerFK) {
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

			if ($this->aDirect !== null) {
				if (!$this->aDirect->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aDirect->getValidationFailures());
				}
			}

			if ($this->aSerial !== null) {
				if (!$this->aSerial->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aSerial->getValidationFailures());
				}
			}


			if (($retval = EventPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}


				if ($this->collPicEvents !== null) {
					foreach($this->collPicEvents as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collMaterialEvents !== null) {
					foreach($this->collMaterialEvents as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collEventI18ns !== null) {
					foreach($this->collEventI18ns as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collSessions !== null) {
					foreach($this->collSessions as $referrerFK) {
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
		$pos = EventPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getDirectId();
				break;
			case 2:
				return $this->getSerialId();
				break;
			case 3:
				return $this->getDate();
				break;
			case 4:
				return $this->getDisplay();
				break;
			case 5:
				return $this->getCreateSerial();
				break;
			case 6:
				return $this->getEnableQuery();
				break;
			case 7:
				return $this->getEmailQuery();
				break;
			case 8:
				return $this->getAuthor();
				break;
			case 9:
				return $this->getProducer();
				break;
			case 10:
				return $this->getExternal();
				break;
			case 11:
				return $this->getUrl();
				break;
			case 12:
				return $this->getSecured();
				break;
			case 13:
				return $this->getPassword();
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
		$keys = EventPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getDirectId(),
			$keys[2] => $this->getSerialId(),
			$keys[3] => $this->getDate(),
			$keys[4] => $this->getDisplay(),
			$keys[5] => $this->getCreateSerial(),
			$keys[6] => $this->getEnableQuery(),
			$keys[7] => $this->getEmailQuery(),
			$keys[8] => $this->getAuthor(),
			$keys[9] => $this->getProducer(),
			$keys[10] => $this->getExternal(),
			$keys[11] => $this->getUrl(),
			$keys[12] => $this->getSecured(),
			$keys[13] => $this->getPassword(),
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
		$pos = EventPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				$this->setDirectId($value);
				break;
			case 2:
				$this->setSerialId($value);
				break;
			case 3:
				$this->setDate($value);
				break;
			case 4:
				$this->setDisplay($value);
				break;
			case 5:
				$this->setCreateSerial($value);
				break;
			case 6:
				$this->setEnableQuery($value);
				break;
			case 7:
				$this->setEmailQuery($value);
				break;
			case 8:
				$this->setAuthor($value);
				break;
			case 9:
				$this->setProducer($value);
				break;
			case 10:
				$this->setExternal($value);
				break;
			case 11:
				$this->setUrl($value);
				break;
			case 12:
				$this->setSecured($value);
				break;
			case 13:
				$this->setPassword($value);
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
		$keys = EventPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setDirectId($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setSerialId($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setDate($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setDisplay($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setCreateSerial($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setEnableQuery($arr[$keys[6]]);
		if (array_key_exists($keys[7], $arr)) $this->setEmailQuery($arr[$keys[7]]);
		if (array_key_exists($keys[8], $arr)) $this->setAuthor($arr[$keys[8]]);
		if (array_key_exists($keys[9], $arr)) $this->setProducer($arr[$keys[9]]);
		if (array_key_exists($keys[10], $arr)) $this->setExternal($arr[$keys[10]]);
		if (array_key_exists($keys[11], $arr)) $this->setUrl($arr[$keys[11]]);
		if (array_key_exists($keys[12], $arr)) $this->setSecured($arr[$keys[12]]);
		if (array_key_exists($keys[13], $arr)) $this->setPassword($arr[$keys[13]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(EventPeer::DATABASE_NAME);

		if ($this->isColumnModified(EventPeer::ID)) $criteria->add(EventPeer::ID, $this->id);
		if ($this->isColumnModified(EventPeer::DIRECT_ID)) $criteria->add(EventPeer::DIRECT_ID, $this->direct_id);
		if ($this->isColumnModified(EventPeer::SERIAL_ID)) $criteria->add(EventPeer::SERIAL_ID, $this->serial_id);
		if ($this->isColumnModified(EventPeer::DATE)) $criteria->add(EventPeer::DATE, $this->date);
		if ($this->isColumnModified(EventPeer::DISPLAY)) $criteria->add(EventPeer::DISPLAY, $this->display);
		if ($this->isColumnModified(EventPeer::CREATE_SERIAL)) $criteria->add(EventPeer::CREATE_SERIAL, $this->create_serial);
		if ($this->isColumnModified(EventPeer::ENABLE_QUERY)) $criteria->add(EventPeer::ENABLE_QUERY, $this->enable_query);
		if ($this->isColumnModified(EventPeer::EMAIL_QUERY)) $criteria->add(EventPeer::EMAIL_QUERY, $this->email_query);
		if ($this->isColumnModified(EventPeer::AUTHOR)) $criteria->add(EventPeer::AUTHOR, $this->author);
		if ($this->isColumnModified(EventPeer::PRODUCER)) $criteria->add(EventPeer::PRODUCER, $this->producer);
		if ($this->isColumnModified(EventPeer::EXTERNAL)) $criteria->add(EventPeer::EXTERNAL, $this->external);
		if ($this->isColumnModified(EventPeer::URL)) $criteria->add(EventPeer::URL, $this->url);
		if ($this->isColumnModified(EventPeer::SECURED)) $criteria->add(EventPeer::SECURED, $this->secured);
		if ($this->isColumnModified(EventPeer::PASSWORD)) $criteria->add(EventPeer::PASSWORD, $this->password);

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
		$criteria = new Criteria(EventPeer::DATABASE_NAME);

		$criteria->add(EventPeer::ID, $this->id);

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
	 * @param      object $copyObj An object of Event (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setDirectId($this->direct_id);

		$copyObj->setSerialId($this->serial_id);

		$copyObj->setDate($this->date);

		$copyObj->setDisplay($this->display);

		$copyObj->setCreateSerial($this->create_serial);

		$copyObj->setEnableQuery($this->enable_query);

		$copyObj->setEmailQuery($this->email_query);

		$copyObj->setAuthor($this->author);

		$copyObj->setProducer($this->producer);

		$copyObj->setExternal($this->external);

		$copyObj->setUrl($this->url);

		$copyObj->setSecured($this->secured);

		$copyObj->setPassword($this->password);


		if ($deepCopy) {
			// important: temporarily setNew(false) because this affects the behavior of
			// the getter/setter methods for fkey referrer objects.
			$copyObj->setNew(false);

			foreach($this->getPicEvents() as $relObj) {
				$copyObj->addPicEvent($relObj->copy($deepCopy));
			}

			foreach($this->getMaterialEvents() as $relObj) {
				$copyObj->addMaterialEvent($relObj->copy($deepCopy));
			}

			foreach($this->getEventI18ns() as $relObj) {
				$copyObj->addEventI18n($relObj->copy($deepCopy));
			}

			foreach($this->getSessions() as $relObj) {
				$copyObj->addSession($relObj->copy($deepCopy));
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
	 * @return     Event Clone of current object.
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
	 * @return     EventPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new EventPeer();
		}
		return self::$peer;
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
	 * Declares an association between this object and a Serial object.
	 *
	 * @param      Serial $v
	 * @return     void
	 * @throws     PropelException
	 */
	public function setSerial($v)
	{


		if ($v === null) {
			$this->setSerialId(NULL);
		} else {
			$this->setSerialId($v->getId());
		}


		$this->aSerial = $v;
	}


	/**
	 * Get the associated Serial object
	 *
	 * @param      Connection Optional Connection object.
	 * @return     Serial The associated Serial object.
	 * @throws     PropelException
	 */
	public function getSerial($con = null)
	{
		if ($this->aSerial === null && ($this->serial_id !== null)) {
			// include the related Peer class
			include_once 'lib/model/om/BaseSerialPeer.php';

			$this->aSerial = SerialPeer::retrieveByPK($this->serial_id, $con);

			/* The following can be used instead of the line above to
			   guarantee the related object contains a reference
			   to this object, but this level of coupling
			   may be undesirable in many circumstances.
			   As it can lead to a db query with many results that may
			   never be used.
			   $obj = SerialPeer::retrieveByPK($this->serial_id, $con);
			   $obj->addSerials($this);
			 */
		}
		return $this->aSerial;
	}


	/**
	 * Get the associated Serial object
	 *
	 * @param      Connection Optional Connection object.
	 * @return     Serial The associated Serial object.
	 * @throws     PropelException
	 */
	public function getSerialWithI18n($con = null)
	{
		if ($this->aSerial === null && ($this->serial_id !== null)) {
			// include the related Peer class
			include_once 'lib/model/om/BaseSerialPeer.php';

			$this->aSerial = SerialPeer::retrieveByPKWithI18n($this->serial_id, $this->getCulture(), $con);

			/* The following can be used instead of the line above to
			   guarantee the related object contains a reference
			   to this object, but this level of coupling
			   may be undesirable in many circumstances.
			   As it can lead to a db query with many results that may
			   never be used.
			   $obj = SerialPeer::retrieveByPKWithI18n($this->serial_id, $this->getCulture(), $con);
			   $obj->addSerials($this);
			 */
		}
		return $this->aSerial;
	}

	/**
	 * Temporary storage of collPicEvents to save a possible db hit in
	 * the event objects are add to the collection, but the
	 * complete collection is never requested.
	 * @return     void
	 */
	public function initPicEvents()
	{
		if ($this->collPicEvents === null) {
			$this->collPicEvents = array();
		}
	}

	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Event has previously
	 * been saved, it will retrieve related PicEvents from storage.
	 * If this Event is new, it will return
	 * an empty collection or the current collection, the criteria
	 * is ignored on a new object.
	 *
	 * @param      Connection $con
	 * @param      Criteria $criteria
	 * @throws     PropelException
	 */
	public function getPicEvents($criteria = null, $con = null)
	{
		// include the Peer class
		include_once 'lib/model/om/BasePicEventPeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collPicEvents === null) {
			if ($this->isNew()) {
			   $this->collPicEvents = array();
			} else {

				$criteria->add(PicEventPeer::OTHER_ID, $this->getId());

				PicEventPeer::addSelectColumns($criteria);
				$this->collPicEvents = PicEventPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(PicEventPeer::OTHER_ID, $this->getId());

				PicEventPeer::addSelectColumns($criteria);
				if (!isset($this->lastPicEventCriteria) || !$this->lastPicEventCriteria->equals($criteria)) {
					$this->collPicEvents = PicEventPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastPicEventCriteria = $criteria;
		return $this->collPicEvents;
	}

	/**
	 * Returns the number of related PicEvents.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      Connection $con
	 * @throws     PropelException
	 */
	public function countPicEvents($criteria = null, $distinct = false, $con = null)
	{
		// include the Peer class
		include_once 'lib/model/om/BasePicEventPeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		$criteria->add(PicEventPeer::OTHER_ID, $this->getId());

		return PicEventPeer::doCount($criteria, $distinct, $con);
	}

	/**
	 * Method called to associate a PicEvent object to this object
	 * through the PicEvent foreign key attribute
	 *
	 * @param      PicEvent $l PicEvent
	 * @return     void
	 * @throws     PropelException
	 */
	public function addPicEvent(PicEvent $l)
	{
		$this->collPicEvents[] = $l;
		$l->setEvent($this);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Event is new, it will return
	 * an empty collection; or if this Event has previously
	 * been saved, it will retrieve related PicEvents from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Event.
	 */
	public function getPicEventsJoinPic($criteria = null, $con = null)
	{
		// include the Peer class
		include_once 'lib/model/om/BasePicEventPeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collPicEvents === null) {
			if ($this->isNew()) {
				$this->collPicEvents = array();
			} else {

				$criteria->add(PicEventPeer::OTHER_ID, $this->getId());

				$this->collPicEvents = PicEventPeer::doSelectJoinPic($criteria, $con);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(PicEventPeer::OTHER_ID, $this->getId());

			if (!isset($this->lastPicEventCriteria) || !$this->lastPicEventCriteria->equals($criteria)) {
				$this->collPicEvents = PicEventPeer::doSelectJoinPic($criteria, $con);
			}
		}
		$this->lastPicEventCriteria = $criteria;

		return $this->collPicEvents;
	}

	/**
	 * Temporary storage of collMaterialEvents to save a possible db hit in
	 * the event objects are add to the collection, but the
	 * complete collection is never requested.
	 * @return     void
	 */
	public function initMaterialEvents()
	{
		if ($this->collMaterialEvents === null) {
			$this->collMaterialEvents = array();
		}
	}

	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Event has previously
	 * been saved, it will retrieve related MaterialEvents from storage.
	 * If this Event is new, it will return
	 * an empty collection or the current collection, the criteria
	 * is ignored on a new object.
	 *
	 * @param      Connection $con
	 * @param      Criteria $criteria
	 * @throws     PropelException
	 */
	public function getMaterialEvents($criteria = null, $con = null)
	{
		// include the Peer class
		include_once 'lib/model/om/BaseMaterialEventPeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collMaterialEvents === null) {
			if ($this->isNew()) {
			   $this->collMaterialEvents = array();
			} else {

				$criteria->add(MaterialEventPeer::EVENT_ID, $this->getId());

				MaterialEventPeer::addSelectColumns($criteria);
				$this->collMaterialEvents = MaterialEventPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(MaterialEventPeer::EVENT_ID, $this->getId());

				MaterialEventPeer::addSelectColumns($criteria);
				if (!isset($this->lastMaterialEventCriteria) || !$this->lastMaterialEventCriteria->equals($criteria)) {
					$this->collMaterialEvents = MaterialEventPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastMaterialEventCriteria = $criteria;
		return $this->collMaterialEvents;
	}

	/**
	 * Returns the number of related MaterialEvents.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      Connection $con
	 * @throws     PropelException
	 */
	public function countMaterialEvents($criteria = null, $distinct = false, $con = null)
	{
		// include the Peer class
		include_once 'lib/model/om/BaseMaterialEventPeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		$criteria->add(MaterialEventPeer::EVENT_ID, $this->getId());

		return MaterialEventPeer::doCount($criteria, $distinct, $con);
	}

	/**
	 * Method called to associate a MaterialEvent object to this object
	 * through the MaterialEvent foreign key attribute
	 *
	 * @param      MaterialEvent $l MaterialEvent
	 * @return     void
	 * @throws     PropelException
	 */
	public function addMaterialEvent(MaterialEvent $l)
	{
		$this->collMaterialEvents[] = $l;
		$l->setEvent($this);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Event is new, it will return
	 * an empty collection; or if this Event has previously
	 * been saved, it will retrieve related MaterialEvents from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Event.
	 */
	public function getMaterialEventsJoinMatType($criteria = null, $con = null)
	{
		// include the Peer class
		include_once 'lib/model/om/BaseMaterialEventPeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collMaterialEvents === null) {
			if ($this->isNew()) {
				$this->collMaterialEvents = array();
			} else {

				$criteria->add(MaterialEventPeer::EVENT_ID, $this->getId());

				$this->collMaterialEvents = MaterialEventPeer::doSelectJoinMatType($criteria, $con);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(MaterialEventPeer::EVENT_ID, $this->getId());

			if (!isset($this->lastMaterialEventCriteria) || !$this->lastMaterialEventCriteria->equals($criteria)) {
				$this->collMaterialEvents = MaterialEventPeer::doSelectJoinMatType($criteria, $con);
			}
		}
		$this->lastMaterialEventCriteria = $criteria;

		return $this->collMaterialEvents;
	}

	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Event has previously
	 * been saved, it will retrieve related MaterialEvents from storage.
	 * If this Event is new, it will return
	 * an empty collection or the current collection, the criteria
	 * is ignored on a new object.
	 *
	 * @param      Connection $con
	 * @param      Criteria $criteria
	 * @throws     PropelException
	 */
	public function getMaterialEventsWithI18n($criteria = null, $con = null)
	{
		// include the Peer class
		include_once 'lib/model/om/BaseMaterialEventPeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collMaterialEvents === null) {
			if ($this->isNew()) {
			   $this->collMaterialEvents = array();
			} else {

				$criteria->add(MaterialEventPeer::EVENT_ID, $this->getId());

				$this->collMaterialEvents = MaterialEventPeer::doSelectWithI18n($criteria, $this->getCulture(), $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(MaterialEventPeer::EVENT_ID, $this->getId());

				if (!isset($this->lastMaterialEventCriteria) || !$this->lastMaterialEventCriteria->equals($criteria)) {
					$this->collMaterialEvents = MaterialEventPeer::doSelectWithI18n($criteria, $this->getCulture(), $con);
				}
			}
		}
		$this->lastMaterialEventCriteria = $criteria;
		return $this->collMaterialEvents;
	}

	/**
	 * Temporary storage of collEventI18ns to save a possible db hit in
	 * the event objects are add to the collection, but the
	 * complete collection is never requested.
	 * @return     void
	 */
	public function initEventI18ns()
	{
		if ($this->collEventI18ns === null) {
			$this->collEventI18ns = array();
		}
	}

	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Event has previously
	 * been saved, it will retrieve related EventI18ns from storage.
	 * If this Event is new, it will return
	 * an empty collection or the current collection, the criteria
	 * is ignored on a new object.
	 *
	 * @param      Connection $con
	 * @param      Criteria $criteria
	 * @throws     PropelException
	 */
	public function getEventI18ns($criteria = null, $con = null)
	{
		// include the Peer class
		include_once 'lib/model/om/BaseEventI18nPeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collEventI18ns === null) {
			if ($this->isNew()) {
			   $this->collEventI18ns = array();
			} else {

				$criteria->add(EventI18nPeer::ID, $this->getId());

				EventI18nPeer::addSelectColumns($criteria);
				$this->collEventI18ns = EventI18nPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(EventI18nPeer::ID, $this->getId());

				EventI18nPeer::addSelectColumns($criteria);
				if (!isset($this->lastEventI18nCriteria) || !$this->lastEventI18nCriteria->equals($criteria)) {
					$this->collEventI18ns = EventI18nPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastEventI18nCriteria = $criteria;
		return $this->collEventI18ns;
	}

	/**
	 * Returns the number of related EventI18ns.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      Connection $con
	 * @throws     PropelException
	 */
	public function countEventI18ns($criteria = null, $distinct = false, $con = null)
	{
		// include the Peer class
		include_once 'lib/model/om/BaseEventI18nPeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		$criteria->add(EventI18nPeer::ID, $this->getId());

		return EventI18nPeer::doCount($criteria, $distinct, $con);
	}

	/**
	 * Method called to associate a EventI18n object to this object
	 * through the EventI18n foreign key attribute
	 *
	 * @param      EventI18n $l EventI18n
	 * @return     void
	 * @throws     PropelException
	 */
	public function addEventI18n(EventI18n $l)
	{
		$this->collEventI18ns[] = $l;
		$l->setEvent($this);
	}

	/**
	 * Temporary storage of collSessions to save a possible db hit in
	 * the event objects are add to the collection, but the
	 * complete collection is never requested.
	 * @return     void
	 */
	public function initSessions()
	{
		if ($this->collSessions === null) {
			$this->collSessions = array();
		}
	}

	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Event has previously
	 * been saved, it will retrieve related Sessions from storage.
	 * If this Event is new, it will return
	 * an empty collection or the current collection, the criteria
	 * is ignored on a new object.
	 *
	 * @param      Connection $con
	 * @param      Criteria $criteria
	 * @throws     PropelException
	 */
	public function getSessions($criteria = null, $con = null)
	{
		// include the Peer class
		include_once 'lib/model/om/BaseSessionPeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collSessions === null) {
			if ($this->isNew()) {
			   $this->collSessions = array();
			} else {

				$criteria->add(SessionPeer::EVENT_ID, $this->getId());

				SessionPeer::addSelectColumns($criteria);
				$this->collSessions = SessionPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(SessionPeer::EVENT_ID, $this->getId());

				SessionPeer::addSelectColumns($criteria);
				if (!isset($this->lastSessionCriteria) || !$this->lastSessionCriteria->equals($criteria)) {
					$this->collSessions = SessionPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastSessionCriteria = $criteria;
		return $this->collSessions;
	}

	/**
	 * Returns the number of related Sessions.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      Connection $con
	 * @throws     PropelException
	 */
	public function countSessions($criteria = null, $distinct = false, $con = null)
	{
		// include the Peer class
		include_once 'lib/model/om/BaseSessionPeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		$criteria->add(SessionPeer::EVENT_ID, $this->getId());

		return SessionPeer::doCount($criteria, $distinct, $con);
	}

	/**
	 * Method called to associate a Session object to this object
	 * through the Session foreign key attribute
	 *
	 * @param      Session $l Session
	 * @return     void
	 * @throws     PropelException
	 */
	public function addSession(Session $l)
	{
		$this->collSessions[] = $l;
		$l->setEvent($this);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Event is new, it will return
	 * an empty collection; or if this Event has previously
	 * been saved, it will retrieve related Sessions from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Event.
	 */
	public function getSessionsJoinDirect($criteria = null, $con = null)
	{
		// include the Peer class
		include_once 'lib/model/om/BaseSessionPeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collSessions === null) {
			if ($this->isNew()) {
				$this->collSessions = array();
			} else {

				$criteria->add(SessionPeer::EVENT_ID, $this->getId());

				$this->collSessions = SessionPeer::doSelectJoinDirect($criteria, $con);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(SessionPeer::EVENT_ID, $this->getId());

			if (!isset($this->lastSessionCriteria) || !$this->lastSessionCriteria->equals($criteria)) {
				$this->collSessions = SessionPeer::doSelectJoinDirect($criteria, $con);
			}
		}
		$this->lastSessionCriteria = $criteria;

		return $this->collSessions;
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
			if ($this->collPicEvents) {
				foreach ((array) $this->collPicEvents as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collMaterialEvents) {
				foreach ((array) $this->collMaterialEvents as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collEventI18ns) {
				foreach ((array) $this->collEventI18ns as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collSessions) {
				foreach ((array) $this->collSessions as $o) {
					$o->clearAllReferences($deep);
				}
			}
		} // if ($deep)

		$this->collPicEvents = null;
		$this->collMaterialEvents = null;
		$this->collEventI18ns = null;
		$this->collSessions = null;
		$this->aDirect = null;
		$this->aSerial = null;
	}

  public function getCulture()
  {
    return $this->culture;
  }

  public function setCulture($culture)
  {
    $this->culture = $culture;
  }

  public function getTitle()
  {
    $obj = $this->getCurrentEventI18n();

    return ($obj ? $obj->getTitle() : null);
  }

  public function setTitle($value)
  {
    $this->getCurrentEventI18n()->setTitle($value);
  }

  public function getDescription()
  {
    $obj = $this->getCurrentEventI18n();

    return ($obj ? $obj->getDescription() : null);
  }

  public function setDescription($value)
  {
    $this->getCurrentEventI18n()->setDescription($value);
  }

  protected $current_i18n = array();

  public function getCurrentEventI18n()
  {
    if (!isset($this->current_i18n[$this->culture]))
    {
      $obj = EventI18nPeer::retrieveByPK($this->getId(), $this->culture);
      if ($obj)
      {
        $this->setEventI18nForCulture($obj, $this->culture);
      }
      else
      {
        $this->setEventI18nForCulture(new EventI18n(), $this->culture);
        $this->current_i18n[$this->culture]->setCulture($this->culture);
      }
    }

    return $this->current_i18n[$this->culture];
  }

  public function setEventI18nForCulture($object, $culture)
  {
    $this->current_i18n[$culture] = $object;
    $this->addEventI18n($object);
  }


  public function __call($method, $arguments)
  {
    if (!$callable = sfMixer::getCallable('BaseEvent:'.$method))
    {
      throw new sfException(sprintf('Call to undefined method BaseEvent::%s', $method));
    }

    array_unshift($arguments, $this);

    return call_user_func_array($callable, $arguments);
  }


} // BaseEvent
