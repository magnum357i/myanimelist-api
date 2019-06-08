<?php

/**
 * Class for All Options
 *
 * @package	 		MyAnimeList API
 * @author     		Magnum357 [https://github.com/magnum357i/]
 * @copyright  		2018
 * @license    		http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace MyAnimeList\Helper;

use \MyAnimeList\Helper\Text;

class Config {

	/**
	 * @var 		array 			Curl options
	 */
	protected $curl = [

		'RETURNTRANSFER' => TRUE,
		'HEADER'         => FALSE,
		'USERAGENT'      => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.110 Safari/537.36',
		'FOLLOWLOCATION' => FALSE,
		'CONNECTTIMEOUT' => 15,
		'TIMEOUT'        => 60,
		'SSL_VERIFYHOST' => FALSE,
		'SSL_VERIFYPEER' => FALSE
	];

	/**
	 * reversename: If true, names becomes the first-last order instead of the last-first order
	 * cache: If true, cache system of file system runs, so data to be taken is saved to a json file
	 * bigimages: Real image sizes on staff, characters etc.
	 * expiredbyday: Cache expired time by day
	 *
	 * @var 		array
	 */
	protected static $settings = [];

	/**
	 *
	 * @return 		void
	 */
	public function __construct() {

		static::$settings = [ 'reversename' => FALSE, 'enablecache' => FALSE, 'bigimages' => FALSE, 'expiredbyday' => 2 ];
	}

	/**
	 * Magic Method: Get
	 *
	 * @return 		mixed
	 */
	public function __get( $key ) {

		if ( !isset( static::$settings[ $key ] ) ) throw new \Exception( "[MyAnimeList Config Error] Undefined setting: {$key}" );

		return static::$settings[ $key ];
	}

	/**
	 * Magic Method: Set
	 *
	 * @return 		void
	 */
	public function __set( $key, $value ) {

		if ( !isset( static::$settings[ $key ] ) ) throw new \Exception( "[MyAnimeList Config Error] Undefined setting: {$key}" );

		$success = FALSE;

		switch ( $key ) {

			case 'reversename':  $success = Text::validate( $value, 'bool' );                   break;
			case 'enablecache':  $success = Text::validate( $value, 'bool' );                   break;
			case 'bigimages':    $success = Text::validate( $value, 'bool' );                   break;
			case 'expiredbyday': $success = Text::validate( $value, 'number', [ 'min' => 0 ] ); break;
		}

		if ( !$success ) throw new \Exception( "[MyAnimeList Config Error] Invalid value for {$key}" );

		static::$settings[ $key ] = $value;
	}

	/**
	 * Curl Settings
	 *
	 * @return 		array
	 */
	public function curlSettings() {

		return $this->curl;
	}

	/**
	 * Set new user agent
	 *
	 * @param 		$value 				Value of curl setting you entered
	 * @param 		$setting 			Curl setting
	 * @return 		void
	 */
	public function setCurlOption( $value, $setting ) {

		$this->curl[ $setting ] = $value;
	}
}