<?php

/**
 * Base static class for performing query and update operations on the 'uned_media_old' table.
 *
 * 
 *
 * @package    lib.model.om
 */
abstract class BaseUnedMediaOldPeer {

	/** the default database name for this class */
	const DATABASE_NAME = 'propel';

	/** the table name for this class */
	const TABLE_NAME = 'uned_media_old';

	/** A class that can be returned by this peer. */
	const CLASS_DEFAULT = 'lib.model.UnedMediaOld';

	/** The total number of columns. */
	const NUM_COLUMNS = 37;

	/** The number of lazy-loaded columns. */
	const NUM_LAZY_LOAD_COLUMNS = 0;


	/** the column name for the ID field */
	const ID = 'uned_media_old.ID';

	/** the column name for the ORIGINAL_ID field */
	const ORIGINAL_ID = 'uned_media_old.ORIGINAL_ID';

	/** the column name for the MM_ID field */
	const MM_ID = 'uned_media_old.MM_ID';

	/** the column name for the FECHA_DE_CREACION field */
	const FECHA_DE_CREACION = 'uned_media_old.FECHA_DE_CREACION';

	/** the column name for the FECHA_DE_ACTUALIZACION field */
	const FECHA_DE_ACTUALIZACION = 'uned_media_old.FECHA_DE_ACTUALIZACION';

	/** the column name for the TITULO field */
	const TITULO = 'uned_media_old.TITULO';

	/** the column name for the DESCRIPCION_CORTA field */
	const DESCRIPCION_CORTA = 'uned_media_old.DESCRIPCION_CORTA';

	/** the column name for the DESCRIPCION field */
	const DESCRIPCION = 'uned_media_old.DESCRIPCION';

	/** the column name for the ALT field */
	const ALT = 'uned_media_old.ALT';

	/** the column name for the PIE field */
	const PIE = 'uned_media_old.PIE';

	/** the column name for the ORIGEN field */
	const ORIGEN = 'uned_media_old.ORIGEN';

	/** the column name for the AUTOR field */
	const AUTOR = 'uned_media_old.AUTOR';

	/** the column name for the REALIZADOR field */
	const REALIZADOR = 'uned_media_old.REALIZADOR';

	/** the column name for the ANO field */
	const ANO = 'uned_media_old.ANO';

	/** the column name for the TITULO_ORIGINAL field */
	const TITULO_ORIGINAL = 'uned_media_old.TITULO_ORIGINAL';

	/** the column name for the REFERENCIA_A_LA_FUENTE field */
	const REFERENCIA_A_LA_FUENTE = 'uned_media_old.REFERENCIA_A_LA_FUENTE';

	/** the column name for the STREAMING field */
	const STREAMING = 'uned_media_old.STREAMING';

	/** the column name for the DENEGAR_DESCARGA field */
	const DENEGAR_DESCARGA = 'uned_media_old.DENEGAR_DESCARGA';

	/** the column name for the FECHA_INICIO field */
	const FECHA_INICIO = 'uned_media_old.FECHA_INICIO';

	/** the column name for the FECHA_FIN field */
	const FECHA_FIN = 'uned_media_old.FECHA_FIN';

	/** the column name for the CRITICO field */
	const CRITICO = 'uned_media_old.CRITICO';

	/** the column name for the DERECHOS field */
	const DERECHOS = 'uned_media_old.DERECHOS';

	/** the column name for the SUBTITULOS field */
	const SUBTITULOS = 'uned_media_old.SUBTITULOS';

	/** the column name for the AUTORIA field */
	const AUTORIA = 'uned_media_old.AUTORIA';

	/** the column name for the PRESET_ORIGINAL field */
	const PRESET_ORIGINAL = 'uned_media_old.PRESET_ORIGINAL';

	/** the column name for the PRESET_ALTA field */
	const PRESET_ALTA = 'uned_media_old.PRESET_ALTA';

	/** the column name for the PRESET_MEDIA field */
	const PRESET_MEDIA = 'uned_media_old.PRESET_MEDIA';

	/** the column name for the PRESET_BAJA field */
	const PRESET_BAJA = 'uned_media_old.PRESET_BAJA';

	/** the column name for the TEMATICAS field */
	const TEMATICAS = 'uned_media_old.TEMATICAS';

	/** the column name for the CATEGORIAS field */
	const CATEGORIAS = 'uned_media_old.CATEGORIAS';

	/** the column name for the DESTINOS_DE_PUBLICACION field */
	const DESTINOS_DE_PUBLICACION = 'uned_media_old.DESTINOS_DE_PUBLICACION';

	/** the column name for the THUMBS field */
	const THUMBS = 'uned_media_old.THUMBS';

	/** the column name for the TAGS field */
	const TAGS = 'uned_media_old.TAGS';

	/** the column name for the ENLACES field */
	const ENLACES = 'uned_media_old.ENLACES';

