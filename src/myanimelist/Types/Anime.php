<?php

/**
 * MyAnimeList Anime API
 *
 * @package	 		MyAnimeList API
 * @author     		Magnum357 [https://github.com/magnum357i/]
 * @copyright  		2018
 * @license    		http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace myanimelist\Types;

class Anime extends \myanimelist\Helper\Builder
{

	/**
	 * Set type
	 */
	public static $type = 'anime';

	/**
	 * Prefix to call function
	 */
	public static $prefix = '';

	/**
	 * Call title functions
	 *
	 * @return 		this class
	 */
	public function title() {

		static::$prefix = 'title';

		return $this;
	}

	/**
	 * Get broadcast values
	 *
	 * @return 		this class
	 */
	public function broadcast() {

		if ( !isset( static::$data[ 'broadcast' ] ) ) {

			$this->_broadcast();
		}

		static::$prefix = 'broadcast';

		return $this;
	}

	/**
	 * Get aired values
	 *
	 * @return 		this class
	 */
	public function aired() {

		if ( !isset( static::$data[ 'aired' ] ) ) {

			$this->_aired();
		}

		static::$prefix = 'aired';

		return $this;
	}

	/**
	 * Get first of date values
	 *
	 * @return this class
	 */
	public function first() {

		if ( in_array( static::$prefix, array( 'aired' ) ) ) {

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

		if ( in_array( static::$prefix, array( 'aired' ) ) ) {

			static::$prefix = static::$prefix . 'last';
		}

		return $this;
	}

	/**
	 * Get duration values
	 *
	 * @return 		this class
	 */
	public function duration() {

		if ( !isset( static::$data[ 'duration' ] ) ) {

			$this->_duration();
		}

		static::$prefix = 'duration';

		return $this;
	}

	/**
	 * Get premiered values
	 *
	 * @return 		this class
	 */
	public function premiered() {

		if ( !isset( static::$data[ 'premiered' ] ) ) {

			$this->_premiered();
		}

		static::$prefix = 'premiered';

		return $this;
	}

	/**
	 * Call related functions
	 *
	 * @return 		this class
	 */
	public function related() {

		static::$prefix = 'related';

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

		if ( $data != FALSE ) $data = $this->text()->replace( '\s*\(.+\)', '', $data, 'si' );

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
	protected function _titlejapanese()
	{
		$key = 'titlejapanese';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		$data = $this->request()->match( '<span class="dark_text">japanese:</span>(.*?)</div>' );

		return static::setValue( $key, $this->lastChanges( $data ) );
	}

	/**
	 * Get title for sysnonmys
	 *
	 * @return 		string
	 */
	protected function _titlesysnonmys() {

		$key = 'titlesysnonmys';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		$data = $this->request()->match( '<span class="dark_text">synonyms:</span>(.*?)</div>' );

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

		$data = $this->request()->match( '(https://myanimelist.cdn-dena.com/images/anime/[0-9]+/[0-9]+\.jpg)' );

		if ( $data == FALSE ) $data = $this->request()->match( '(https://cdn.myanimelist.net/images/anime/[0-9]+/[0-9]+\.jpg)' );

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

		if ( $data == FALSE OR $this->text()->validate( array( 'mode' => 'count', 'max_len' => 50 ), $data ) ) return FALSE;

		return static::setValue( $key, $this->lastChanges( $data ) );
	}

	/**
	 * Get rating
	 *
	 * @return 		string
	 */
	protected function _rating() {

		$key = 'rating';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		$data = $this->request()->match( '<span class="dark_text">rating:</span>(.*?)</div>' );

		if ( $data == FALSE ) return FALSE;

		if ( $this->text()->validate( array( 'mode' => 'regex', 'regex_code' => 'none', 'regex_flags' => 'si' ), $data ) ) return FALSE;

		$data = str_replace( array( '- Teens 13 or older', '(violence & profanity)', '- Mild Nudity', ' ' ), '', $data );
		$data = trim( $data );

		return static::setValue( $key, $this->lastChanges( $data ) );
	}

	/**
	 * Get licensors
	 *
	 * @return 		string
	 */
	protected function _licensors() {

		$key = 'licensors';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		$data = $this->request()->matchTable(
		array( $this, 'lastChanges' ),
		$this->config(),
		$this->text(),
		'<span class="dark_text">licensors:</span>(.*?)</div>',
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
	 * Get producers
	 *
	 * @return 		string
	 */
	protected function _producers() {

		$key = 'producers';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		$data = $this->request()->matchTable(
		array( $this, 'lastChanges' ),
		$this->config(),
		$this->text(),
		'<span class="dark_text">producers:</span>(.*?)</div>',
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
	 * Get score
	 *
	 * @return 		string
	 */
	protected function _point() {

		$key = 'point';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		$data = $this->request()->match( '<span class="dark_text">score:</span>(.*?)<sup>' );

		if ( $data == FALSE ) $data = $this->request()->match( '<span itemprop="ratingValue">(.*?)</span>' );

		if ( $data == FALSE ) return FALSE;

		$data = str_replace( ',', '.', $data );

		if ( !$this->text()->validate( array( 'mode' => 'regex', 'regex_code' => '^\d\.\d\d$' ), $data ) ) return FALSE;

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

		if ( $data == FALSE ) return FALSE;

		$data = str_replace( '#', '', $data );

		if ( !$this->text()->validate( array( 'mode' => 'number' ), $data ) )
		{
			return FALSE;
		}
		else
		{
			$data = '#' . $data;
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
	 * Get source
	 *
	 * @return 		string
	 */
	protected function _source() {

		$key = 'source';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		$data = $this->request()->match( '<span class="dark_text">source:</span>(.*?)</div>' );

		if ( $this->text()->validate( array( 'mode' => 'regex', 'regex_code' => 'unknown', 'regex_flags' => 'si' ), $data ) ) return FALSE;

		if ( $data == FALSE OR $this->text()->validate( array( 'mode' => 'count', 'max_len' => 50 ), $data ) ) return FALSE;

		return static::setValue( $key, $this->lastChanges( $data ) );
	}

	/**
	 * Get aired
	 *
	 * @return 		array
	 */
	protected function _aired()
	{
		$key = 'aired';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		$data = $this->request()->match( '<span class="dark_text">aired:</span>(.*?)</div>' );

		if ( $data == FALSE ) return FALSE;

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

			return static::setValue( $key, $this->lastChanges( $data ) );
		}

		preg_match( '/(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)\s*(\d+),\s*(\d+)\s*to\s*\?/', $data, $out );

		if ( !empty( $out ) )
		{
			$data = array(
				'first_month' => static::lastChanges( $out[1] ),
				'first_day'   => static::lastChanges( $out[2] ),
				'first_year'  => static::lastChanges( $out[3] ),
				'last_month'  => 'no',
				'last_day'    => 'no',
				'last_year'   => 'no'
			);

			return static::setValue( $key, $this->lastChanges( $data ) );
		}

		preg_match( '/(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)\s*(\d+),\s*(\d+)/', $data, $out );

		if ( !empty( $out ) )
		{
			$data = array(
				'first_month' => static::lastChanges( $out[1] ),
				'first_day'   => static::lastChanges( $out[2] ),
				'first_year'  => static::lastChanges( $out[3] )
			);

			return static::setValue( $key, $this->lastChanges( $data ) );
		}

		return FALSE;
	}

	/**
	 * Get first month of aired
	 *
	 * @return 		string
	 */
	protected function _airedfirstmonth() {

		return ( isset( static::$data[ 'aired' ][ 'first_month' ] ) ) ? static::$data[ 'aired' ][ 'first_month' ] : FALSE;
	}

	/**
	 * Get first day of aired
	 *
	 * @return 		string
	 */
	protected function _airedfirstday() {

		return ( isset( static::$data[ 'aired' ][ 'first_day' ] ) ) ? static::$data[ 'aired' ][ 'first_day' ] : FALSE;
	}

	/**
	 * Get first year of aired
	 *
	 * @return 		string
	 */
	protected function _airedfirstyear() {

		return ( isset( static::$data[ 'aired' ][ 'first_year' ] ) ) ? static::$data[ 'aired' ][ 'first_year' ] : FALSE;
	}

	/**
	 * Get last month of aired
	 *
	 * @return 		string
	 */
	protected function _airedlastmonth() {

		return ( isset( static::$data[ 'aired' ][ 'last_month' ] ) ) ? static::$data[ 'aired' ][ 'last_month' ] : FALSE;
	}

	/**
	 * Get last day of aired
	 *
	 * @return 		string
	 */
	protected function _airedlastday() {

		return ( isset( static::$data[ 'aired' ][ 'last_day' ] ) ) ? static::$data[ 'aired' ][ 'last_day' ] : FALSE;
	}

	/**
	 * Get last year of aired
	 *
	 * @return 		string
	 */
	protected function _airedlastyear() {

		return ( isset( static::$data[ 'aired' ][ 'last_year' ] ) ) ? static::$data[ 'aired' ][ 'last_year' ] : FALSE;
	}

	/**
	 * Get episode
	 *
	 * @return 		string
	 */
	protected function _episode() {

		$key = 'episode';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		$data = $this->request()->match( '<span class="dark_text">episodes:</span>(.*?)</div>' );

		if ( $data == FALSE OR $this->text()->validate( array( 'mode' => 'count', 'max_len' => 50 ), $data ) ) return FALSE;

		$data = $this->text()->replace( '[^0-9]+', '', $data );

		if ( !$this->text()->validate( array( 'mode' => 'number' ), $data ) ) return FALSE;

		return static::setValue( $key, $this->lastChanges( $data ) );
	}

	/**
	 * Get studios
	 *
	 * @return 		array
	 */
	protected function _studios() {

		$key = 'studios';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		$data = $this->request()->matchTable(
		array( $this, 'lastChanges' ),
		$this->config(),
		$this->text(),
		'<span class="dark_text">studios:</span>(.*?)</div>',
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
	 * Get duration
	 *
	 * @return 		array
	 */
	protected function _duration() {

		$key = 'duration';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		$data = $this->request()->match( '<span class="dark_text">duration:</span>(.*?)</div>' );

		if ( $data == FALSE OR $this->text()->validate( array( 'mode' => 'count', 'max_len' => 100 ), $data ) ) return FALSE;

		$data = $this->text()->replace( '\s+per ep\.', '', $data );

		preg_match( '/(\d+) hr\. (\d+) min\./', $data, $out );

		if ( !empty( $out ) ) {

			$data = array( 'hour' => $this->lastChanges( $out[1] ), 'min' => $this->lastChanges( $out[2] ) );

			return static::setValue( $key, $data );
		}

		preg_match( '/(\d+) min\./', $data, $out );

		if ( !empty( $out ) ) {

			$data = array( 'hour' => 0, 'min' => $this->lastChanges( $out[1] ) );

			return static::setValue( $key, $data );
		}

		return FALSE;
	}

	/**
	 * Get hour of duration
	 *
	 * @return 		string
	 */
	protected function _durationhour() {

		return ( isset( static::$data[ 'duration' ][ 'hour' ] ) ) ? static::$data[ 'duration' ][ 'hour' ] : FALSE;
	}

	/**
	 * Get minute of duration
	 *
	 * @return 		string
	 */
	protected function _durationmin() {

		return ( isset( static::$data[ 'duration' ][ 'min' ] ) ) ? static::$data[ 'duration' ][ 'min' ] : FALSE;
	}

	/**
	 * Get premiered
	 *
	 * @return 		array
	 */
	protected function _premiered() {

		$key = 'premiered';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		$data = $this->request()->match( '<span class="dark_text">premiered:</span>(.*?)</div>' );

		if ( $data == FALSE OR $this->text()->validate( array( 'mode' => 'count', 'max_len' => 100 ), $data ) ) {

			return FALSE;
		}

		preg_match( '/^(\w+) (\d+)$/', $data, $out );

		if ( !empty( $out ) ) {

			$data = array(
				'season' => $this->lastChanges( $out[1] ),
				'year'   => $this->lastChanges( $out[2] )
			);

			return static::setValue( $key, $data );
		}

		return FALSE;
	}

	/**
	 * Get season of premiered
	 *
	 * @return 		string
	 */
	protected function _premieredseason() {

		return ( isset( static::$data[ 'premiered' ][ 'season' ] ) ) ? static::$data[ 'premiered' ][ 'season' ] : FALSE;
	}

	/**
	 * Get year of premiered
	 *
	 * @return 		string
	 */
	protected function _premieredyear() {

		return ( isset( static::$data[ 'premiered' ][ 'year' ] ) ) ? static::$data[ 'premiered' ][ 'year' ] : FALSE;
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

		if ( $data == FALSE ) return FALSE;

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

		$data = $this->text()->formatK( $data );

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
	 * Get broadcast
	 *
	 * @return 		array
	 */
	protected function _broadcast() {

		$key = 'broadcast';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		$data = $this->request()->match( '<span class="dark_text">broadcast:</span>(.*?)</div>' );

		if ( $data == FALSE OR $this->text()->validate( array( 'mode' => 'count', 'max_len' => 100 ), $data ) ) return FALSE;

		preg_match( '/(\w+) at (\d+):(\d+) \(\w+\)/', $data, $out );

		if ( !empty( $out ) ) {

			$data = array(
				'day'         => $this->lastChanges( $out[1] ),
				'hour'   => $this->lastChanges( $out[2] ),
				'minute' => $this->lastChanges( $out[3] )
			);

			return static::setValue( $key, $data );
		}

		return FALSE;
	}

	/**
	 * Get broadcast day
	 *
	 * @return 		string
	 */
	protected function _broadcastday() {

		return ( isset( static::$data[ 'broadcast' ][ 'day' ] ) ) ? static::$data[ 'broadcast' ][ 'day' ] : FALSE;
	}

	/**
	 * Get broadcast hour
	 *
	 * @return 		string
	 */
	protected function _broadcasthour() {

		return ( isset( static::$data[ 'broadcast' ][ 'hour' ] ) ) ? static::$data[ 'broadcast' ][ 'hour' ] : FALSE;
	}

	/**
	 * Get broadcast minute
	 *
	 * @return 		string
	 */
	protected function _broadcastminute() {

		return ( isset( static::$data[ 'broadcast' ][ 'minute' ] ) ) ? static::$data[ 'broadcast' ][ 'minute' ] : FALSE;
	}

	/**
	 * Get year
	 *
	 * @return 		string
	 */
	protected function _year() {

		$key = 'year';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		$data = $this->request()->match( '<span class="dark_text">aired:</span>(.*?)</div>' );

		preg_match( '/(\d{4})/', $data, $out );

		return static::setValue( $key, $this->lastChanges( ( isset( $out[1] ) AND $out[1] > 1800 AND $out[1] < 2200 ) ? $out[1] : FALSE ) );
	}

	/**
	 * Get voice
	 *
	 * @return 		array
	 */
	protected function _voice() {

		$key = 'voice';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		$data = $this->request()->matchTable(
		array( $this, 'lastChanges' ),
		$this->config(),
		$this->text(),
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
		static::$limit
		);

		return static::setValue( $key, $data );
	}

	/**
	 * Get staff
	 *
	 * @return 		array
	 */
	protected function _staff() {

		$key = 'staff';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		$data = $this->request()->matchTable(
		array( $this, 'lastChanges' ),
		$this->config(),
		$this->text(),
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

	/**
	 * Get trailer
	 *
	 * @return 		string
	 */
	protected function _trailer() {

		$key = 'trailer';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		$data = $this->request()->match( '<div class="video-promotion">.*?<a[^>]+href="(.*?)"[^>]+>' );

		if ( $data == FALSE ) return FALSE;

		$data = $this->text()->replace( '\?.+',   '',         $data );
		$data = str_replace(     'embed/', 'watch?v=', $data );

		return static::setValue( 'trailer', $this->lastChanges( $data ) );
	}
}