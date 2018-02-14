<?php

/**
 * MyAnimeList Anime Api
 *
 * @author     		Magnum357 [https://github.com/magnum357i/]
 * @copyright  		2018
 * @license    		http://www.opensource.org/licenses/mit-license.html  MIT License
 * @version    		0.8.3
 */

namespace myanimelist\Types;

class Anime
{
	use \myanimelist\Grabber\Helper, \myanimelist\Grabber\Request;

	/**
	 * Set type
	 */
	public static $type = 'anime';

	/**
	 * Get title
	 *
	 * @return void | string (in output)
	 */
	public function _titleoriginal()
	{
		$key = 'titleoriginal';

		if ( isset( static::$data[ $key ] ) )
		{
			return FALSE;
		}

		$data = static::match( '<span itemprop="name">(.*?)</span>' );

		if ( $data != FALSE )
		{
			$data = static::replace( '\s*\(.+\)', '', $data, 'si' );
		}

		return static::setValue( $key, static::lastChanges( $data ) );
	}

	/**
	 * Get title for english
	 *
	 * @return void | string (in output)
	 */
	public function _titleenglish()
	{
		$key = 'titleenglish';

		if ( isset( static::$data[ $key ] ) )
		{
			return FALSE;
		}

		$data = static::match( '<span class="dark_text">english:</span>(.*?)</div>' );

		return static::setValue( $key, static::lastChanges( $data ) );
	}

	/**
	 * Get title for japanese
	 *
	 * @return void | string (in output)
	 */
	public function _titlejapanese()
	{
		$key = 'titlejapanese';

		if ( isset( static::$data[ $key ] ) )
		{
			return FALSE;
		}

		$data = static::match( '<span class="dark_text">japanese:</span>(.*?)</div>' );

		return static::setValue( $key, static::lastChanges( $data ) );
	}

	/**
	 * Get title for sysnonmys
	 *
	 * @return void | string (in output)
	 */
	public function _titlesysnonmys()
	{
		$key = 'titlesysnonmys';

		if ( isset( static::$data[ $key ] ) )
		{
			return FALSE;
		}

		$data = static::match( '<span class="dark_text">synonyms:</span>(.*?)</div>' );

		return static::setValue( $key, static::lastChanges( $data ) );
	}

	/**
	 * Get poster
	 *
	 * @return void | string (in output)
	 */
	public function _poster()
	{
		$key = 'poster';

		if ( isset( static::$data[ $key ] ) )
		{
			return FALSE;
		}

		$data = static::match( '(https://myanimelist.cdn-dena.com/images/anime/[0-9]+/[0-9]+\.jpg)' );

		return static::setValue( $key, static::lastChanges( $data ) );
	}

	/**
	 * Get description
	 *
	 * @return void | string (in output)
	 */
	public function _description()
	{
		$key = 'description';

		if ( isset( static::$data[ $key ] ) )
		{
			return FALSE;
		}

		$data = static::match( '<span itemprop="description">(.*?)</span>', '<br>' );

		if ( $data == FALSE )
		{
			return FALSE;
		}

		$data = static::descCleaner( $data );

		return static::setValue( $key, static::lastChanges( $data ) );
	}

	/**
	 * Get type
	 *
	 * @return void | string (in output)
	 */
	public function _type()
	{
		$key = 'type';

		if ( isset( static::$data[ $key ] ) )
		{
			return FALSE;
		}

		$data = static::match( '<span class="dark_text">type:</span>(.*?)</div>' );

		if ( $data == FALSE OR static::validate( array( 'mode' => 'count', 'max_len' => 50 ), $data ) )
		{
			return FALSE;
		}

		return static::setValue( $key, static::lastChanges( $data ) );
	}

	/**
	 * Get rating
	 *
	 * @return void | string (in output)
	 */
	public function _rating()
	{
		$key = 'rating';

		if ( isset( static::$data[ $key ] ) )
		{
			return FALSE;
		}

		$data = static::match( '<span class="dark_text">rating:</span>(.*?)</div>' );

		if ( $data == FALSE )
		{
			return FALSE;
		}

        if ( static::validate( array( 'mode' => 'regex', 'regex_code' => 'none', 'regex_flags' => 'si' ), $data ) )
        {
            return FALSE;
        }

		$data = str_replace( array( '- Teens 13 or older', '(violence &amp; profanity)', '- Mild Nudity', ' ' ), '', $data );
		$data = trim( $data );

		return static::setValue( $key, static::lastChanges( $data ) );
	}