	/** the column name for the RELACIONADOS field */
	const RELACIONADOS = 'uned_media_old.RELACIONADOS';

	/** the column name for the DOCUMENTOS_ADJUNTOS field */
	const DOCUMENTOS_ADJUNTOS = 'uned_media_old.DOCUMENTOS_ADJUNTOS';

	/** the column name for the ESTADO field */
	const ESTADO = 'uned_media_old.ESTADO';

	/** The PHP to DB Name Mapping */
	private static $phpNameMap = null;


	/**
	 * holds an array of fieldnames
	 *
	 * first dimension keys are the type constants
	 * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
	 */
	private static $fieldNames = array (
		BasePeer::TYPE_PHPNAME => array ('Id', 'OriginalId', 'MmId', 'FechaDeCreacion', 'FechaDeActualizacion', 'Titulo', 'DescripcionCorta', 'Descripcion', 'Alt', 'Pie', 'Origen', 'Autor', 'Realizador', 'Ano', 'TituloOriginal', 'ReferenciaALaFuente', 'Streaming', 'DenegarDescarga', 'FechaInicio', 'FechaFin', 'Critico', 'Derechos', 'Subtitulos', 'Autoria', 'PresetOriginal', 'PresetAlta', 'PresetMedia', 'PresetBaja', 'Tematicas', 'Categorias', 'DestinosDePublicacion', 'Thumbs', 'Tags', 'Enlaces', 'Relacionados', 'DocumentosAdjuntos', 'Estado', ),
		BasePeer::TYPE_COLNAME => array (UnedMediaOldPeer::ID, UnedMediaOldPeer::ORIGINAL_ID, UnedMediaOldPeer::MM_ID, UnedMediaOldPeer::FECHA_DE_CREACION, UnedMediaOldPeer::FECHA_DE_ACTUALIZACION, UnedMediaOldPeer::TITULO, UnedMediaOldPeer::DESCRIPCION_CORTA, UnedMediaOldPeer::DESCRIPCION, UnedMediaOldPeer::ALT, UnedMediaOldPeer::PIE, UnedMediaOldPeer::ORIGEN, UnedMediaOldPeer::AUTOR, UnedMediaOldPeer::REALIZADOR, UnedMediaOldPeer::ANO, UnedMediaOldPeer::TITULO_ORIGINAL, UnedMediaOldPeer::REFERENCIA_A_LA_FUENTE, UnedMediaOldPeer::STREAMING, UnedMediaOldPeer::DENEGAR_DESCARGA, UnedMediaOldPeer::FECHA_INICIO, UnedMediaOldPeer::FECHA_FIN, UnedMediaOldPeer::CRITICO, UnedMediaOldPeer::DERECHOS, UnedMediaOldPeer::SUBTITULOS, UnedMediaOldPeer::AUTORIA, UnedMediaOldPeer::PRESET_ORIGINAL, UnedMediaOldPeer::PRESET_ALTA, UnedMediaOldPeer::PRESET_MEDIA, UnedMediaOldPeer::PRESET_BAJA, UnedMediaOldPeer::TEMATICAS, UnedMediaOldPeer::CATEGORIAS, UnedMediaOldPeer::DESTINOS_DE_PUBLICACION, UnedMediaOldPeer::THUMBS, UnedMediaOldPeer::TAGS, UnedMediaOldPeer::ENLACES, UnedMediaOldPeer::RELACIONADOS, UnedMediaOldPeer::DOCUMENTOS_ADJUNTOS, UnedMediaOldPeer::ESTADO, ),
		BasePeer::TYPE_FIELDNAME => array ('id', 'original_id', 'mm_id', 'fecha_de_creacion', 'fecha_de_actualizacion', 'titulo', 'descripcion_corta', 'descripcion', 'alt', 'pie', 'origen', 'autor', 'realizador', 'ano', 'titulo_original', 'referencia_a_la_fuente', 'streaming', 'denegar_descarga', 'fecha_inicio', 'fecha_fin', 'critico', 'derechos', 'subtitulos', 'autoria', 'preset_original', 'preset_alta', 'preset_media', 'preset_baja', 'tematicas', 'categorias', 'destinos_de_publicacion', 'thumbs', 'tags', 'enlaces', 'relacionados', 'documentos_adjuntos', 'estado', ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, )
	);

