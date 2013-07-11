<?php

/**
 * Base class that represents a row from the 'uned_media_old' table.
 *
 * 
 *
 * @package    lib.model.om
 */
abstract class BaseUnedMediaOld extends BaseObject  implements Persistent {


	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        UnedMediaOldPeer
	 */
	protected static $peer;


	/**
	 * The value for the id field.
	 * @var        int
	 */
	protected $id;


	/**
	 * The value for the original_id field.
	 * @var        int
	 */
	protected $original_id;


	/**
	 * The value for the mm_id field.
	 * @var        int
	 */
	protected $mm_id;


	/**
	 * The value for the fecha_de_creacion field.
	 * @var        int
	 */
	protected $fecha_de_creacion;


	/**
	 * The value for the fecha_de_actualizacion field.
	 * @var        int
	 */
	protected $fecha_de_actualizacion;


	/**
	 * The value for the titulo field.
	 * @var        string
	 */
	protected $titulo;


	/**
	 * The value for the descripcion_corta field.
	 * @var        string
	 */
	protected $descripcion_corta;


	/**
	 * The value for the descripcion field.
	 * @var        string
	 */
	protected $descripcion;


	/**
	 * The value for the alt field.
	 * @var        string
	 */
	protected $alt;


	/**
	 * The value for the pie field.
	 * @var        string
	 */
	protected $pie;


	/**
	 * The value for the origen field.
	 * @var        string
	 */
	protected $origen;


	/**
	 * The value for the autor field.
	 * @var        string
	 */
	protected $autor;


	/**
	 * The value for the realizador field.
	 * @var        string
	 */
	protected $realizador;


	/**
	 * The value for the ano field.
	 * @var        int
	 */
	protected $ano;


	/**
	 * The value for the titulo_original field.
	 * @var        string
	 */
	protected $titulo_original;


	/**
	 * The value for the referencia_a_la_fuente field.
	 * @var        string
	 */
	protected $referencia_a_la_fuente;


	/**
	 * The value for the streaming field.
	 * @var        string
	 */
	protected $streaming;


	/**
	 * The value for the denegar_descarga field.
	 * @var        string
	 */
	protected $denegar_descarga;


	/**
	 * The value for the fecha_inicio field.
	 * @var        int
	 */
	protected $fecha_inicio;


	/**
	 * The value for the fecha_fin field.
	 * @var        int
	 */
	protected $fecha_fin;


	/**
	 * The value for the critico field.
	 * @var        string
	 */
	protected $critico;


	/**
	 * The value for the derechos field.
	 * @var        string
	 */
	protected $derechos;


	/**
	 * The value for the subtitulos field.
	 * @var        string
	 */
	protected $subtitulos;


	/**
	 * The value for the autoria field.
	 * @var        string
	 */
	protected $autoria;


	/**
	 * The value for the preset_original field.
	 * @var        string
	 */
	protected $preset_original;


	/**
	 * The value for the preset_alta field.
	 * @var        string
	 */
	protected $preset_alta;


	/**
	 * The value for the preset_media field.
	 * @var        string
	 */
	protected $preset_media;


	/**
	 * The value for the preset_baja field.
	 * @var        string
	 */
	protected $preset_baja;


	/**
	 * The value for the tematicas field.
	 * @var        string
	 */
	protected $tematicas;


	/**
	 * The value for the categorias field.
	 * @var        string
	 */
	protected $categorias;


	/**
	 * The value for the destinos_de_publicacion field.
	 * @var        string
	 */
	protected $destinos_de_publicacion;


	/**
	 * The value for the thumbs field.
	 * @var        string
	 */
	protected $thumbs;


	/**
	 * The value for the tags field.
	 * @var        string
	 */
	protected $tags;


	/**
	 * The value for the enlaces field.
	 * @var        string
	 */
	protected $enlaces;


	/**
	 * The value for the relacionados field.
	 * @var        string
	 */
	protected $relacionados;


	/**
	 * The value for the documentos_adjuntos field.
	 * @var        string
	 */
	protected $documentos_adjuntos;


	/**
	 * The value for the estado field.
	 * @var        string
	 */
	protected $estado;

	/**
	 * @var        Mm
	 */
	protected $aMm;

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
	 * Get the [original_id] column value.
	 * 
	 * @return     int
	 */
	public function getOriginalId()
	{

		return $this->original_id;
	}

	/**
	 * Get the [mm_id] column value.
	 * 
	 * @return     int
	 */
	public function getMmId()
	{

		return $this->mm_id;
	}