	/**
	 * Get vote
	 *
	 * @return void | string (in output)
	 */
	public function _vote()
	{
		$key = 'vote';

		if ( isset( static::$data[ $key ] ) )
		{
			return FALSE;
		}

		$data = static::match( 'scored by <span itemprop="ratingCount">(.*?)</span> users' );

		if ( $data == FALSE )
		{
			return FALSE;
		}

		$data = static::formatK( $data );

		return static::setValue( $key, static::lastChanges( $data ) );
	}

	/**
	 * Get score
	 *
	 * @return void | string (in output)
	 */
	public function _point()
	{
		$key = 'point';

		if ( isset( static::$data[ $key ] ) )
		{
			return FALSE;
		}

		$data = static::match( '<span class="dark_text">score:</span>(.*?)<sup>' );

		if ( $data == FALSE )
		{
			$data = static::match( '<span itemprop="ratingValue">(.*?)</span>' );
		}

		if ( $data == FALSE )
		{
			return FALSE;
		}

		$data = str_replace( ',', '.', $data );

		if ( !static::validate( array( 'mode' => 'regex', 'regex_code' => '^\d\.\d\d$' ), $data ) )
		{
			return FALSE;
		}

		return static::setValue( $key, static::lastChanges( $data ) );
	}

	/**
	 * Get rank
	 *
	 * @return void | string (in output)
	 */
	public function _rank()
	{
		$key = 'rank';

		if ( isset( static::$data[ $key ] ) )
		{
			return FALSE;
		}

		$data = static::match( '<span class="dark_text">ranked:</span>(.*?)<sup>' );

		if ( $data == FALSE )
		{
			return FALSE;
		}

		$data = str_replace( '#', '', $data );

		if ( !static::validate( array( 'mode' => 'number' ), $data ) )
		{
			return FALSE;
		}
		else
		{
			$data = '#' . $data;
		}

		return static::setValue( $key, static::lastChanges( $data ) );
	}

	/**
	 * Get genres
	 *
	 * @return void | array (in output)
	 */
	public function _genres()
	{
		$key = 'genres';

		if ( isset( static::$data[ $key ] ) )
		{
			return FALSE;
		}

		$data = static::match( '<span class="dark_text">genres:</span>(.*?)</div>' );

		if ( $data == FALSE )
		{
			return FALSE;
		}

		return static::setValue( $key, static::listValue( $data, ',' ) );
	}

	/**
	 * Get source
	 *
	 * @return void | string (in output)
	 */
	public function _source()
	{
		$key = 'source';

		if ( isset( static::$data[ $key ] ) )
		{
			return FALSE;
		}

		$data = static::match( '<span class="dark_text">source:</span>(.*?)</div>' );

        if ( static::validate( array( 'mode' => 'regex', 'regex_code' => 'unknown', 'regex_flags' => 'si' ), $data ) )
        {
            return FALSE;
        }

		if ( $data == FALSE OR static::validate( array( 'mode' => 'count', 'max_len' => 50 ), $data ) )
		{
			return FALSE;
		}

		return static::setValue( $key, static::lastChanges( $data ) );
	}

	/**
	 * Get aired
	 *
	 * @return void | array (in output)
	 */
	public function _aired()
	{
		$key = 'aired';

		if ( isset( static::$data[ $key ] ) )
		{
			return FALSE;
		}

		$data = static::match( '<span class="dark_text">aired:</span>(.*?)</div>' );

		if ( $data == FALSE OR static::validate( array( 'mode' => 'count', 'max_len' => 100 ), $data ) )
		{
			return FALSE;
		}

		preg_match( '/(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)\s*(\d+),\s*(\d+)\s*to\s*(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)\s*(\d+),\s*(\d+)/', $data, $out );

		if ( !empty( $out ) )
		{
			$data = array(
				'first_month' => static::lastChanges( $out[1] ),
				'first_day'   => static::lastChanges( $out[2] ),
				'first_year'  => static::lastChanges( $out[3] ),
				'last_month'  => static::lastChanges( $out[4] ),
				'last_day'    => static::lastChanges( $out[5] ),
				'last_year'   => static::lastChanges( $out[6] )
			);

			return static::setValue( $key, $data );
		}

		preg_match( '/(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)\s*(\d+),\s*(\d+)\s*to\s*\?/', $data, $out );

		if ( !empty( $out ) )
		{
			$data = array(
				'first_month' => static::lastChanges( $out[1] ),
				'first_day'   => static::lastChanges( $out[2] ),
				'first_year'  => static::lastChanges( $out[3] ),
				'last'        => 'no'
			);

			return static::setValue( $key, $data );
		}

		preg_match( '/(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)\s*(\d+),\s*(\d+)/', $data, $out );

		if ( !empty( $out ) )
		{
			$data = array(
				'first_month' => static::lastChanges( $out[1] ),
				'first_day'   => static::lastChanges( $out[2] ),
				'first_year'  => static::lastChanges( $out[3] )
			);

			return static::setValue( $key, $data );
		}

		return FALSE;
	}

