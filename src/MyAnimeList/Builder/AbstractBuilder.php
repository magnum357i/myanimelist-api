<?php

namespace MyAnimeList\Builder;

use \MyAnimeList\Cache\CacheInterface;
use \MyAnimeList\Cache\Cache;
use \MyAnimeList\Helper\Request;
use \MyAnimeList\Helper\Config;
use \MyAnimeList\Helper\Text;

abstract class AbstractBuilder {

	/**
	 * Software version
	 */
	const VERSION = '1.0.0.0';

	/**
	 * Are the values changed?
	 */
	protected $changed = FALSE;

	/**
	 * Does data come from the cache?
	 */
	protected $cached = FALSE;

	/**
	 * Data Store
	 */
	protected $_data = [];

	/**
	 * Saving directories
	 */
	public static $folders = [ 'main' => NULL, 'file' => NULL, 'image' => NULL ];

	/**
	 * base_url/?
	 */
	protected $urlPatterns = [];

	/**
	 * Patterns for externalLink
	 */
	protected static $externalLinks = [];

	/**
	 * Limit for voice, staff, related etc.
	 */
	protected static $limit = 10;

	/**
	 * Edited Time
	 */
	protected $edited = 0;

	/**
	 * Elapsed Time
	 */
	protected $elapsed = 0;

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

		if ( $this->config()::isOnCache() AND $this->checkCache() ) {

			$this->cached = TRUE;
			$this->_data  = $this->getFileContent();
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

		if ( $this->config()::isOnCache() AND $this->edited == 0 ) $this->edited = time();

		return $this->edited;
	}

	/**
	 * Elapsed time
	 *
	 * @return 		unix
	 */
	public function elapsedTime() {

		return $this->elapsed;
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

		if ( $this->config()->getExpiredDay() == 0 ) return FALSE;

		$expDay  = ( $this->config()->getExpiredDay() > 0 ) ? $this->config()->getExpiredDay() : 1;
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

		$this->elapsed = time();
		$this->cache   = $cache;

		if ( $this->cache == NULL ) $this->cache = new Cache( static::$type, static::$folders );

		$this->request()->createUrl( $this->url() );
	}

	/**
	 * Magic Method: Get
	 *
	 * @return 		mixed
	 */
	public function __get( $key ) {

		$dataParams = $this->keySplitter( $key );
		$value      = $this->getValue( $dataParams[ 'prefix' ], $dataParams[ 'key' ] );

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

		if ( $this->config()::isOnCache() AND !empty( $this->_data ) AND ( $this->changed OR $this->request()->isSuccess() ) ) {

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

		if ( $this->config == NULL ) 	$this->config = new Config();


		return $this->config;
	}

	/**
	 * Text class
	 */
	protected $text = NULL;

	public function text() {

		if ( $this->text == NULL ) $this->text = new Text();

		return $this->text;
	}

	/**
	 * Request class
	 */
	protected $request = NULL;

	public function request() {

		if ( $this->request == NULL ) {

			$this->request = new Request();
		}

		return $this->request;
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
	 * Assign a value to static::data
	 *
	 * @param 		string 			$prefix 			First key of data
	 * @param 		string 			$key 				Data key (If $prefix has, it's second key of data)
	 * @param 		mixed 			$value 			Data value
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
	 * The request link returns
	 *
	 * @return 		string
	 */
	public function link() {

		return $this->request()::SITE . $this->url();
	}
}