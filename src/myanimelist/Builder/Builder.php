<?php

/**
 * Builder Template
 *
 * @package	 		MyAnimeList API
 * @author     		Magnum357 [https://github.com/magnum357i/]
 * @copyright  		2018
 * @license    		http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace myanimelist\Builder;

use \myanimelist\Helper\Request;
use \myanimelist\Helper\Cache;
use \myanimelist\Helper\Config;
use \myanimelist\Helper\Text;

abstract class Builder {

	/**
	 * Software version
	 */
	const VERSION = '0.9.9';

	/**
	 * "single": Saves all values in a single file
	 * "multi": Creates files by first keys
	 */
	protected $cacheMode = 'single';

	/**
	 * Are the values changed?
	 */
	protected $changed = FALSE;

	/**
	 * Does data come from the cache?
	 */
	protected $cached = FALSE;

	/**
	 * Variable for output
	 */
	protected static $data = [];

	/**
	 * Start and end of elapsed time
	 */
	protected $times = [ 'start' => 0, 'end' => 0 ];

	/**
	 * Saving directories
	 */
	protected $folders = [ 'main' => NULL, 'file' => NULL, 'image' => NULL ];

	/**
	 * base_url/?
	 */
	protected $urlPatterns = [];

	/**
	 * Prefixes to call function
	 */
	protected static $prefix = [];

	/**
	 * Prefix count
	 */
	protected static $prefixCount = 0;

	/**
	 * Methods to allow for prefix
	 */
	protected static $methodsToAllow = [];

	/**
	 * Patterns for externalLink
	 */
	protected static $externalLinks = [];

	/**
	 * Limit for voice, staff, related etc.
	 */
	protected static $limit = 10;

	/**
	 * Set limit
	 *
	 * @param 		int 			$int 				Limit number for values returned array
	 * @return 		instance
	 */
	public function setLimit( $int ) {

		static::$limit = ( $int > 2 OR $int < 100 ) ? $int : 10;

		return $this;
	}

	/**
	 * Load required function
	 *
	 * @param 		string 			$functionName 				A function name
	 * @return 		void
	 */
	 protected function setRequired( $functionName ) {

		if ( !isset( static::$data[ $functionName ] ) ) {

			$value = $this->$functionName();

			if ( $value != FALSE ) static::$data[ $functionName ] = $value;
		}

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
	 * Page is correct?
	 *
	 * @return 		bool
	 */
	public function isSuccess() {

		return $this->request()->isSuccess() OR $this->cached;
	}

	/**
	 *
	 * @return 		void
	 */
	public function __construct() {

		static::$data = [];
	}

	/**
	 * Send request to the page or get data from cache
	 *
	 * @return 		void
	 */
	public function sendRequestOrGetData() {

		$this->request()->createUrl( $this->url() );

		$this->times[ 'start' ] = time();

		if ( $this->config()->isOnCache() ) {

			$fileName = $this->getFileName();

			if ( $this->cache()->checkFile( "{$fileName}_time" ) ) {

				$cacheTime = $this->cache()->readFile( "{$fileName}_time" );

				if ( $this->cache()->expired( $cacheTime ) ) {

					$this->cached = TRUE;

					if ( $this->cacheMode == 'single' ) {

						static::$data = $this->cache()->readFile( $fileName );
					}
				}
			}
		}

		if ( ( $this->cacheMode == 'single' AND empty( static::$data ) ) OR !$this->cached ) {

			$this->request()->send( $this->config()->curlSettings() );
		}
	}

	/**
	 * Magic Method: Get
	 *
	 * @param 		string 			$key 				Key
	 * @return 		mixed
	 */
	public function __get( $key ) {

		$functionName  = 'get';
		$functionName .= ucfirst( $key );
		$functionName .= ( static::$prefixCount > 0 ) ? 'With' . implode( '', array_map( 'ucfirst', static::$prefix ) ) : '';
		$functionName .= 'FromData';
		$value         = FALSE;

		if ( method_exists( $this, $functionName ) ) {

			$value = static::getValue( $key );

			if ( $value == FALSE ) {

				if ( $this->request()::isSent() ) {

					static::setValue( $key, $this->$functionName() );

					$value = static::getValue( $key );
				}
				else if ( $this->config()->isOnCache() AND $this->cacheMode == 'multi' AND !isset( static::$data[ $key ] ) ) {

					$fileName = $this->getFileName();

					if ( $this->cache()->checkFile( "{$fileName}_{$key}" ) ) {

						static::setValue( $key, $this->cache()->readFile( "{$fileName}_{$key}" ) );

						$value = static::getValue( $key );
					}
				}
			}

			static::$prefix      = [];
			static::$prefixCount = 0;

			return $value;
		}

		throw new \Exception( "[MyAnimeList Error] Undefined variable: {$key}" );
	}

	/**
	 * Magic Method: Set
	 *
	 * @param 		string 			$key 				Key
	 * @param 		string 			$value 			Value
	 * @return 		void
	 */
	public function __set( $key, $value ) {

		$this->changed = TRUE;

		static::setValue( $key, $value );

		static::$prefix = [];
	}

	/**
	 * Magic Method: Destruct
	 *
	 * @return 		void
	 */
	public function __destruct() {

		if ( $this->config()->isOnCache() AND ( $this->changed OR $this->request()->isSuccess() ) ) {

			$fileName = $this->getFileName();

			if ( $this->cacheMode == 'single' ) {

				$this->cache()->writeFile( $fileName,          static::$data );
				$this->cache()->writeFile( "{$fileName}_time", time() );
			}
			else if ( $this->cacheMode == 'multi' ) {

				foreach( static::$data as $key => $value ) {

					if ( $key != 'link' ) {

						$this->cache()->writeFile( "{$fileName}_{$key}", $value );
					}
				}

				$this->cache()->writeFile( "{$fileName}_time", time() );
			}
			else {

				throw new \Exception( "[MyAnimeList Error] Invalid cache mode. Please use 'single' or 'multi' keywords." );
			}
		}
	}

	/**
	 * Magic Method: Tostring
	 *
	 * @return 		string
	 */
	public function __tostring() {

		return json_encode( static::$data );
	}

	/**
	 * Magic Method: Call
	 *
	 * @return 		void
	 */
	public function __call( $method, $args ) {

		$passed = FALSE;

		switch( static::$prefixCount ) {

			case 0:
			$passed = ( in_array( $method, static::$methodsToAllow ) OR isset( static::$methodsToAllow[ $method ] ) ) ? TRUE : FALSE;
			break;
			case 1:
			$passed = in_array( $method, static::$methodsToAllow[ static::$prefix[ 0 ] ] )                        ? TRUE : FALSE;
			break;
		}

		if ( $passed == TRUE ) {

			static::$prefix[]    = $method;
			static::$prefixCount = count( static::$prefix );
		}
		else {

			throw new \Exception( "[MyAnimeList Error] Bad prefix: {$method}" );
		}

		return $this;
	}

	/**
	 * Cache class
	 */
	protected $cache = NULL;

	public function cache() {

		if ( $this->cache == NULL ) $this->cache = new Cache( static::$type, $this->folders );

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
	 * Show elapsed time
	 *
	 * @return 		string
	 */
	public function elapsedTime() {

		$this->times[ 'end' ] = time();

		$start = date_create( date( 'Y-m-d H:i:s', $this->times[ 'start' ] ) );
		$end   = date_create( date( 'Y-m-d H:i:s', $this->times[ 'end' ] ) );
		$diff  = date_diff( $start, $end );

		return $diff->format( '%s sec.' );
	}

	/**
	 * Assign a value to static::data
	 *
	 * @param 		string 			$value 			Data key
	 * @param 		mixed 			$value 			Data value
	 * @return 		void
	 */
	protected static function setValue( $key, $value ) {

		if ( $value ) {

			switch ( static::$prefixCount ) {

				case 0: static::$data[ $key ]                                                 = $value; break;
				case 1: static::$data[ static::$prefix[ 0 ] ][ $key ]                         = $value; break;
				case 2: static::$data[ static::$prefix[ 0 ] ][ static::$prefix[ 1 ] ][ $key ] = $value; break;
			}
		}
	}

	/**
	 * Get a value from static::data
	 *
	 * @param 		string 			$value 			Data key
	 * @return 		mixed
	 */
	protected static function getValue( $key ) {

		$result = FALSE;

		switch ( static::$prefixCount ) {

			case 0: $result = isset( static::$data[ $key ] )                                                 ? static::$data[ $key ]                                                 : FALSE; break;
			case 1: $result = isset( static::$data[ static::$prefix[ 0 ] ][ $key ] )                         ? static::$data[ static::$prefix[ 0 ] ][ $key ]                         : FALSE; break;
			case 2: $result = isset( static::$data[ static::$prefix[ 0 ] ][ static::$prefix[ 1 ] ][ $key ] ) ? static::$data[ static::$prefix[ 0 ] ][ static::$prefix[ 1 ] ][ $key ] : FALSE; break;
		}

		return $result;
	}

	/**
	 * Return assigned values
	 *
	 * @return 		array
	 */
	protected function output() {

		return static::$data;
	}
}