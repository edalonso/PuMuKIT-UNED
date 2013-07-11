<?php


/**
 * This class adds structure of 'virtual_ground_relation' table to 'propel' DatabaseMap object.
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
class VirtualGroundRelationMapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.map.VirtualGroundRelationMapBuilder';

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

		$tMap = $this->dbMap->addTable('virtual_ground_relation');
		$tMap->setPhpName('VirtualGroundRelation');

		$tMap->setUseIdGenerator(false);

		$tMap->addForeignPrimaryKey('VIRTUAL_GROUND_ID', 'VirtualGroundId', 'int' , CreoleTypes::INTEGER, 'virtual_ground', 'ID', true, null);

		$tMap->addForeignPrimaryKey('GROUND_ID', 'GroundId', 'int' , CreoleTypes::INTEGER, 'ground', 'ID', true, null);

		$tMap->addColumn('ENABLE', 'Enable', 'boolean', CreoleTypes::BOOLEAN, true, null);

	} // doBuild()

} // VirtualGroundRelationMapBuilder