	/**
	 * Get date of first episode
	 *
	 * @return void | array (in output)
	 */
	public function _firstepisode()
	{
		$key = 'firstepisode';

		if ( isset( static::$data[ $key ] ) )
		{
			return FALSE;
		}

		$data = static::match( '<span class="dark_text">aired:</span>(.*?)</div>' );

		if ( $data == FALSE OR static::validate( array( 'mode' => 'count', 'max_len' => 100 ), $data ) )
		{
			return FALSE;
		}

		preg_match( '/(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)\s*(\d\d?),\s*(\d\d\d\d)/', $data, $out );

		if ( !empty( $out ) )
		{
			$data = array(
				'month' => static::lastChanges( $out[1] ),
				'day'   => static::lastChanges( $out[2] ),
				'year'  => static::lastChanges( $out[3] )
			);

			return static::setValue( $key, $data );
		}

		return FALSE;
	}

	/**
	 * Get date of last episode
	 *
	 * @return void | array (in output)
	 */
	public function _lastepisode()
	{
		$key = 'lastepisode';

		if ( isset( static::$data[ $key ] ) )
		{
			return FALSE;
		}

		$data = static::match( '<span class="dark_text">aired:</span>(.*?)</div>' );

		if ( $data == FALSE OR static::validate( array( 'mode' => 'count', 'max_len' => 100 ), $data ) )
		{
			return FALSE;
		}

		preg_match( '/\w+\s*\d+,\s*\d+\s*to\s*(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)\s*(\d\d?),\s*(\d\d\d\d)/', $data, $out );

		if ( !empty( $out ) )
		{
			$data = array(
				'month' => static::lastChanges( $out[1] ),
				'day'   => static::lastChanges( $out[2] ),
				'year'  => static::lastChanges( $out[3] )
			);

			return static::setValue( $key, $data );
		}

		return FALSE;
	}

	/**
	 * Get episode
	 *
	 * @return void | array (in output)
	 */
	public function _episode()
	{
		$key = 'episode';

		if ( isset( static::$data[ $key ] ) )
		{
			return FALSE;
		}

		$data = static::match( '<span class="dark_text">episodes:</span>(.*?)</div>' );

		if ( $data == FALSE OR static::validate( array( 'mode' => 'count', 'max_len' => 50 ), $data ) )
		{
			return FALSE;
		}

		$data = static::replace( '[^0-9]+', '', $data );

		if ( !static::validate( array( 'mode' => 'number' ), $data ) )
		{
			return FALSE;
		}

		return static::setValue( $key, static::lastChanges( $data ) );
	}

	/**
	 * Get studios
	 *
	 * @return void | array (in output)
	 */
	public function _studios()
	{
		$key = 'studios';

		if ( isset( static::$data[ $key ] ) )
		{
			return FALSE;
		}

		$data = static::match( '<span class="dark_text">studios:</span>(.*?)</div>' );

		if ( $data == FALSE )
		{
			return FALSE;
		}

        if ( static::validate( array( 'mode' => 'regex', 'regex_code' => 'none found', 'regex_flags' => 'si' ), $data ) )
        {
            return FALSE;
        }

		return static::setValue( $key, static::listValue( $data, ',' ) );
	}

