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
	 * @param 		string 			$match  			Regex code for match ( except the start and end character )
	 * @param 		bool 			$allow_tags 		Which tags should not be deleted?
	 * @return 		string
	 */
	public function match( $match, $allow_tags=NULL ) {

		preg_match( '@' . $match . '@si', static::$content, $result );

		if ( isset( $result[1] ) ) {

			$match =  $result[1];

			if ( $allow_tags != NULL) {

				$match = strip_tags( $result[1], $allow_tags );
			}
			else {

				$match = strip_tags( $result[1] );
			}

			$match = trim( $match );

			return  $match;
		}
		else {

			return FALSE;
		}
	}

	/**
	 * Get data as table
	 *
	 * @param 		callable 		$lastChanges 		Things before write
	 * @param 		object 			$config 			Config object
	 * @param 		object 			$text 				Text object
	 * @param 		string 			$table_query 		A regex code to match a table
	 * @param 		string 			$row_query 			A regex code to match a row in the table
	 * @param 		array 			$query_list 		A regex code to match a value in the row
	 * @param 		array 			$key_list 			A key to assign the value in the row
	 * @param 		string 			$limit 				How many records will return?
	 * @param 		bool 			$last 				Reverse sorting?
	 * @param 		string 			$sort_query 		Is it especially ordered by value?
	 * @return 		array
	 */
	public function matchTable( callable $lastChanges, \myanimelist\Helper\Config $config, \myanimelist\Helper\Text $text, $table_query='', $row_query='', $query_list=[], $key_list=[], $limit=0, $last=FALSE, $sort_query='' ) {

		if ( empty( $query_list ) OR empty( $key_list ) ) return FALSE;

		preg_match( '@' . $table_query . '@si', static::$content, $table );

		if ( empty( $table[1] ) ) return FALSE;

		preg_match_all( '@' . $row_query . '@si', $table[1], $rows );

		if ( empty( $rows[1] ) ) return FALSE;

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
			else if ( preg_match('/list/', $key, $no ) ) {

				$value = $text->listValue( $value, ',', $lastChanges );
			}
			else {

				$value = call_user_func( $lastChanges, $value );
			}

			return $value;
		};

		$i      = 0;
		$result = [];
		$count  = count( $rows[1] );

		while( $i < $count ) {

			if ( $sort_query == '' AND $limit > 0 AND $i >= $limit ) {

				break;
			}

			for ( $k = 0; $k < count( $query_list ); $k++ ) {

				preg_match( '@' . $query_list[ $k ] . '@si', $rows[1][ ( $last == TRUE ) ? $count - $i - 1 : $i ], $row_value );

				if ( !empty( $row_value[1] ) ) {

					$row_value = $row_value[1];

					if ( $sort_query == '' ) {

						$row_value = $reflection( $lastChanges, $config, $text, $row_value, $key_list[ $k ] );
					}
					else {

						if ( !isset( $result[ $i ][ 'sort' ] ) ) {

							preg_match( '@' . $sort_query . '@si', $rows[1][ ( $last == TRUE ) ? $count - $i - 1 : $i ], $sort_value );

							if ( !empty( $sort_value[1] ) ) {

								$result[ $i ][ 'sort' ] = $sort_value[1];
							}
						}
					}

					$result[ $i ][ $key_list[ $k ] ] = $row_value;
				}
			}

			$i++;
		}

		if ( $sort_query != '' AND isset( $result[0][ 'sort' ] ) ) {

			usort( $result, function( $a, $b ) {

				return $a[ 'sort' ] - $b[ 'sort' ];
			});

			$temp_result = [];

			foreach ( $result as $i => $key ) {

				if ( $limit > 0 AND $i >= $limit ) break;

				for ( $k = 0; $k < count( $query_list ); $k++ ) {

					if ( isset( $result[ ( $last == TRUE ) ? $count - $i - 1 : $i ][ $key_list[ $k] ] ) ) {

						$temp_result[ $i ][ $key_list[ $k ] ] = $reflection( $lastChanges, $config, $text, $result[ ( $last == TRUE ) ? $count - $i - 1 : $i ][ $key_list[ $k ] ], $key_list[ $k ] );
					}
				}
			}

			$result = $temp_result;
		}

		return ( count( $result ) > 0) ? $result : FALSE;
	}
}