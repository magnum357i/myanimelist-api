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
	public static $version = '0.9.2';

	/**
	 * MAL Id
	 */
	public static $id;

	/**
	 * Set type
	 */
	public static $type = 'anime';

	/**
	 * Variable for output
	 */
	protected static $data = array();

	/**
	 * Start and end of elapsed time
	 */
	protected $times = array(
		'start' => 0,
		'end'   => 0
	);

	/**
	 * Prefix to call function
	 */
	protected static $prefix = '';

	/**
	 * Limit for voice, staff, related etc.
	 */
	protected static $limit = 10;

	/**
	 * Get data
	 *
	 * @return 		void
	 */
	public function get() {

		$this->times[ 'start' ] = time();

		if ( $this->config()->cache == TRUE AND $this->cache()->check() ) {

			static::$data = $this->cache()->get();

			if ( static::$data == FALSE ) static::$data = array();
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
	 * Take object parameter and send request
	 *
	 * @param 		int 			$id 				MAL id
	 * @return 		void
	 */
	public function __construct( $id ) {

		static::$id = $id;
	}

	/**
	 * Magic Method: Get
	 *
	 * @param 		string 			$key 				Key
	 * @return 		mixed
	 */
	public function __get( $key ) {

		$prefix         = static::$prefix;
		$dataKey        = "{$prefix}{$key}";
		$functionName   = "_{$dataKey}";
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
	 * @return 		void
	 */
	public function __tostring() {

		return json_encode( static::$data );
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

			$this->request = new \myanimelist\Helper\Request( static::$id, static::$type );
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