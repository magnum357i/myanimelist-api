<?php

/**
 * MyAnimeList Manga Api
 *
 * @author     		Magnum357 [https://github.com/magnum357i/]
 * @copyright  		2018
 * @license    		http://www.opensource.org/licenses/mit-license.html  MIT License
 * @version    		0.8.2
 */

namespace myanimelist\Types;

class Manga
{
	use \myanimelist\Grabber\Helper, \myanimelist\Grabber\Request;

	/**
	 * Set type
	 */
	public static $type = 'manga';

	/**
	 * If true, names becomes the first-last order instead of the last-first order
	 */
	public static $reverseName = TRUE;

	/**
	 * Take object parameter and send request
	 *
	 * @param  string $id Enter page id on MAL
	 * @return void
	 */
	public function __construct( $id )
	{
		$this->request($id);
	}

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

		$data = $this->match( '(https://myanimelist.cdn-dena.com/images/manga/[0-9]+/[0-9]+\.jpg)' );

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

		$data = $this->match( '<span itemprop="description">(.*?)</span>', '<br>' );

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

		$data = $this->match( '<span class="dark_text">type:</span>(.*?)</div>' );

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
	 * Get point
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

		$data = str_replace( ',', '.', $data );

		if ( !static::validate( array( 'mode' => 'regex', 'regex_code' => '^\d\.\d\d$' ), $data ) )
		{
			return FALSE;
		}

		$data = mb_substr( $data, 0, 3 );

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

		$data = str_replace( '#', '', $data );

