<?php

/**
 * CURL Request
 *
 * @author     		Magnum357 [https://github.com/magnum357i/]
 * @copyright  		2018
 * @license    		http://www.opensource.org/licenses/mit-license.html  MIT License
 * @version    		0.9.0
 */

namespace myanimelist\Helper;

class Request {

	/**
	 * Request data
	 */
	public static $requestData = array(
		'url'  => '',
		'type' => '',
		'id'   => 0
	);

	/**
	 * Website url
	 */
	public const SITE = 'https://myanimelist.net/';

	/**
	 * Pages
	 */
	public const PAGES = array(
		'anime'     => 'anime/',
		'manga'     => 'manga/',
		'character' => 'character/',
		'people'    => 'people/'
	);

	/**
	 * Here will load the raw html
	 */
	public static $content = '';

	/**
	 * Load the raw html to static::$content
	 *
	 * @return 	void
	 */
	public function get( \myanimelist\Helper\Config $config ) {

		try {

			$cSession = curl_init();

			curl_setopt( $cSession, CURLOPT_URL,            static::$requestData[ 'url' ] );
			curl_setopt( $cSession, CURLOPT_RETURNTRANSFER, $config->curl[ 'returnTransfer' ] );
			curl_setopt( $cSession, CURLOPT_HEADER,         $config->curl[ 'header' ] );
			curl_setopt( $cSession, CURLOPT_USERAGENT,      $config->curl[ 'userAgent' ] );
			curl_setopt( $cSession, CURLOPT_FOLLOWLOCATION, $config->curl[ 'followLocation' ] );
			curl_setopt( $cSession, CURLOPT_CONNECTTIMEOUT, $config->curl[ 'connectTimeout' ] );
			curl_setopt( $cSession, CURLOPT_TIMEOUT,        $config->curl[ 'timeout' ] );
			curl_setopt( $cSession, CURLOPT_SSL_VERIFYHOST, $config->curl[ 'ssl_verifyHost' ] );
			curl_setopt( $cSession, CURLOPT_SSL_VERIFYPEER, $config->curl[ 'ssl_verifypeer' ] );

			static::$content = curl_exec( $cSession );
			static::$content = html_entity_decode( static::$content );

			curl_close( $cSession );
		}
		catch ( \Exception $e ) { }

		return static::$content;
	}

	/**
	 * Take object parameter and send request
	 *
	 * @param 	string 	$id 	Enter page id on MAL
	 * @return 	void
	 */
	public function __construct( $id, $type ) {

		static::$requestData[ 'id' ]  = $id;
		static::$requestData[ 'url' ] = static::SITE . static::PAGES[ $type ] . $id;
	}
}