<?php

namespace MyAnimeList\Builder;

use \MyAnimeList\Cache\CacheInterface;
use \MyAnimeList\Cache\CacheAdapter;
use \MyAnimeList\Helper\Request;
use \MyAnimeList\Helper\Config;

abstract class AbstractBuilder {

	/**
	 * @var 		string 			Software version
	 */
	const VERSION = '1.0.0.6';

	/**
	 * @var 		string 			MAL Type
	 */
	protected static $type = '';

	/**
	 * @var 		string 			Key list for all purposes
	 */
	public $keyList = [];

	/**
	 * @var 		bool 			Are the values changed?
	 */
	protected $changed = FALSE;

	/**
	 * @var 		bool 			Does data come from the cache?
	 */
	protected $cached = FALSE;

	/**
	 * @var 		array 			Data Store
	 */
	protected $_data = [];

	/**
	 * @var 		array 			Saving folders
	 */
	public static $folders = [ 'main' => NULL, 'file' => NULL, 'image' => NULL ];

	/**
	 * @var 		array 			Patterns for externalLink
	 */
	protected static $externalLinks = [

		'anime'    => 'anime/{s}',          'manga'     => 'manga/{s}',          'genre' => 'anime/genre/{s}',
		'people'   => 'people/{s}',         'character' => 'character/{s}',
		'producer' => 'anime/producer/{s}', 'magazine'  => 'manga/magazine/{s}'
	];

	/**
	 * @var 		int 			Limit for voice, staff, related etc.
	 */
	protected static $limit = 10;

	/**
	 * @var 		int 			Edited Time
	 */
	protected $edited = 0;

	/**
	 * File name for cache
	 *
	 * @return 		string
	 */
	protected function getFileName() {}

	/**
	 * Image name for cache
	 *
	 * @return 		string
	 */
	protected function getImageName() {}

	/**
	 * Url query
	 *
	 * @return 		string
	 */
	protected function url() {}

	/**
	 * Is cache expired?
	 *
	 * @param 		$time 				Timestamp
	 * @return 		bool
	 */
	protected function expired( $time ) {

		if ( $this->config()->expiredbyday == 0 ) return FALSE;

		$expDay  = ( $this->config()->expiredbyday > 0 ) ? $this->config()->expiredbyday : 1;
		$expTime = strtotime( "-{$expDay} days" );

		if ( $expTime < $time ) {

			$this->edited = $time;

			return TRUE;
		}

		return FALSE;
	}

	/**
	 * Get the content of the cache file
	 *
	 * @param 		string 			$suffix 				File suffix
	 * @return 		mixed
	 */
	protected function getFileContent( $suffix='' ) {

		$suffix = ( $suffix != '' ) ? '_' . $suffix : '';

		return $this->cache()->readFile( $this->getFileName() . $suffix );
	}

	/**
	 * Split given value to find prefix and return key with prefix
	 *
	 * @return 		array
	 */
	protected function keySplitter( $key ) {

		$key = preg_replace( '/([A-Z])/', ' $0', $key, 1 );
		$key = explode( ' ', $key  );

		if ( isset( $key[ 1 ] ) ) {

			return [ 'prefix' => $key[ 0 ], 'key' => mb_strtolower( $key[ 1 ] ) ];
		}

		return [ 'prefix' => '', 'key' => $key[ 0 ] ];
	}

	/**
	 *
	 * @param 		object 			$cache 			Cache class
	 * @return 		void
	 */
	public function __construct( \MyAnimeList\Cache\CacheInterface $cache=NULL ) {

		static::$type = mb_strtolower( explode( '\\', get_called_class() )[ 2 ] );
		$this->cache  = $cache;

		if ( $this->cache == NULL ) $this->cache = new CacheAdapter( static::$type, static::$folders );

		$this->request()->createUrl( $this->url() );
	}

	/**
	 * Magic Method: Get
	 *
	 * @return 		mixed
	 */
	public function __get( $key ) {

		$dataParams    = $this->keySplitter( $key );
		$value         = $this->getValue( $dataParams[ 'prefix' ], $dataParams[ 'key' ] );

		if ( $value !== FALSE ) {

			return $value;
		}
		else {

			$functionName  = 'get';
			$functionName .= $dataParams[ 'key' ];
			$functionName .= ( $dataParams[ 'prefix' ] != '' ) ? 'With' . $dataParams[ 'prefix' ] : '';
			$functionName .= 'FromData';

			if ( method_exists( $this, $functionName ) ) {

				$value = $this->$functionName();

				$this->setValue( $dataParams[ 'prefix' ], $dataParams[ 'key' ], $value );

				return $value;
			}
		}

		return NULL;
	}

	/**
	 * Magic Method: Set
	 *
	 * @return 		void
	 */
	public function __set( $key, $value ) {

		if ( $this->changed == FALSE ) $this->changed = TRUE;

		$dataParams = $this->keySplitter( $key );

		$this->setValue( $dataParams[ 'prefix' ], $dataParams[ 'key' ], $value );
	}

	/**
	 * Magic Method: Unset
	 *
	 * @return 		void
	 */
	public function __unset( $key ) {

		if ( $this->changed == FALSE ) $this->changed = TRUE;

		$dataParams = $this->keySplitter( $key );

		if ( $dataParams[ 'prefix' ] == '' ) {

			unset( $this->_data[ $dataParams[ 'key' ] ] );
		}
		else {

			unset( $this->_data[ $dataParams[ 'prefix' ] ][ $dataParams[ 'key' ] ] );
		}
	}

