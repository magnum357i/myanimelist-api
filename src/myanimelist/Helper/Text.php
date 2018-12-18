<?php

/**
 * Text Converts
 *
 * @package	 		MyAnimeList API
 * @author     		Magnum357 [https://github.com/magnum357i/]
 * @copyright  		2018
 * @license    		http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace myanimelist\Helper;

class Text {

	/**
	 * Names becomes the first-last order instead of the last-first order
	 *
	 * @param 		string 			$name 			A person name with a comma
	 * @param 		string 			$mode 			Reverse mode
	 * @return 		array
	 */
	public function reverseName( $name, $mode='1' ) {

		switch ( $mode ) {

			case '1': return $this->replace( '(.+),\s*(.+)',            '$2 $1',    $name ); break;
			case '2': return $this->replace( '(.+),\s*(.+)\s*(\(.+\))', '$2 $1 $3', $name ); break;
			case '3': return $this->replace( '(.+)(\s*"[^"]+"\s*)(.+)', '$3$2$1',   $name ); break;
		}

		return $name;
	}

	/**
	 * Separates the text from a character and returns it as array
	 *
	 * @param 		string 			$value 					A text
	 * @param 		string 			$exp 					Seperate character
	 * @param 		callback 		$lastChanges 			Run function before returned any value
	 * @return 		array
	 */
	public function listValue( $value, $exp, callable $lastChanges ) {

		if ( $value == FALSE ) {

			return FALSE;
		}

		$result = array();
		$splits = explode( $exp, $value );

		foreach( $splits as $split ) {

			$split = trim( $split );

			if ( \strlen( $split ) > 0 and $split != "..." and $split != "&nbsp;" ) {

				$result[] = call_user_func( $lastChanges, $split );
			}
		}

		return ( count( $result ) > 0 ) ? $result : FALSE;
	}

	/**
	 * K (number/1000) Converter
	 *
	 * @param 		int 			$number 			A number
	 * @return 		bool
	 */
	public function formatK( $number ) {

		$number = $this->replace( '[^0-9]+', '', $number );

		if ( !$this->validate( array( 'mode' => 'number' ), $number ) ) {

			return FALSE;
		}

		return ( $number > 1000 ) ? round( $number / 1000 ) . 'K' : $number;
	}

	/**
	 * Clean desc
	 *
	 * @param 		string 			$desc 			Description to clean
	 * @return 		string
	 */
	public function descCleaner( $desc ) {

		# Unnecessary Strings

		$desc = $this->replace( '\s*\[written by mal rewrite\]', '', $desc, 'si' );
		$desc = $this->replace( '\(source\:[^\(]+\)\s*',         '', $desc, 'si' );
		$desc = $this->replace( '\s*Included one\-shot.+',       '', $desc, 'si' );
		$desc = $this->replace( 'this series is on hiatus.+',    '', $desc, 'si' );

		# Br tags

		// Search for br tag in start and begin of desc

		$removelastbr_maxcount = 10;

		$removelastbr_count    = 1;
		$removelastbr_pattern  = '<br \/>\s*$';

		// Remove br tags in start of desc

		while ( $removelastbr_count < $removelastbr_maxcount ) {

			if ( $this->validate( array( 'mode' => 'regex', 'regex_code' => $removelastbr_pattern ), $desc ) ) {

				$desc = $this->replace( $removelastbr_pattern, '', $desc, 'si' );
			}
			else {

				break;
			}

			$removelastbr_count++;
		}

		// Reset counter

		$removelastbr_count    = 1;

		// New pattern for end

		$removelastbr_pattern = '^\s*<br \/>\s*';

		// Remove br tags in end of desc

		while ( $removelastbr_count < $removelastbr_maxcount ) {

			if ( $this->validate( array( 'mode' => 'regex', 'regex_code' => $removelastbr_pattern ), $desc ) ) {

				$desc = $this->replace( $removelastbr_pattern, '', $desc, 'si' );
			}
			else {

				break;
			}

			$removelastbr_count++;
		}

		return ( !$this->validate( array( 'mode' => 'count', 'max_len' => 20 ), $desc ) ) ? FALSE : $desc;
	}

	/**
	 * Validate
	 *
	 * @param 		array 			$options 			Options to validate mods
	 * @param 		string 			$text 				String to check
	 * @return 		bool
	 */
	public function validate( $options, $text ) {

		switch ( $options[ 'mode' ] ) {

			case 'regex':  return preg_match( '/' . $options[ 'regex_code' ] . '/' . ( isset( $options[ 'regex_flags' ] ) ? $options[ 'regex_flags' ] : '' ), $text, $result ) ? TRUE : FALSE; break;

			case 'number': return ( is_numeric( $text ) ) ? TRUE : FALSE; break;

			case 'count':  return ( mb_strlen( $text ) > $options[ 'max_len' ] ) ? TRUE : FALSE; break;
		}

		return 	FALSE;
	}

	/**
	 * Change string simply
	 *
	 * @param 		string 			$match 				Old value in regex format
	 * @param 		string 			$replace 			New value
	 * @param 		string 			$str 				A text
	 * @param 		string 			$flags 				Regex flags
	 * @return 		string
	 */
	public function replace( $match, $replace, $str, $flags='' ) {

		return preg_replace( '/' . $match . '/' . $flags, $replace, $str );
	}
}