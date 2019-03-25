<?php

/**
 * Class for All Options
 *
 * @package	 		MyAnimeList API
 * @author     		Magnum357 [https://github.com/magnum357i/]
 * @copyright  		2018
 * @license    		http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace myanimelist\Helper;

class Config {

	/**
	 * If true, names becomes the first-last order instead of the last-first order
	 */
	protected $reverseName = FALSE;

	/**
	 * If true, cache system runs, so data to take is saved to a json file
	 */
	protected $cache = FALSE;

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
	 * Enable cache setting
	 *
	 * @return 		void
	 */
	public function enableCache() {

		$this->cache = TRUE;
	}

	/**
	 * Enable reverse name setting
	 *
	 * @return 		void
	 */
	public function convertName() {

		$this->reverseName = TRUE;
	}

	/**
	 * Is the cache on?
	 *
	 * @return 		bool
	 */
	public function isOnCache() {

		return $this->cache;
	}

	/**
	 * Are the names being converted?
	 *
	 * @return 		bool
	 */
	public function isOnNameConverting() {

		return $this->reverseName;
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