	/**
	 * Get duration
	 *
	 * @return void | string (in output)
	 */
	public function _duration()
	{
		$key = 'duration';

		if ( isset( static::$data[ $key ] ) )
		{
			return FALSE;
		}

		$data = static::match( '<span class="dark_text">duration:</span>(.*?)</div>' );

		if ( $data == FALSE OR static::validate( array( 'mode' => 'count', 'max_len' => 100 ), $data ) )
		{
			return FALSE;
		}

		$data = static::replace( '\s+per ep\.', '', $data );

		preg_match( '/(\d+) hr\. (\d+) min\./', $data, $out );

		if ( !empty( $out ) )
		{
			$data = array( 'hour' => static::lastChanges( $out[1] ), 'min' => static::lastChanges( $out[2] ) );

			return static::setValue( $key, $data );
		}

		preg_match( '/(\d+) min\./', $data, $out );

		if ( !empty( $out ) )
		{
			$data = array( 'hour' => 0, 'min' => static::lastChanges( $out[1] ) );

			return static::setValue( $key, $data );
		}

		return FALSE;
	}

	/**
	 * Get premiered
	 *
	 * @return void | string (in output)
	 */
	public function _premiered()
	{
		$key = 'premiered';

		if ( isset( static::$data[ $key ] ) )
		{
			return FALSE;
		}

		$data = static::match( '<span class="dark_text">premiered:</span>(.*?)</div>' );

		if ( $data == FALSE OR static::validate( array( 'mode' => 'count', 'max_len' => 100 ), $data ) )
		{
			return FALSE;
		}

		preg_match( '/^(\w+) (\d+)$/', $data, $out );

		if ( !empty( $out ) )
		{
			$data = array(
				'season' => static::lastChanges( $out[1] ),
				'year'   => static::lastChanges( $out[2] )
			);

			return static::setValue( $key, $data );
		}

		return FALSE;
	}

	/**
	 * Get producers
	 *
	 * @return void | array (in output)
	 */
	public function _producers()
	{
		$key = 'producers';

		if ( isset( static::$data[ $key ] ) )
		{
			return FALSE;
		}

		$data = static::match( '<span class="dark_text">producers:</span>(.*?)</div>' );

		if ( $data == FALSE )
		{
			return FALSE;
		}

		return static::setValue( $key, static::listValue( $data, ',' ) );
	}

	/**
	 * Get popularity
	 *
	 * @return void | string (in output)
	 */
	public function _popularity()
	{
		$key = 'popularity';

		if ( isset( static::$data[ $key ] ) )
		{
			return FALSE;
		}

		$data = static::match( '<span class="dark_text">popularity:</span>(.*?)</div>' );

		if ( $data == FALSE )
		{
			return FALSE;
		}

		$data = str_replace( '#', '', $data );

		if ( !static::validate( array( 'mode' => 'number' ), $data ) )
		{
			return FALSE;
		}

		$data = '#' . $data;

		return static::setValue( $key, static::lastChanges( $data ) );
	}

	/**
	 * Get members
	 *
	 * @return void | string (in output)
	 */
	public function _members()
	{
		$key = 'members';

		if ( isset( static::$data[ $key ] ) )
		{
			return FALSE;
		}

		$data = static::match( '<span class="dark_text">members:</span>(.*?)</div>' );

		if ( $data == FALSE )
		{
			return FALSE;
		}

		$data = static::formatK( $data );

		return static::setValue( $key, static::lastChanges( $data ) );
	}

	/**
	 * Get favorites
	 *
	 * @return void | string (in output)
	 */
	public function _favorites()
	{
		$key = 'favorites';

		if ( isset( static::$data[ $key ] ) )
		{
			return FALSE;
		}

		$data = static::match( '<span class="dark_text">favorites:</span>(.*?)</div>' );

		if ( $data == FALSE )
		{
			return FALSE;
		}

		$data = static::formatK( $data );

		return static::setValue( $key, static::lastChanges( $data ) );
	}

	/**
	 * Get status
	 *
	 * @return void | string (in output)
	 */
	public function _status()
	{
		$key = 'status';

		if ( isset( static::$data[ $key ] ) )
		{
			return FALSE;
		}

		$data = static::match( '<span class="dark_text">status:</span>(.*?)</div>' );

		return static::setValue( $key, static::lastChanges( $data ) );
	}

