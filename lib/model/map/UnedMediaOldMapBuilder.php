<?php


/**
 * This class adds structure of 'uned_media_old' table to 'propel' DatabaseMap object.
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
class UnedMediaOldMapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.map.UnedMediaOldMapBuilder';

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

		$tMap = $this->dbMap->addTable('uned_media_old');
		$tMap->setPhpName('UnedMediaOld');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'int', CreoleTypes::INTEGER, true, null);

		$tMap->addColumn('ORIGINAL_ID', 'OriginalId', 'int', CreoleTypes::INTEGER, true, null);

		$tMap->addForeignKey('MM_ID', 'MmId', 'int', CreoleTypes::INTEGER, 'mm', 'ID', false, null);

		$tMap->addColumn('FECHA_DE_CREACION', 'FechaDeCreacion', 'int', CreoleTypes::TIMESTAMP, true, null);

		$tMap->addColumn('FECHA_DE_ACTUALIZACION', 'FechaDeActualizacion', 'int', CreoleTypes::TIMESTAMP, true, null);

		$tMap->addColumn('TITULO', 'Titulo', 'string', CreoleTypes::VARCHAR, true, 255);

		$tMap->addColumn('DESCRIPCION_CORTA', 'DescripcionCorta', 'string', CreoleTypes::VARCHAR, true, 255);

		$tMap->addColumn('DESCRIPCION', 'Descripcion', 'string', CreoleTypes::LONGVARCHAR, false, null);

		$tMap->addColumn('ALT', 'Alt', 'string', CreoleTypes::VARCHAR, true, 255);

		$tMap->addColumn('PIE', 'Pie', 'string', CreoleTypes::VARCHAR, true, 255);

		$tMap->addColumn('ORIGEN', 'Origen', 'string', CreoleTypes::VARCHAR, true, 255);

		$tMap->addColumn('AUTOR', 'Autor', 'string', CreoleTypes::VARCHAR, true, 255);

		$tMap->addColumn('REALIZADOR', 'Realizador', 'string', CreoleTypes::VARCHAR, true, 255);

		$tMap->addColumn('ANO', 'Ano', 'int', CreoleTypes::TIMESTAMP, true, null);

		$tMap->addColumn('TITULO_ORIGINAL', 'TituloOriginal', 'string', CreoleTypes::VARCHAR, true, 255);

		$tMap->addColumn('REFERENCIA_A_LA_FUENTE', 'ReferenciaALaFuente', 'string', CreoleTypes::VARCHAR, true, 255);

		$tMap->addColumn('STREAMING', 'Streaming', 'string', CreoleTypes::VARCHAR, true, 255);

		$tMap->addColumn('DENEGAR_DESCARGA', 'DenegarDescarga', 'string', CreoleTypes::VARCHAR, true, 255);

		$tMap->addColumn('FECHA_INICIO', 'FechaInicio', 'int', CreoleTypes::TIMESTAMP, true, null);

		$tMap->addColumn('FECHA_FIN', 'FechaFin', 'int', CreoleTypes::TIMESTAMP, true, null);

		$tMap->addColumn('CRITICO', 'Critico', 'string', CreoleTypes::VARCHAR, true, 255);

		$tMap->addColumn('DERECHOS', 'Derechos', 'string', CreoleTypes::VARCHAR, true, 255);

		$tMap->addColumn('SUBTITULOS', 'Subtitulos', 'string', CreoleTypes::VARCHAR, true, 255);

		$tMap->addColumn('AUTORIA', 'Autoria', 'string', CreoleTypes::VARCHAR, true, 255);

		$tMap->addColumn('PRESET_ORIGINAL', 'PresetOriginal', 'string', CreoleTypes::VARCHAR, true, 255);

		$tMap->addColumn('PRESET_ALTA', 'PresetAlta', 'string', CreoleTypes::VARCHAR, true, 255);

		$tMap->addColumn('PRESET_MEDIA', 'PresetMedia', 'string', CreoleTypes::VARCHAR, true, 255);

		$tMap->addColumn('PRESET_BAJA', 'PresetBaja', 'string', CreoleTypes::VARCHAR, true, 255);

		$tMap->addColumn('TEMATICAS', 'Tematicas', 'string', CreoleTypes::VARCHAR, true, 255);

		$tMap->addColumn('CATEGORIAS', 'Categorias', 'string', CreoleTypes::VARCHAR, true, 255);

		$tMap->addColumn('DESTINOS_DE_PUBLICACION', 'DestinosDePublicacion', 'string', CreoleTypes::VARCHAR, true, 255);

		$tMap->addColumn('THUMBS', 'Thumbs', 'string', CreoleTypes::VARCHAR, true, 255);

		$tMap->addColumn('TAGS', 'Tags', 'string', CreoleTypes::VARCHAR, true, 255);

		$tMap->addColumn('ENLACES', 'Enlaces', 'string', CreoleTypes::LONGVARCHAR, true, null);

		$tMap->addColumn('RELACIONADOS', 'Relacionados', 'string', CreoleTypes::VARCHAR, true, 255);

		$tMap->addColumn('DOCUMENTOS_ADJUNTOS', 'DocumentosAdjuntos', 'string', CreoleTypes::LONGVARCHAR, true, null);

		$tMap->addColumn('ESTADO', 'Estado', 'string', CreoleTypes::VARCHAR, true, 255);

	} // doBuild()

} // UnedMediaOldMapBuilder
