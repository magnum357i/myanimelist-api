<?php

/**
 * Text Converts
 *
 * @package 		MyAnimeList API
 * @author 			Magnum357 [https://github.com/magnum357i/]
 * @copyright 		2018
 * @license 		http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace MyAnimeList\Helper;

class Text {

	/**
	 * Names becomes the first-last order instead of the last-first order
	 *
	 * @param 		string 				$name 					A person name with a comma
	 * @param 		int 				$mode 					Reverse mode
	 * @return 		string
	 */
	public static function reverseName( $name, $mode=1 ) {

		switch ( $mode ) {

			case 1: $name = static::replace( '(.+),\s*(.+)',            '$2 $1',    $name ); break;
			case 2: $name = static::replace( '(.+),\s*(.+)\s*(\(.+\))', '$2 $1 $3', $name ); break;
			case 3: $name = static::replace( '(.+)(\s*"[^"]+"\s*)(.+)', '$3$2$1',   $name ); break;
		}

		return $name;
	}

	/**
	 * Separates the text from a character and returns it as array
	 *
	 * @param 		string 				$value 				A text
	 * @param 		string 				$exp 				Seperate character
	 * @return 		array
	 */
	public static function listValue( $value, $exp ) {

		$result = [];
		$splits = explode( $exp, $value );

		foreach( $splits as $split ) {

			$split = trim( $split );

			if ( \strlen( $split ) > 0 and $split != "..." and $split != "&nbsp;" ) {

				$result[] = $split;
			}
		}

		return ( count( $result ) > 0 ) ? $result : FALSE;
	}

	/**
	 * K (number/1000) Converter
	 *
	 * @param 		int 				$number 				A number
	 * @return 		string
	 */
	public static function formatK( $number ) {

		$number = static::replace( '[^0-9]+', '', $number );

		if ( !static::validate( $number, 'number' ) ) return FALSE;

		return ( $number > 1000 ) ? round( $number / 1000 ) . 'K' : (string) $number;
	}

	/**
	 * Round a number
	 *
	 * @param 		int 				$number 				A number
	 * @param 		int 				$precision 				Decimal
	 * @return 		string
	 */
	public static function roundNumber( $number, $precision=0 ) {

		return (string) round( static::replace( '[,]', '.', $number ), $precision );
	}

	/**
	 * Clean description
	 *
	 * @param 		string 				$desc 				Description to clean
	 * @return 		string
	 */
	public static function descCleaner( $desc ) {

		$desc = static::replace( '\s*\[written by mal rewrite\]', '', $desc, 'si' );
		$desc = static::replace( '\(source\:[^\(]+\)\s*',         '', $desc, 'si' );

		$maxSearch     = 10;
		$count         = 1;
		$patternSearch = '<br \/>\s*$';

		while ( $count < $maxSearch ) {

			if ( static::validate( $desc, 'string', [ 'regex' => $patternSearch ] ) ) {

				$desc = static::replace( $patternSearch, '', $desc, 'si' );
			}
			else {

				break;
			}

			$count++;
		}

		$count         = 1;
		$patternSearch = '^\s*<br \/>\s*';

		while ( $count < $maxSearch ) {

			if ( static::validate( $desc, 'string', [ 'regex' => $patternSearch ] ) ) {

				$desc = static::replace( $patternSearch, '', $desc, 'si' );
			}
			else {

				break;
			}

			$count++;
		}

		return ( !static::validate( $desc, 'string', [ 'min' => 20 ] ) ) ? FALSE : $desc;
	}

	/**
	 * Validate
	 *
	 * @param 		mixed 				$value 					Value to validate
	 * @param 		array 				$mode 					Validate mode
	 * @param 		array 				$options 				Options to validate modes
	 * @return 		bool
	 */
	public static function validate( $value, $mode, $options=[] ) {

		$ok = FALSE;

		switch ( $mode ) {

			case 'bool':

				$ok = ( is_bool( $value ) ) ? TRUE : FALSE;

			break;
			case 'string':

				if ( isset( $options[ 'regex' ] ) ) {

					$ok = preg_match( '@' . $options[ 'regex' ] . '@si', $value );
				}
				else if ( isset( $options[ 'min' ] ) OR isset( $options[ 'max' ] ) ) {

					$count = mb_strlen( $value );

					if ( isset( $options[ 'min' ] ) AND !isset( $options[ 'max' ] ) ) {

						$ok = ( $count >= $options[ 'min' ] ) ? TRUE : FALSE;
					}
					else if ( !isset( $options[ 'max' ] ) AND isset( $options[ 'max' ] ) ) {

						$ok = ( $count <= $options[ 'max' ] ) ? TRUE : FALSE;
					}
					else  {

						$ok = ( $count >= $options[ 'min' ] AND $count <= $options[ 'max' ] ) ? TRUE : FALSE;
					}
				}

			break;
			case 'number':

				$ok = ( is_numeric( $value ) ) ? TRUE : FALSE;

				if ( $ok ) {

					if ( isset( $options[ 'min' ] ) AND !isset( $options[ 'max' ] ) ) {

						$ok = ( $value >= $options[ 'min' ] ) ? TRUE : FALSE;
					}
					else if ( !isset( $options[ 'max' ] ) AND isset( $options[ 'max' ] ) ) {

						$ok = ( $value <= $options[ 'max' ] ) ? TRUE : FALSE;
					}
					else if ( isset( $options[ 'min' ] ) AND isset( $options[ 'max' ] ) )  {

						$ok = ( $value >= $options[ 'min' ] AND $value <= $options[ 'max' ] ) ? TRUE : FALSE;
					}
				}

			break;
		}

		return $ok;
	}

	/**
	 * Change string simply
	 *
	 * @param 		string 				$match 					Old value in regex format
	 * @param 		string 				$replace 				New value
	 * @param 		string 				$str 					A text
	 * @param 		string 				$flags 					Regex flags
	 * @return 		string
	 */
	public static function replace( $match, $replace, $str, $flags='' ) {

		return preg_replace( '@' . $match . '@' . $flags, $replace, $str );
	}

	/**
	 * Converts date format date to original date
	 *
	 * @param 		string 				$month 				Month
	 * @param 		string 				$day 				Day
	 * @param 		string 				$year 				Year
	 * @return 		array
	 */
	public static function originalDate( $month, $day, $year ) {

		$day    = ( mb_strlen( $day ) == 1 ) ? '0' . $day : $day;
		$month  = mb_substr( $month, 0, 3 );
		$months = [

			'Jan' => '01', 'Feb' => '02', 'Mar' => '03', 'Apr' => '04', 'May' => '05', 'Jun' => '06',
			'Jul' => '07', 'Aug' => '08', 'Sep' => '09', 'Oct' => '10', 'Nov' => '11', 'Dec' => '12'
		];

		if ( isset( $months[ $month ] ) ) return [ 'month' => $months[ $month ], 'day' => $day, 'year' => $year ];

		return FALSE;
	}

	/**
	 * Remove japanese characters
	 *
	 * @param 		string 				$str 				String with japanese characters
	 * @return 		array
	 */
	public static function removeJapChars( $str ) {

		$str = preg_replace( '/[\p{Han}\p{Katakana}\p{Hiragana}ー・.、;]+/u', '', $str );
		$str = str_replace( '()', '', $str );
		$str = str_replace( '( ', '(', $str );
		$str = preg_replace( '/\([\p{P}\s]+\)/u', '', $str );

		return $str;
	}

	/**
	 * Timezone conversion
	 *
	 * @param 		string 				$timezone 				'default' or Timezone
	 * @param 		array 				$aired 					E.g: [ 'year' => 'xxxx', 'month' => 'xx', 'day' => 'xx' ]
	 * @param 		array 				$value 					A array with timezone, hour, minute, dayIndex and dayTitle
	 * @return 		array
	 */
	public static function convertTimezone( $timezone, $aired, $value ) {

		if ( $timezone == 'default' ) $timezone = date_default_timezone_get();

		if  ( $value[ 'timezone' ] != $timezone AND isset( $aired ) ) {

			$date = new \DateTime(

				implode( '-', [ $aired[ 'year' ], $aired[ 'month' ], $aired[ 'day' ] ] ) . ' ' . implode( ':', [ $value[ 'hour' ], $value[ 'minute' ] ] ),
				new \DateTimeZone( $value[ 'timezone' ] )
			);

			$days                = [ 'Monday' => 1, 'Tuesday' => 2, 'Wednesday' => 3, 'Thursday' => 4, 'Friday' => 5, 'Saturday' => 6, 'Sunday' => 7 ];
			$newValues           = explode( '-', $date->setTimezone( new \DateTimeZone( $timezone ) )->format( 'H-i-l' ) );
			$value[ 'timezone' ] = $timezone;
			$value[ 'hour' ]     = $newValues[ 0 ];
			$value[ 'minute' ]   = $newValues[ 1 ];
			$value[ 'dayTitle' ] = "{$newValues[ 2 ]}s";
			$value[ 'dayIndex' ] = (string) $days[ $newValues[ 2 ] ];
		}

		return $value;
	}
}