	/**
	 * Get broadcast
	 *
	 * @return void | array (in output)
	 */
	public function _broadcast()
	{
		$key = 'broadcast';

		if ( isset( static::$data[ $key ] ) )
		{
			return FALSE;
		}

		$data = static::match( '<span class="dark_text">broadcast:</span>(.*?)</div>' );

		if ( $data == FALSE OR static::validate( array( 'mode' => 'count', 'max_len' => 100 ), $data ) )
		{
			return FALSE;
		}

		preg_match( '/(\w+) at (\d+):(\d+) \(\w+\)/', $data, $out );

		if ( !empty( $out ) )
		{
			$data = array(
				'day'         => static::lastChanges( $out[1] ),
				'hour'   => static::lastChanges( $out[2] ),
				'minute' => static::lastChanges( $out[3] )
			);

			return static::setValue( $key, $data );
		}

		return FALSE;
	}

	/**
	 * Get year
	 *
	 * @return void | string (in output)
	 */
	public function _year()
	{
		$key = 'year';

		if ( isset( static::$data[ $key ] ) )
		{
			return FALSE;
		}

		$data = static::match( '<span class="dark_text">aired:</span>(.*?)</div>' );

		preg_match( '/(\d{4})/', $data, $out );

		return static::setValue( $key, static::lastChanges( ( isset( $out[1] ) AND $out[1] > 1800 AND $out[1] < 2200 ) ? $out[1] : FALSE ) );
	}

	/**
	 * Get voice
	 *
	 * @param  int   		$limit   	How many rows do you want to fetch?
	 * @return void | array (in output)
	 */
	public function _voice( $limit=10 )
	{
		$key = 'voice';

		if ( isset( static::$data[ $key ] ) )
		{
			return FALSE;
		}

		$data = static::matchTable(
		'</div>characters & voice actors</h2>(.*?)<a name="staff">',
		'<tr>(.*?)</tr>',
		array(
        '<a href="[^"]+/(character/[0-9]+)/[^"]+">[^<]+</a>',
		'<a href="[^"]+/character/.*?/.*?">([^<]+)</a>',
        '<a href="[^"]+/(people/[0-9]+)/[^"]+">[^<]+</a>',
        '<a href="[^"]+/people/.*?/.*?">([^<]+)</a>',
        '<a href="[^"]+/people/.*?/.*?">[^<]+</a>.*?<small>(.*?)</small>'
		),
		array(
        'character_link',
        'character_name',
        'people_link',
        'people_name',
        'people_lang'
		),
		$limit
		);

		return static::setValue( $key, $data );
	}

	/**
	 * Get staff
	 *
	 * @param  int   		$limit   	How many rows do you want to fetch?
	 * @return void | array (in output)
	 */
	public function _staff( $limit=10 )
	{
		$key = 'staff';

		if ( isset( static::$data[ $key ] ) )
		{
			return FALSE;
		}

		$data = static::matchTable(
        '</div>.*?staff[^<]*?</h2>(.*?)<h2>',
        '<table.*?>(.*?)</table>',
        array(
        '<a href="[^"]+/(people/[0-9]+)/[^"]+">[^<]+</a>',
        '<a href="[^"]+/people/.*?/.*?">([^<]+)</a>',
        '<a href="[^"]+/people/.*?/.*?">[^<]+</a>.*?<small>(.*?)</small>'
        ),
        array(
        'people_link',
        'people_name',
        'people_positions_list'
        ),
        $limit
		);

		return static::setValue( $key, $data );
	}

