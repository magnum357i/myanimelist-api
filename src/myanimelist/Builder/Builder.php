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
	const VERSION = '0.9.8';

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
	protected $times = [

		'start' => 0,
		'end'   => 0
	];

	/**
	 * Saving directories
	 */
	protected $folders = [

		'main'  => NULL,
		'file'  => NULL,
		'image' => NULL
	];

	/**
	 * base_url/?
	 */
	protected $urlPatterns = [];

	/**
	 * Prefix to call function
	 */
	protected static $prefix = '';

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
	 * @param 		int 			Limit number
	 * @return 		instance
	 */
	public function setLimit( $int ) {

		static::$limit = ( $int > 2 OR $int < 100 ) ? $int : 10;

		return $this;
	}

	/**
	 * File name for cache
	 *
	 * @return 		string
	 */
	protected function fileName() {}

	/**
	 * Image name for cache
	 *
	 * @return 		string
	 */
	protected function imageName() {}

	/**
	 * Url query
	 *
	 * @return 		string
	 */
	protected function url() {}

	/**
	 * Changes you want to apply before values are displayed
	 *
	 * @param 		string 			$data 				String before writing
	 * @return 		mix
	 */
	public function lastChanges( $data ) {

		if ( $data == FALSE ) return FALSE;

		return $data;
	}

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

			$fileName = $this->fileName();

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

		$prefix         = static::$prefix;
		$funcKey        = "{$prefix}{$key}";
		$functionName   = "_{$funcKey}";
		static::$prefix = '';

		if ( method_exists( $this, $functionName ) ) {

			if ( $this->cacheMode == 'multi' AND !isset( static::$data[ $funcKey ] ) ) {

				$fileName = $this->fileName();

				if ( $this->cache()->checkFile( "{$fileName}_{$funcKey}" ) ) {

					static::$data[ $funcKey ] = $this->cache()->readFile( "{$fileName}_{$funcKey}" );
				}
			}

			return $this->$functionName();
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

		static::$data[ static::$prefix . $key ] = $value;
		static::$prefix                         = '';
	}

	/**
	 * Magic Method: Destruct
	 *
	 * @return 		void
	 */
	public function __destruct() {

		if ( $this->config()->isOnCache() AND ( $this->changed OR $this->request()->isSuccess() ) ) {

			$fileName = $this->fileName();

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

		if (
			in_array( $method, static::$methodsToAllow )
			OR
			isset( static::$methodsToAllow[ $method ] )
			OR
			isset( static::$methodsToAllow[ static::$prefix ] ) AND in_array( $method, static::$methodsToAllow[ static::$prefix ] )
		) {

			static::$prefix = static::$prefix . $method;
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
	 * @param 		string 			$key 				key of array of $data
	 * @param 		string 			$value 			value of array of $data
	 * @return 		bool
	 */
	protected static function setValue( $key, $value ) {

		if ( $value ) {

			static::$data[ $key ] = $value;

			return $value;
		}
		else {

			return FALSE;
		}
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