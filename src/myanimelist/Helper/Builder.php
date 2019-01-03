<?php

/**
 * Builder to do new type
 *
 * @package	 		MyAnimeList API
 * @author     		Magnum357 [https://github.com/magnum357i/]
 * @copyright  		2018
 * @license    		http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace myanimelist\Helper;

class Builder {

	/**
	 * Software Version
	 */
	const VERSION = '0.9.6';

	/**
	 * MAL Id
	 */
	public static $id;

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
	 * base_url/?
	 */
	protected $urlPaths = [
		'anime'     => 'anime/{id}',
		'manga'     => 'manga/{id}',
		'people'    => 'people/{id}',
		'character' => 'character/{id}'
	];

	/**
	 * Prefix to call function
	 */
	protected static $prefix = '';

	/**
	 * Methods to allow for prefix
	 */
	public static $methodsToAllow = [];

	/**
	 * Limit for voice, staff, related etc.
	 */
	protected static $limit = 10;

	/**
	 * Set limit
	 *
	 * @param 		int 			Limit number
	 * @return 		this class
	 */
	public function setLimit( $int ) {

		static::$limit = ( $int > 2 OR $int < 100 ) ? $int : 10;

		return $this;
	}

	/**
	 * Create url to request
	 *
	 * @return 		void
	 */
	protected function createUrl() {

		$this->request()->createUrl( str_replace( '{id}', static::$id, $this->urlPaths[ static::$type ] ) );
	}

	/**
	 * Get data
	 *
	 * @return 		void
	 */
	public function get() {

		$this->createUrl();

		$this->times[ 'start' ] = time();

		if ( $this->config()->cache == TRUE AND $this->cache()->check() ) {

			static::$data = $this->cache()->get();

			if ( static::$data == FALSE ) static::$data = [];
		}

		if ( empty( static::$data ) ) {

			$this->request()->send( $this->config()->curl );
		}
	}

	/**
	 * Changes you want to apply before values are displayed
	 *
	 * @param 		string 			$data 				String before writing
	 * @return 		string
	 */
	public function lastChanges( $data ) {

		if ( $data == FALSE ) return FALSE;

		if ( $this->config()->encodeValue == TRUE ) {

			$data = htmlentities( $data );
		}

		return $data;
	}

	/**
	 * Page is correct?
	 *
	 * @return 		bool
	 */
	public function isSuccess() {

		return $this->request()->isSuccess() OR !empty( static::$data );
	}

	/**
	 * Take object parameter
	 *
	 * @param 		int 			$id 				MAL id
	 * @return 		void
	 */
	public function __construct( $id ) {

		static::$id   = $id;
		static::$data = [];
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

			return $this->$functionName();
		}

		throw new \Exception( "[MyAnimeList Error] Undefined variable: {$key}" );
	}

	/**
	 * Magic Method: Set
	 *
	 * @param 		string 			$key 			Key
	 * @param 		string 			$value 			Value
	 * @return 		void
	 */
	public function __set( $key, $value ) {

		static::$data[ static::$prefix . $key ] = $value;
	}

	/**
	 * Magic Method: Destruct
	 *
	 * @return 		void
	 */
	public function __destruct() {

		if ( $this->config()->cache == TRUE ) {

			$this->cache()->file[ 'content' ] = static::$data;

			$this->cache()->set();
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
	 * Cache Class
	 */
	protected $cache = NULL;

	public function cache() {

		if ( $this->cache == NULL ) {

			$this->cache = new \myanimelist\Helper\Cache( static::$id, static::$type );
		}

		return $this->cache;
	}

	/**
	 * Config Class
	 */
	protected $config = NULL;

	public function config() {

		if ( $this->config == NULL ) {

			$this->config = new \myanimelist\Helper\Config();
		}

		return $this->config;
	}

	/**
	 * Text Class
	 */
	protected $text = NULL;

	public function text() {

		if ( $this->text == NULL ) {

			$this->text = new \myanimelist\Helper\Text();
		}

		return $this->text;
	}

	/**
	 * Request Class
	 */
	protected $request = NULL;

	public function request() {

		if ( $this->request == NULL ) {

			$this->request = new \myanimelist\Helper\Request();
		}

		return $this->request;
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
	 * @param 		string 			$key 			key of array of $data
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