		if ( !static::validate( array( 'mode' => 'number' ), $data ) )
		{
			return FALSE;
		}
		else
		{
			$data = '#' . $data;;
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

		$data = $this->match( '<span class="dark_text">genres:</span>(.*?)</div>' );

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

		$data = str_replace( array( '.', ',' ), '', $data );

		if ( !static::validate( array( 'mode' => 'number' ), $data ) )
		{
			return FALSE;
		}

		$data = ( $data > 1000 ) ? round( $data / 1000 ) : $data;

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
	 * Get authors
	 *
	 * @return void | array (in output)
	 */
	public function _authors()
	{
		$key = 'authors';

		if ( isset( static::$data[ $key ] ) )
		{
			return FALSE;
		}

		$data = $this->match( '<span class="dark_text">authors:</span>(.*?)</div>' );

		if ( $data == FALSE )
		{
			return FALSE;
		}

		$data = static::listValue( $data, '),' );

		foreach ( $data as &$value )
		{
			if ( end( $data ) != $value )
			{
				$value = $value . ')';
			}

			if ( static::$reverseName == TRUE )
			{
				$value = static::reverseName( $value, '2' );
			}
		}

		return static::setValue( $key, $data );
	}

	/**
	 * Get volume
	 *
	 * @return void | string (in output)
	 */
	public function _volume()
	{
		$key = 'volume';

		if ( isset( static::$data[ $key ] ) )
		{
			return FALSE;
		}

		$data = $this->match('<span class="dark_text">volumes:</span>(.*?)</div>');

		if ( $data == FALSE OR !static::validate( array( 'mode' => 'number' ), $data ) )
		{
			return FALSE;
		}

		return static::setValue( $key, static::lastChanges( $data ) );
	}

	/**
	 * Get chapter
	 *
	 * @return void | string (in output)
	 */
	public function _chapter()
	{
		$key = 'chapter';

		if ( isset( static::$data[ $key ] ) )
		{
			return FALSE;
		}

		$data = $this->match( '<span class="dark_text">chapters:</span>(.*?)</div>' );

		if ( $data == FALSE OR !static::validate( array( 'mode' => 'number' ), $data ) )
		{
			return FALSE;
		}

		return static::setValue( $key, static::lastChanges( $data ) );
	}

	/**
	 * Get serialization
	 *
	 * @return void | string (in output)
	 */
	public function _serialization()
	{
		$key = 'serialization';

		if ( isset( static::$data[ $key ] ) )
		{
			return FALSE;
		}

		$data = $this->match( '<span class="dark_text">serialization:</span>(.*?)</div>' );

        if ( static::validate( array( 'mode' => 'regex', 'regex_code' => 'none', 'regex_flags' => 'si' ), $data ) )
        {
            return FALSE;
        }

		return static::setValue( $key, static::lastChanges( $data ) );
	}

	/**
	 * Get published date
	 *
	 * @return void | string (in output)
	 */
	public function _published()
	{
		$key = 'published';

		if ( isset( static::$data[ $key ] ) )
		{
			return FALSE;
		}

		$data = $this->match( '<span class="dark_text">published:</span>(.*?)</div>' );

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
	public function _firstchapter()
	{
		$key = 'firstchapter';

		if ( isset( static::$data[ $key ] ) )
		{
			return FALSE;
		}

		$data = static::match( '<span class="dark_text">published:</span>(.*?)</div>' );

		if ( $data == FALSE OR static::validate( array( 'mode' => 'count', 'max_len' => 100 ), $data ) )
		{
			return FALSE;
		}

		preg_match( '/(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)\s+(\d\d?),\s*(\d\d\d\d)/', $data, $out );

		if ( !empty( $out ) )
		{
			$data = array(
				'month' => static::lastChanges( $out[1] ),
				'day'   => static::lastChanges( $out[2] ),
				'year'  => static::lastChanges( $out[3] )
			);

			return static::setValue( $key, $data );
		}

		preg_match( '/(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)\s+(\d\d\d\d)/', $data, $out );

		if ( !empty( $out ) )
		{
			$data = array(
				'month' => static::lastChanges( $out[1] ),
				'year'  => static::lastChanges( $out[2] )
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
	public function _lastchapter()
	{
		$key = 'lastchapter';

		if ( isset( static::$data[ $key ] ) )
		{
			return FALSE;
		}

		$data = static::match( '<span class="dark_text">published:</span>(.*?)</div>' );

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

		preg_match( '/\w+\s*\d+\s*to\s*(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)\s*(\d\d\d\d)/', $data, $out );

		if ( !empty( $out ) )
		{
			$data = array(
				'month' => static::lastChanges( $out[1] ),
				'year'  => static::lastChanges( $out[2] )
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

		$data = static::match( '<span class="dark_text">published:</span>(.*?)</div>' );

		preg_match( '/(\d{4})/', $data, $out );

		return static::setValue( $key, ( isset( $out[1] ) AND $out[1] > 1800 AND $out[1] < 2200 ) ? $out[1] : FALSE );
	}

	/**
	 * Get character
	 *
	 * @param  int $limit How many rows do you want to fetch?
	 * @return void | array (in output)
	 */
	public function _characters( $limit=10 )
	{
		$key = 'characters';

		if ( isset( static::$data[ $key ] ) )
		{
			return FALSE;
		}

		$data = static::matchTable(
        '</div>characters</h2><div.*?">(.+?</table>)</div></div>',
        '<table[^>]*>(.*?)</table>',
        array(
        '<a href="[^"]+/(character/[0-9]+)/[^"]+">[^<]+</a>',
        '<a href="[^"]+/character/[0-9]+/[^"]+">([^<]+)</a>',
        '<small>([^<]+)</small>'
        ),
        array(
        'character_link',
        'character_name',
        'character_role'
        ),
        $limit
		);

		return static::setValue( $key, $data );
	}

	/**
	 * Get related ( probably all )
	 *
	 * @param  string 	$type  		Which related you take?
	 * @param  int    	$limit 		How many rows do you want to fetch?
	 * @return void | array (in output)
	 */
	public function _related( $type, $limit=10 )
	{
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

		return FALSE;
	}

	/**
	 * Get link to the request page
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

		return static::setValue( $key, static::lastChanges( static::$requestUrl ) );
	}
}