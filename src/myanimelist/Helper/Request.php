<?php

/**
 * CURL Request Class
 *
 * @package	 		MyAnimeList API
 * @author     		Magnum357 [https://github.com/magnum357i/]
 * @copyright  		2018
 * @license    		http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace myanimelist\Helper;

class Request {

	/**
	 * Request url
	 */
	public static $url = NULL;

	/**
	 * Website url
	 */
	const SITE = 'https://myanimelist.net/';

	/**
	 * Here will load the raw html
	 */
	public static $content = '';

	/**
	 * Status of send
	 */
	protected static $sent = FALSE;

	/**
	 * Is the request sent
	 *
	 * @return 		bool
	 */
	public static function isSent() {

		return static::$sent;
	}

	/**
	 * Status of send
	 */
	protected $success = FALSE;

	/**
	 * Is the request successfully?
	 *
	 * @return 		bool
	 */
	public function isSuccess() {

		return $this->success;
	}

	/**
	 * Load the raw html to static::$content
	 *
	 * @param 		array 			$curlOptions 		Curl options
	 * @return 		void
	 */
	public function send( $curlOptions=[] ) {

		try {

			$cSession = curl_init();

			curl_setopt( $cSession, CURLOPT_URL, static::$url );

			foreach ( $curlOptions as $setting => $value ) {

				curl_setopt( $cSession, constant( "CURLOPT_{$setting}" ), $value );
			}

			static::$content = curl_exec( $cSession );
			static::$content = html_entity_decode( static::$content );

			if ( curl_getinfo( $cSession, CURLINFO_HTTP_CODE ) == 200 ) {

				$this->success = TRUE;
			}

			static::$sent = TRUE;

			curl_close( $cSession );
		}
		catch ( \Exception $e ) { }
	}

	/**
	 * Create url to request
	 *
	 * @param 		string 			$query 			Url without the site name
	 * @return 		void
	 */
	public function createUrl( $query ) {

		static::$url = static::SITE . $query;
	}

	/**
	 * Match string from raw html
	 *
	 * @param 		string 		$templates 				Regex template or templates
	 * @param 		bool 			$allowTags 				Which tags should not be deleted?
	 * @param 		bool 			$clean 				Strip value
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

			preg_match( '@' . $template . '@si', ( $content != NULL ) ? $content : static::$content, $out );

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
	 * Makes the value simple
	 *
	 * @param 		object 			$config 			Config object
	 * @param 		object 			$text 			Text object
	 * @param 		string 			$value 			A value
	 * @param 		string 			$key 				A key
	 * @return 		array
	 */
	public static function reflection( \myanimelist\Helper\Config $config, \myanimelist\Helper\Text $text, $value, $key ) {

		if ( $value == NULL ) return NULL;

		$value = strip_tags( $value );
		$value = trim( $value );

		if ( preg_match( '/link$/', $key ) ) {

			$value = static::SITE . $value;
		}
		else if ( $config->isOnNameConverting() && preg_match( '/name$/', $key ) ) {

			$value = $text->reverseName( $value );
		}
		else if ( preg_match( '/list$/', $key ) ) {

			$value = $text->listValue( $value, ',' );
		}

		return $value;
	}

	/**
	 * Get data as table
	 *
	 * @param 		object 			$config 			Config object
	 * @param 		object 			$text 			Text object
	 * @param 		string 			$tableQuery 		A regex code to match a table
	 * @param 		string 			$rowQuery 			A regex code to match a row in the table
	 * @param 		array 			$queryList 			A regex code to match a value in the row
	 * @param 		array 			$keyList 			A key to assign the value in the row
	 * @param 		string 			$limit 			How many records will return?
	 * @param 		array 			$customChanges 		Desired changes in the value
	 * @param 		bool 				$last 			Reverse sorting?
	 * @param 		string 			$sortKey 			Sort by key
	 * @return 		array
	 */
	public static function matchTable( \myanimelist\Helper\Config $config, \myanimelist\Helper\Text $text, $tableQuery='', $rowQuery='', $queryList=[], $keyList=[], $limit=0, $customChanges=NULL, $last=FALSE, $sortKey='' ) {

		preg_match( '@' . $tableQuery . '@si', static::$content, $table );

		if ( empty( $table ) ) return FALSE;

		preg_match_all( '@' . $rowQuery . '@si', $table[ 1 ], $rows );

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

						$rowValue = static::reflection( $config, $text, $rowValue, $keyList[ $k ] );
					}

					if ( $rowValue != NULL ) {

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

		return ( count( $result ) > 0 ) ? $result : FALSE;
	}
}