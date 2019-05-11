<?php

/**
 * Text Converts
 *
 * @package	 		MyAnimeList API
 * @author     		Magnum357 [https://github.com/magnum357i/]
 * @copyright  		2018
 * @license    		http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace MyAnimeList\Helper;

class Text {

	/**
	 * Names becomes the first-last order instead of the last-first order
	 *
	 * @param 		string 			$name 			A person name with a comma
	 * @param 		int 				$mode 			Reverse mode
	 * @return 		string
	 */
	public function reverseName( $name, $mode=1 ) {

		switch ( $mode ) {

			case 1: $name = $this->replace( '(.+),\s*(.+)',            '$2 $1',    $name ); break;
			case 2: $name = $this->replace( '(.+),\s*(.+)\s*(\(.+\))', '$2 $1 $3', $name ); break;
			case 3: $name = $this->replace( '(.+)(\s*"[^"]+"\s*)(.+)', '$3$2$1',   $name ); break;
		}

		return $name;
	}

	/**
	 * Separates the text from a character and returns it as array
	 *
	 * @param 		string 		$value 				A text
	 * @param 		string 		$exp 					Seperate character
	 * @return 		array
	 */
	public function listValue( $value, $exp ) {

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
	 * @param 		int 			$number 			A number
	 * @return 		string
	 */
	public function formatK( $number ) {

		$number = $this->replace( '[^0-9]+', '', $number );

		if ( !$this->validate( [ 'mode' => 'number' ], $number ) ) 	return FALSE;

		return ( $number > 1000 ) ? round( $number / 1000 ) . 'K' : $number;
	}

	/**
	 * Clean description
	 *
	 * @param 		string 			$desc 			Description to clean
	 * @return 		string
	 */
	public function descCleaner( $desc ) {

		$desc = $this->replace( '\s*\[written by mal rewrite\]', '', $desc, 'si' );
		$desc = $this->replace( '\(source\:[^\(]+\)\s*',         '', $desc, 'si' );

		$maxSearch     = 10;
		$count         = 1;
		$patternSearch = '<br \/>\s*$';

		while ( $count < $maxSearch ) {

			if ( $this->validate( [ 'mode' => 'regex', 'regex_code' => $patternSearch ], $desc ) ) {

				$desc = $this->replace( $patternSearch, '', $desc, 'si' );
			}
			else {

				break;
			}

			$count++;
		}

		$count         = 1;
		$patternSearch = '^\s*<br \/>\s*';

		while ( $count < $maxSearch ) {

			if ( $this->validate( [ 'mode' => 'regex', 'regex_code' => $patternSearch ], $desc ) ) {

				$desc = $this->replace( $patternSearch, '', $desc, 'si' );
			}
			else {

				break;
			}

			$count++;
		}

		return ( !$this->validate( [ 'mode' => 'count', 'max_len' => 20 ], $desc ) ) ? FALSE : $desc;
	}

	/**
	 * Validate
	 *
	 * @param 		array 			$options 			Options to validate mods
	 * @param 		string 			$text 			String to check
	 * @return 		bool
	 */
	public function validate( $options, $text ) {

		switch ( $options[ 'mode' ] ) {

			case 'regex':  return preg_match( '/' . $options[ 'regex_code' ] . '/' . ( isset( $options[ 'regex_flags' ] ) ? $options[ 'regex_flags' ] : '' ), $text ); break;

			case 'number': return ( is_numeric( $text ) ) ? TRUE : FALSE; break;

			case 'count':  return ( mb_strlen( $text ) > $options[ 'max_len' ] ) ? TRUE : FALSE; break;
		}

		return FALSE;
	}

	/**
	 * Change string simply
	 *
	 * @param 		string 			$match 			Old value in regex format
	 * @param 		string 			$replace 			New value
	 * @param 		string 			$str 				A text
	 * @param 		string 			$flags 			Regex flags
	 * @return 		string
	 */
	public function replace( $match, $replace, $str, $flags='' ) {

		return preg_replace( '/' . $match . '/' . $flags, $replace, $str );
	}

	/**
	 * Converts date format date to original date
	 *
	 * @param 		string 			$month 			Month
	 * @param 		string 			$day 				Day
	 * @param 		string 			$year 			Year
	 * @return 		array
	 */
	public function originalDate( $month, $day, $year ) {

		$day    = ( mb_strlen( $day ) == 1 ) ? '0' . $day : $day;
		$month  = mb_substr( $month, 0, 3 );
		$months = [

			'Jan' => '01', 'Feb' => '02', 'Mar' => '03', 'Apr' => '04', 'May' => '05', 'Jun' => '06',
			'Jul' => '07', 'Aug' => '08', 'Sep' => '09', 'Oct' => '10', 'Nov' => '11', 'Dec' => '12'
		];

		if ( isset( $months[ $month ] ) ) return [ 'month' => $months[ $month ], 'day' => $day, 'year' => $year ];

		return FALSE;
	}
}