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
	 * Methods to allow for prefix
	 */
	public static $methodsToAllow = [
		'title',
		'score',
		'statistic',
		'related',
		'published' => [ 'first', 'last' ]
	];

	/**
	 * Get title
	 *
	 * @return 		string
	 * @usage 		title()->original
	 */
	protected function _titleoriginal() {

		$key = 'titleoriginal';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		if ( !$this->request()->isSent() ) return FALSE;

		$data = $this->request()->match( '<span itemprop="name">(.*?)</span>' );

		return static::setValue( $key, $this->lastChanges( $data ) );
	}

	/**
	 * Get title for english
	 *
	 * @return 		string
	 * @usage 		title()->english
	 */
	protected function _titleenglish() {

		$key = 'titleenglish';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		if ( !$this->request()->isSent() ) return FALSE;

		$data = $this->request()->match( '<span class="dark_text">english:</span>(.*?)</div>' );

		return static::setValue( $key, $this->lastChanges( $data ) );
	}

	/**
	 * Get title for japanese
	 *
	 * @return 		string
	 * @usage 		title()->japanese
	 */
	protected function _titlejapanese() {

		$key = 'titlejapanese';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		if ( !$this->request()->isSent() ) return FALSE;

		$data = $this->request()->match( '<span class="dark_text">japanese:</span>(.*?)</div>' );

		return static::setValue( $key, $this->lastChanges( $data ) );
	}

	/**
	 * Get poster
	 *
	 * @return 		string
	 * @usage 		poster
	 */
	protected function _poster() {

		$key = 'poster';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		if ( !$this->request()->isSent() ) return FALSE;

		$data = $this->request()->match( '(https://myanimelist.cdn-dena.com/images/manga/[0-9]+/[0-9]+\.jpg)' );

		if ( $data == FALSE ) $data = $this->request()->match( '(https://cdn.myanimelist.net/images/manga/[0-9]+/[0-9]+\.jpg)' );

		if ( $data == FALSE ) return FALSE;

		if ( $this->config()->cache == TRUE ) {

			$newPoster = $this->cache()->savePoster( $data );
			$data      = $newPoster;
		}

		return static::setValue( $key, $this->lastChanges( $data ) );
	}

	/**
	 * Get description
	 *
	 * @return 		string
	 * @usage 		description
	 */
	protected function _description() {

		$key = 'description';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		if ( !$this->request()->isSent() ) return FALSE;

		$data = $this->request()->match( '<span itemprop="description">(.*?)</span>', '<br>' );

		if ( $data == FALSE ) return FALSE;

		$data = $this->text()->descCleaner( $data );

		return static::setValue( $key, $this->lastChanges( $data ) );
	}

	/**
	 * Get category
	 *
	 * @return 		string
	 * @usage 		category
	 */
	protected function _category() {

		$key = 'category';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		if ( !$this->request()->isSent() ) return FALSE;

		$data = $this->request()->match( '<span class="dark_text">type:</span>(.*?)</div>' );

		return static::setValue( $key, $this->lastChanges( $data ) );
	}

	/**
	 * Get vote
	 *
	 * @return 		array
	 * @usage 		none
	 */
	protected function vote() {

		$key = 'vote';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		if ( !$this->request()->isSent() ) return FALSE;

		$data = $this->request()->match( 'scored by <span itemprop="ratingCount">(.*?)</span> users' );

		if ( $data == FALSE ) return FALSE;

		$data = $this->text()->replace( '[^0-9]+', '', $data );
		$data = [
			'simple' => $this->lastChanges( $this->text()->formatK( $data ) ),
			'full'   => $this->lastChanges( $data )
		];

		return static::setValue( $key, $this->lastChanges( $data ) );
	}

	/**
	 * Get number with K of vote
	 *
	 * @return 		string
	 * @usage 		score()->vote
	 */
	protected function _scorevote() {

		if ( !isset( static::$data[ 'vote' ] ) ) $this->vote();

		return ( isset( static::$data[ 'vote' ][ 'simple' ] ) ) ? static::$data[ 'vote' ][ 'simple' ] : FALSE;
	}

	/**
	 * Get number without K of vote
	 *
	 * @return 		string
	 * @usage 		score()->voteraw
	 */
	protected function _scorevoteraw() {

		if ( !isset( static::$data[ 'vote' ] ) ) $this->vote();

		return ( isset( static::$data[ 'vote' ][ 'full' ] ) ) ? static::$data[ 'vote' ][ 'full' ] : FALSE;
	}

	/**
	 * Get point
	 *
	 * @return 		string
	 * @usage 		score()->point
	 */
	protected function _scorepoint() {

		$key = 'point';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		if ( !$this->request()->isSent() ) return FALSE;

		$data = $this->request()->match( '<span class="dark_text">score:</span>(.*?)<sup>' );

		if ( $data == FALSE ) $data = $this->request()->match( '<span itemprop="ratingValue">(.*?)</span>' );

		$data = $this->text()->replace( '[^0-9]+', '', $data );
		$data = mb_substr( $data, 0, 2 );
		$data = mb_substr( $data, 0, 1 ) . '.' . mb_substr( $data, 1, 2 );

		return static::setValue( $key, $this->lastChanges( $data ) );
	}

	/**
	 * Get rank
	 *
	 * @return 		string
	 * @usage 		statistic()->rank
	 */
	protected function _statisticrank() {

		$key = 'rank';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		if ( !$this->request()->isSent() ) return FALSE;

		$data = $this->request()->match( '<span class="dark_text">ranked:</span>(.*?)<sup>' );

		$data = str_replace( '#', '', $data );

		if ( !$this->text()->validate( [ 'mode' => 'number' ], $data ) )
		{
			return FALSE;
		}
		else
		{
			$data = "#{$data}";
		}

		return static::setValue( $key, $this->lastChanges( $data ) );
	}

	/**
	 * Get genres
	 *
	 * @return 		array
	 * @usage 		genres
	 */
	protected function _genres() {

		$key = 'genres';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		if ( !$this->request()->isSent() ) return FALSE;

		$data = $this->request()->matchTable(
		[ $this, 'lastChanges' ],
		$this->config(),
		$this->text(),
		'<span class="dark_text">genres:</span>(.*?)</div>',
		'(<a href=[^>]+>.*?</a>)',
		[
		'<a href="/([^"]+)"[^>]+>.*?</a>',
		'<a href="[^"]+"[^>]+>(.*?)</a>'
		],
		[
		'link',
		'name'
		],
		static::$limit
		);

		return static::setValue( $key, $data );
	}

	/**
	 * Get popularity
	 *
	 * @return 		string
	 * @usage 		statistic()->popularity
	 */
	protected function _statisticpopularity() {

		$key = 'popularity';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		if ( !$this->request()->isSent() ) return FALSE;

		$data = $this->request()->match( '<span class="dark_text">popularity:</span>(.*?)</div>' );

		$data = str_replace( '#', '', $data );

		if ( !$this->text()->validate( [ 'mode' => 'number' ], $data ) ) return FALSE;

		$data = "#{$data}";

		return static::setValue( $key, $this->lastChanges( $data ) );
	}

	/**
	 * Get member
	 *
	 * @return 		array
	 * @usage 		none
	 */
	protected function member() {

		$key = 'member';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		if ( !$this->request()->isSent() ) return FALSE;

		$data = $this->request()->match( '<span class="dark_text">members:</span>(.*?)</div>' );

		if ( $data == FALSE ) return FALSE;

		$data = $this->text()->replace( '[^0-9]+', '', $data );
		$data = [
			'simple' => $this->lastChanges( $this->text()->formatK( $data ) ),
			'full'   => $this->lastChanges( $data )
		];

		return static::setValue( $key, $this->lastChanges( $data ) );
	}

	/**
	 * Get number with K of member
	 *
	 * @return 		string
	 * @usage 		statistic()->member
	 */
	protected function _statisticmember() {

		if ( !isset( static::$data[ 'member' ] ) ) $this->member();

		return ( isset( static::$data[ 'member' ][ 'simple' ] ) ) ? static::$data[ 'member' ][ 'simple' ] : FALSE;
	}

	/**
	 * Get number without K of member
	 *
	 * @return 		string
	 * @usage 		statistic()->memberraw
	 */
	protected function _statisticmemberraw() {

		if ( !isset( static::$data[ 'member' ] ) ) $this->member();

		return ( isset( static::$data[ 'member' ][ 'full' ] ) ) ? static::$data[ 'member' ][ 'full' ] : FALSE;
	}

	/**
	 * Get favorite
	 *
	 * @return 		array
	 * @usage 		none
	 */
	protected function favorite() {

		$key = 'favorite';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		if ( !$this->request()->isSent() ) return FALSE;

		$data = $this->request()->match( '<span class="dark_text">favorites:</span>(.*?)</div>' );

		if ( $data == FALSE ) return FALSE;

		$data = $this->text()->replace( '[^0-9]+', '', $data );
		$data = [
			'simple' => $this->lastChanges( $this->text()->formatK( $data ) ),
			'full'   => $this->lastChanges( $data )
		];

		return static::setValue( $key, $this->lastChanges( $data ) );
	}

	/**
	 * Get number with K of favorite
	 *
	 * @return 		string
	 * @usage 		statistic()->favorite
	 */
	protected function _statisticfavorite() {

		if ( !isset( static::$data[ 'favorite' ] ) ) $this->favorite();

		return ( isset( static::$data[ 'favorite' ][ 'simple' ] ) ) ? static::$data[ 'favorite' ][ 'simple' ] : FALSE;
	}

	/**
	 * Get number without K of favorite
	 *
	 * @return 		string
	 * @usage 		statistic()->favoriteraw
	 */
	protected function _statisticfavoriteraw() {

		if ( !isset( static::$data[ 'favorite' ] ) ) $this->favorite();

		return ( isset( static::$data[ 'favorite' ][ 'full' ] ) ) ? static::$data[ 'favorite' ][ 'full' ] : FALSE;
	}

	/**
	 * Get status
	 *
	 * @return 		string
	 * @usage 		status
	 */
	protected function _status() {

		$key = 'status';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		if ( !$this->request()->isSent() ) return FALSE;

		$data = $this->request()->match( '<span class="dark_text">status:</span>(.*?)</div>' );

		return static::setValue( $key, $this->lastChanges( $data ) );
	}

	/**
	 * Get authors
	 *
	 * @return 		array
	 * @usage 		authors
	 */
	protected function _authors() {

		$key = 'authors';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		if ( !$this->request()->isSent() ) return FALSE;

		$data = $this->request()->match( '<span class="dark_text">authors:</span>(.*?)</div>' );

		if ( $data == FALSE ) return FALSE;

		$data = $this->text()->listValue( $data, '),', [ $this, 'lastChanges' ] );

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
	 * @usage 		volume
	 */
	protected function _volume() {

		$key = 'volume';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		if ( !$this->request()->isSent() ) return FALSE;

		$data = $this->request()->match('<span class="dark_text">volumes:</span>(.*?)</div>');

		if ( $data == FALSE OR !$this->text()->validate( [ 'mode' => 'number' ], $data ) ) return FALSE;

		return static::setValue( $key, $this->lastChanges( $data ) );
	}

	/**
	 * Get chapter
	 *
	 * @return 		string
	 * @usage 		chapter
	 */
	protected function _chapter() {

		$key = 'chapter';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		if ( !$this->request()->isSent() ) return FALSE;

		$data = $this->request()->match( '<span class="dark_text">chapters:</span>(.*?)</div>' );

		if ( $data == FALSE OR !$this->text()->validate( [ 'mode' => 'number' ], $data ) ) return FALSE;

		return static::setValue( $key, $this->lastChanges( $data ) );
	}

	/**
	 * Get serialization
	 *
	 * @return 		string
	 * @usage 		serialization
	 */
	protected function _serialization() {

		$key = 'serialization';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		if ( !$this->request()->isSent() ) return FALSE;

		$data = $this->request()->matchTable(
		[ $this, 'lastChanges' ],
		$this->config(),
		$this->text(),
		'<span class="dark_text">serialization:</span>(.*?)</div>',
		'(<a href=[^>]+>.*?</a>)',
		[
		'<a href="/([^"]+)"[^>]+>.*?</a>',
		'<a href="[^"]+"[^>]+>(.*?)</a>'
		],
		[
		'link',
		'name'
		],
		static::$limit
		);

		return static::setValue( $key, $data );
	}

	/**
	 * Get published date
	 *
	 * @return 		string
	 * @usage 		none
	 */
	protected function published() {

		$key = 'published';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		if ( !$this->request()->isSent() ) return FALSE;

		$data = $this->request()->match( '<span class="dark_text">published:</span>(.*?)</div>' );

		if ( $data == FALSE OR $this->text()->validate( [ 'mode' => 'count', 'max_len' => 100 ], $data ) ) return FALSE;

		preg_match( '/(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)\s*(\d+),\s*(\d+)\s*to\s*(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)\s*(\d+),\s*(\d+)/', $data, $out );

		if ( !empty( $out ) ) {

			$data = [
				'first_month' => $this->lastChanges( $out[1] ),
				'first_day'   => $this->lastChanges( $out[2] ),
				'first_year'  => $this->lastChanges( $out[3] ),
				'last_month'  => $this->lastChanges( $out[4] ),
				'last_day'    => $this->lastChanges( $out[5] ),
				'last_year'   => $this->lastChanges( $out[6] )
			];

			return static::setValue( $key, $data );
		}

		preg_match( '/(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)\s*(\d+),\s*(\d+)\s*to\s*\?/', $data, $out );

		if ( !empty( $out ) ) {

			$data = [
				'first_month' => $this->lastChanges( $out[1] ),
				'first_day'   => $this->lastChanges( $out[2] ),
				'first_year'  => $this->lastChanges( $out[3] ),
				'last_month'  => 'no',
				'last_day'    => 'no',
				'last_year'   => 'no'
			];

			return static::setValue( $key, $data );
		}

		preg_match( '/(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)\s*(\d+),\s*(\d+)/', $data, $out );

		if ( !empty( $out ) ) {

			$data = [
				'first_month' => $this->lastChanges( $out[1] ),
				'first_day'   => $this->lastChanges( $out[2] ),
				'first_year'  => $this->lastChanges( $out[3] )
			];

			return static::setValue( $key, $data );
		}

		return FALSE;
	}

	/**
	 * Get first month of published date
	 *
	 * @return 		string
	 * @usage 		published()->first()->month
	 */
	protected function _publishedfirstmonth() {

		if ( !isset( static::$data[ 'published' ] ) ) $this->published();

		return ( isset( static::$data[ 'published' ][ 'first_month' ] ) ) ? static::$data[ 'published' ][ 'first_month' ] : FALSE;
	}

	/**
	 * Get first day of published date
	 *
	 * @return 		string
	 * @usage 		published()->first()->day
	 */
	protected function _publishedfirstday() {

		if ( !isset( static::$data[ 'published' ] ) ) $this->published();

		return ( isset( static::$data[ 'published' ][ 'first_day' ] ) ) ? static::$data[ 'published' ][ 'first_day' ] : FALSE;
	}

	/**
	 * Get first year of published date
	 *
	 * @return 		string
	 * @usage 		published()->first()->year
	 */
	protected function _publishedfirstyear() {

		if ( !isset( static::$data[ 'published' ] ) ) $this->published();

		return ( isset( static::$data[ 'published' ][ 'first_year' ] ) ) ? static::$data[ 'published' ][ 'first_year' ] : FALSE;
	}

	/**
	 * Get last month of published date
	 *
	 * @return 		string
	 * @usage 		published()->last()->month
	 */
	protected function _publishedlastmonth() {

		if ( !isset( static::$data[ 'published' ] ) ) $this->published();

		return ( isset( static::$data[ 'published' ][ 'last_month' ] ) ) ? static::$data[ 'published' ][ 'last_month' ] : FALSE;
	}

	/**
	 * Get last day of published date
	 *
	 * @return 		string
	 * @usage 		published()->last()->day
	 */
	protected function _publishedlastday() {

		if ( !isset( static::$data[ 'published' ] ) ) $this->published();

		return ( isset( static::$data[ 'published' ][ 'last_day' ] ) ) ? static::$data[ 'published' ][ 'last_day' ] : FALSE;
	}

	/**
	 * Get last year of published date
	 *
	 * @return 		string
	 * @usage 		published()->last()->year
	 */
	protected function _publishedlastyear() {

		if ( !isset( static::$data[ 'published' ] ) ) $this->published();

		return ( isset( static::$data[ 'published' ][ 'last_year' ] ) ) ? static::$data[ 'published' ][ 'last_year' ] : FALSE;
	}

	/**
	 * Get year
	 *
	 * @return 		string
	 * @usage 		year
	 */
	protected function _year() {

		$key = 'year';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		if ( !$this->request()->isSent() ) return FALSE;

		$data = $this->request()->match( '<span class="dark_text">published:</span>(.*?)</div>' );

		preg_match( '/(\d{4})/', $data, $out );

		if ( !isset( $out[ 1 ] ) ) return FALSE;

		$data = $out[ 1 ];

		return static::setValue( $key, $this->lastChanges( $data ) );
	}

	/**
	 * Get character
	 *
	 * @return 		array
	 * @usage 		characters
	 */
	protected function _characters() {

		$key = 'characters';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		if ( !$this->request()->isSent() ) return FALSE;

		$data = $this->request()->matchTable(
		[ $this, 'lastChanges' ],
		$this->config(),
		$this->text(),
		'</div>characters</h2><div.*?">(.+?</table>)</div></div>',
		'<table[^>]*>(.*?)</table>',
		[
		'<a href="[^"]+/(character/[0-9]+)/[^"]+">[^<]+</a>',
		'<a href="[^"]+/character/[0-9]+/[^"]+">([^<]+)</a>',
		'<small>([^<]+)</small>'
		],
		[
		'character_link',
		'character_name',
		'character_role'
		],
		static::$limit
		);

		return static::setValue( $key, $data );
	}

	/**
	 * Get adaptation
	 *
	 * @return 		array
	 * @usage 		related()->adaptation
	 */
	protected function _relatedadaptation() {

		$key = 'adaptation';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		if ( !$this->request()->isSent() ) return FALSE;

		$data = $this->request()->matchTable(
		[ $this, 'lastChanges' ],
		$this->config(),
		$this->text(),
		'<td.*?>adaptation:</td>.*?(<td.*?>.*?</td>)',
		'(<a href=[^>]+>.*?</a>)',
		[
		'<a href="[^"]*/(anime/[0-9]+|manga/[0-9]+)[^"]*">.*?</a>',
		'<a href="[^"]+">(.*?)</a>'
		],
		[
		'link',
		'title'
		],
		static::$limit
		);

		return static::setValue( $key, $data );
	}

	/**
	 * Get sequel
	 *
	 * @return 		array
	 * @usage 		related()->sequel
	 */
	protected function _relatedsequel() {

		$key = 'sequel';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		if ( !$this->request()->isSent() ) return FALSE;

		$data = $this->request()->matchTable(
		[ $this, 'lastChanges' ],
		$this->config(),
		$this->text(),
		'<td.*?>sequel:</td>.*?(<td.*?>.*?</td>)',
		'(<a href=[^>]+>.*?</a>)',
		[
		'<a href="[^"]*/(anime/[0-9]+|manga/[0-9]+)[^"]*">.*?</a>',
		'<a href="[^"]+">(.*?)</a>'
		],
		[
		'link',
		'title'
		],
		static::$limit
		);

		return static::setValue( $key, $data );
	}

	/**
	 * Get prequel
	 *
	 * @return 		array
	 * @usage 		related()->prequel
	 */
	protected function _relatedprequel() {

		$key = 'prequel';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		if ( !$this->request()->isSent() ) return FALSE;

		$data = $this->request()->matchTable(
		[ $this, 'lastChanges' ],
		$this->config(),
		$this->text(),
		'<td.*?>prequel:</td>.*?(<td.*?>.*?</td>)',
		'(<a href=[^>]+>.*?</a>)',
		[
		'<a href="[^"]*/(anime/[0-9]+|manga/[0-9]+)[^"]*">.*?</a>',
		'<a href="[^"]+">(.*?)</a>'
		],
		[
		'link',
		'title'
		],
		static::$limit
		);

		return static::setValue( $key, $data );
	}

	/**
	 * Get parentstory
	 *
	 * @return 		array
	 * @usage 		related()->parentstory
	 */
	protected function _relatedparentstory() {

		$key = 'parentstory';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		if ( !$this->request()->isSent() ) return FALSE;

		$data = $this->request()->matchTable(
		[ $this, 'lastChanges' ],
		$this->config(),
		$this->text(),
		'<td.*?>parent story:</td>.*?(<td.*?>.*?</td>)',
		'(<a href=[^>]+>.*?</a>)',
		[
		'<a href="[^"]*/(anime/[0-9]+|manga/[0-9]+)[^"]*">.*?</a>',
		'<a href="[^"]+">(.*?)</a>'
		],
		[
		'link',
		'title'
		],
		static::$limit
		);

		return static::setValue( $key, $data );
	}

	/**
	 * Get sidestory
	 *
	 * @return 		array
	 * @usage 		related()->sidestory
	 */
	protected function _relatedsidestory() {

		$key = 'sidestory';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		if ( !$this->request()->isSent() ) return FALSE;

		$data = $this->request()->matchTable(
		[ $this, 'lastChanges' ],
		$this->config(),
		$this->text(),
		'<td.*?>side story:</td>.*?(<td[^>]*>.*?</td>)',
		'(<a href=[^>]+>.*?</a>)',
		[
		'<a href="[^"]*/(anime/[0-9]+|manga/[0-9]+)[^"]*">.*?</a>',
		'<a href="[^"]+">(.*?)</a>'
		],
		[
		'link',
		'title'
		],
		static::$limit
		);

		return static::setValue( $key, $data );
	}

	/**
	 * Get other
	 *
	 * @return 		array
	 * @usage 		related()->other
	 */
	protected function _relatedother() {

		$key = 'other';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		if ( !$this->request()->isSent() ) return FALSE;

		$data = $this->request()->matchTable(
		[ $this, 'lastChanges' ],
		$this->config(),
		$this->text(),
		'<td.*?>other:</td>.*?(<td[^>]*>.*?</td>)',
		'(<a href=[^>]+>.*?</a>)',
		[
		'<a href="[^"]*/(anime/[0-9]+|manga/[0-9]+)[^"]*">.*?</a>',
		'<a href="[^"]+">(.*?)</a>'
		],
		[
		'link',
		'title'
		],
		static::$limit
		);

		return static::setValue( $key, $data );
	}

	/**
	 * Get spinoff
	 *
	 * @return 		array
	 * @usage 		related()->spinoff
	 */
	protected function _relatedspinoff() {

		$key = 'spinoff';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		if ( !$this->request()->isSent() ) return FALSE;

		$data = $this->request()->matchTable(
		[ $this, 'lastChanges' ],
		$this->config(),
		$this->text(),
		'<td.*?>spin\-off:</td>.*?(<td[^>]*>.*?</td>)',
		'(<a href=[^>]+>.*?</a>)',
		[
		'<a href="[^"]*/(anime/[0-9]+|manga/[0-9]+)[^"]*">.*?</a>',
		'<a href="[^"]+">(.*?)</a>'
		],
		[
		'link',
		'title'
		],
		static::$limit
		);

		return static::setValue( $key, $data );
	}

	/**
	 * Get alternativeversion
	 *
	 * @return 		array
	 * @usage 		related()->alternativeversion
	 */
	protected function _relatedalternativeversion() {

		$key = 'alternativeversion';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		if ( !$this->request()->isSent() ) return FALSE;

		$data = $this->request()->matchTable(
		[ $this, 'lastChanges' ],
		$this->config(),
		$this->text(),
		'<td.*?>alternative version:</td>.*?(<td[^>]*>.*?</td>)',
		'(<a href=[^>]+>.*?</a>)',
		[
		'<a href="[^"]*/(anime/[0-9]+|manga/[0-9]+)[^"]*">.*?</a>',
		'<a href="[^"]+">(.*?)</a>'
		],
		[
		'link',
		'title'
		],
		static::$limit
		);

		return static::setValue( $key, $data );
	}

	/**
	 * Get link of the request page
	 *
	 * @return 		string
	 * @usage 		link
	 */
	protected function _link() {

		$key = 'link';

		if ( isset( static::$data[ $key ] ) ) return static::$data[ $key ];

		if ( !$this->request()->isSent() ) return FALSE;

		return static::setValue( 'link', $this->lastChanges( $this->request()::$url ) );
	}
}