	/**
	 * Get the [optionally formatted] [fecha_de_creacion] column value.
	 * 
	 * @param      string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the integer unix timestamp will be returned.
	 * @return     mixed Formatted date/time value as string or integer unix timestamp (if format is NULL).
	 * @throws     PropelException - if unable to convert the date/time to timestamp.
	 */
	public function getFechaDeCreacion($format = 'Y-m-d H:i:s')
	{

		if ($this->fecha_de_creacion === null || $this->fecha_de_creacion === '') {
			return null;
		} elseif (!is_int($this->fecha_de_creacion)) {
			// a non-timestamp value was set externally, so we convert it
			$ts = strtotime($this->fecha_de_creacion);
			if ($ts === -1 || $ts === false) { // in PHP 5.1 return value changes to FALSE
				throw new PropelException("Unable to parse value of [fecha_de_creacion] as date/time value: " . var_export($this->fecha_de_creacion, true));
			}
		} else {
			$ts = $this->fecha_de_creacion;
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
	 * Get the [optionally formatted] [fecha_de_actualizacion] column value.
	 * 
	 * @param      string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the integer unix timestamp will be returned.
	 * @return     mixed Formatted date/time value as string or integer unix timestamp (if format is NULL).
	 * @throws     PropelException - if unable to convert the date/time to timestamp.
	 */
	public function getFechaDeActualizacion($format = 'Y-m-d H:i:s')
	{

		if ($this->fecha_de_actualizacion === null || $this->fecha_de_actualizacion === '') {
			return null;
		} elseif (!is_int($this->fecha_de_actualizacion)) {
			// a non-timestamp value was set externally, so we convert it
			$ts = strtotime($this->fecha_de_actualizacion);
			if ($ts === -1 || $ts === false) { // in PHP 5.1 return value changes to FALSE
				throw new PropelException("Unable to parse value of [fecha_de_actualizacion] as date/time value: " . var_export($this->fecha_de_actualizacion, true));
			}
		} else {
			$ts = $this->fecha_de_actualizacion;
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
	 * Get the [titulo] column value.
	 * 
	 * @return     string
	 */
	public function getTitulo()
	{

		return $this->titulo;
	}

	/**
	 * Get the [descripcion_corta] column value.
	 * 
	 * @return     string
	 */
	public function getDescripcionCorta()
	{

		return $this->descripcion_corta;
	}

	/**
	 * Get the [descripcion] column value.
	 * 
	 * @return     string
	 */
	public function getDescripcion()
	{

		return $this->descripcion;
	}

	/**
	 * Get the [alt] column value.
	 * 
	 * @return     string
	 */
	public function getAlt()
	{

		return $this->alt;
	}

	/**
	 * Get the [pie] column value.
	 * 
	 * @return     string
	 */
	public function getPie()
	{

		return $this->pie;
	}

	/**
	 * Get the [origen] column value.
	 * 
	 * @return     string
	 */
	public function getOrigen()
	{

		return $this->origen;
	}

	/**
	 * Get the [autor] column value.
	 * 
	 * @return     string
	 */
	public function getAutor()
	{

		return $this->autor;
	}

	/**
	 * Get the [realizador] column value.
	 * 
	 * @return     string
	 */
	public function getRealizador()
	{

		return $this->realizador;
	}

	/**
	 * Get the [optionally formatted] [ano] column value.
	 * 
	 * @param      string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the integer unix timestamp will be returned.
	 * @return     mixed Formatted date/time value as string or integer unix timestamp (if format is NULL).
	 * @throws     PropelException - if unable to convert the date/time to timestamp.
	 */
	public function getAno($format = 'Y-m-d H:i:s')
	{

		if ($this->ano === null || $this->ano === '') {
			return null;
		} elseif (!is_int($this->ano)) {
			// a non-timestamp value was set externally, so we convert it
			$ts = strtotime($this->ano);
			if ($ts === -1 || $ts === false) { // in PHP 5.1 return value changes to FALSE
				throw new PropelException("Unable to parse value of [ano] as date/time value: " . var_export($this->ano, true));
			}
		} else {
			$ts = $this->ano;
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
	 * Get the [titulo_original] column value.
	 * 
	 * @return     string
	 */
	public function getTituloOriginal()
	{

		return $this->titulo_original;
	}

	/**
	 * Get the [referencia_a_la_fuente] column value.
	 * 
	 * @return     string
	 */
	public function getReferenciaALaFuente()
	{

		return $this->referencia_a_la_fuente;
	}

	/**
	 * Get the [streaming] column value.
	 * 
	 * @return     string
	 */
	public function getStreaming()
	{

		return $this->streaming;
	}

	/**
	 * Get the [denegar_descarga] column value.
	 * 
	 * @return     string
	 */
	public function getDenegarDescarga()
	{

		return $this->denegar_descarga;
	}

	/**
	 * Get the [optionally formatted] [fecha_inicio] column value.
	 * 
	 * @param      string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the integer unix timestamp will be returned.
	 * @return     mixed Formatted date/time value as string or integer unix timestamp (if format is NULL).
	 * @throws     PropelException - if unable to convert the date/time to timestamp.
	 */
	public function getFechaInicio($format = 'Y-m-d H:i:s')
	{

		if ($this->fecha_inicio === null || $this->fecha_inicio === '') {
			return null;
		} elseif (!is_int($this->fecha_inicio)) {
			// a non-timestamp value was set externally, so we convert it
			$ts = strtotime($this->fecha_inicio);
			if ($ts === -1 || $ts === false) { // in PHP 5.1 return value changes to FALSE
				throw new PropelException("Unable to parse value of [fecha_inicio] as date/time value: " . var_export($this->fecha_inicio, true));
			}
		} else {
			$ts = $this->fecha_inicio;
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
	 * Get the [optionally formatted] [fecha_fin] column value.
	 * 
	 * @param      string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the integer unix timestamp will be returned.
	 * @return     mixed Formatted date/time value as string or integer unix timestamp (if format is NULL).
	 * @throws     PropelException - if unable to convert the date/time to timestamp.
	 */
	public function getFechaFin($format = 'Y-m-d H:i:s')
	{

		if ($this->fecha_fin === null || $this->fecha_fin === '') {
			return null;
		} elseif (!is_int($this->fecha_fin)) {
			// a non-timestamp value was set externally, so we convert it
			$ts = strtotime($this->fecha_fin);
			if ($ts === -1 || $ts === false) { // in PHP 5.1 return value changes to FALSE
				throw new PropelException("Unable to parse value of [fecha_fin] as date/time value: " . var_export($this->fecha_fin, true));
			}
		} else {
			$ts = $this->fecha_fin;
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
	 * Get the [critico] column value.
	 * 
	 * @return     string
	 */
	public function getCritico()
	{

		return $this->critico;
	}

	/**
	 * Get the [derechos] column value.
	 * 
	 * @return     string
	 */
	public function getDerechos()
	{

		return $this->derechos;
	}

	/**
	 * Get the [subtitulos] column value.
	 * 
	 * @return     string
	 */
	public function getSubtitulos()
	{

		return $this->subtitulos;
	}

	/**
	 * Get the [autoria] column value.
	 * 
	 * @return     string
	 */
	public function getAutoria()
	{

		return $this->autoria;
	}

	/**
	 * Get the [preset_original] column value.
	 * 
	 * @return     string
	 */
	public function getPresetOriginal()
	{

		return $this->preset_original;
	}

	/**
	 * Get the [preset_alta] column value.
	 * 
	 * @return     string
	 */
	public function getPresetAlta()
	{

		return $this->preset_alta;
	}

	/**
	 * Get the [preset_media] column value.
	 * 
	 * @return     string
	 */
	public function getPresetMedia()
	{

		return $this->preset_media;
	}

	/**
	 * Get the [preset_baja] column value.
	 * 
	 * @return     string
	 */
	public function getPresetBaja()
	{

		return $this->preset_baja;
	}

	/**
	 * Get the [tematicas] column value.
	 * 
	 * @return     string
	 */
	public function getTematicas()
	{

		return $this->tematicas;
	}

	/**
	 * Get the [categorias] column value.
	 * 
	 * @return     string
	 */
	public function getCategorias()
	{

		return $this->categorias;
	}

	/**
	 * Get the [destinos_de_publicacion] column value.
	 * 
	 * @return     string
	 */
	public function getDestinosDePublicacion()
	{

		return $this->destinos_de_publicacion;
	}

	/**
	 * Get the [thumbs] column value.
	 * 
	 * @return     string
	 */
	public function getThumbs()
	{

		return $this->thumbs;
	}

	/**
	 * Get the [tags] column value.
	 * 
	 * @return     string
	 */
	public function getTags()
	{

		return $this->tags;
	}

	/**
	 * Get the [enlaces] column value.
	 * 
	 * @return     string
	 */
	public function getEnlaces()
	{

		return $this->enlaces;
	}

	/**
	 * Get the [relacionados] column value.
	 * 
	 * @return     string
	 */
	public function getRelacionados()
	{

		return $this->relacionados;
	}

	/**
	 * Get the [documentos_adjuntos] column value.
	 * 
	 * @return     string
	 */
	public function getDocumentosAdjuntos()
	{

		return $this->documentos_adjuntos;
	}

	/**
	 * Get the [estado] column value.
	 * 
	 * @return     string
	 */
	public function getEstado()
	{

		return $this->estado;
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
			$this->modifiedColumns[] = UnedMediaOldPeer::ID;
		}

	} // setId()

	/**
	 * Set the value of [original_id] column.
	 * 
	 * @param      int $v new value
	 * @return     void
	 */
	public function setOriginalId($v)
	{

		// Since the native PHP type for this column is integer,
		// we will cast the input value to an int (if it is not).
		if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->original_id !== $v) {
			$this->original_id = $v;
			$this->modifiedColumns[] = UnedMediaOldPeer::ORIGINAL_ID;
		}

	} // setOriginalId()

	/**
	 * Set the value of [mm_id] column.
	 * 
	 * @param      int $v new value
	 * @return     void
	 */
	public function setMmId($v)
	{

		// Since the native PHP type for this column is integer,
		// we will cast the input value to an int (if it is not).
		if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->mm_id !== $v) {
			$this->mm_id = $v;
			$this->modifiedColumns[] = UnedMediaOldPeer::MM_ID;
		}

		if ($this->aMm !== null && $this->aMm->getId() !== $v) {
			$this->aMm = null;
		}

	} // setMmId()

	/**
	 * Set the value of [fecha_de_creacion] column.
	 * 
	 * @param      int $v new value
	 * @return     void
	 */
	public function setFechaDeCreacion($v)
	{

		if ($v !== null && !is_int($v)) {
			$ts = strtotime($v);
			if ($ts === -1 || $ts === false) { // in PHP 5.1 return value changes to FALSE
				throw new PropelException("Unable to parse date/time value for [fecha_de_creacion] from input: " . var_export($v, true));
			}
		} else {
			$ts = $v;
		}
		if ($this->fecha_de_creacion !== $ts) {
			$this->fecha_de_creacion = $ts;
			$this->modifiedColumns[] = UnedMediaOldPeer::FECHA_DE_CREACION;
		}

	} // setFechaDeCreacion()

	/**
	 * Set the value of [fecha_de_actualizacion] column.
	 * 
	 * @param      int $v new value
	 * @return     void
	 */
	public function setFechaDeActualizacion($v)
	{

		if ($v !== null && !is_int($v)) {
			$ts = strtotime($v);
			if ($ts === -1 || $ts === false) { // in PHP 5.1 return value changes to FALSE
				throw new PropelException("Unable to parse date/time value for [fecha_de_actualizacion] from input: " . var_export($v, true));
			}
		} else {
			$ts = $v;
		}
		if ($this->fecha_de_actualizacion !== $ts) {
			$this->fecha_de_actualizacion = $ts;
			$this->modifiedColumns[] = UnedMediaOldPeer::FECHA_DE_ACTUALIZACION;
		}

	} // setFechaDeActualizacion()

	/**
	 * Set the value of [titulo] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setTitulo($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->titulo !== $v) {
			$this->titulo = $v;
			$this->modifiedColumns[] = UnedMediaOldPeer::TITULO;
		}

	} // setTitulo()

	/**
	 * Set the value of [descripcion_corta] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setDescripcionCorta($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->descripcion_corta !== $v) {
			$this->descripcion_corta = $v;
			$this->modifiedColumns[] = UnedMediaOldPeer::DESCRIPCION_CORTA;
		}

	} // setDescripcionCorta()

	/**
	 * Set the value of [descripcion] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setDescripcion($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->descripcion !== $v) {
			$this->descripcion = $v;
			$this->modifiedColumns[] = UnedMediaOldPeer::DESCRIPCION;
		}

	} // setDescripcion()

	/**
	 * Set the value of [alt] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setAlt($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->alt !== $v) {
			$this->alt = $v;
			$this->modifiedColumns[] = UnedMediaOldPeer::ALT;
		}

	} // setAlt()

	/**
	 * Set the value of [pie] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setPie($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->pie !== $v) {
			$this->pie = $v;
			$this->modifiedColumns[] = UnedMediaOldPeer::PIE;
		}

	} // setPie()

	/**
	 * Set the value of [origen] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setOrigen($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->origen !== $v) {
			$this->origen = $v;
			$this->modifiedColumns[] = UnedMediaOldPeer::ORIGEN;
		}

	} // setOrigen()

	/**
	 * Set the value of [autor] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setAutor($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->autor !== $v) {
			$this->autor = $v;
			$this->modifiedColumns[] = UnedMediaOldPeer::AUTOR;
		}

	} // setAutor()

	/**
	 * Set the value of [realizador] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setRealizador($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->realizador !== $v) {
			$this->realizador = $v;
			$this->modifiedColumns[] = UnedMediaOldPeer::REALIZADOR;
		}

	} // setRealizador()

	/**
	 * Set the value of [ano] column.
	 * 
	 * @param      int $v new value
	 * @return     void
	 */
	public function setAno($v)
	{

		if ($v !== null && !is_int($v)) {
			$ts = strtotime($v);
			if ($ts === -1 || $ts === false) { // in PHP 5.1 return value changes to FALSE
				throw new PropelException("Unable to parse date/time value for [ano] from input: " . var_export($v, true));
			}
		} else {
			$ts = $v;
		}
		if ($this->ano !== $ts) {
			$this->ano = $ts;
			$this->modifiedColumns[] = UnedMediaOldPeer::ANO;
		}

	} // setAno()

	/**
	 * Set the value of [titulo_original] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setTituloOriginal($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->titulo_original !== $v) {
			$this->titulo_original = $v;
			$this->modifiedColumns[] = UnedMediaOldPeer::TITULO_ORIGINAL;
		}

	} // setTituloOriginal()

	/**
	 * Set the value of [referencia_a_la_fuente] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setReferenciaALaFuente($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->referencia_a_la_fuente !== $v) {
			$this->referencia_a_la_fuente = $v;
			$this->modifiedColumns[] = UnedMediaOldPeer::REFERENCIA_A_LA_FUENTE;
		}

	} // setReferenciaALaFuente()

	/**
	 * Set the value of [streaming] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setStreaming($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->streaming !== $v) {
			$this->streaming = $v;
			$this->modifiedColumns[] = UnedMediaOldPeer::STREAMING;
		}

	} // setStreaming()

	/**
	 * Set the value of [denegar_descarga] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setDenegarDescarga($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->denegar_descarga !== $v) {
			$this->denegar_descarga = $v;
			$this->modifiedColumns[] = UnedMediaOldPeer::DENEGAR_DESCARGA;
		}

	} // setDenegarDescarga()

	/**
	 * Set the value of [fecha_inicio] column.
	 * 
	 * @param      int $v new value
	 * @return     void
	 */
	public function setFechaInicio($v)
	{

		if ($v !== null && !is_int($v)) {
			$ts = strtotime($v);
			if ($ts === -1 || $ts === false) { // in PHP 5.1 return value changes to FALSE
				throw new PropelException("Unable to parse date/time value for [fecha_inicio] from input: " . var_export($v, true));
			}
		} else {
			$ts = $v;
		}
		if ($this->fecha_inicio !== $ts) {
			$this->fecha_inicio = $ts;
			$this->modifiedColumns[] = UnedMediaOldPeer::FECHA_INICIO;
		}

	} // setFechaInicio()

	/**
	 * Set the value of [fecha_fin] column.
	 * 
	 * @param      int $v new value
	 * @return     void
	 */
	public function setFechaFin($v)
	{

		if ($v !== null && !is_int($v)) {
			$ts = strtotime($v);
			if ($ts === -1 || $ts === false) { // in PHP 5.1 return value changes to FALSE
				throw new PropelException("Unable to parse date/time value for [fecha_fin] from input: " . var_export($v, true));
			}
		} else {
			$ts = $v;
		}
		if ($this->fecha_fin !== $ts) {
			$this->fecha_fin = $ts;
			$this->modifiedColumns[] = UnedMediaOldPeer::FECHA_FIN;
		}

	} // setFechaFin()

	/**
	 * Set the value of [critico] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setCritico($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->critico !== $v) {
			$this->critico = $v;
			$this->modifiedColumns[] = UnedMediaOldPeer::CRITICO;
		}

	} // setCritico()

	/**
	 * Set the value of [derechos] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setDerechos($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->derechos !== $v) {
			$this->derechos = $v;
			$this->modifiedColumns[] = UnedMediaOldPeer::DERECHOS;
		}

	} // setDerechos()

	/**
	 * Set the value of [subtitulos] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setSubtitulos($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->subtitulos !== $v) {
			$this->subtitulos = $v;
			$this->modifiedColumns[] = UnedMediaOldPeer::SUBTITULOS;
		}

	} // setSubtitulos()

	/**
	 * Set the value of [autoria] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setAutoria($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->autoria !== $v) {
			$this->autoria = $v;
			$this->modifiedColumns[] = UnedMediaOldPeer::AUTORIA;
		}

	} // setAutoria()

	/**
	 * Set the value of [preset_original] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setPresetOriginal($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->preset_original !== $v) {
			$this->preset_original = $v;
			$this->modifiedColumns[] = UnedMediaOldPeer::PRESET_ORIGINAL;
		}

	} // setPresetOriginal()

	/**
	 * Set the value of [preset_alta] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setPresetAlta($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->preset_alta !== $v) {
			$this->preset_alta = $v;
			$this->modifiedColumns[] = UnedMediaOldPeer::PRESET_ALTA;
		}

	} // setPresetAlta()

	/**
	 * Set the value of [preset_media] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setPresetMedia($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->preset_media !== $v) {
			$this->preset_media = $v;
			$this->modifiedColumns[] = UnedMediaOldPeer::PRESET_MEDIA;
		}

	} // setPresetMedia()

	/**
	 * Set the value of [preset_baja] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setPresetBaja($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->preset_baja !== $v) {
			$this->preset_baja = $v;
			$this->modifiedColumns[] = UnedMediaOldPeer::PRESET_BAJA;
		}

	} // setPresetBaja()

	/**
	 * Set the value of [tematicas] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setTematicas($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->tematicas !== $v) {
			$this->tematicas = $v;
			$this->modifiedColumns[] = UnedMediaOldPeer::TEMATICAS;
		}

	} // setTematicas()

	/**
	 * Set the value of [categorias] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setCategorias($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->categorias !== $v) {
			$this->categorias = $v;
			$this->modifiedColumns[] = UnedMediaOldPeer::CATEGORIAS;
		}

	} // setCategorias()

	/**
	 * Set the value of [destinos_de_publicacion] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setDestinosDePublicacion($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->destinos_de_publicacion !== $v) {
			$this->destinos_de_publicacion = $v;
			$this->modifiedColumns[] = UnedMediaOldPeer::DESTINOS_DE_PUBLICACION;
		}

	} // setDestinosDePublicacion()

	/**
	 * Set the value of [thumbs] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setThumbs($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->thumbs !== $v) {
			$this->thumbs = $v;
			$this->modifiedColumns[] = UnedMediaOldPeer::THUMBS;
		}

	} // setThumbs()

	/**
	 * Set the value of [tags] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setTags($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->tags !== $v) {
			$this->tags = $v;
			$this->modifiedColumns[] = UnedMediaOldPeer::TAGS;
		}

	} // setTags()

	/**
	 * Set the value of [enlaces] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setEnlaces($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->enlaces !== $v) {
			$this->enlaces = $v;
			$this->modifiedColumns[] = UnedMediaOldPeer::ENLACES;
		}

	} // setEnlaces()

	/**
	 * Set the value of [relacionados] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setRelacionados($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->relacionados !== $v) {
			$this->relacionados = $v;
			$this->modifiedColumns[] = UnedMediaOldPeer::RELACIONADOS;
		}

	} // setRelacionados()

	/**
	 * Set the value of [documentos_adjuntos] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setDocumentosAdjuntos($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->documentos_adjuntos !== $v) {
			$this->documentos_adjuntos = $v;
			$this->modifiedColumns[] = UnedMediaOldPeer::DOCUMENTOS_ADJUNTOS;
		}

	} // setDocumentosAdjuntos()

	/**
	 * Set the value of [estado] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setEstado($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->estado !== $v) {
			$this->estado = $v;
			$this->modifiedColumns[] = UnedMediaOldPeer::ESTADO;
		}

	} // setEstado()

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

			$this->original_id = $rs->getInt($startcol + 1);

			$this->mm_id = $rs->getInt($startcol + 2);

			$this->fecha_de_creacion = $rs->getTimestamp($startcol + 3, null);

			$this->fecha_de_actualizacion = $rs->getTimestamp($startcol + 4, null);

			$this->titulo = $rs->getString($startcol + 5);

			$this->descripcion_corta = $rs->getString($startcol + 6);

			$this->descripcion = $rs->getString($startcol + 7);

			$this->alt = $rs->getString($startcol + 8);

			$this->pie = $rs->getString($startcol + 9);

			$this->origen = $rs->getString($startcol + 10);

			$this->autor = $rs->getString($startcol + 11);

			$this->realizador = $rs->getString($startcol + 12);

			$this->ano = $rs->getTimestamp($startcol + 13, null);

			$this->titulo_original = $rs->getString($startcol + 14);

			$this->referencia_a_la_fuente = $rs->getString($startcol + 15);

			$this->streaming = $rs->getString($startcol + 16);

			$this->denegar_descarga = $rs->getString($startcol + 17);

			$this->fecha_inicio = $rs->getTimestamp($startcol + 18, null);

			$this->fecha_fin = $rs->getTimestamp($startcol + 19, null);

			$this->critico = $rs->getString($startcol + 20);

			$this->derechos = $rs->getString($startcol + 21);

			$this->subtitulos = $rs->getString($startcol + 22);

			$this->autoria = $rs->getString($startcol + 23);

			$this->preset_original = $rs->getString($startcol + 24);

			$this->preset_alta = $rs->getString($startcol + 25);

			$this->preset_media = $rs->getString($startcol + 26);

			$this->preset_baja = $rs->getString($startcol + 27);

			$this->tematicas = $rs->getString($startcol + 28);

			$this->categorias = $rs->getString($startcol + 29);

			$this->destinos_de_publicacion = $rs->getString($startcol + 30);

			$this->thumbs = $rs->getString($startcol + 31);

			$this->tags = $rs->getString($startcol + 32);

			$this->enlaces = $rs->getString($startcol + 33);

			$this->relacionados = $rs->getString($startcol + 34);

			$this->documentos_adjuntos = $rs->getString($startcol + 35);

			$this->estado = $rs->getString($startcol + 36);

			$this->resetModified();

			$this->setNew(false);

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 37; // 37 = UnedMediaOldPeer::NUM_COLUMNS - UnedMediaOldPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating UnedMediaOld object", $e);
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

    foreach (sfMixer::getCallables('BaseUnedMediaOld:delete:pre') as $callable)
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
			$con = Propel::getConnection(UnedMediaOldPeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			UnedMediaOldPeer::doDelete($this, $con);
			$this->setDeleted(true);
			$con->commit();
		} catch (PropelException $e) {
			$con->rollback();
			throw $e;
		}
	

    foreach (sfMixer::getCallables('BaseUnedMediaOld:delete:post') as $callable)
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

    foreach (sfMixer::getCallables('BaseUnedMediaOld:save:pre') as $callable)
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
			$con = Propel::getConnection(UnedMediaOldPeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			$affectedRows = $this->doSave($con);
			$con->commit();
    foreach (sfMixer::getCallables('BaseUnedMediaOld:save:post') as $callable)
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

			if ($this->aMm !== null) {
				if ($this->aMm->isModified() || $this->aMm->getCurrentMmI18n()->isModified()) {
					$affectedRows += $this->aMm->save($con);
				}
				$this->setMm($this->aMm);
			}


			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = UnedMediaOldPeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setId($pk);  //[IMV] update autoincrement primary key

					$this->setNew(false);
				} else {
					$affectedRows += UnedMediaOldPeer::doUpdate($this, $con);
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

			if ($this->aMm !== null) {
				if (!$this->aMm->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aMm->getValidationFailures());
				}
			}


			if (($retval = UnedMediaOldPeer::doValidate($this, $columns)) !== true) {
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
		$pos = UnedMediaOldPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getOriginalId();
				break;
			case 2:
				return $this->getMmId();
				break;
			case 3:
				return $this->getFechaDeCreacion();
				break;
			case 4:
				return $this->getFechaDeActualizacion();
				break;
			case 5:
				return $this->getTitulo();
				break;
			case 6:
				return $this->getDescripcionCorta();
				break;
			case 7:
				return $this->getDescripcion();
				break;
			case 8:
				return $this->getAlt();
				break;
			case 9:
				return $this->getPie();
				break;
			case 10:
				return $this->getOrigen();
				break;
			case 11:
				return $this->getAutor();
				break;
			case 12:
				return $this->getRealizador();
				break;
			case 13:
				return $this->getAno();
				break;
			case 14:
				return $this->getTituloOriginal();
				break;
			case 15:
				return $this->getReferenciaALaFuente();
				break;
			case 16:
				return $this->getStreaming();
				break;
			case 17:
				return $this->getDenegarDescarga();
				break;
			case 18:
				return $this->getFechaInicio();
				break;
			case 19:
				return $this->getFechaFin();
				break;
			case 20:
				return $this->getCritico();
				break;
			case 21:
				return $this->getDerechos();
				break;
			case 22:
				return $this->getSubtitulos();
				break;
			case 23:
				return $this->getAutoria();
				break;
			case 24:
				return $this->getPresetOriginal();
				break;
			case 25:
				return $this->getPresetAlta();
				break;
			case 26:
				return $this->getPresetMedia();
				break;
			case 27:
				return $this->getPresetBaja();
				break;
			case 28:
				return $this->getTematicas();
				break;
			case 29:
				return $this->getCategorias();
				break;
			case 30:
				return $this->getDestinosDePublicacion();
				break;
			case 31:
				return $this->getThumbs();
				break;
			case 32:
				return $this->getTags();
				break;
			case 33:
				return $this->getEnlaces();
				break;
			case 34:
				return $this->getRelacionados();
				break;
			case 35:
				return $this->getDocumentosAdjuntos();
				break;
			case 36:
				return $this->getEstado();
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
		$keys = UnedMediaOldPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getOriginalId(),
			$keys[2] => $this->getMmId(),
			$keys[3] => $this->getFechaDeCreacion(),
			$keys[4] => $this->getFechaDeActualizacion(),
			$keys[5] => $this->getTitulo(),
			$keys[6] => $this->getDescripcionCorta(),
			$keys[7] => $this->getDescripcion(),
			$keys[8] => $this->getAlt(),
			$keys[9] => $this->getPie(),
			$keys[10] => $this->getOrigen(),
			$keys[11] => $this->getAutor(),
			$keys[12] => $this->getRealizador(),
			$keys[13] => $this->getAno(),
			$keys[14] => $this->getTituloOriginal(),
			$keys[15] => $this->getReferenciaALaFuente(),
			$keys[16] => $this->getStreaming(),
			$keys[17] => $this->getDenegarDescarga(),
			$keys[18] => $this->getFechaInicio(),
			$keys[19] => $this->getFechaFin(),
			$keys[20] => $this->getCritico(),
			$keys[21] => $this->getDerechos(),
			$keys[22] => $this->getSubtitulos(),
			$keys[23] => $this->getAutoria(),
			$keys[24] => $this->getPresetOriginal(),
			$keys[25] => $this->getPresetAlta(),
			$keys[26] => $this->getPresetMedia(),
			$keys[27] => $this->getPresetBaja(),
			$keys[28] => $this->getTematicas(),
			$keys[29] => $this->getCategorias(),
			$keys[30] => $this->getDestinosDePublicacion(),
			$keys[31] => $this->getThumbs(),
			$keys[32] => $this->getTags(),
			$keys[33] => $this->getEnlaces(),
			$keys[34] => $this->getRelacionados(),
			$keys[35] => $this->getDocumentosAdjuntos(),
			$keys[36] => $this->getEstado(),
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
		$pos = UnedMediaOldPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				$this->setOriginalId($value);
				break;
			case 2:
				$this->setMmId($value);
				break;
			case 3:
				$this->setFechaDeCreacion($value);
				break;
			case 4:
				$this->setFechaDeActualizacion($value);
				break;
			case 5:
				$this->setTitulo($value);
				break;
			case 6:
				$this->setDescripcionCorta($value);
				break;
			case 7:
				$this->setDescripcion($value);
				break;
			case 8:
				$this->setAlt($value);
				break;
			case 9:
				$this->setPie($value);
				break;
			case 10:
				$this->setOrigen($value);
				break;
			case 11:
				$this->setAutor($value);
				break;
			case 12:
				$this->setRealizador($value);
				break;
			case 13:
				$this->setAno($value);
				break;
			case 14:
				$this->setTituloOriginal($value);
				break;
			case 15:
				$this->setReferenciaALaFuente($value);
				break;
			case 16:
				$this->setStreaming($value);
				break;
			case 17:
				$this->setDenegarDescarga($value);
				break;
			case 18:
				$this->setFechaInicio($value);
				break;
			case 19:
				$this->setFechaFin($value);
				break;
			case 20:
				$this->setCritico($value);
				break;
			case 21:
				$this->setDerechos($value);
				break;
			case 22:
				$this->setSubtitulos($value);
				break;
			case 23:
				$this->setAutoria($value);
				break;
			case 24:
				$this->setPresetOriginal($value);
				break;
			case 25:
				$this->setPresetAlta($value);
				break;
			case 26:
				$this->setPresetMedia($value);
				break;
			case 27:
				$this->setPresetBaja($value);
				break;
			case 28:
				$this->setTematicas($value);
				break;
			case 29:
				$this->setCategorias($value);
				break;
			case 30:
				$this->setDestinosDePublicacion($value);
				break;
			case 31:
				$this->setThumbs($value);
				break;
			case 32:
				$this->setTags($value);
				break;
			case 33:
				$this->setEnlaces($value);
				break;
			case 34:
				$this->setRelacionados($value);
				break;
			case 35:
				$this->setDocumentosAdjuntos($value);
				break;
			case 36:
				$this->setEstado($value);
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
		$keys = UnedMediaOldPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setOriginalId($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setMmId($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setFechaDeCreacion($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setFechaDeActualizacion($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setTitulo($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setDescripcionCorta($arr[$keys[6]]);
		if (array_key_exists($keys[7], $arr)) $this->setDescripcion($arr[$keys[7]]);
		if (array_key_exists($keys[8], $arr)) $this->setAlt($arr[$keys[8]]);
		if (array_key_exists($keys[9], $arr)) $this->setPie($arr[$keys[9]]);
		if (array_key_exists($keys[10], $arr)) $this->setOrigen($arr[$keys[10]]);
		if (array_key_exists($keys[11], $arr)) $this->setAutor($arr[$keys[11]]);
		if (array_key_exists($keys[12], $arr)) $this->setRealizador($arr[$keys[12]]);
		if (array_key_exists($keys[13], $arr)) $this->setAno($arr[$keys[13]]);
		if (array_key_exists($keys[14], $arr)) $this->setTituloOriginal($arr[$keys[14]]);
		if (array_key_exists($keys[15], $arr)) $this->setReferenciaALaFuente($arr[$keys[15]]);
		if (array_key_exists($keys[16], $arr)) $this->setStreaming($arr[$keys[16]]);
		if (array_key_exists($keys[17], $arr)) $this->setDenegarDescarga($arr[$keys[17]]);
		if (array_key_exists($keys[18], $arr)) $this->setFechaInicio($arr[$keys[18]]);
		if (array_key_exists($keys[19], $arr)) $this->setFechaFin($arr[$keys[19]]);
		if (array_key_exists($keys[20], $arr)) $this->setCritico($arr[$keys[20]]);
		if (array_key_exists($keys[21], $arr)) $this->setDerechos($arr[$keys[21]]);
		if (array_key_exists($keys[22], $arr)) $this->setSubtitulos($arr[$keys[22]]);
		if (array_key_exists($keys[23], $arr)) $this->setAutoria($arr[$keys[23]]);
		if (array_key_exists($keys[24], $arr)) $this->setPresetOriginal($arr[$keys[24]]);
		if (array_key_exists($keys[25], $arr)) $this->setPresetAlta($arr[$keys[25]]);
		if (array_key_exists($keys[26], $arr)) $this->setPresetMedia($arr[$keys[26]]);
		if (array_key_exists($keys[27], $arr)) $this->setPresetBaja($arr[$keys[27]]);
		if (array_key_exists($keys[28], $arr)) $this->setTematicas($arr[$keys[28]]);
		if (array_key_exists($keys[29], $arr)) $this->setCategorias($arr[$keys[29]]);
		if (array_key_exists($keys[30], $arr)) $this->setDestinosDePublicacion($arr[$keys[30]]);
		if (array_key_exists($keys[31], $arr)) $this->setThumbs($arr[$keys[31]]);
		if (array_key_exists($keys[32], $arr)) $this->setTags($arr[$keys[32]]);
		if (array_key_exists($keys[33], $arr)) $this->setEnlaces($arr[$keys[33]]);
		if (array_key_exists($keys[34], $arr)) $this->setRelacionados($arr[$keys[34]]);
		if (array_key_exists($keys[35], $arr)) $this->setDocumentosAdjuntos($arr[$keys[35]]);
		if (array_key_exists($keys[36], $arr)) $this->setEstado($arr[$keys[36]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(UnedMediaOldPeer::DATABASE_NAME);

		if ($this->isColumnModified(UnedMediaOldPeer::ID)) $criteria->add(UnedMediaOldPeer::ID, $this->id);
		if ($this->isColumnModified(UnedMediaOldPeer::ORIGINAL_ID)) $criteria->add(UnedMediaOldPeer::ORIGINAL_ID, $this->original_id);
		if ($this->isColumnModified(UnedMediaOldPeer::MM_ID)) $criteria->add(UnedMediaOldPeer::MM_ID, $this->mm_id);
		if ($this->isColumnModified(UnedMediaOldPeer::FECHA_DE_CREACION)) $criteria->add(UnedMediaOldPeer::FECHA_DE_CREACION, $this->fecha_de_creacion);
		if ($this->isColumnModified(UnedMediaOldPeer::FECHA_DE_ACTUALIZACION)) $criteria->add(UnedMediaOldPeer::FECHA_DE_ACTUALIZACION, $this->fecha_de_actualizacion);
		if ($this->isColumnModified(UnedMediaOldPeer::TITULO)) $criteria->add(UnedMediaOldPeer::TITULO, $this->titulo);
		if ($this->isColumnModified(UnedMediaOldPeer::DESCRIPCION_CORTA)) $criteria->add(UnedMediaOldPeer::DESCRIPCION_CORTA, $this->descripcion_corta);
		if ($this->isColumnModified(UnedMediaOldPeer::DESCRIPCION)) $criteria->add(UnedMediaOldPeer::DESCRIPCION, $this->descripcion);
		if ($this->isColumnModified(UnedMediaOldPeer::ALT)) $criteria->add(UnedMediaOldPeer::ALT, $this->alt);
		if ($this->isColumnModified(UnedMediaOldPeer::PIE)) $criteria->add(UnedMediaOldPeer::PIE, $this->pie);
		if ($this->isColumnModified(UnedMediaOldPeer::ORIGEN)) $criteria->add(UnedMediaOldPeer::ORIGEN, $this->origen);
		if ($this->isColumnModified(UnedMediaOldPeer::AUTOR)) $criteria->add(UnedMediaOldPeer::AUTOR, $this->autor);
		if ($this->isColumnModified(UnedMediaOldPeer::REALIZADOR)) $criteria->add(UnedMediaOldPeer::REALIZADOR, $this->realizador);
		if ($this->isColumnModified(UnedMediaOldPeer::ANO)) $criteria->add(UnedMediaOldPeer::ANO, $this->ano);
		if ($this->isColumnModified(UnedMediaOldPeer::TITULO_ORIGINAL)) $criteria->add(UnedMediaOldPeer::TITULO_ORIGINAL, $this->titulo_original);
		if ($this->isColumnModified(UnedMediaOldPeer::REFERENCIA_A_LA_FUENTE)) $criteria->add(UnedMediaOldPeer::REFERENCIA_A_LA_FUENTE, $this->referencia_a_la_fuente);
		if ($this->isColumnModified(UnedMediaOldPeer::STREAMING)) $criteria->add(UnedMediaOldPeer::STREAMING, $this->streaming);
		if ($this->isColumnModified(UnedMediaOldPeer::DENEGAR_DESCARGA)) $criteria->add(UnedMediaOldPeer::DENEGAR_DESCARGA, $this->denegar_descarga);
		if ($this->isColumnModified(UnedMediaOldPeer::FECHA_INICIO)) $criteria->add(UnedMediaOldPeer::FECHA_INICIO, $this->fecha_inicio);
		if ($this->isColumnModified(UnedMediaOldPeer::FECHA_FIN)) $criteria->add(UnedMediaOldPeer::FECHA_FIN, $this->fecha_fin);
		if ($this->isColumnModified(UnedMediaOldPeer::CRITICO)) $criteria->add(UnedMediaOldPeer::CRITICO, $this->critico);
		if ($this->isColumnModified(UnedMediaOldPeer::DERECHOS)) $criteria->add(UnedMediaOldPeer::DERECHOS, $this->derechos);
		if ($this->isColumnModified(UnedMediaOldPeer::SUBTITULOS)) $criteria->add(UnedMediaOldPeer::SUBTITULOS, $this->subtitulos);
		if ($this->isColumnModified(UnedMediaOldPeer::AUTORIA)) $criteria->add(UnedMediaOldPeer::AUTORIA, $this->autoria);
		if ($this->isColumnModified(UnedMediaOldPeer::PRESET_ORIGINAL)) $criteria->add(UnedMediaOldPeer::PRESET_ORIGINAL, $this->preset_original);
		if ($this->isColumnModified(UnedMediaOldPeer::PRESET_ALTA)) $criteria->add(UnedMediaOldPeer::PRESET_ALTA, $this->preset_alta);
		if ($this->isColumnModified(UnedMediaOldPeer::PRESET_MEDIA)) $criteria->add(UnedMediaOldPeer::PRESET_MEDIA, $this->preset_media);
		if ($this->isColumnModified(UnedMediaOldPeer::PRESET_BAJA)) $criteria->add(UnedMediaOldPeer::PRESET_BAJA, $this->preset_baja);
		if ($this->isColumnModified(UnedMediaOldPeer::TEMATICAS)) $criteria->add(UnedMediaOldPeer::TEMATICAS, $this->tematicas);
		if ($this->isColumnModified(UnedMediaOldPeer::CATEGORIAS)) $criteria->add(UnedMediaOldPeer::CATEGORIAS, $this->categorias);
		if ($this->isColumnModified(UnedMediaOldPeer::DESTINOS_DE_PUBLICACION)) $criteria->add(UnedMediaOldPeer::DESTINOS_DE_PUBLICACION, $this->destinos_de_publicacion);
		if ($this->isColumnModified(UnedMediaOldPeer::THUMBS)) $criteria->add(UnedMediaOldPeer::THUMBS, $this->thumbs);
		if ($this->isColumnModified(UnedMediaOldPeer::TAGS)) $criteria->add(UnedMediaOldPeer::TAGS, $this->tags);
		if ($this->isColumnModified(UnedMediaOldPeer::ENLACES)) $criteria->add(UnedMediaOldPeer::ENLACES, $this->enlaces);
		if ($this->isColumnModified(UnedMediaOldPeer::RELACIONADOS)) $criteria->add(UnedMediaOldPeer::RELACIONADOS, $this->relacionados);
		if ($this->isColumnModified(UnedMediaOldPeer::DOCUMENTOS_ADJUNTOS)) $criteria->add(UnedMediaOldPeer::DOCUMENTOS_ADJUNTOS, $this->documentos_adjuntos);
		if ($this->isColumnModified(UnedMediaOldPeer::ESTADO)) $criteria->add(UnedMediaOldPeer::ESTADO, $this->estado);

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
		$criteria = new Criteria(UnedMediaOldPeer::DATABASE_NAME);

		$criteria->add(UnedMediaOldPeer::ID, $this->id);

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
	 * @param      object $copyObj An object of UnedMediaOld (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setOriginalId($this->original_id);

		$copyObj->setMmId($this->mm_id);

		$copyObj->setFechaDeCreacion($this->fecha_de_creacion);

		$copyObj->setFechaDeActualizacion($this->fecha_de_actualizacion);

		$copyObj->setTitulo($this->titulo);

		$copyObj->setDescripcionCorta($this->descripcion_corta);

		$copyObj->setDescripcion($this->descripcion);

		$copyObj->setAlt($this->alt);

		$copyObj->setPie($this->pie);

		$copyObj->setOrigen($this->origen);

		$copyObj->setAutor($this->autor);

		$copyObj->setRealizador($this->realizador);

		$copyObj->setAno($this->ano);

		$copyObj->setTituloOriginal($this->titulo_original);

		$copyObj->setReferenciaALaFuente($this->referencia_a_la_fuente);

		$copyObj->setStreaming($this->streaming);

		$copyObj->setDenegarDescarga($this->denegar_descarga);

		$copyObj->setFechaInicio($this->fecha_inicio);

		$copyObj->setFechaFin($this->fecha_fin);

		$copyObj->setCritico($this->critico);

		$copyObj->setDerechos($this->derechos);

		$copyObj->setSubtitulos($this->subtitulos);

		$copyObj->setAutoria($this->autoria);

		$copyObj->setPresetOriginal($this->preset_original);

		$copyObj->setPresetAlta($this->preset_alta);

		$copyObj->setPresetMedia($this->preset_media);

		$copyObj->setPresetBaja($this->preset_baja);

		$copyObj->setTematicas($this->tematicas);

		$copyObj->setCategorias($this->categorias);

		$copyObj->setDestinosDePublicacion($this->destinos_de_publicacion);

		$copyObj->setThumbs($this->thumbs);

		$copyObj->setTags($this->tags);

		$copyObj->setEnlaces($this->enlaces);

		$copyObj->setRelacionados($this->relacionados);

		$copyObj->setDocumentosAdjuntos($this->documentos_adjuntos);

		$copyObj->setEstado($this->estado);


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
	 * @return     UnedMediaOld Clone of current object.
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
	 * @return     UnedMediaOldPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new UnedMediaOldPeer();
		}
		return self::$peer;
	}

	/**
	 * Declares an association between this object and a Mm object.
	 *
	 * @param      Mm $v
	 * @return     void
	 * @throws     PropelException
	 */
	public function setMm($v)
	{


		if ($v === null) {
			$this->setMmId(NULL);
		} else {
			$this->setMmId($v->getId());
		}


		$this->aMm = $v;
	}


	/**
	 * Get the associated Mm object
	 *
	 * @param      Connection Optional Connection object.
	 * @return     Mm The associated Mm object.
	 * @throws     PropelException
	 */
	public function getMm($con = null)
	{
		if ($this->aMm === null && ($this->mm_id !== null)) {
			// include the related Peer class
			include_once 'lib/model/om/BaseMmPeer.php';

			$this->aMm = MmPeer::retrieveByPK($this->mm_id, $con);

			/* The following can be used instead of the line above to
			   guarantee the related object contains a reference
			   to this object, but this level of coupling
			   may be undesirable in many circumstances.
			   As it can lead to a db query with many results that may
			   never be used.
			   $obj = MmPeer::retrieveByPK($this->mm_id, $con);
			   $obj->addMms($this);
			 */
		}
		return $this->aMm;
	}


	/**
	 * Get the associated Mm object
	 *
	 * @param      Connection Optional Connection object.
	 * @return     Mm The associated Mm object.
	 * @throws     PropelException
	 */
	public function getMmWithI18n($con = null)
	{
		if ($this->aMm === null && ($this->mm_id !== null)) {
			// include the related Peer class
			include_once 'lib/model/om/BaseMmPeer.php';

			$this->aMm = MmPeer::retrieveByPKWithI18n($this->mm_id, $this->getCulture(), $con);

			/* The following can be used instead of the line above to
			   guarantee the related object contains a reference
			   to this object, but this level of coupling
			   may be undesirable in many circumstances.
			   As it can lead to a db query with many results that may
			   never be used.
			   $obj = MmPeer::retrieveByPKWithI18n($this->mm_id, $this->getCulture(), $con);
			   $obj->addMms($this);
			 */
		}
		return $this->aMm;
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

		$this->aMm = null;
	}


  public function __call($method, $arguments)
  {
    if (!$callable = sfMixer::getCallable('BaseUnedMediaOld:'.$method))
    {
      throw new sfException(sprintf('Call to undefined method BaseUnedMediaOld::%s', $method));
    }

    array_unshift($arguments, $this);

    return call_user_func_array($callable, $arguments);
  }


} // BaseUnedMediaOld
