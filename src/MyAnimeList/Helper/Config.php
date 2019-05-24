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

class Config {

	/**
	 * Curl options
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
		if ( ( $key == 'expiredbyday' AND !is_numeric( $value ) ) OR ( $key != 'expiredbyday' AND !is_bool( $value ) ) ) throw new \Exception( "[MyAnimeList Config Error] Invalid value for {$key}" );

		static::$settings[ $key ] = $value;
	}

	/**
	 * Curl Settings
	 *
	 * @return 		bool
	 */
	public function curlSettings() {

		return $this->curl;
	}

	/**
	 * Get the number of the expired day
	 *
	 * @return 		int
	 */
	public function getExpiredDay() {

		return $this->expiredByDay;
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