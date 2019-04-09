<?php

/**
 * MyAnimeList Manga Page API
 *
 * @package	 		MyAnimeList API
 * @author     		Magnum357 [https://github.com/magnum357i/]
 * @copyright  		2018
 * @license    		http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace myanimelist\Page;

class Manga extends \myanimelist\Builder\Page {

	/**
	 * Set type
	 */
	protected static $type = 'manga';

	/**
	 * Methods to allow for prefix
	 */
	protected static $methodsToAllow = [ 'title', 'score', 'statistic', 'related', 'published' => [ 'first', 'last' ] ];

	/**
	 * Patterns for externalLink
	 */
	protected static $externalLinks = [ 'genre' => 'manga/genre/{s}', 'magazine' => 'manga/magazine/{s}', 'character' => 'character/{s}', 'people' => 'people/{s}', 'anime' => 'anime/{s}', 'manga' => 'manga/{s}' ];

	/**
	 * @return 		string
	 * @usage 		title()->original
	 */
	protected function getOriginalWithTitleFromData() {

		return $this->request()::match( '<span itemprop="name">(.*?)</span>' );
	}

	/**
	 * @return 		string
	 * @usage 		title()->english
	 */
	protected function getEnglishWithTitleFromData() {

		return $this->request()::match( '<span class="dark_text">english:</span>(.*?)</div>' );
	}

	/**
	 * @return 		string
	 * @usage 		title()->japanese
	 */
	protected function getJapaneseWithTitleFromData() {

		return $this->request()::match( '<span class="dark_text">japanese:</span>(.*?)</div>' );
	}

	/**
	 * @return 		string
	 * @usage 		title()->sysnonmys
	 */
	protected function getSysnonmysWithTitleFromData() {

		return $this->request()::match( '<span class="dark_text">synonyms:</span>(.*?)</div>' );
	}

	/**
	 * @return 		string
	 * @usage 		poster
	 */
	protected function getPosterFromData() {

		$data = $this->request()::match( [ '(https://myanimelist.cdn-dena.com/images/manga/[0-9]+/[0-9]+\.jpg)', '(https://cdn.myanimelist.net/images/manga/[0-9]+/[0-9]+\.jpg)' ] );

		if ( $data == FALSE ) return FALSE;

		if ( $this->config()->isOnCache() ) {

			$newPoster = $this->cache()->savePoster( $this->getImageName(), $data );
			$data      = $newPoster;
		}

		return $data;
	}

	/**
	 * @return 		string
	 * @usage 		description
	 */
	protected function getDescriptionFromData() {

		$data = $this->request()::match( '<span itemprop="description">(.*?)</span>', '<br>' );

		if ( $data == FALSE ) return FALSE;

		$data = $this->text()->descCleaner( $data );

		return $data;
	}

	/**
	 * @return 		string
	 * @usage 		category
	 */
	protected function getCategoryFromData() {

		return $this->request()::match( '<span class="dark_text">type:</span>(.*?)</div>' );
	}

	/**
	 * @return 		array
	 * @usage 		none
	 */
	protected function vote() {

		$data = $this->request()::match( 'scored by <span itemprop="ratingCount">(.*?)</span> users' );

		if ( $data == FALSE ) return FALSE;

		$data = $this->text()->replace( '[^0-9]+', '', $data );

		return $data;
	}

	/**
	 * @return 		string
	 * @usage 		score()->vote
	 */
	protected function getVoteWithScoreFromData() {

		$data = $this->getVoterawWithScoreFromData();

		if ( $data == FALSE ) return FALSE;

		return $this->text()->formatK( $data );
	}

	/**
	 * @return 		string
	 * @usage 		score()->voteraw
	 */
	protected function getVoterawWithScoreFromData() {

		return $this->vote();
	}

	/**
	 * @return 		string
	 * @usage 		score()->point
	 */
	protected function getPointWithScoreFromData() {

		$data = $this->request()::match( [ '<span class="dark_text">score:</span>(.*?)<sup>', '<span itemprop="ratingValue">(.*?)</span>' ] );

		if ( $data == FALSE ) return FALSE;

		$data = $this->text()->replace( '[^0-9]+', '', $data );

		if ( !$this->text()->validate( [ 'mode' => 'number' ], $data ) ) return FALSE;

		$data = mb_substr( $data, 0, 2 );
		$data = mb_substr( $data, 0, 1 ) . '.' . mb_substr( $data, 1, 2 );

		return $data;
	}

	/**
	 * @return 		string
	 * @usage 		statistic()->rank
	 */
	protected function getRankWithStatisticFromData() {

		$data = $this->request()::match( '<span class="dark_text">ranked:</span>(.*?)<sup>' );

		if ( $data == FALSE ) return FALSE;

		$data = str_replace( '#', '', $data );

		if ( !$this->text()->validate( [ 'mode' => 'number' ], $data ) ) return FALSE;

		return "#{$data}";
	}

	/**
	 * @return 		array
	 * @usage 		genres
	 */
	protected function getGenresFromData() {

		return
		$this->request()::matchTable(
		$this->config(), $this->text(),
		'<span class="dark_text">genres:</span>(.*?)</div>', '(<a href=[^>]+>.*?</a>)',
		[ '<a href="[^"]+genre/(\d+)[^"]+"[^>]+>.*?</a>', '<a href="[^"]+"[^>]+>(.*?)</a>' ],
		[ 'id', 'title' ],
		static::$limit
		);
	}

	/**
	 * @return 		string
	 * @usage 		statistic()->popularity
	 */
	protected function getpopularityWithStatisticFromData() {

		$data = $this->request()::match( '<span class="dark_text">popularity:</span>(.*?)</div>' );

		if ( $data == FALSE ) return FALSE;

		$data = str_replace( '#', '', $data );

		if ( !$this->text()->validate( [ 'mode' => 'number' ], $data ) ) return FALSE;

		return "#{$data}";
	}

	/**
	 * @return 		array
	 * @usage 		none
	 */
	protected function member() {

		$data = $this->request()::match( '<span class="dark_text">members:</span>(.*?)</div>' );

		if ( $data == FALSE ) return FALSE;

		$data = $this->text()->replace( '[^0-9]+', '', $data );

		return $data;
	}

	/**
	 * @return 		string
	 * @usage 		statistic()->member
	 */
	protected function getMemberWithStatisticFromData() {

		$data = $this->getMemberrawWithStatisticFromData();

		if ( $data == FALSE ) return FALSE;

		return $this->text()->formatK( $data );
	}

	/**
	 * @return 		string
	 * @usage 		statistic()->memberraw
	 */
	protected function getMemberrawWithStatisticFromData() {

		return $this->member();
	}

	/**
	 * @return 		array
	 * @usage 		none
	 */
	protected function favorite() {

		$data = $this->request()::match( '<span class="dark_text">favorites:</span>(.*?)</div>' );

		if ( $data == FALSE ) return FALSE;

		$data = $this->text()->replace( '[^0-9]+', '', $data );

		return $data;
	}

	/**
	 * @return 		string
	 * @usage 		statistic()->favorite
	 */
	protected function getFavoriteWithStatisticFromData() {

		$data = $this->getFavoriterawWithStatisticFromData();

		if ( $data == FALSE ) return FALSE;

		return $this->text()->formatK( $data );
	}

	/**
	 * @return 		string
	 * @usage 		statistic()->favoriteraw
	 */
	protected function getFavoriterawWithStatisticFromData() {

		return $this->favorite();
	}

	/**
	 * @return 		string
	 * @usage 		status
	 */
	protected function getStatusFromData() {

		return $this->request()::match( '<span class="dark_text">status:</span>(.*?)</div>' );
	}

	/**
	 * @return 		array
	 * @usage 		authors
	 */
	protected function getAuthorsFromData() {

		return
		$this->request()::matchTable(
		$this->config(), $this->text(),
		'<span class="dark_text">authors:</span>(.*?)</div>', '(<a[^>]+>.*?</a>[^<,]+)',
		[ '<a href="[^"]+people/(\d+)[^"]+">.*?</a>', '<a href="[^"]+people/\d+[^"]+">(.*?)</a>', '<a href="[^"]+people/\d+[^"]+">.*?</a>\s+\(([^)]+)\)' ],
		[ 'id', 'name', 'job' ],
		0
		);
	}

	/**
	 * @return 		string
	 * @usage 		volume
	 */
	protected function getVolumeFromData() {

		$data = $this->request()::match('<span class="dark_text">volumes:</span>(.*?)</div>');

		if ( $data == FALSE OR !$this->text()->validate( [ 'mode' => 'number' ], $data ) ) return FALSE;

		return $data;
	}

	/**
	 * @return 		string
	 * @usage 		chapter
	 */
	protected function getChapterFromData() {

		$data = $this->request()::match( '<span class="dark_text">chapters:</span>(.*?)</div>' );

		if ( $data == FALSE OR !$this->text()->validate( [ 'mode' => 'number' ], $data ) ) return FALSE;

		return $data;
	}

	/**
	 * @return 		string
	 * @usage 		serialization
	 */
	protected function getSerializationFromData() {

		return
		$this->request()::matchTable(
		$this->config(), $this->text(),
		'<span class="dark_text">serialization:</span>(.*?)</div>', '(<a href=[^>]+>.*?</a>)',
		[ '<a href="[^"]+manga/magazine/(\d+)[^"]+"[^>]+>.*?</a>', '<a href="[^"]+"[^>]+>(.*?)</a>' ],
		[ 'id', 'title' ],
		static::$limit
		);
	}

	/**
	 * @return 		string
	 * @usage 		none
	 */
	protected function published() {

		$data = $this->request()::match( '<span class="dark_text">published:</span>(.*?)</div>' );

		preg_match( '/(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)\s*(\d+),\s*(\d+)\s*to\s*(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)\s*(\d+),\s*(\d+)/', $data, $out );

		if ( !empty( $out ) ) {

			return [

				'first' => [ 'month' => $out[ 1 ], 'day' => $out[ 2 ], 'year' => $out[ 3 ] ],
				'last'  => [ 'month' => $out[ 4 ], 'day' => $out[ 5 ], 'year' => $out[ 6 ] ]
			];
		}

		preg_match( '/(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)\s*(\d+),\s*(\d+)\s*to\s*\?/', $data, $out );

		if ( !empty( $out ) ) {

			return [

				'first' => [ 'month' => $out[ 1 ], 'day' => $out[ 2 ], 'year' => $out[ 3 ] ],
				'last'  => [ 'month' => 'no',      'day' => 'no',      'year' => 'no' ]
			];
		}

		preg_match( '/(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)\s*(\d+),\s*(\d+)/', $data, $out );

		if ( !empty( $out ) ) {

			return [ 'first' => [ 'month' => $out[ 1 ], 'day' => $out[ 2 ], 'year' => $out[ 3 ] ] ];
		}

		return FALSE;
	}

	/**
	 * @return 		string
	 * @usage 		published()->first()->month
	 */
	protected function getMonthWithPublishedFirstFromData() {

		$this->setRequired( 'published' );

		return ( isset( static::$data[ 'published' ][ 'first_month' ] ) ) ? static::$data[ 'published' ][ 'first_month' ] : FALSE;
	}

	/**
	 * @return 		string
	 * @usage 		published()->first()->day
	 */
	protected function getDayWithPublishedFirstFromData() {

		$this->setRequired( 'published' );

		return ( isset( static::$data[ 'published' ][ 'first_day' ] ) ) ? static::$data[ 'published' ][ 'first_day' ] : FALSE;
	}

	/**
	 * @return 		string
	 * @usage 		published()->first()->year
	 */
	protected function getYearWithPublishedFirstFromData() {

		$this->setRequired( 'published' );

		return ( isset( static::$data[ 'published' ][ 'first_year' ] ) ) ? static::$data[ 'published' ][ 'first_year' ] : FALSE;
	}

	/**
	 * @return 		string
	 * @usage 		published()->last()->month
	 */
	protected function getMonthWithPublishedLastFromData() {

		$this->setRequired( 'published' );

		return ( isset( static::$data[ 'published' ][ 'last_month' ] ) ) ? static::$data[ 'published' ][ 'last_month' ] : FALSE;
	}

	/**
	 * @return 		string
	 * @usage 		published()->last()->day
	 */
	protected function getDayWithPublishedLastFromData() {

		$this->setRequired( 'published' );

		return ( isset( static::$data[ 'published' ][ 'last_day' ] ) ) ? static::$data[ 'published' ][ 'last_day' ] : FALSE;
	}

	/**
	 * @return 		string
	 * @usage 		published()->last()->year
	 */
	protected function getYearWithPublishedLastFromData() {

		$this->setRequired( 'published' );

		return ( isset( static::$data[ 'published' ][ 'last_year' ] ) ) ? static::$data[ 'published' ][ 'last_year' ] : FALSE;
	}

	/**
	 * @return 		string
	 * @usage 		year
	 */
	protected function getYearFromData() {

		$data = $this->request()::match( '<span class="dark_text">published:</span>(.*?)</div>' );

		if ( $data == FALSE ) return FALSE;

		preg_match( '/(\d{4})/', $data, $out );

		return !isset( $out[ 1 ] ) ? FALSE : $out[ 1 ];
	}

	/**
	 * @return 		array
	 * @usage 		characters
	 */
	protected function getCharactersFromData() {

		return
		$this->request()::matchTable(
		$this->config(), $this->text(),
		'</div>characters</h2><div.*?">(.+?</table>)</div></div>', '<table[^>]*>(.*?)</table>',
		[ '<a href="[^"]+character/(\d+)[^"]+">[^<]+</a>', '<a href="[^"]+character/\d+[^"]+">([^<]+)</a>', '<small>([^<]+)</small>' ],
		[ 'id', 'name', 'role' ],
		static::$limit
		);

		return static::setValue( $key, $data );
	}

	/**
	 * @return 		array
	 * @usage 		related()->adaptation
	 */
	protected function getAdaptationWithRelatedFromData() {

		return
		$this->request()::matchTable(
		$this->config(), $this->text(),
		'<td.*?>adaptation:</td>.*?(<td.*?>.*?</td>)', '(<a href=[^>]+>.*?</a>)',
		[ '<a href="[^"]+anime/(\d+)[^"]+">.*?</a>', '<a href="[^"]+">(.*?)</a>' ],
		[ 'id', 'title' ],
		static::$limit
		);

		return static::setValue( $key, $data );
	}

	/**
	 * @return 		array
	 * @usage 		related()->sequel
	 */
	protected function getSequelWithRelatedFromData() {

		return
		$this->request()::matchTable(
		$this->config(), $this->text(),
		'<td.*?>sequel:</td>.*?(<td.*?>.*?</td>)', '(<a href=[^>]+>.*?</a>)',
		[ '<a href="[^"]+manga/(\d+)[^"]+">.*?</a>', '<a href="[^"]+">(.*?)</a>' ],
		[ 'id', 'title' ],
		static::$limit
		);

		return static::setValue( $key, $data );
	}

	/**
	 * @return 		array
	 * @usage 		related()->prequel
	 */
	protected function getPrequelWithRelatedFromData() {

		return
		$this->request()::matchTable(
		$this->config(), $this->text(),
		'<td.*?>prequel:</td>.*?(<td.*?>.*?</td>)', '(<a href=[^>]+>.*?</a>)',
		[ '<a href="[^"]+manga/(\d+)[^"]+">.*?</a>', '<a href="[^"]+">(.*?)</a>' ],
		[ 'id', 'title' ],
		static::$limit
		);

		return static::setValue( $key, $data );
	}

	/**
	 * @return 		array
	 * @usage 		related()->parentstory
	 */
	protected function getParentstoryWithRelatedFromData() {

		return
		$this->request()::matchTable(
		$this->config(), $this->text(),
		'<td.*?>parent story:</td>.*?(<td.*?>.*?</td>)', '(<a href=[^>]+>.*?</a>)',
		[ '<a href="[^"]+manga/(\d+)[^"]+">.*?</a>', '<a href="[^"]+">(.*?)</a>' ],
		[ 'id', 'title' ],
		static::$limit
		);

		return static::setValue( $key, $data );
	}

	/**
	 * @return 		array
	 * @usage 		related()->sidestory
	 */
	protected function getSidestoryWithRelatedFromData() {

		return
		$this->request()::matchTable(
		$this->config(), $this->text(),
		'<td.*?>side story:</td>.*?(<td[^>]*>.*?</td>)', '(<a href=[^>]+>.*?</a>)',
		[ '<a href="[^"]+manga/(\d+)[^"]+">.*?</a>', '<a href="[^"]+">(.*?)</a>' ],
		[ 'id', 'title' ],
		static::$limit
		);
	}

	/**
	 * @return 		array
	 * @usage 		related()->other
	 */
	protected function getOtherWithRelatedFromData() {

		return
		$this->request()::matchTable(
		$this->config(), $this->text(),
		'<td.*?>other:</td>.*?(<td[^>]*>.*?</td>)', '(<a href=[^>]+>.*?</a>)',
		[ '<a href="[^"]+manga/(\d+)[^"]+">.*?</a>', '<a href="[^"]+">(.*?)</a>' ],
		[ 'id', 'title' ],
		static::$limit
		);

		return static::setValue( $key, $data );
	}

	/**
	 * @return 		array
	 * @usage 		related()->spinoff
	 */
	protected function getSpinoffWithRelatedFromData() {

		return
		$this->request()::matchTable(
		$this->config(), $this->text(),
		'<td.*?>spin\-off:</td>.*?(<td[^>]*>.*?</td>)', '(<a href=[^>]+>.*?</a>)',
		[ '<a href="[^"]+manga/(\d+)[^"]+">.*?</a>', '<a href="[^"]+">(.*?)</a>' ],
		[ 'id', 'title' ],
		static::$limit
		);
	}

	/**
	 * @return 		array
	 * @usage 		related()->alternativeversion
	 */
	protected function getAlternativeversionWithRelatedFromData() {

		return
		$this->request()::matchTable(
		$this->config(), $this->text(),
		'<td.*?>alternative version:</td>.*?(<td[^>]*>.*?</td>)', '(<a href=[^>]+>.*?</a>)',
		[ '<a href="[^"]+manga/(\d+)[^"]+">.*?</a>', '<a href="[^"]+">(.*?)</a>' ],
		[ 'id', 'title' ],
		static::$limit
		);
	}

	/**
	 * @return 		string
	 * @usage 		link
	 */
	public function link() {

		return $this->request()::$url;
	}
}