	/**
	 * Magic Method: Isset
	 *
	 * @return 		bool
	 */
	public function __isset( $key ) {

		$dataParams = $this->keySplitter( $key );
		$result     = FALSE;

		if ( $dataParams[ 'prefix' ] == '' ) {

			$result = isset( $this->_data[ $dataParams[ 'key' ] ] );
		}
		else {

			$result = isset( $this->_data[ $dataParams[ 'prefix' ] ][ $dataParams[ 'key' ] ] );
		}

		if ( !$result ) return ( $this->__get( $key ) ) ? TRUE : FALSE;

		return TRUE;
	}

	/**
	 * Magic Method: Destruct
	 *
	 * @return 		void
	 */
	public function __destruct() {

		if ( $this->config()->enablecache AND !empty( $this->_data ) AND ( $this->changed OR $this->request()->isSuccess() ) ) {

			$fileName = $this->getFileName();

			$this->cache()->writeFile( $fileName, $this->_data );

			if ( $this->request()->isSuccess() ) {

				$this->cache()->writeFile( "{$fileName}_time", time() );
			}
		}
	}

	/**
	 * Magic Method: Tostring
	 *
	 * @return 		string
	 */
	public function __tostring() {

		return json_encode( $this->_data );
	}

	/**
	 * Cache class
	 */
	protected $cache = NULL;

	public function cache() {

		return $this->cache;
	}

	/**
	 * Config class
	 */
	protected $config = NULL;

	public function config() {

		if ( $this->config == NULL ) $this->config = new Config;

		return $this->config;
	}

	/**
	 * Request class
	 */
	protected $request = NULL;

	public function request() {

		if ( $this->request == NULL ) $this->request = new Request;

		return $this->request;
	}

	/**
	 * Assign a value to static::data
	 *
	 * @param 		string 			$prefix 			First key of data
	 * @param 		string 			$key 				Data key (If $prefix has, it's second key of data)
	 * @param 		mixed 			$value 				Data value
	 * @return 		void
	 */
	protected function setValue( $prefix, $key, $value ) {

		if ( $value ) {

			if ( $prefix == '' ) {

				$this->_data[ $key ] = $value;
			}
			else {

				$this->_data[ $prefix ][ $key ] = $value;
			}
		}
	}

	/**
	 * Get a value from static::data
	 *
	 * @param 		string 			$prefix 			First key of data
	 * @param 		string 			$key 				Data key (If $prefix has, it's second key of data)
	 * @return 		mixed
	 */
	protected function getValue( $prefix, $key ) {

		$result = FALSE;

		if ( $prefix == '' ) {

			$result = isset( $this->_data[ $key ] ) ? $this->_data[ $key ] : FALSE;
		}
		else {

			$result = isset( $this->_data[ $prefix ][ $key ] ) ? $this->_data[ $prefix ][ $key ] : FALSE;
		}

		return $result;
	}

	/**
	 * Set limit
	 *
	 * @param 		int 			$int 				Limit number for values returned array
	 * @return 		instance
	 */
	public function setLimit( $int ) {

		static::$limit = ( $int > 0 OR $int < 100 ) ? $int : 10;

		return $this;
	}

	/**
	 * Page is correct?
	 *
	 * @return 		bool
	 */
	public function isSuccess() {

		return ( $this->request()->isSuccess() OR $this->cached ) ? TRUE : FALSE;
	}

	/**
	 * Send request to the page or get data from cache
	 *
	 * @return 		void
	 */
	public function sendRequestOrGetData() {

		if ( $this->config()->enablecache AND $this->checkCache() ) {

			$this->cached  = TRUE;
			$this->_data = $this->getFileContent();
		}

		if ( ( empty( $this->_data ) OR !$this->cached ) ) {

			$this->request()->send( $this->config()->curlSettings() );
		}
	}

	/**
	 * File change time
	 *
	 * @return 		unix
	 */
	public function editedTime() {

		if ( $this->config()->enablecache AND $this->edited == 0 ) $this->edited = time();

		return $this->edited;
	}

	/**
	 * Can the cache file be used?
	 *
	 * @return 		bool|null
	 */
	public function checkCache() {

		$timeFile = $this->getFileName() . '_time';

		if ( $this->cache()->hasFile( $timeFile ) ) {

			$this->edited = $this->cache()->readFile( $timeFile );

			return $this->expired( $this->edited );
		}

		return NULL;
	}

	/**
	 * Return the data
	 *
	 * @return 		array
	 */
	public function output() {

		return $this->_data;
	}

	/**
	 * Get statuses of all data, in other words get all data
	 *
	 * @param 		$limitation 				Limit point to be put before the value you want
	 * @param 		$defaultLimit 				Limit to be set after limit point
	 * @return 		array
	 */
	public function scanAvailableValues( $limitation=[], $defaultLimit=10 ) {

		$success = [];
		$fail    = [];

		foreach ( $this->keyList as $key ) {

			$this->setLimit( $defaultLimit );

			if ( isset( $limitation[ $key ] ) ) $this->setLimit( $limitation[ $key ] );

			if ( isset( $this->$key ) ) {

				$success[] = $key;
			}
			else {

				$fail[] = $key;
			}
		}

		return [ 'success' => $success, 'fail' => $fail ];
	}

	/**
	 * Create mal link from given query string
	 *
	 * @param 		string 			$group 			Key of $externalLinks
	 * @param 		string 			$s 				Page id
	 * @return 		string
	 */
	public function externalLink( $group, $s ) {

		return $this->request()::SITE . str_replace( '{s}', $s, static::$externalLinks[ $group ] );
	}

	/**
	 * The request link returns
	 *
	 * @return 		string
	 */
	public function link() {

		return $this->request()::SITE . $this->url();
	}
}