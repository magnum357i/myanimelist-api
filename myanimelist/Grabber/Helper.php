<?php

/**
 * MyAnimeList Helper Functions
 *
 * @author     		Magnum357 [https://github.com/magnum357i/]
 * @copyright  		2018
 * @license    		http://www.opensource.org/licenses/mit-license.html  MIT License
 * @version    		0.8.3
 */

namespace myanimelist\Grabber;

trait Helper
{
	/**
	 * Assign a value to static::data
	 *
	 * @param  string   	$key   		key of array of $data
	 * @param  string   	$value   	value of array of $data
	 * @return void | bool
	 */
	private static function setValue( $key, $value )
	{
		if ( $value )
		{
			static::$data[ $key ] = $value;

			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	/**
	 * Return assigned values
	 *
	 * @return array
	 */
	public function output()
	{
		return static::$data;
	}

	/**
	 * Match string from raw html
	 *
	 * @param   string   	$match   		Regex code for match ( except the start and end character )
	 * @param   string   	$allow_tags   	Which tags should not be deleted?
	 * @return	string | bool (FALSE)
	 */
    protected static function match( $match, $allow_tags=NULL )
    {
        preg_match( '@' . $match . '@si', static::$content, $result );

        if ( isset( $result[1] ) )
        {
        	$match =  $result[1];

        	if ( $allow_tags != NULL)
        	{
				$match = strip_tags( $result[1], $allow_tags );
			}
			else
			{
				$match = strip_tags( $result[1] );
			}

			$match = trim( $match );

			return  $match;
        }
        else
        {
            return FALSE;
        }
    }

	/**
	 * K (number/1000) Converter
	 *
	 * @param   string   	$allow_tags   	Number to convert
	 * @return	bool
	 */
    protected static function formatK( $number )
    {
		$number = static::replace( '[^0-9]+', '', $number );

		if ( !static::validate( array( 'mode' => 'number' ), $number ) )
		{
			return FALSE;
		}

		return ( $number > 1000 ) ? round( $number / 1000 ) . 'K' : $number;
    }

	/**
	 * Clean desc
	 *
	 * @param   string   	$desc   	Description to clean
	 * @return	string
	 */
    protected static function descCleaner( $desc )
   	{
   		# Unnecessary Strings

		$desc = static::replace( '\s*\[written by mal rewrite\]', '', $desc, 'si' );
		$desc = static::replace( '\(source\:[^\(]+\)\s*',         '', $desc, 'si' );
		$desc = static::replace( '\s*Included one\-shot.+',       '', $desc, 'si' );
		$desc = static::replace( 'this series is on hiatus.+',    '', $desc, 'si' );

   		# Br tags

   		// Search for br tag in start and begin of desc
		$removelastbr_maxcount = 10;

		$removelastbr_count    = 1;
		$removelastbr_pattern  = '<br \/>\s*$';

		// Remove br tags in start of desc
		while ( $removelastbr_count < $removelastbr_maxcount )
		{
			if ( static::validate( array( 'mode' => 'regex', 'regex_code' => $removelastbr_pattern ), $desc ) )
			{
				$desc = static::replace( $removelastbr_pattern, '', $desc, 'si' );
			}
			else
			{
				break;
			}

			$removelastbr_count++;
		}

		// Reset counter
		$removelastbr_count    = 1;
		// New pattern for end
		$removelastbr_pattern = '^\s*<br \/>\s*';

		// Remove br tags in end of desc
		while ( $removelastbr_count < $removelastbr_maxcount )
		{
			if ( static::validate( array( 'mode' => 'regex', 'regex_code' => $removelastbr_pattern ), $desc ) )
			{
				$desc = static::replace( $removelastbr_pattern, '', $desc, 'si' );
			}
			else
			{
				break;
			}

			$removelastbr_count++;
		}

		return ( !static::validate( array( 'mode' => 'count', 'max_len' => 20 ), $desc ) OR static::validate( array( 'mode' => 'regex', 'regex_code' => 'no biography written', 'regex_flags' => 'si' ), $desc ) ) ? FALSE : $desc;
   	}

	/**
	 * Validate
	 *
	 * @param   array   	$options   	Options to validate mods
	 * @param   string   	$data   	String to check
	 * @return	bool
	 */
    protected static function validate( $options, $data )
    {
    	switch ( $options[ 'mode' ] )
    	{
    		case 'regex':  return preg_match( '/' . $options[ 'regex_code' ] . '/' . ( isset( $options[ 'regex_flags' ] ) ? $options[ 'regex_flags' ] : '' ), $data, $result ) ? TRUE : FALSE; break;

    		case 'number': return ( is_numeric( $data ) ) ? TRUE : FALSE; break;

    		case 'count':  return ( mb_strlen( $data ) > $options[ 'max_len' ] ) ? TRUE : FALSE; break;
    	}

    	return 	FALSE;
    }

	/**
	 * Change string simply
	 *
	 * @param   string   	$match   	Old value in regex format
	 * @param   string   	$replace   	New value
	 * @param   string   	$str   		A text
	 * @param   string   	$flags   	Regex flags
	 * @return	string
	 */
    protected static function replace( $match, $replace, $str, $flags='' )
    {
        return preg_replace( '/' . $match . '/' . $flags, $replace, $str );
    }

	/**
	 * Separates the text from a character and returns it as array
	 *
	 * @param   string   	$value   	A text
	 * @param   string   	$exp   		Seperate character
	 * @return	array
	 */
    protected static function listValue( $value, $exp )
    {
    	if ( $value == FALSE )
    	{
    		return FALSE;
    	}

    	$result = array();
    	$splits = explode( $exp, $value );

		foreach( $splits as $split )
		{
			$split = trim( $split );

			if ( \strlen($split) > 0 and $split != "..." and $split != "&nbsp;" )
			{
				$result[] = static::lastChanges( $split );
			}
		}

		return ( count( $result ) > 0 ) ? $result : FALSE;
    }

	/**
	 * Names becomes the first-last order instead of the last-first order
	 *
	 * @param   string   	$name   	A person name with a comma
	 * @param   string   	$mode   	Reverse mode
	 * @return	array
	 */
    protected static function reverseName( $name, $mode=1 )
    {
    	switch ( $mode )
    	{
    		case '1': return static::replace( '(.+),\s*(.+)',            '$2 $1',    $name ); break;
    		case '2': return static::replace( '(.+),\s*(.+)\s*(\(.+\))', '$2 $1 $3', $name ); break;
    		case '3': return static::replace( '(.+)(\s*"[^"]+"\s*)(.+)', '$3$2$1',   $name ); break;
    	}

    	return $name;
    }

	/**
	 * Get data as table
	 *
	 * @param   string   	$table_query   	A regex code to match a table
	 * @param   string   	$row_query   	A regex code to match a row in the table
	 * @param   array   	$query_list   	A regex code to match a value in the row
	 * @param   array   	$key_list   	A key to assign the value in the row
	 * @param   string   	$limit 		  	How many records will return?
	 * @param   bool 	  	$last 		  	Reverse sorting?
	 * @param   string 	  	$sort_query 	Is it especially ordered by value?
	 * @return	array
	 */
    protected static function matchTable( $table_query='', $row_query='', $query_list=array(), $key_list=array(), $limit=0, $last=FALSE, $sort_query='' )
    {
		if ( empty( $query_list ) OR empty( $key_list ) )
		{
			return FALSE;
		}

		preg_match( '@' . $table_query . '@si', static::$content, $table );

		if ( empty( $table[1] ) )
		{
			return FALSE;
		}

		preg_match_all( '@' . $row_query . '@si', $table[1], $rows );

		if ( empty( $rows[1] ) )
		{
			return FALSE;
		}

		$reflection = function( $value, $key )
		{
			$value = strip_tags( $value );
			$value = trim( $value );

			if ( preg_match( '/link/', $key, $no ) )
			{
				$value = static::$url . $value;
				$value = static::lastChanges( $value );
			}
			else if ( static::$reverseName == TRUE && preg_match( '/name/', $key, $no ) )
			{
				$value = static::reverseName( $value );
				$value = static::lastChanges( $value );
			}
			else if ( preg_match('/list/', $key, $no ) )
			{
				$value = static::listValue( $value, "," );
			}
			else
			{
				$value = static::lastChanges( $value );
			}

			return $value;
		};

		$i      = 0;
		$result = array();
		$count  = count( $rows[1] );

		while( $i < $count )
		{
			if ( $sort_query == '' AND $limit > 0 AND $i >= $limit )
			{
				break;
			}

			for ( $k = 0; $k < count( $query_list ); $k++ )
			{
				preg_match( '@' . $query_list[ $k ] . '@si', $rows[1][ ( $last == TRUE ) ? $count - $i - 1 : $i ], $row_value );

				if ( !empty( $row_value[1] ) )
				{
					$row_value = $row_value[1];

					if ( $sort_query == '' )
					{
						$row_value = $reflection( $row_value, $key_list[ $k ] );
					}
					else
					{
						if ( !isset( $result[ $i ][ 'sort' ] ) )
						{
							preg_match( '@' . $sort_query . '@si', $rows[1][ ( $last == TRUE ) ? $count - $i - 1 : $i ], $sort_value );

							if ( !empty( $sort_value[1] ) )
							{
								$result[ $i ]['sort'] = $sort_value[1];
							}
						}
					}

					$result[ $i ][ $key_list[ $k ] ] = $row_value;
				}
			}

			$i++;
		}

		if ( $sort_query != '' AND isset( $result[0][ 'sort' ] ) )
		{
			usort( $result, function( $a, $b )
			{
    			return $a[ 'sort' ] - $b[ 'sort' ];
			});

			$temp_result = array();

			foreach ( $result as $i => $key )
			{
				if ( $limit > 0 AND $i >= $limit )
				{
					break;
				}

				for ( $k = 0; $k < count( $query_list ); $k++ )
				{
					if ( isset( $result[ ( $last == TRUE ) ? $count - $i - 1 : $i ][ $key_list[ $k] ] ) )
					{
						$temp_result[ $i ][ $key_list[ $k ] ] = $reflection( $result[ ( $last == TRUE ) ? $count - $i - 1 : $i ][ $key_list[ $k ] ], $key_list[ $k ] );
					}
				}
			}

			$result = $temp_result;
		}

		return ( count( $result ) > 0) ? $result : FALSE;
    }
}