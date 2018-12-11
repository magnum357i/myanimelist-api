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
	public $reverseName = TRUE;

	/**
	 * If true, converts text to html entities
	 */
	public $encodeValue = FALSE;

	/**
	 * If true, cache system runs, so data to take is saved to a json file
	 */
	public $cache = FALSE;

	/**
	 * Curl Options
	 */
	public $curl = array(
		'returnTransfer' => TRUE,
		'header'         => FALSE,
		'userAgent'      => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.110 Safari/537.36',
		'followLocation' => FALSE,
		'connectTimeout' => 15,
		'timeout'        => 60,
		'ssl_verifyHost' => FALSE,
		'ssl_verifypeer' => FALSE
	);
}