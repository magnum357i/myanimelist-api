<?php

/**
 * CURL Request Class
 *
 * @package	 		MyAnimeList API
 * @author     		Magnum357 [https://github.com/magnum357i/]
 * @copyright  		2018
 * @license    		http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace MyAnimeList\Helper;

use \MyAnimeList\Helper\Text;

class Request {

	/**
	 * @var 		string 			Request url
	 */
	protected $url = '';

	/**
	 * @var 		string 			Website url
	 */
	const SITE = 'https://myanimelist.net/';

	/**
	 * @var 		string 			Here will load the raw html
	 */
	protected static $content = '';

	/**
	 * @var 		array 			Request Info
	 */
	protected static $info = [];

	/**
	 *
	 * @return 		void
	 */
	public function __construct() {

		static::$info = NULL;
	}

	/**
	 * Is the request sent
	 *
	 * @return 		bool
	 */
	public static function isSent() {

		return ( static::$info != NULL ) ? TRUE : FALSE;
	}

	/**
	 * Is the request successfully?
	 *
	 * @return 		bool
	 */
	public function isSuccess() {

		return ( isset( static::$info[ 'http_code' ] ) AND static::$info[ 'http_code' ] == 200 ) ? TRUE : FALSE;
	}

	/**
	 * Request info
	 *
	 * @return 		array
	 */
	public function info() {

		return static::$info;
	}

	/**
	 * Load the raw html to static::$content
	 *
	 * @param 		array 			$curlOptions 			Curl options
	 * @return 		void
	 */
	public function send( $curlOptions=[] ) {

		try {

			$cSession = curl_init();

			curl_setopt( $cSession, CURLOPT_URL, $this->url );

			foreach ( $curlOptions as $setting => $value ) {

				curl_setopt( $cSession, constant( "CURLOPT_{$setting}" ), $value );
			}

			static::$content = curl_exec( $cSession );
			static::$content = html_entity_decode( static::$content, ENT_QUOTES );
			static::$info    = curl_getinfo( $cSession );

			curl_close( $cSession );
		}
		catch ( \Exception $e ) { }
	}

	/**
	 * Create url to request
	 *
	 * @param 		string 			$query 					Url without the site name
	 * @return 		void
	 */
	public function createUrl( $query ) {

		$this->url = static::SITE . $query;
	}

	/**
	 * Match string from raw html
	 *
	 * @param 		string 			$templates 				Regex template or templates
	 * @param 		bool 			$allowTags 				Which tags should not be deleted?
	 * @param 		bool 			$clean 					Strip value
	 * @param 		bool 			$content 				Html content
	 * @return 		string
	 */
	public static function match( $templates, $allowTags=NULL, $clean=TRUE, $content=NULL ) {

		if ( !is_array( $templates ) ) {

			$tempTemplates = $templates;
			$templates     = [];
			$templates[]   = $tempTemplates;
		}

		$result = FALSE;

		foreach ( $templates as $template ) {

			preg_match( "@{$template}@si", ( $content != NULL ) ? $content : static::$content, $out );

			if ( isset( $out[ 1 ] ) ) {

				if ( $clean == TRUE ) {

					$result = $out[ 1 ];
					$result = ( $allowTags != NULL ) ? strip_tags( $result, $allowTags ) : strip_tags( $result );
					$result = trim( $result );
				}
				else {

					$result = $out[ 1 ];
				}

				break;
			}
		}

		return $result;
	}

	/**
	 * Simplify the value
	 *
	 * @param 		object 				$config 			Config object
	 * @param 		string 				$value 				A value
	 * @param 		string 				$key 				A key
	 * @return 		mixed
	 */
	public static function reflection( \MyAnimeList\Helper\Config $config, $value, $key ) {

		if ( $value == NULL ) return NULL;

		preg_match( '@(link|name|list|poster|studios|genres)$@', $key, $out );

		$key = ( isset( $out[ 1 ] ) ) ? $out[ 1 ] : $key;

		if ( $key != 'studios' AND $key != 'genres' ) $value = trim( strip_tags( $value ) );

		switch ( $key ) {

			case 'link': $value = static::SITE . $value; break;
			case 'name': if ( $config->reversename ) $value = Text::reverseName( $value ); break;
			case 'list': $value = Text::listValue( $value, ',' ); break;
			case 'poster':

				if ( $config->bigimages ) {

					preg_match( '@images/([^/]+)/(\d+/\d+)@', $value, $out );

					if ( !empty( $out ) ) {

						$id    = $out[ 2 ];
						$type  = $out[ 1 ];
						$value = "https://cdn.myanimelist.net/images/{$type}/{$id}.jpg";
					}
				}

			break;
			case 'studios':

				if ( $value != '-' ) {

					preg_match_all( '@<a href="[^"]+producer/(\d+)[^"]+"[^>]+>(.*?)</a>@', $value, $result );

					$count = count( $result[ 1 ] );
					$rows  = [];

					for ( $i = 0; $i < $count; $i++ ) $rows[] = [ 'id' => $result[ 1 ][ $i ], 'title' => $result[ 2 ][ $i ] ];

					$value = $rows;
				}
				else {

					$value = NULL;
				}

			break;
			case 'genres':

				preg_match_all( '@<a href="[^"]+genre/(\d+)[^"]+"[^>]+>(.*?)</a>@', $value, $result );

				$count = count( $result[ 1 ] );
				$rows  = [];

				for ( $i = 0; $i < $count; $i++ ) $rows[] = [ 'id' => $result[ 1 ][ $i ], 'title' => $result[ 2 ][ $i ] ];

				$value = $rows;

			break;
		}

		return $value;
	}

	/**
	 * Get data as table
	 *
	 * @param 		object 			$config 			Config object
	 * @param 		string 			$tableQuery 		A regex code to match a table
	 * @param 		string 			$rowQuery 			A regex code to match a row in the table
	 * @param 		array 			$queryList 			A regex code to match a value in the row
	 * @param 		array 			$keyList 			A key to assign the value in the row
	 * @param 		string 			$limit 				How many records will return?
	 * @param 		array 			$customChanges 		Desired changes in the value
	 * @param 		bool 			$last 				Reverse sorting?
	 * @param 		string 			$sortKey 			Sort by key
	 * @return 		array
	 */
	public static function matchTable( \MyAnimeList\Helper\Config $config, $tableQuery='', $rowQuery='', $queryList=[], $keyList=[], $limit=0, $customChanges=NULL, $last=FALSE, $sortKey='' ) {

		preg_match( "@{$tableQuery}@si", static::$content, $table );

		if ( empty( $table ) ) return FALSE;

		preg_match_all( "@{$rowQuery}@si", $table[ 1 ], $rows );

		if ( empty( $rows[ 1 ] ) ) return FALSE;

		$result = [];
		$j      = 0;
		$count  = count( $rows[ 1 ] );

		for( $i = 0; $i < $count; $i++ ) {

			$assignedValue = FALSE;

			if ( $sortKey == '' AND $limit > 0 AND $i >= $limit ) break;

			for ( $k = 0; $k < count( $queryList ); $k++ ) {

				$rowValue = static::match( $queryList[ $k ], NULL, FALSE, $rows[ 1 ][ ( $last == TRUE ) ? $count - $i - 1 : $i ] );

				if ( $rowValue != FALSE ) {

					if ( $customChanges != NULL && isset( $customChanges[ $keyList[ $k ] ] ) ) {

						$rowValue = call_user_func( $customChanges[ $keyList[ $k ] ], $rowValue );
					}
					else {

						$rowValue = static::reflection( $config, $rowValue, $keyList[ $k ] );
					}

					if ( $rowValue != NULL AND !empty( $rowValue ) ) {

						$assignedValue                  = TRUE;
						$result[ $j ][ $keyList[ $k ] ] = $rowValue;
					}
				}
			}

			if ( $assignedValue ) $j++;
		}

		if ( $sortKey != '' AND isset( $result[ 0 ][ $sortKey ] ) ) {

			usort( $result,

				function( $a, $b ) use ( $sortKey ) {

					return $a[ $sortKey ] - $b[ $sortKey ];
				}
			);

			$result = array_slice( $result, 0, $limit );
		}

		return ( !empty( $result ) ) ? $result : FALSE;
	}
}