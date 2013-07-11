<?php


/**
 * This class adds structure of 'event' table to 'propel' DatabaseMap object.
 *
 *
 *
 * These statically-built map classes are used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    lib.model.map
 */
class EventMapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.map.EventMapBuilder';

	/**
	 * The database map.
	 */
	private $dbMap;

	/**
	 * Tells us if this DatabaseMapBuilder is built so that we
	 * don't have to re-build it every time.
	 *
	 * @return     boolean true if this DatabaseMapBuilder is built, false otherwise.
	 */
	public function isBuilt()
	{
		return ($this->dbMap !== null);
	}

	/**
	 * Gets the databasemap this map builder built.
	 *
	 * @return     the databasemap
	 */
	public function getDatabaseMap()
	{
		return $this->dbMap;
	}

	/**
	 * The doBuild() method builds the DatabaseMap
	 *
	 * @return     void
	 * @throws     PropelException
	 */
	public function doBuild()
	{
		$this->dbMap = Propel::getDatabaseMap('propel');

		$tMap = $this->dbMap->addTable('event');
		$tMap->setPhpName('Event');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'int', CreoleTypes::INTEGER, true, null);

		$tMap->addForeignKey('DIRECT_ID', 'DirectId', 'int', CreoleTypes::INTEGER, 'direct', 'ID', false, null);

		$tMap->addForeignKey('SERIAL_ID', 'SerialId', 'int', CreoleTypes::INTEGER, 'serial', 'ID', false, null);

		$tMap->addColumn('DATE', 'Date', 'int', CreoleTypes::TIMESTAMP, true, null);

		$tMap->addColumn('DISPLAY', 'Display', 'boolean', CreoleTypes::BOOLEAN, true, null);

		$tMap->addColumn('CREATE_SERIAL', 'CreateSerial', 'boolean', CreoleTypes::BOOLEAN, true, null);

		$tMap->addColumn('ENABLE_QUERY', 'EnableQuery', 'boolean', CreoleTypes::BOOLEAN, false, null);

		$tMap->addColumn('EMAIL_QUERY', 'EmailQuery', 'string', CreoleTypes::VARCHAR, false, 100);

		$tMap->addColumn('AUTHOR', 'Author', 'string', CreoleTypes::VARCHAR, false, 250);

		$tMap->addColumn('PRODUCER', 'Producer', 'string', CreoleTypes::VARCHAR, false, 250);

		$tMap->addColumn('EXTERNAL', 'External', 'boolean', CreoleTypes::BOOLEAN, true, null);

		$tMap->addColumn('URL', 'Url', 'string', CreoleTypes::VARCHAR, false, 250);

		$tMap->addColumn('SECURED', 'Secured', 'boolean', CreoleTypes::BOOLEAN, true, null);

		$tMap->addColumn('PASSWORD', 'Password', 'string', CreoleTypes::VARCHAR, false, 250);

	} // doBuild()

} // EventMapBuilder