	/**
	 * holds an array of keys for quick access to the fieldnames array
	 *
	 * first dimension keys are the type constants
	 * e.g. self::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
	 */
	private static $fieldKeys = array (
		BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'OriginalId' => 1, 'MmId' => 2, 'FechaDeCreacion' => 3, 'FechaDeActualizacion' => 4, 'Titulo' => 5, 'DescripcionCorta' => 6, 'Descripcion' => 7, 'Alt' => 8, 'Pie' => 9, 'Origen' => 10, 'Autor' => 11, 'Realizador' => 12, 'Ano' => 13, 'TituloOriginal' => 14, 'ReferenciaALaFuente' => 15, 'Streaming' => 16, 'DenegarDescarga' => 17, 'FechaInicio' => 18, 'FechaFin' => 19, 'Critico' => 20, 'Derechos' => 21, 'Subtitulos' => 22, 'Autoria' => 23, 'PresetOriginal' => 24, 'PresetAlta' => 25, 'PresetMedia' => 26, 'PresetBaja' => 27, 'Tematicas' => 28, 'Categorias' => 29, 'DestinosDePublicacion' => 30, 'Thumbs' => 31, 'Tags' => 32, 'Enlaces' => 33, 'Relacionados' => 34, 'DocumentosAdjuntos' => 35, 'Estado' => 36, ),
		BasePeer::TYPE_COLNAME => array (UnedMediaOldPeer::ID => 0, UnedMediaOldPeer::ORIGINAL_ID => 1, UnedMediaOldPeer::MM_ID => 2, UnedMediaOldPeer::FECHA_DE_CREACION => 3, UnedMediaOldPeer::FECHA_DE_ACTUALIZACION => 4, UnedMediaOldPeer::TITULO => 5, UnedMediaOldPeer::DESCRIPCION_CORTA => 6, UnedMediaOldPeer::DESCRIPCION => 7, UnedMediaOldPeer::ALT => 8, UnedMediaOldPeer::PIE => 9, UnedMediaOldPeer::ORIGEN => 10, UnedMediaOldPeer::AUTOR => 11, UnedMediaOldPeer::REALIZADOR => 12, UnedMediaOldPeer::ANO => 13, UnedMediaOldPeer::TITULO_ORIGINAL => 14, UnedMediaOldPeer::REFERENCIA_A_LA_FUENTE => 15, UnedMediaOldPeer::STREAMING => 16, UnedMediaOldPeer::DENEGAR_DESCARGA => 17, UnedMediaOldPeer::FECHA_INICIO => 18, UnedMediaOldPeer::FECHA_FIN => 19, UnedMediaOldPeer::CRITICO => 20, UnedMediaOldPeer::DERECHOS => 21, UnedMediaOldPeer::SUBTITULOS => 22, UnedMediaOldPeer::AUTORIA => 23, UnedMediaOldPeer::PRESET_ORIGINAL => 24, UnedMediaOldPeer::PRESET_ALTA => 25, UnedMediaOldPeer::PRESET_MEDIA => 26, UnedMediaOldPeer::PRESET_BAJA => 27, UnedMediaOldPeer::TEMATICAS => 28, UnedMediaOldPeer::CATEGORIAS => 29, UnedMediaOldPeer::DESTINOS_DE_PUBLICACION => 30, UnedMediaOldPeer::THUMBS => 31, UnedMediaOldPeer::TAGS => 32, UnedMediaOldPeer::ENLACES => 33, UnedMediaOldPeer::RELACIONADOS => 34, UnedMediaOldPeer::DOCUMENTOS_ADJUNTOS => 35, UnedMediaOldPeer::ESTADO => 36, ),
		BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'original_id' => 1, 'mm_id' => 2, 'fecha_de_creacion' => 3, 'fecha_de_actualizacion' => 4, 'titulo' => 5, 'descripcion_corta' => 6, 'descripcion' => 7, 'alt' => 8, 'pie' => 9, 'origen' => 10, 'autor' => 11, 'realizador' => 12, 'ano' => 13, 'titulo_original' => 14, 'referencia_a_la_fuente' => 15, 'streaming' => 16, 'denegar_descarga' => 17, 'fecha_inicio' => 18, 'fecha_fin' => 19, 'critico' => 20, 'derechos' => 21, 'subtitulos' => 22, 'autoria' => 23, 'preset_original' => 24, 'preset_alta' => 25, 'preset_media' => 26, 'preset_baja' => 27, 'tematicas' => 28, 'categorias' => 29, 'destinos_de_publicacion' => 30, 'thumbs' => 31, 'tags' => 32, 'enlaces' => 33, 'relacionados' => 34, 'documentos_adjuntos' => 35, 'estado' => 36, ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, )
	);

	/**
	 * @return     MapBuilder the map builder for this peer
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function getMapBuilder()
	{
		include_once 'lib/model/map/UnedMediaOldMapBuilder.php';
		return BasePeer::getMapBuilder('lib.model.map.UnedMediaOldMapBuilder');
	}
	/**
	 * Gets a map (hash) of PHP names to DB column names.
	 *
	 * @return     array The PHP to DB name map for this peer
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 * @deprecated Use the getFieldNames() and translateFieldName() methods instead of this.
	 */
	public static function getPhpNameMap()
	{
		if (self::$phpNameMap === null) {
			$map = UnedMediaOldPeer::getTableMap();
			$columns = $map->getColumns();
			$nameMap = array();
			foreach ($columns as $column) {
				$nameMap[$column->getPhpName()] = $column->getColumnName();
			}
			self::$phpNameMap = $nameMap;
		}
		return self::$phpNameMap;
	}
	/**
	 * Translates a fieldname to another type
	 *
	 * @param      string $name field name
	 * @param      string $fromType One of the class type constants TYPE_PHPNAME,
	 *                         TYPE_COLNAME, TYPE_FIELDNAME, TYPE_NUM
	 * @param      string $toType   One of the class type constants
	 * @return     string translated name of the field.
	 */
	static public function translateFieldName($name, $fromType, $toType)
	{
		$toNames = self::getFieldNames($toType);
		$key = isset(self::$fieldKeys[$fromType][$name]) ? self::$fieldKeys[$fromType][$name] : null;
		if ($key === null) {
			throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(self::$fieldKeys[$fromType], true));
		}
		return $toNames[$key];
	}

	/**
	 * Returns an array of of field names.
	 *
	 * @param      string $type The type of fieldnames to return:
	 *                      One of the class type constants TYPE_PHPNAME,
	 *                      TYPE_COLNAME, TYPE_FIELDNAME, TYPE_NUM
	 * @return     array A list of field names
	 */

	static public function getFieldNames($type = BasePeer::TYPE_PHPNAME)
	{
		if (!array_key_exists($type, self::$fieldNames)) {
			throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants TYPE_PHPNAME, TYPE_COLNAME, TYPE_FIELDNAME, TYPE_NUM. ' . $type . ' was given.');
		}
		return self::$fieldNames[$type];
	}

	/**
	 * Convenience method which changes table.column to alias.column.
	 *
	 * Using this method you can maintain SQL abstraction while using column aliases.
	 * <code>
	 *		$c->addAlias("alias1", TablePeer::TABLE_NAME);
	 *		$c->addJoin(TablePeer::alias("alias1", TablePeer::PRIMARY_KEY_COLUMN), TablePeer::PRIMARY_KEY_COLUMN);
	 * </code>
	 * @param      string $alias The alias for the current table.
	 * @param      string $column The column name for current table. (i.e. UnedMediaOldPeer::COLUMN_NAME).
	 * @return     string
	 */
	public static function alias($alias, $column)
	{
		return str_replace(UnedMediaOldPeer::TABLE_NAME.'.', $alias.'.', $column);
	}

	/**
	 * Add all the columns needed to create a new object.
	 *
	 * Note: any columns that were marked with lazyLoad="true" in the
	 * XML schema will not be added to the select list and only loaded
	 * on demand.
	 *
	 * @param      criteria object containing the columns to add.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function addSelectColumns(Criteria $criteria)
	{

		$criteria->addSelectColumn(UnedMediaOldPeer::ID);

		$criteria->addSelectColumn(UnedMediaOldPeer::ORIGINAL_ID);

		$criteria->addSelectColumn(UnedMediaOldPeer::MM_ID);

		$criteria->addSelectColumn(UnedMediaOldPeer::FECHA_DE_CREACION);

		$criteria->addSelectColumn(UnedMediaOldPeer::FECHA_DE_ACTUALIZACION);

		$criteria->addSelectColumn(UnedMediaOldPeer::TITULO);

		$criteria->addSelectColumn(UnedMediaOldPeer::DESCRIPCION_CORTA);

		$criteria->addSelectColumn(UnedMediaOldPeer::DESCRIPCION);

		$criteria->addSelectColumn(UnedMediaOldPeer::ALT);

		$criteria->addSelectColumn(UnedMediaOldPeer::PIE);

		$criteria->addSelectColumn(UnedMediaOldPeer::ORIGEN);

		$criteria->addSelectColumn(UnedMediaOldPeer::AUTOR);

		$criteria->addSelectColumn(UnedMediaOldPeer::REALIZADOR);

		$criteria->addSelectColumn(UnedMediaOldPeer::ANO);

		$criteria->addSelectColumn(UnedMediaOldPeer::TITULO_ORIGINAL);

		$criteria->addSelectColumn(UnedMediaOldPeer::REFERENCIA_A_LA_FUENTE);

		$criteria->addSelectColumn(UnedMediaOldPeer::STREAMING);

		$criteria->addSelectColumn(UnedMediaOldPeer::DENEGAR_DESCARGA);

		$criteria->addSelectColumn(UnedMediaOldPeer::FECHA_INICIO);

		$criteria->addSelectColumn(UnedMediaOldPeer::FECHA_FIN);

		$criteria->addSelectColumn(UnedMediaOldPeer::CRITICO);

		$criteria->addSelectColumn(UnedMediaOldPeer::DERECHOS);

		$criteria->addSelectColumn(UnedMediaOldPeer::SUBTITULOS);

		$criteria->addSelectColumn(UnedMediaOldPeer::AUTORIA);

		$criteria->addSelectColumn(UnedMediaOldPeer::PRESET_ORIGINAL);

		$criteria->addSelectColumn(UnedMediaOldPeer::PRESET_ALTA);

		$criteria->addSelectColumn(UnedMediaOldPeer::PRESET_MEDIA);

		$criteria->addSelectColumn(UnedMediaOldPeer::PRESET_BAJA);

		$criteria->addSelectColumn(UnedMediaOldPeer::TEMATICAS);

		$criteria->addSelectColumn(UnedMediaOldPeer::CATEGORIAS);

		$criteria->addSelectColumn(UnedMediaOldPeer::DESTINOS_DE_PUBLICACION);

		$criteria->addSelectColumn(UnedMediaOldPeer::THUMBS);

		$criteria->addSelectColumn(UnedMediaOldPeer::TAGS);

		$criteria->addSelectColumn(UnedMediaOldPeer::ENLACES);

		$criteria->addSelectColumn(UnedMediaOldPeer::RELACIONADOS);

		$criteria->addSelectColumn(UnedMediaOldPeer::DOCUMENTOS_ADJUNTOS);

		$criteria->addSelectColumn(UnedMediaOldPeer::ESTADO);

	}

	const COUNT = 'COUNT(uned_media_old.ID)';
	const COUNT_DISTINCT = 'COUNT(DISTINCT uned_media_old.ID)';

	/**
	 * Returns the number of rows matching criteria.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct Whether to select only distinct columns (You can also set DISTINCT modifier in Criteria).
	 * @param      Connection $con
	 * @return     int Number of matching rows.
	 */
	public static function doCount(Criteria $criteria, $distinct = false, $con = null)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// clear out anything that might confuse the ORDER BY clause
		$criteria->clearSelectColumns()->clearOrderByColumns();
		if ($distinct || in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->addSelectColumn(UnedMediaOldPeer::COUNT_DISTINCT);
		} else {
			$criteria->addSelectColumn(UnedMediaOldPeer::COUNT);
		}

		// just in case we're grouping: add those columns to the select statement
		foreach($criteria->getGroupByColumns() as $column)
		{
			$criteria->addSelectColumn($column);
		}

		$rs = UnedMediaOldPeer::doSelectRS($criteria, $con);
		if ($rs->next()) {
			return $rs->getInt(1);
		} else {
			// no rows returned; we infer that means 0 matches.
			return 0;
		}
	}
	/**
	 * Method to select one object from the DB.
	 *
	 * @param      Criteria $criteria object used to create the SELECT statement.
	 * @param      Connection $con
	 * @return     UnedMediaOld
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectOne(Criteria $criteria, $con = null)
	{
		$critcopy = clone $criteria;
		$critcopy->setLimit(1);
		$objects = UnedMediaOldPeer::doSelect($critcopy, $con);
		if ($objects) {
			return $objects[0];
		}
		return null;
	}
	/**
	 * Method to do selects.
	 *
	 * @param      Criteria $criteria The Criteria object used to build the SELECT statement.
	 * @param      Connection $con
	 * @return     array Array of selected Objects
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelect(Criteria $criteria, $con = null)
	{
		return UnedMediaOldPeer::populateObjects(UnedMediaOldPeer::doSelectRS($criteria, $con));
	}
	/**
	 * Prepares the Criteria object and uses the parent doSelect()
	 * method to get a ResultSet.
	 *
	 * Use this method directly if you want to just get the resultset
	 * (instead of an array of objects).
	 *
	 * @param      Criteria $criteria The Criteria object used to build the SELECT statement.
	 * @param      Connection $con the connection to use
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 * @return     ResultSet The resultset object with numerically-indexed fields.
	 * @see        BasePeer::doSelect()
	 */
	public static function doSelectRS(Criteria $criteria, $con = null)
	{

    foreach (sfMixer::getCallables('BaseUnedMediaOldPeer:addDoSelectRS:addDoSelectRS') as $callable)
    {
      call_user_func($callable, 'BaseUnedMediaOldPeer', $criteria, $con);
    }


		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}

		if (!$criteria->getSelectColumns()) {
			$criteria = clone $criteria;
			UnedMediaOldPeer::addSelectColumns($criteria);
		}

		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		// BasePeer returns a Creole ResultSet, set to return
		// rows indexed numerically.
		return BasePeer::doSelect($criteria, $con);
	}
	/**
	 * The returned array will contain objects of the default type or
	 * objects that inherit from the default.
	 *
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function populateObjects(ResultSet $rs)
	{
		$results = array();
	
		// set the class once to avoid overhead in the loop
		$cls = UnedMediaOldPeer::getOMClass();
		$cls = Propel::import($cls);
		// populate the object(s)
		while($rs->next()) {
		
			$obj = new $cls();
			$obj->hydrate($rs);
			$results[] = $obj;
			
		}
		return $results;
	}

	/**
	 * Returns the number of rows matching criteria, joining the related Mm table
	 *
	 * @param      Criteria $c
	 * @param      boolean $distinct Whether to select only distinct columns (You can also set DISTINCT modifier in Criteria).
	 * @param      Connection $con
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinMm(Criteria $criteria, $distinct = false, $con = null)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// clear out anything that might confuse the ORDER BY clause
		$criteria->clearSelectColumns()->clearOrderByColumns();
		if ($distinct || in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->addSelectColumn(UnedMediaOldPeer::COUNT_DISTINCT);
		} else {
			$criteria->addSelectColumn(UnedMediaOldPeer::COUNT);
		}

		// just in case we're grouping: add those columns to the select statement
		foreach($criteria->getGroupByColumns() as $column)
		{
			$criteria->addSelectColumn($column);
		}

		$criteria->addJoin(UnedMediaOldPeer::MM_ID, MmPeer::ID);

		$rs = UnedMediaOldPeer::doSelectRS($criteria, $con);
		if ($rs->next()) {
			return $rs->getInt(1);
		} else {
			// no rows returned; we infer that means 0 matches.
			return 0;
		}
	}


	/**
	 * Selects a collection of UnedMediaOld objects pre-filled with their Mm objects.
	 *
	 * @return     array Array of UnedMediaOld objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinMm(Criteria $c, $con = null)
	{
		$c = clone $c;

		// Set the correct dbName if it has not been overridden
		if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		UnedMediaOldPeer::addSelectColumns($c);
		$startcol = (UnedMediaOldPeer::NUM_COLUMNS - UnedMediaOldPeer::NUM_LAZY_LOAD_COLUMNS) + 1;
		MmPeer::addSelectColumns($c);

		$c->addJoin(UnedMediaOldPeer::MM_ID, MmPeer::ID);
		$rs = BasePeer::doSelect($c, $con);
		$results = array();

		while($rs->next()) {

			$omClass = UnedMediaOldPeer::getOMClass();

			$cls = Propel::import($omClass);
			$obj1 = new $cls();
			$obj1->hydrate($rs);

			$omClass = MmPeer::getOMClass();

			$cls = Propel::import($omClass);
			$obj2 = new $cls();
			$obj2->hydrate($rs, $startcol);

			$newObject = true;
			foreach($results as $temp_obj1) {
				$temp_obj2 = $temp_obj1->getMm(); //CHECKME
				if ($temp_obj2->getPrimaryKey() === $obj2->getPrimaryKey()) {
					$newObject = false;
					// e.g. $author->addBookRelatedByBookId()
					$temp_obj2->addUnedMediaOld($obj1); //CHECKME
					break;
				}
			}
			if ($newObject) {
				$obj2->initUnedMediaOlds();
				$obj2->addUnedMediaOld($obj1); //CHECKME
			}
			$results[] = $obj1;
		}
		return $results;
	}


	/**
	 * Returns the number of rows matching criteria, joining all related tables
	 *
	 * @param      Criteria $c
	 * @param      boolean $distinct Whether to select only distinct columns (You can also set DISTINCT modifier in Criteria).
	 * @param      Connection $con
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinAll(Criteria $criteria, $distinct = false, $con = null)
	{
		$criteria = clone $criteria;

		// clear out anything that might confuse the ORDER BY clause
		$criteria->clearSelectColumns()->clearOrderByColumns();
		if ($distinct || in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->addSelectColumn(UnedMediaOldPeer::COUNT_DISTINCT);
		} else {
			$criteria->addSelectColumn(UnedMediaOldPeer::COUNT);
		}

		// just in case we're grouping: add those columns to the select statement
		foreach($criteria->getGroupByColumns() as $column)
		{
			$criteria->addSelectColumn($column);
		}

		$criteria->addJoin(UnedMediaOldPeer::MM_ID, MmPeer::ID);

		$rs = UnedMediaOldPeer::doSelectRS($criteria, $con);
		if ($rs->next()) {
			return $rs->getInt(1);
		} else {
			// no rows returned; we infer that means 0 matches.
			return 0;
		}
	}


	/**
	 * Selects a collection of UnedMediaOld objects pre-filled with all related objects.
	 *
	 * @return     array Array of UnedMediaOld objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinAll(Criteria $c, $con = null)
	{
		$c = clone $c;

		// Set the correct dbName if it has not been overridden
		if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		UnedMediaOldPeer::addSelectColumns($c);
		$startcol2 = (UnedMediaOldPeer::NUM_COLUMNS - UnedMediaOldPeer::NUM_LAZY_LOAD_COLUMNS) + 1;

		MmPeer::addSelectColumns($c);
		$startcol3 = $startcol2 + MmPeer::NUM_COLUMNS;

		$c->addJoin(UnedMediaOldPeer::MM_ID, MmPeer::ID);

		$rs = BasePeer::doSelect($c, $con);
		$results = array();

		while($rs->next()) {

			$omClass = UnedMediaOldPeer::getOMClass();


			$cls = Propel::import($omClass);
			$obj1 = new $cls();
			$obj1->hydrate($rs);


				// Add objects for joined Mm rows
	
			$omClass = MmPeer::getOMClass();


			$cls = Propel::import($omClass);
			$obj2 = new $cls();
			$obj2->hydrate($rs, $startcol2);

			$newObject = true;
			for ($j=0, $resCount=count($results); $j < $resCount; $j++) {
				$temp_obj1 = $results[$j];
				$temp_obj2 = $temp_obj1->getMm(); // CHECKME
				if ($temp_obj2->getPrimaryKey() === $obj2->getPrimaryKey()) {
					$newObject = false;
					$temp_obj2->addUnedMediaOld($obj1); // CHECKME
					break;
				}
			}

			if ($newObject) {
				$obj2->initUnedMediaOlds();
				$obj2->addUnedMediaOld($obj1);
			}

			$results[] = $obj1;
		}
		return $results;
	}

	/**
	 * Returns the TableMap related to this peer.
	 * This method is not needed for general use but a specific application could have a need.
	 * @return     TableMap
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function getTableMap()
	{
		return Propel::getDatabaseMap(self::DATABASE_NAME)->getTable(self::TABLE_NAME);
	}

	/**
	 * The class that the Peer will make instances of.
	 *
	 * This uses a dot-path notation which is tranalted into a path
	 * relative to a location on the PHP include_path.
	 * (e.g. path.to.MyClass -> 'path/to/MyClass.php')
	 *
	 * @return     string path.to.ClassName
	 */
	public static function getOMClass()
	{
		return UnedMediaOldPeer::CLASS_DEFAULT;
	}

	/**
	 * Method perform an INSERT on the database, given a UnedMediaOld or Criteria object.
	 *
	 * @param      mixed $values Criteria or UnedMediaOld object containing data that is used to create the INSERT statement.
	 * @param      Connection $con the connection to use
	 * @return     mixed The new primary key.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doInsert($values, $con = null)
	{

    foreach (sfMixer::getCallables('BaseUnedMediaOldPeer:doInsert:pre') as $callable)
    {
      $ret = call_user_func($callable, 'BaseUnedMediaOldPeer', $values, $con);
      if (false !== $ret)
      {
        return $ret;
      }
    }


		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}

		if ($values instanceof Criteria) {
			$criteria = clone $values; // rename for clarity
		} else {
			$criteria = $values->buildCriteria(); // build Criteria from UnedMediaOld object
		}

		$criteria->remove(UnedMediaOldPeer::ID); // remove pkey col since this table uses auto-increment


		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		try {
			// use transaction because $criteria could contain info
			// for more than one table (I guess, conceivably)
			$con->begin();
			$pk = BasePeer::doInsert($criteria, $con);
			$con->commit();
		} catch(PropelException $e) {
			$con->rollback();
			throw $e;
		}

		
    foreach (sfMixer::getCallables('BaseUnedMediaOldPeer:doInsert:post') as $callable)
    {
      call_user_func($callable, 'BaseUnedMediaOldPeer', $values, $con, $pk);
    }

    return $pk;
	}

	/**
	 * Method perform an UPDATE on the database, given a UnedMediaOld or Criteria object.
	 *
	 * @param      mixed $values Criteria or UnedMediaOld object containing data that is used to create the UPDATE statement.
	 * @param      Connection $con The connection to use (specify Connection object to exert more control over transactions).
	 * @return     int The number of affected rows (if supported by underlying database driver).
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doUpdate($values, $con = null)
	{

    foreach (sfMixer::getCallables('BaseUnedMediaOldPeer:doUpdate:pre') as $callable)
    {
      $ret = call_user_func($callable, 'BaseUnedMediaOldPeer', $values, $con);
      if (false !== $ret)
      {
        return $ret;
      }
    }


		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}

		$selectCriteria = new Criteria(self::DATABASE_NAME);

		if ($values instanceof Criteria) {
			$criteria = clone $values; // rename for clarity

			$comparison = $criteria->getComparison(UnedMediaOldPeer::ID);
			$selectCriteria->add(UnedMediaOldPeer::ID, $criteria->remove(UnedMediaOldPeer::ID), $comparison);

		} else { // $values is UnedMediaOld object
			$criteria = $values->buildCriteria(); // gets full criteria
			$selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
		}

		// set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		$ret = BasePeer::doUpdate($selectCriteria, $criteria, $con);
	

    foreach (sfMixer::getCallables('BaseUnedMediaOldPeer:doUpdate:post') as $callable)
    {
      call_user_func($callable, 'BaseUnedMediaOldPeer', $values, $con, $ret);
    }

    return $ret;
  }

	/**
	 * Method to DELETE all rows from the uned_media_old table.
	 *
	 * @return     int The number of affected rows (if supported by underlying database driver).
	 */
	public static function doDeleteAll($con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}
		$affectedRows = 0; // initialize var to track total num of affected rows
		try {
			// use transaction because $criteria could contain info
			// for more than one table or we could emulating ON DELETE CASCADE, etc.
			$con->begin();
			$affectedRows += BasePeer::doDeleteAll(UnedMediaOldPeer::TABLE_NAME, $con);
			$con->commit();
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollback();
			throw $e;
		}
	}

	/**
	 * Method perform a DELETE on the database, given a UnedMediaOld or Criteria object OR a primary key value.
	 *
	 * @param      mixed $values Criteria or UnedMediaOld object or primary key or array of primary keys
	 *              which is used to create the DELETE statement
	 * @param      Connection $con the connection to use
	 * @return     int 	The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
	 *				if supported by native driver or if emulated using Propel.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	 public static function doDelete($values, $con = null)
	 {
		if ($con === null) {
			$con = Propel::getConnection(UnedMediaOldPeer::DATABASE_NAME);
		}

		if ($values instanceof Criteria) {
			$criteria = clone $values; // rename for clarity
		} elseif ($values instanceof UnedMediaOld) {

			$criteria = $values->buildPkeyCriteria();
		} else {
			// it must be the primary key
			$criteria = new Criteria(self::DATABASE_NAME);
			$criteria->add(UnedMediaOldPeer::ID, (array) $values, Criteria::IN);
		}

		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		$affectedRows = 0; // initialize var to track total num of affected rows

		try {
			// use transaction because $criteria could contain info
			// for more than one table or we could emulating ON DELETE CASCADE, etc.
			$con->begin();
			
			$affectedRows += BasePeer::doDelete($criteria, $con);
			$con->commit();
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollback();
			throw $e;
		}
	}

	/**
	 * Validates all modified columns of given UnedMediaOld object.
	 * If parameter $columns is either a single column name or an array of column names
	 * than only those columns are validated.
	 *
	 * NOTICE: This does not apply to primary or foreign keys for now.
	 *
	 * @param      UnedMediaOld $obj The object to validate.
	 * @param      mixed $cols Column name or array of column names.
	 *
	 * @return     mixed TRUE if all columns are valid or the error message of the first invalid column.
	 */
	public static function doValidate(UnedMediaOld $obj, $cols = null)
	{
		$columns = array();

		if ($cols) {
			$dbMap = Propel::getDatabaseMap(UnedMediaOldPeer::DATABASE_NAME);
			$tableMap = $dbMap->getTable(UnedMediaOldPeer::TABLE_NAME);

			if (! is_array($cols)) {
				$cols = array($cols);
			}

			foreach($cols as $colName) {
				if ($tableMap->containsColumn($colName)) {
					$get = 'get' . $tableMap->getColumn($colName)->getPhpName();
					$columns[$colName] = $obj->$get();
				}
			}
		} else {

		}

		$res =  BasePeer::doValidate(UnedMediaOldPeer::DATABASE_NAME, UnedMediaOldPeer::TABLE_NAME, $columns);
    if ($res !== true) {
        $request = sfContext::getInstance()->getRequest();
        foreach ($res as $failed) {
            $col = UnedMediaOldPeer::translateFieldname($failed->getColumn(), BasePeer::TYPE_COLNAME, BasePeer::TYPE_PHPNAME);
            $request->setError($col, $failed->getMessage());
        }
    }

    return $res;
	}

	/**
	 * Retrieve a single object by pkey.
	 *
	 * @param      mixed $pk the primary key.
	 * @param      Connection $con the connection to use
	 * @return     UnedMediaOld
	 */
	public static function retrieveByPK($pk, $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}

		$criteria = new Criteria(UnedMediaOldPeer::DATABASE_NAME);

		$criteria->add(UnedMediaOldPeer::ID, $pk);


		$v = UnedMediaOldPeer::doSelect($criteria, $con);

		return !empty($v) > 0 ? $v[0] : null;
	}

	/**
	 * Retrieve multiple objects by pkey.
	 *
	 * @param      array $pks List of primary keys
	 * @param      Connection $con the connection to use
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function retrieveByPKs($pks, $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}

		$objs = null;
		if (empty($pks)) {
			$objs = array();
		} else {
			$criteria = new Criteria();
			$criteria->add(UnedMediaOldPeer::ID, $pks, Criteria::IN);
			$objs = UnedMediaOldPeer::doSelect($criteria, $con);
		}
		return $objs;
	}

} // BaseUnedMediaOldPeer

// static code to register the map builder for this Peer with the main Propel class
if (Propel::isInit()) {
	// the MapBuilder classes register themselves with Propel during initialization
	// so we need to load them here.
	try {
		BaseUnedMediaOldPeer::getMapBuilder();
	} catch (Exception $e) {
		Propel::log('Could not initialize Peer: ' . $e->getMessage(), Propel::LOG_ERR);
	}
} else {
	// even if Propel is not yet initialized, the map builder class can be registered
	// now and then it will be loaded when Propel initializes.
	require_once 'lib/model/map/UnedMediaOldMapBuilder.php';
	Propel::registerMapBuilder('lib.model.map.UnedMediaOldMapBuilder');
}
