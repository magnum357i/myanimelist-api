<?php

/**
 * MyAnimeList Request File
 *
 * @author     		Magnum357 [https://github.com/magnum357i/]
 * @copyright  		2018
 * @license    		http://www.opensource.org/licenses/mit-license.html  MIT License
 * @version    		0.8.2
 */

namespace myanimelist\Grabber;

trait Request
{
	/**
	 * It is taken html entry conversion as the last action to matched values
	 */
	public static $encodeValue = TRUE;

	/**
	 * Decode the content to make a better match
	 */
	public static $decodeContent = TRUE;

	/**
	 * Request url
	 */
	protected static $requestUrl = NULL;

	/**
	 * Website url
	 */
	public static $url = 'https://myanimelist.net/';

	/**
	 * Pages
	 */
	public static $pages = array(
		'anime'     => 'anime/',
		'manga'     => 'manga/',
		'character' => 'character/',
		'people'    => 'people/',
	);

	/**
	 * Here will load the raw html
	 */
	public static $content = '';

	/**
	 * Load the raw html to static::$content
	 *
	 * @return	void
	 */
    protected function request( $id )
    {
    	static::$requestUrl = static::$url . static::$pages[ static::$type ] . $id;

		try
		{
			$cSession = curl_init();

			curl_setopt( $cSession, CURLOPT_URL,            static::$requestUrl );
			curl_setopt( $cSession, CURLOPT_RETURNTRANSFER, TRUE );
			curl_setopt( $cSession, CURLOPT_HEADER,         FALSE );

			static::$content = curl_exec( $cSession );

			curl_close( $cSession );
		}
		catch ( \Exception $e ) { }

    	if ( static::$decodeContent == TRUE )
    	{
    		static::$content = html_entity_decode( static::$content );
    	}
    }

	/**
	 * It passes here before the data is returned
	 *
	 * @return string
	 */
	protected static function lastChanges( $data )
	{
		if ( $data == FALSE )
		{
			return FALSE;
		}

		if ( static::$encodeValue == TRUE )
		{
			$data = htmlentities( $data );
		}
		else
		{
			$data = html_entity_decode( $data );
		}

		return $data;
	}
}