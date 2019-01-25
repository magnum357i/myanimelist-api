<?php

/**
 * CURL Request
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
	protected $sent = FALSE;

	/**
	 * Is the request sent
	 *
	 * @return 		bool
	 */
	public function isSent() {

		return $this->sent;
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

			curl_setopt( $cSession, CURLOPT_URL,            static::$url );
			curl_setopt( $cSession, CURLOPT_RETURNTRANSFER, $curlOptions[ 'returnTransfer' ] );
			curl_setopt( $cSession, CURLOPT_HEADER,         $curlOptions[ 'header' ] );
			curl_setopt( $cSession, CURLOPT_USERAGENT,      $curlOptions[ 'userAgent' ] );
			curl_setopt( $cSession, CURLOPT_FOLLOWLOCATION, $curlOptions[ 'followLocation' ] );
			curl_setopt( $cSession, CURLOPT_CONNECTTIMEOUT, $curlOptions[ 'connectTimeout' ] );
			curl_setopt( $cSession, CURLOPT_TIMEOUT,        $curlOptions[ 'timeout' ] );
			curl_setopt( $cSession, CURLOPT_SSL_VERIFYHOST, $curlOptions[ 'ssl_verifyHost' ] );
			curl_setopt( $cSession, CURLOPT_SSL_VERIFYPEER, $curlOptions[ 'ssl_verifypeer' ] );

			static::$content = curl_exec( $cSession );
			static::$content = html_entity_decode( static::$content );

			if ( curl_getinfo( $cSession, CURLINFO_HTTP_CODE ) == 200 ) {

				$this->success = TRUE;
			}

			$this->sent = TRUE;

			curl_close( $cSession );
		}
		catch ( \Exception $e ) { }
	}

	/**
	 * Create url to request
	 *
	 * @param 		string 			$u 				Url without the site name
	 * @return 		void
	 */
	public function createUrl( $u ) {

		static::$url = static::SITE . $u;
	}

	/**
	 * Match string from raw html
	 *
	 * @param 		string 			$template  			Regex code for match ( except the start and end character )
	 * @param 		bool 			$allowTags 			Which tags should not be deleted?
	 * @return 		string
	 */
	public function match( $template, $allowTags=NULL ) {

		preg_match( '@' . $template . '@si', static::$content, $result );

		if ( isset( $result[ 1 ] ) ) {

			$m = $result[ 1 ];
			$m = ( $allowTags != NULL ) ? strip_tags( $m, $allowTags ) : strip_tags( $m );
			$m = trim( $m );

			return  $m;
		}
		else {

			return FALSE;
		}
	}

	/**
	 * Match strings from raw html
	 *
	 * @param 		string 			$templates  		Regex code for match ( except the start and end character )
	 * @param 		bool 			$allowTags 			Which tags should not be deleted?
	 * @return 		string
	 */
	public function matchGroup( $templates, $allowTags=NULL ) {

		$result = FALSE;

		foreach ( $templates as $template ) {

			$result = $this->match( $template, $allowTags );

			if ( $result != FALSE ) break;
		}

		return $result;
	}

	/**
	 * Get data as table
	 *
	 * @param 		callable 		$lastChanges 		Things before write
	 * @param 		object 			$config 			Config object
	 * @param 		object 			$text 				Text object
	 * @param 		string 			$tableQuery 		A regex code to match a table
	 * @param 		string 			$rowQuery 			A regex code to match a row in the table
	 * @param 		array 			$queryList 			A regex code to match a value in the row
	 * @param 		array 			$keyList 			A key to assign the value in the row
	 * @param 		string 			$limit 				How many records will return?
	 * @param 		bool 			$last 				Reverse sorting?
	 * @param 		string 			$sortQuery 			Is it especially ordered by value?
	 * @return 		array
	 */
	public function matchTable( callable $lastChanges, \myanimelist\Helper\Config $config, \myanimelist\Helper\Text $text, $tableQuery='', $rowQuery='', $queryList=[], $keyList=[], $limit=0, $last=FALSE, $sortQuery='' ) {

		if ( empty( $queryList ) OR empty( $keyList ) ) return FALSE;

		preg_match( '@' . $tableQuery . '@si', static::$content, $table );

		if ( empty( $table[ 1 ] ) ) return FALSE;

		preg_match_all( '@' . $rowQuery . '@si', $table[ 1 ], $rows );

		if ( empty( $rows[ 1 ] ) ) return FALSE;

		$reflection = function( $lastChanges, $config, $text, $value, $key ) {

			$value = strip_tags( $value );
			$value = trim( $value );

			if ( preg_match( '/link/', $key, $no ) ) {

				$value = static::SITE . $value;
				$value = call_user_func( $lastChanges, $value );
			}
			else if ( $config->reverseName == TRUE && preg_match( '/name/', $key, $no ) ) {

				$value = $text->reverseName( $value );
				$value = call_user_func( $lastChanges, $value );
			}
			else if ( preg_match( '/list/', $key, $no ) ) {

				$value = $text->listValue( $value, ',', $lastChanges );
			}
			else {

				$value = call_user_func( $lastChanges, $value );
			}

			return $value;
		};

		$i      = 0;
		$result = [];
		$count  = count( $rows[ 1 ] );

		while( $i < $count ) {

			if ( $sortQuery == '' AND $limit > 0 AND $i >= $limit ) break;

			for ( $k = 0; $k < count( $queryList ); $k++ ) {

				preg_match( '@' . $queryList[ $k ] . '@si', $rows[ 1 ][ ( $last == TRUE ) ? $count - $i - 1 : $i ], $row_value );

				if ( !empty( $row_value[ 1 ] ) ) {

					$row_value = $row_value[ 1 ];

					if ( $sortQuery == '' ) {

						$row_value = $reflection( $lastChanges, $config, $text, $row_value, $keyList[ $k ] );
					}
					else {

						if ( !isset( $result[ $i ][ 'sort' ] ) ) {

							preg_match( '@' . $sortQuery . '@si', $rows[ 1 ][ ( $last == TRUE ) ? $count - $i - 1 : $i ], $sort_value );

							if ( !empty( $sort_value[ 1 ] ) ) {

								$result[ $i ][ 'sort' ] = $sort_value[ 1 ];
							}
						}
					}

					$result[ $i ][ $keyList[ $k ] ] = $row_value;
				}
			}

			$i++;
		}

		if ( $sortQuery != '' AND isset( $result[ 0 ][ 'sort' ] ) ) {

			usort( $result, function( $a, $b ) {

				return $a[ 'sort' ] - $b[ 'sort' ];
			});

			$temp_result = [];

			foreach ( $result as $i => $key ) {

				if ( $limit > 0 AND $i >= $limit ) break;

				for ( $k = 0; $k < count( $queryList ); $k++ ) {

					if ( isset( $result[ ( $last == TRUE ) ? $count - $i - 1 : $i ][ $keyList[ $k] ] ) ) {

						$temp_result[ $i ][ $keyList[ $k ] ] = $reflection( $lastChanges, $config, $text, $result[ ( $last == TRUE ) ? $count - $i - 1 : $i ][ $keyList[ $k ] ], $keyList[ $k ] );
					}
				}
			}

			$result = $temp_result;
		}

		return ( count( $result ) > 0 ) ? $result : FALSE;
	}
}