	/**
	 * Get related ( probably all related types )
	 *
	 * @param  string   	$type  		Which related you take?
	 * @param  int   		$limit   	How many rows do you want to fetch?
	 * @return void | array (in output)
	 */
	public function _related( $type, $limit=10 )
	{
		$data = FALSE;

		switch ( $type )
		{
			case 'adaptation':

				$data = static::matchTable(
        		'<td.*?>adaptation:</td>.*?(<td.*?>.*?</td>)',
        		'(<a href=[^>]+>.*?</a>)',
        		array(
        		'<a href="[^"]*/(anime/[0-9]+|manga/[0-9]+)[^"]*">.*?</a>',
        		'<a href="[^"]+">(.*?)</a>'
        		),
        		array(
        		'link',
        		'title'
        		),
        		$limit
				);

				return static::setValue( $type, $data );

				break;

			case 'sequel':

				$data = static::matchTable(
        		'<td.*?>sequel:</td>.*?(<td.*?>.*?</td>)',
        		'(<a href=[^>]+>.*?</a>)',
        		array(
        		'<a href="[^"]*/(anime/[0-9]+|manga/[0-9]+)[^"]*">.*?</a>',
        		'<a href="[^"]+">(.*?)</a>'
        		),
        		array(
        		'link',
        		'title'
        		),
        		$limit
				);

				return static::setValue( $type, $data );

				break;

			case 'prequel':

				$data = static::matchTable(
        		'<td.*?>prequel:</td>.*?(<td.*?>.*?</td>)',
        		'(<a href=[^>]+>.*?</a>)',
        		array(
        		'<a href="[^"]*/(anime/[0-9]+|manga/[0-9]+)[^"]*">.*?</a>',
        		'<a href="[^"]+">(.*?)</a>'
        		),
        		array(
        		'link',
        		'title'
        		),
        		$limit
				);

				return static::setValue( $type, $data );

				break;

			case 'parentstory':

				$data = static::matchTable(
        		'<td.*?>parent story:</td>.*?(<td.*?>.*?</td>)',
        		'(<a href=[^>]+>.*?</a>)',
        		array(
        		'<a href="[^"]*/(anime/[0-9]+|manga/[0-9]+)[^"]*">.*?</a>',
        		'<a href="[^"]+">(.*?)</a>'
        		),
        		array(
        		'link',
        		'title'
        		),
        		$limit
				);

				return static::setValue( $type, $data );

				break;

			case 'sidestory':

				$data = static::matchTable(
        		'<td.*?>side story:</td>.*?(<td[^>]*>.*?</td>)',
        		'(<a href=[^>]+>.*?</a>)',
        		array(
        		'<a href="[^"]*/(anime/[0-9]+|manga/[0-9]+)[^"]*">.*?</a>',
        		'<a href="[^"]+">(.*?)</a>'
        		),
        		array(
        		'link',
        		'title'
        		),
        		$limit
				);

				return static::setValue( $type, $data );

				break;

			case 'other':

				$data = static::matchTable(
        		'<td.*?>other:</td>.*?(<td[^>]*>.*?</td>)',
        		'(<a href=[^>]+>.*?</a>)',
        		array(
        		'<a href="[^"]*/(anime/[0-9]+|manga/[0-9]+)[^"]*">.*?</a>',
        		'<a href="[^"]+">(.*?)</a>'
        		),
        		array(
        		'link',
        		'title'
        		),
        		$limit
				);

				return static::setValue( $type, $data );

				break;

			case 'spinoff':

				$data = static::matchTable(
        		'<td.*?>spin\-off:</td>.*?(<td[^>]*>.*?</td>)',
        		'(<a href=[^>]+>.*?</a>)',
        		array(
        		'<a href="[^"]*/(anime/[0-9]+|manga/[0-9]+)[^"]*">.*?</a>',
        		'<a href="[^"]+">(.*?)</a>'
        		),
        		array(
        		'link',
        		'title'
        		),
        		$limit
				);

				return static::setValue( $type, $data );

				break;

			case 'alternativeversion':

				$data = static::matchTable(
        		'<td.*?>alternative version:</td>.*?(<td[^>]*>.*?</td>)',
        		'(<a href=[^>]+>.*?</a>)',
        		array(
        		'<a href="[^"]*/(anime/[0-9]+|manga/[0-9]+)[^"]*">.*?</a>',
        		'<a href="[^"]+">(.*?)</a>'
        		),
        		array(
        		'link',
        		'title'
        		),
        		$limit
				);

				return static::setValue( $type, $data );

				break;
		}

		return $data;
	}

	/**
	 * Get link of the request page
	 *
	 * @return void | string (in output)
	 */
	public function _link()
	{
		$key = 'link';

		if ( isset( static::$data[ $key ] ) )
		{
			return FALSE;
		}

		return static::setValue( 'link', static::lastChanges( static::$requestUrl ) );
	}

	/**
	 * Get trailer
	 *
	 * @return void | string (in output)
	 */
	public function _trailer()
	{
		$key = 'trailer';

		if ( isset( static::$data[ $key ] ) )
		{
			return FALSE;
		}

		$data = static::match( '<div class="video-promotion">.*?<a[^>]+href="(.*?)"[^>]+>' );

		if ( $data == FALSE )
		{
			return FALSE;
		}

		$data = static::replace( '\?.+',   '',         $data );
		$data = str_replace(     'embed/', 'watch?v=', $data );

		return static::setValue( 'trailer', static::lastChanges( $data ) );
	}
}