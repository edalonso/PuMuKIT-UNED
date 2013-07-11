<?php


/**
 * This class adds structure of 'relation_category' table to 'propel' DatabaseMap object.
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
class RelationCategoryMapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.map.RelationCategoryMapBuilder';

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

		$tMap = $this->dbMap->addTable('relation_category');
		$tMap->setPhpName('RelationCategory');

		$tMap->setUseIdGenerator(false);

		$tMap->addForeignPrimaryKey('ONE_ID', 'OneId', 'int' , CreoleTypes::INTEGER, 'category', 'ID', true, null);

		$tMap->addForeignPrimaryKey('TWO_ID', 'TwoId', 'int' , CreoleTypes::INTEGER, 'category', 'ID', true, null);

		$tMap->addColumn('RECOMMENDED', 'Recommended', 'boolean', CreoleTypes::BOOLEAN, true, null);

	} // doBuild()

} // RelationCategoryMapBuilder