<?php

/**
 * MyAnimeList Manga API
 *
 * @package	 		MyAnimeList API
 * @author     		Magnum357 [https://github.com/magnum357i/]
 * @copyright  		2018
 * @license    		http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace myanimelist\Types;

class Manga extends \myanimelist\Helper\Builder {

	/**
	 * Set type
	 */
	public static $type = 'manga';

	/**
	 * Prefix to call function
	 */
	public static $prefix = '';

	/**
	 * Call title functions
	 *
	 * @return this class
	 */
	public function title() {

		static::$prefix = 'title';

		return $this;
	}

	/**
	 * Get published values
	 *
	 * @return this class
	 */
	public function published() {

		if ( !isset( static::$data[ 'published' ] ) ) {

			$this->_published();
		}

		static::$prefix = 'published';

		return $this;
	}

	/**
	 * Call related functions
	 *
	 * @return this class
	 */
	public function related() {

		static::$prefix = 'related';

		return $this;
	}

	/**
	 * Get first of date values
	 *
	 * @return this class
	 */
	public function first() {

		if ( in_array( static::$prefix, array( 'published' ) ) ) {

			static::$prefix = static::$prefix . 'first';
		}

		return $this;
	}

	/**
	 * Get last of date values
	 *
	 * @return this class
	 */
	public function last() {

		if ( in_array( static::$prefix, array( 'published' ) ) ) {

			static::$prefix = static::$prefix . 'last';
		}

		return $this;
	}

	/**
	 * Set limit
	 *
	 * @param 		int 			Limit number
	 * @return 		this class
	 */
	public function setLimit( $int ) {

		static::$limit = $int;

		return $this;
	}

	/**
	 * Page is correct?
	 *
	 * @return 		bool
	 */
	public function isSuccess() {

		return ( empty( $this->_titleoriginal() ) ) ? FALSE : TRUE;
	}

	/**
	 * Get title
	 *
	 * @return 		string
	 */
	protected function _titleoriginal() {

		$key = 'titleoriginal';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		$data = $this->request()->match( '<span itemprop="name">(.*?)</span>' );

		return static::setValue( $key, $this->lastChanges( $data ) );
	}

	/**
	 * Get title for english
	 *
	 * @return 		string
	 */
	protected function _titleenglish() {

		$key = 'titleenglish';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		$data = $this->request()->match( '<span class="dark_text">english:</span>(.*?)</div>' );

		return static::setValue( $key, $this->lastChanges( $data ) );
	}

	/**
	 * Get title for japanese
	 *
	 * @return 		string
	 */
	protected function _titlejapanese() {

		$key = 'titlejapanese';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		$data = $this->request()->match( '<span class="dark_text">japanese:</span>(.*?)</div>' );

		return static::setValue( $key, $this->lastChanges( $data ) );
	}

	/**
	 * Get poster
	 *
	 * @return 		string
	 */
	protected function _poster() {

		$key = 'poster';

		if ( !isset( static::$data[ 'saveposter' ] ) ) static::setValue( 'saveposter', 'no' );

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		$data = $this->request()->match( '(https://myanimelist.cdn-dena.com/images/manga/[0-9]+/[0-9]+\.jpg)' );

		if ( $data == FALSE ) $data = $this->request()->match( '(https://cdn.myanimelist.net/images/manga/[0-9]+/[0-9]+\.jpg)' );

		if ( $data == FALSE ) return FALSE;

		if ( $this->config()->cache == TRUE AND static::$data[ 'saveposter' ] == 'no' ) {

			$newPoster = $this->cache()->savePoster( $data );
			$data      = $newPoster;

			static::setValue( 'saveposter', 'yes' );
		}

		return static::setValue( $key, $this->lastChanges( $data ) );
	}

	/**
	 * Get description
	 *
	 * @return 		string
	 */
	protected function _description() {

		$key = 'description';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		$data = $this->request()->match( '<span itemprop="description">(.*?)</span>', '<br>' );

		if ( $data == FALSE ) return FALSE;

		$data = $this->text()->descCleaner( $data );

		return static::setValue( $key, $this->lastChanges( $data ) );
	}

	/**
	 * Get category
	 *
	 * @return 		string
	 */
	protected function _category() {

		$key = 'category';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		$data = $this->request()->match( '<span class="dark_text">type:</span>(.*?)</div>' );

		return static::setValue( $key, $this->lastChanges( $data ) );
	}

	/**
	 * Get vote
	 *
	 * @return 		string
	 */
	protected function _vote() {

		$key = 'vote';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		$data = $this->request()->match( 'scored by <span itemprop="ratingCount">(.*?)</span> users' );

		if ( $data == FALSE ) return FALSE;

		$data = $this->text()->formatK( $data );

		return static::setValue( $key, $this->lastChanges( $data ) );
	}

	/**
	 * Get point
	 *
	 * @return 		string
	 */
	protected function _point() {

		$key = 'point';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		$data = $this->request()->match( '<span class="dark_text">score:</span>(.*?)<sup>' );

		if ( $data == FALSE ) $data = $this->request()->match( '<span itemprop="ratingValue">(.*?)</span>' );

		$data = str_replace( ',', '.', $data );

		if ( !$this->text()->validate( array( 'mode' => 'regex', 'regex_code' => '^\d\.\d\d$' ), $data ) ) return FALSE;

		$data = mb_substr( $data, 0, 3 );

		return static::setValue( $key, $this->lastChanges( $data ) );
	}

	/**
	 * Get rank
	 *
	 * @return 		string
	 */
	protected function _rank() {

		$key = 'rank';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		$data = $this->request()->match( '<span class="dark_text">ranked:</span>(.*?)<sup>' );

		$data = str_replace( '#', '', $data );

		if ( !$this->text()->validate( array( 'mode' => 'number' ), $data ) )
		{
			return FALSE;
		}
		else
		{
			$data = '#' . $data;;
		}

		return static::setValue( $key, $this->lastChanges( $data ) );
	}

	/**
	 * Get genres
	 *
	 * @return 		array
	 */
	protected function _genres() {

		$key = 'genres';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		$data = $this->request()->matchTable(
		array( $this, 'lastChanges' ),
		$this->config(),
		$this->text(),
		'<span class="dark_text">genres:</span>(.*?)</div>',
		'(<a href=[^>]+>.*?</a>)',
		array(
		'<a href="/([^"]+)"[^>]+>.*?</a>',
		'<a href="[^"]+"[^>]+>(.*?)</a>'
		),
		array(
		'link',
		'name'
		),
		static::$limit
		);

		return static::setValue( $key, $data );
	}

	/**
	 * Get popularity
	 *
	 * @return 		string
	 */
	protected function _popularity() {

		$key = 'popularity';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		$data = $this->request()->match( '<span class="dark_text">popularity:</span>(.*?)</div>' );

		$data = str_replace( '#', '', $data );

		if ( !$this->text()->validate( array( 'mode' => 'number' ), $data ) ) return FALSE;

		$data = '#' . $data;

		return static::setValue( $key, $this->lastChanges( $data ) );
	}

	/**
	 * Get members
	 *
	 * @return 		string
	 */
	protected function _members() {

		$key = 'members';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		$data = $this->request()->match( '<span class="dark_text">members:</span>(.*?)</div>' );

		if ( $data == FALSE ) return FALSE;

		$data = $this->text()->formatK( $data );

		return static::setValue( $key, $this->lastChanges( $data ) );
	}

	/**
	 * Get favorites
	 *
	 * @return 		string
	 */
	protected function _favorites() {

		$key = 'favorites';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		$data = $this->request()->match( '<span class="dark_text">favorites:</span>(.*?)</div>' );

		if ( $data == FALSE ) return FALSE;

		$data = str_replace( array( '.', ',' ), '', $data );

		if ( !$this->text()->validate( array( 'mode' => 'number' ), $data ) ) return FALSE;

		$data = ( $data > 1000 ) ? round( $data / 1000 ) : $data;

		return static::setValue( $key, $this->lastChanges( $data ) );
	}

	/**
	 * Get status
	 *
	 * @return 		string
	 */
	protected function _status() {

		$key = 'status';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		$data = $this->request()->match( '<span class="dark_text">status:</span>(.*?)</div>' );

		return static::setValue( $key, $this->lastChanges( $data ) );
	}

	/**
	 * Get authors
	 *
	 * @return 		array
	 */
	protected function _authors() {

		$key = 'authors';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		$data = $this->request()->match( '<span class="dark_text">authors:</span>(.*?)</div>' );

		if ( $data == FALSE ) return FALSE;

		$data = $this->text()->listValue( $data, '),', array( $this, 'lastChanges' ) );

		foreach ( $data as &$value )
		{
			if ( end( $data ) != $value ) $value = $value . ')';

			if ( $this->config()->reverseName == TRUE ) $value = $this->text()->reverseName( $value, '2' );
		}

		return static::setValue( $key, $data );
	}

	/**
	 * Get volume
	 *
	 * @return 		string
	 */
	protected function _volume() {

		$key = 'volume';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		$data = $this->request()->match('<span class="dark_text">volumes:</span>(.*?)</div>');

		if ( $data == FALSE OR !$this->text()->validate( array( 'mode' => 'number' ), $data ) ) return FALSE;

		return static::setValue( $key, $this->lastChanges( $data ) );
	}

	/**
	 * Get chapter
	 *
	 * @return 		string
	 */
	protected function _chapter() {

		$key = 'chapter';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		$data = $this->request()->match( '<span class="dark_text">chapters:</span>(.*?)</div>' );

		if ( $data == FALSE OR !$this->text()->validate( array( 'mode' => 'number' ), $data ) ) return FALSE;

		return static::setValue( $key, $this->lastChanges( $data ) );
	}

	/**
	 * Get serialization
	 *
	 * @return 		string
	 */
	protected function _serialization() {

		$key = 'serialization';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		$data = $this->request()->matchTable(
		array( $this, 'lastChanges' ),
		$this->config(),
		$this->text(),
		'<span class="dark_text">serialization:</span>(.*?)</div>',
		'(<a href=[^>]+>.*?</a>)',
		array(
		'<a href="/([^"]+)"[^>]+>.*?</a>',
		'<a href="[^"]+"[^>]+>(.*?)</a>'
		),
		array(
		'link',
		'name'
		),
		static::$limit
		);

		return static::setValue( $key, $data );
	}

	/**
	 * Get published date
	 *
	 * @return 		string
	 */
	protected function _published() {

		$key = 'published';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		$data = $this->request()->match( '<span class="dark_text">published:</span>(.*?)</div>' );

		if ( $data == FALSE OR $this->text()->validate( array( 'mode' => 'count', 'max_len' => 100 ), $data ) ) return FALSE;

		preg_match( '/(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)\s*(\d+),\s*(\d+)\s*to\s*(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)\s*(\d+),\s*(\d+)/', $data, $out );

		if ( !empty( $out ) ) {

			$data = array(
				'first_month' => $this->lastChanges( $out[1] ),
				'first_day'   => $this->lastChanges( $out[2] ),
				'first_year'  => $this->lastChanges( $out[3] ),
				'last_month'  => $this->lastChanges( $out[4] ),
				'last_day'    => $this->lastChanges( $out[5] ),
				'last_year'   => $this->lastChanges( $out[6] )
			);

			return static::setValue( $key, $data );
		}

		preg_match( '/(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)\s*(\d+),\s*(\d+)\s*to\s*\?/', $data, $out );

		if ( !empty( $out ) ) {

			$data = array(
				'first_month' => $this->lastChanges( $out[1] ),
				'first_day'   => $this->lastChanges( $out[2] ),
				'first_year'  => $this->lastChanges( $out[3] ),
				'last_month'  => 'no',
				'last_day'    => 'no',
				'last_year'   => 'no'
			);

			return static::setValue( $key, $data );
		}

		preg_match( '/(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)\s*(\d+),\s*(\d+)/', $data, $out );

		if ( !empty( $out ) ) {

			$data = array(
				'first_month' => $this->lastChanges( $out[1] ),
				'first_day'   => $this->lastChanges( $out[2] ),
				'first_year'  => $this->lastChanges( $out[3] )
			);

			return static::setValue( $key, $data );
		}

		return FALSE;
	}

	/**
	 * Get first month of published date
	 *
	 * @return 		string
	 */
	protected function _publishedfirstmonth() {

		return ( isset( static::$data[ 'published' ][ 'first_month' ] ) ) ? static::$data[ 'published' ][ 'first_month' ] : FALSE;
	}

	/**
	 * Get first day of published date
	 *
	 * @return 		string
	 */
	protected function _publishedfirstday() {

		return ( isset( static::$data[ 'published' ][ 'first_day' ] ) ) ? static::$data[ 'published' ][ 'first_day' ] : FALSE;
	}

	/**
	 * Get last month of published date
	 *
	 * @return 		string
	 */
	protected function _publishedlastmonth() {

		return ( isset( static::$data[ 'published' ][ 'last_month' ] ) ) ? static::$data[ 'published' ][ 'last_month' ] : FALSE;
	}

	/**
	 * Get last day of published date
	 *
	 * @return 		string
	 */
	protected function _publishedlastday() {

		return ( isset( static::$data[ 'published' ][ 'last_day' ] ) ) ? static::$data[ 'published' ][ 'last_day' ] : FALSE;
	}

	/**
	 * Get last year of published date
	 *
	 * @return 		string
	 */
	protected function _publishedlastyear() {

		return ( isset( static::$data[ 'published' ][ 'last_year' ] ) ) ? static::$data[ 'published' ][ 'last_year' ] : FALSE;
	}

	/**
	 * Get first year of published date
	 *
	 * @return 		string
	 */
	protected function _publishedfirstyear() {

		return ( isset( static::$data[ 'published' ][ 'first_year' ] ) ) ? static::$data[ 'published' ][ 'first_year' ] : FALSE;
	}

	/**
	 * Get year
	 *
	 * @return 		string
	 */
	protected function _year() {

		$key = 'year';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		$data = $this->request()->match( '<span class="dark_text">published:</span>(.*?)</div>' );

		preg_match( '/(\d{4})/', $data, $out );

		return static::setValue( $key, ( isset( $out[1] ) AND $out[1] > 1800 AND $out[1] < 2200 ) ? $out[1] : FALSE );
	}

	/**
	 * Get character
	 *
	 * @return 		array
	 */
	protected function _characters() {

		$key = 'characters';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		$data = $this->request()->matchTable(
		array( $this, 'lastChanges' ),
		$this->config(),
		$this->text(),
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
		static::$limit
		);

		return static::setValue( $key, $data );
	}

	/**
	 * Get adaptation
	 *
	 * @return 		array
	 */
	protected function _relatedadaptation() {

		$key = 'adaptation';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		$data = $this->request()->matchTable(
		array( $this, 'lastChanges' ),
		$this->config(),
		$this->text(),
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
		static::$limit
		);

		return static::setValue( $key, $data );
	}

	/**
	 * Get sequel
	 *
	 * @return 		array
	 */
	protected function _relatedsequel() {

		$key = 'sequel';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		$data = $this->request()->matchTable(
		array( $this, 'lastChanges' ),
		$this->config(),
		$this->text(),
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
		static::$limit
		);

		return static::setValue( $key, $data );
	}

	/**
	 * Get prequel
	 *
	 * @return 		array
	 */
	protected function _relatedprequel() {

		$key = 'prequel';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		$data = $this->request()->matchTable(
		array( $this, 'lastChanges' ),
		$this->config(),
		$this->text(),
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
		static::$limit
		);

		return static::setValue( $key, $data );
	}

	/**
	 * Get parentstory
	 *
	 * @return 		array
	 */
	protected function _relatedparentstory() {

		$key = 'parentstory';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		$data = $this->request()->matchTable(
		array( $this, 'lastChanges' ),
		$this->config(),
		$this->text(),
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
		static::$limit
		);

		return static::setValue( $key, $data );
	}

	/**
	 * Get sidestory
	 *
	 * @return 		array
	 */
	protected function _relatedsidestory() {

		$key = 'sidestory';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		$data = $this->request()->matchTable(
		array( $this, 'lastChanges' ),
		$this->config(),
		$this->text(),
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
		static::$limit
		);

		return static::setValue( $key, $data );
	}

	/**
	 * Get other
	 *
	 * @return 		array
	 */
	protected function _relatedother() {

		$key = 'other';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		$data = $this->request()->matchTable(
		array( $this, 'lastChanges' ),
		$this->config(),
		$this->text(),
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
		static::$limit
		);

		return static::setValue( $key, $data );
	}

	/**
	 * Get spinoff
	 *
	 * @return 		array
	 */
	protected function _relatedspinoff() {

		$key = 'spinoff';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		$data = $this->request()->matchTable(
		array( $this, 'lastChanges' ),
		$this->config(),
		$this->text(),
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
		static::$limit
		);

		return static::setValue( $key, $data );
	}

	/**
	 * Get alternativeversion
	 *
	 * @return 		array
	 */
	protected function _relatedalternativeversion() {

		$key = 'alternativeversion';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		$data = $this->request()->matchTable(
		array( $this, 'lastChanges' ),
		$this->config(),
		$this->text(),
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
		static::$limit
		);

		return static::setValue( $key, $data );
	}

	/**
	 * Get link of the request page
	 *
	 * @return 		string
	 */
	protected function _link() {

		$key = 'link';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		return static::setValue( 'link', $this->lastChanges( $this->request()::$requestData[ 'url' ] ) );
	}
}