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
	 * Cache expired time by day
	 */
	protected $expiredByDay = 2;

	/**
	 * If true, names becomes the first-last order instead of the last-first order
	 */
	protected static $reverseName = NULL;

	/**
	 * If true, cache system of file system runs, so data to be taken is saved to a json file
	 */
	protected static $cache = NULL;

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
	 *
	 * @return 		void
	 */
	public function __construct() {

		static::$reverseName = FALSE;
		static::$cache       = FALSE;
	}

	/**
	 * Set expired time
	 *
	 * @param 		$dayNumber 			Number in days
	 * @return 		void
	 */
	public function setExpiredTime( $dayNumber ) {

		$this->expiredByDay = $dayNumber;
	}

	/**
	 * Enable file cache setting
	 *
	 * @return 		void
	 */
	public function enableCache() {

		static::$cache = TRUE;
	}

	/**
	 * Is the file cache on?
	 *
	 * @return 		bool
	 */
	public static function isOnCache() {

		return static::$cache;
	}

	/**
	 * Enable reverse name setting
	 *
	 * @return 		void
	 */
	public function convertName() {

		static::$reverseName = TRUE;
	}

	/**
	 * Are the names being converted?
	 *
	 * @return 		bool
	 */
	public static function isOnNameConverting() {

		return static::$reverseName;
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
	 * @param 		$value 			Value of curl setting you entered
	 * @param 		$setting 			Curl setting
	 * @return 		void
	 */
	public function setCurlOption( $value, $setting ) {

		$this->curl[ $setting ] = $value;
	}
}