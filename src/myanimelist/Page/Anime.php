<?php

/**
 * MyAnimeList Anime Page API
 *
 * @package	 		MyAnimeList API
 * @author     		Magnum357 [https://github.com/magnum357i/]
 * @copyright  		2018
 * @license    		http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace myanimelist\Page;

class Anime extends \myanimelist\Builder\Page {

	/**
	 * Set type
	 */
	protected static $type = 'anime';

	/**
	 * Methods to allow for prefix
	 */
	protected static $methodsToAllow = [ 'title', 'score', 'statistic', 'broadcast', 'duration', 'premiered', 'related', 'aired' => [ 'first', 'last' ], 'song' ];

	/**
	 * Patterns for externalLink
	 */
	protected static $externalLinks = [ 'genre' => 'anime/genre/{s}', 'producer' => 'anime/producer/{s}', 'character' => 'character/{s}', 'people' => 'people/{s}', 'anime' => 'anime/{s}', 'manga' => 'manga/{s}' ];

	/**
	 * @return 		string
	 * @usage 		title()->original
	 */
	protected function getOriginalWithTitleFromData() {

		$data = $this->request()::match( '<span itemprop="name">(.*?)</span>' );

		if ( $data == FALSE ) return FALSE;

		return $this->text()->replace( '\s*\(.+\)', '', $data, 'si' );
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

		$data = $this->request()::match( [ '(https://myanimelist.cdn-dena.com/images/anime/[0-9]+/[0-9]+\.jpg)', '(https://cdn.myanimelist.net/images/anime/[0-9]+/[0-9]+\.jpg)' ] );

		if ( $data == FALSE ) return FALSE;

		if ( $this->config()->isOnCache() ) {

			$newPoster = $this->cache()->savePoster( $this->getImageName(), $data );
			$data      = $newPoster;
		}

		return $data;
	}

	/**
	 *
	 * @return 		string
	 * @usage 		description
	 */
	protected function getDescriptionFromData() {

		$data = $this->request()::match( '<span itemprop="description">(.*?)</span>', '<br>' );

		if ( $data == FALSE ) return FALSE;

		return $this->text()->descCleaner( $data );
	}

	/**
	 * @return 		string
	 * @usage 		category
	 */
	protected function getCategoryFromData() {

		return $this->request()::match( '<span class="dark_text">type:</span>(.*?)</div>' );
	}

	/**
	 * @return 		string
	 * @usage 		rating
	 */
	protected function getRatingFromData() {

		$data = $this->request()::match( '<span class="dark_text">rating:</span>(.*?)</div>' );

		if ( $data == FALSE ) return FALSE;

		if ( $this->text()->validate( [ 'mode' => 'regex', 'regex_code' => 'none', 'regex_flags' => 'si' ], $data ) ) return FALSE;

		$data = str_replace( [ '- Teens 13 or older', '(violence & profanity)', '- Mild Nudity', ' ' ], '', $data );
		$data = trim( $data );

		return $data;
	}

	/**
	 * @return 		array
	 * @usage 		licensors
	 */
	protected function getLicensorsFromData() {

		return
		$this->request()::matchTable(
		$this->config(), $this->text(),
		'<span class="dark_text">licensors:</span>(.*?)</div>', '(<a href=[^>]+>.*?</a>)',
		[ '<a href="[^"]+producer/(\d+)[^"]+"[^>]+>.*?</a>', '<a href="[^"]+"[^>]+>(.*?)</a>' ],
		[ 'id', 'title' ],
		static::$limit
		);
	}

	/**
	 * @return 		array
	 * @usage 		producers
	 */
	protected function getProducersFromData() {

		return
		$this->request()::matchTable(
		$this->config(), $this->text(),
		'<span class="dark_text">producers:</span>(.*?)</div>', '(<a href=[^>]+>.*?</a>)',
		[ '<a href="[^"]+producer/(\d+)[^"]+"[^>]+>.*?</a>', '<a href="[^"]+"[^>]+>(.*?)</a>' ],
		[ 'id', 'title' ],
		static::$limit
		);
	}

	/**
	 * @return 		string
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
	 * @return 		array
	 * @usage 		genres
	 */
	protected function getGenresFromData() {

		return
		$this->request()::matchTable(
		$this->config(), $this->text(),
		'<span class="dark_text">genres:</span>(.*?)</div>', 	'(<a href=[^>]+>.*?</a>)',
		[ '<a href="[^"]+genre/(\d+)[^"]+"[^>]+>.*?</a>', '<a href="[^"]+"[^>]+>(.*?)</a>' ],
		[ 'id', 'title' ],
		static::$limit
		);
	}

	/**
	 * @return 		string
	 * @usage 		source
	 */
	protected function getSourceFromData() {

		$data = $this->request()::match( '<span class="dark_text">source:</span>(.*?)</div>' );

		if ( $data === FALSE OR $this->text()->validate( [ 'mode' => 'regex', 'regex_code' => 'unknown', 'regex_flags' => 'si' ], $data ) === FALSE ) return FALSE;

		return $data;
	}

	/**
	 * @return 		array
	 * @usage 		none
	 */
	protected function aired() {

		$data = $this->request()::match( '<span class="dark_text">aired:</span>(.*?)</div>' );

		if ( $data == FALSE ) return FALSE;

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

				'first' => [ 'month' => $out[ 1 ], 	'day' => $out[ 2 ], 'year' => $out[ 3 ] ],
				'last'  => [ 'month' => 'no',       'day' => 'no',      'year' => 'no' ]
			];
		}

		preg_match( '/(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)\s*(\d+),\s*(\d+)/', $data, $out );

		if ( !empty( $out ) ) {

			return [

				'first' => [ 'month' => $out[ 1 ], 	'day' => $out[ 2 ], 'year' => $out[ 3 ] ]
			];
		}

		return FALSE;
	}

	/**
	 * @return 		string
	 * @usage 		aired()->first()->month
	 */
	protected function getMonthWithAiredFirstFromData() {

		$this->setRequired( 'aired' );

		return ( isset( static::$data[ 'aired' ][ 'first' ][ 'month' ] ) ) ? static::$data[ 'aired' ][ 'first' ][ 'month' ] : FALSE;
	}

	/**
	 * @return 		string
	 * @usage 		aired()->first()->day
	 */
	protected function getDayWithAiredFirstFromData() {

		$this->setRequired( 'aired' );

		return ( isset( static::$data[ 'aired' ][ 'first' ][ 'day' ] ) ) ? static::$data[ 'aired' ][ 'first' ][ 'day' ] : FALSE;
	}

	/**
	 * @return 		string
	 * @usage 		aired()->first()->year
	 */
	protected function getYearWithAiredFirstFromData() {

		$this->setRequired( 'aired' );

		return ( isset( static::$data[ 'aired' ][ 'first' ][ 'year' ] ) ) ? static::$data[ 'aired' ][ 'first' ][ 'year' ] : FALSE;
	}

	/**
	 * @return 		string
	 * @usage 		aired()->last()->month
	 */
	protected function getMonthWithAiredLastFromData() {

		$this->setRequired( 'aired' );

		return ( isset( static::$data[ 'aired' ][ 'last' ][ 'month' ] ) ) ? static::$data[ 'aired' ][ 'last' ][ 'month' ] : FALSE;
	}

	/**
	 * @return 		string
	 * @usage 		aired()->last()->day
	 */
	protected function getDayWithAiredLastFromData() {

		$this->setRequired( 'aired' );

		return ( isset( static::$data[ 'aired' ][ 'last' ][ 'day' ] ) ) ? static::$data[ 'aired' ][ 'last' ][ 'day' ] : FALSE;
	}

	/**
	 * @return 		string
	 * @usage 		aired()->last()->year
	 */
	protected function getYearWithAiredLastFromData() {

		$this->setRequired( 'aired' );

		return ( isset( static::$data[ 'aired' ][ 'last' ][ 'year' ] ) ) ? static::$data[ 'aired' ][ 'last' ][ 'year' ] : FALSE;
	}

	/**
	 * @return 		string
	 * @usage 		episode
	 */
	protected function getEpisodeFromData() {

		$data = $this->request()::match( '<span class="dark_text">episodes:</span>(.*?)</div>' );
		$data = $this->text()->replace( '[^0-9]+', '', $data );

		return $data;
	}

	/**
	 * @return 		array
	 * @usage 		studios
	 */
	protected function getStudiosFromData() {

		return
		$this->request()::matchTable(
		$this->config(), $this->text(),
		'<span class="dark_text">studios:</span>(.*?)</div>', '(<a href=[^>]+>.*?</a>)',
		[ '<a href="[^"]+producer/(\d+)[^"]+"[^>]+>.*?</a>', '<a href="[^"]+"[^>]+>(.*?)</a>' ],
		[ 'id', 'title' ],
		static::$limit
		);
	}

	/**
	 * @return 		array
	 * @usage 		none
	 */
	protected function duration() {

		$data = $this->request()::match( '<span class="dark_text">duration:</span>(.*?)</div>' );
		$data = $this->text()->replace( '\s+per ep\.', '', $data );

		preg_match( '/(\d+) hr\. (\d+) min\./', $data, $out );

		if ( !empty( $out ) ) return [ 'hour' => $out[ 1 ], 'min' => $out[ 2 ] ];

		preg_match( '/(\d+) min\./', $data, $out );

		if ( !empty( $out ) ) return [ 'hour' => '0', 'min' => $out[ 1 ] ];

		return FALSE;
	}

	/**
	 * @return 		string
	 * @usage 		duration()->hour
	 */
	protected function getHourWithDurationFromData() {

		$this->setRequired( 'duration' );

		return ( isset( static::$data[ 'duration' ][ 'hour' ] ) ) ? static::$data[ 'duration' ][ 'hour' ] : FALSE;
	}

	/**
	 * @return 		string
	 * @usage 		duration()->min
	 */
	protected function getMinWithDurationFromData() {

		$this->setRequired( 'duration' );

		return ( isset( static::$data[ 'duration' ][ 'min' ] ) ) ? static::$data[ 'duration' ][ 'min' ] : FALSE;
	}

	/**
	 * @return 		array
	 * @usage 		none
	 */
	protected function premiered() {

		$data = $this->request()::match( '<span class="dark_text">premiered:</span>(.*?)</div>' );

		preg_match( '/^(\w+) (\d+)$/', $data, $out );

		if ( empty( $out ) ) return FALSE;

		return [ 'season' => $out[ 1 ], 'year' => $out[ 2 ] ];
	}

	/**
	 * @return 		string
	 * @usage 		premiered()->season
	 */
	protected function getSeasonWithPremieredFromData() {

		$this->setRequired( 'premiered' );

		return ( isset( static::$data[ 'premiered' ][ 'season' ] ) ) ? static::$data[ 'premiered' ][ 'season' ] : FALSE;
	}

	/**
	 * @return 		string
	 * @usage 		premiered()->year
	 */
	protected function getYearWithPremieredFromData() {

		$this->setRequired( 'premiered' );

		return ( isset( static::$data[ 'premiered' ][ 'year' ] ) ) ? static::$data[ 'premiered' ][ 'year' ] : FALSE;
	}

	/**
	 * @return 		string
	 * @usage 		statistic()->rank
	 */
	protected function getRankWithStatisticFromData() {

		$data = $this->request()::match( '<span class="dark_text">ranked:</span>(.*?)<sup>' );

		if ( $data == FALSE ) return FALSE;

		$data = str_replace( '#', '', $data );

		return !$this->text()->validate( [ 'mode' => 'number' ], $data ) ? FALSE : "#{$data}";
	}

	/**
	 * @return 		string
	 * @usage 		statistic()->popularity
	 */
	protected function getPopularityWithStatisticFromData() {

		$data = $this->request()::match( '<span class="dark_text">popularity:</span>(.*?)</div>' );

		if ( $data == FALSE ) return FALSE;

		$data = str_replace( '#', '', $data );

		return !$this->text()->validate( [ 'mode' => 'number' ], $data ) ? FALSE : "#{$data}";
	}

	/**
	 * @return 		string
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
	 * @return 		string
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
	 * @usage 		none
	 */
	protected function broadcast() {

		$data = $this->request()::match( '<span class="dark_text">broadcast:</span>(.*?)</div>' );

		preg_match( '/(\w+) at (\d+):(\d+) \(\w+\)/', $data, $out );

		return !empty( $out ) ? [ 'day' => $out[ 1 ], 'hour' => $out[ 2 ], 'minute' => $out[ 3 ] ] : FALSE;
	}

	/**
	 * @return 		string
	 * @usage 		broadcast()->day
	 */
	protected function getDayWithBroadcastFromData() {

		$this->setRequired( 'broadcast' );

		return ( isset( static::$data[ 'broadcast' ][ 'day' ] ) ) ? static::$data[ 'broadcast' ][ 'day' ] : FALSE;
	}

	/**
	 * @return 		string
	 * @usage 		broadcast()->hour
	 */
	protected function getHourWithBroadcastFromData() {

		$this->setRequired( 'broadcast' );

		return ( isset( static::$data[ 'broadcast' ][ 'hour' ] ) ) ? static::$data[ 'broadcast' ][ 'hour' ] : FALSE;
	}

	/**
	 * @return 		string
	 * @usage 		broadcast()->minute
	 */
	protected function getMinuteWithBroadcastFromData() {

		$this->setRequired( 'broadcast' );

		return ( isset( static::$data[ 'broadcast' ][ 'minute' ] ) ) ? static::$data[ 'broadcast' ][ 'minute' ] : FALSE;
	}

	/**
	 * @return 		string
	 * @usage 		year
	 */
	protected function getYearFromData() {

		$data = $this->request()::match( '<span class="dark_text">aired:</span>(.*?)</div>' );

		preg_match( '/(\d{4})/', $data, $out );

		return !isset( $out[ 1 ] ) ? FALSE : $out[ 1 ];
	}

	/**
	 * @return 		array
	 * @usage 		voice
	 */
	protected function getVoiceFromData() {

		return
		$this->request()::matchTable(
		$this->config(), $this->text(),
		'</div>characters & voice actors</h2>(.*?)<a name="staff">', '<tr>(.*?)</tr>',
		[
		'<a href="[^"]+/character/(\d+)/[^"]+">[^<]+</a>', '<a href="[^"]+/character/.*?/.*?">([^<]+)</a>',
		'<a href="[^"]+/people/(\d+)/[^"]+">[^<]+</a>', '<a href="[^"]+/people/.*?/.*?">([^<]+)</a>', '<a href="[^"]+/people/.*?/.*?">[^<]+</a>.*?<small>(.*?)</small>'
		],
		[
		'character_id', 'character_name',
		'people_id', 'people_name', 'people_lang'
		],
		static::$limit
		);
	}

	/**
	 * @return 		array
	 * @usage 		staff
	 */
	protected function getStaffFromData() {

		return
		$this->request()::matchTable(
		$this->config(), $this->text(),
		'</div>.*?staff[^<]*?</h2>(.*?)<h2>', '<table.*?>(.*?)</table>',
		[ '<a href="[^"]+/people/(\d+)/[^"]+">[^<]+</a>', '<a href="[^"]+/people/.*?/.*?">([^<]+)</a>', '<a href="[^"]+/people/.*?/.*?">[^<]+</a>.*?<small>(.*?)</small>' ],
		[ 'people_id', 'people_name', 'people_positions_list' ],
		static::$limit
		);
	}

	/**
	 * @return 		array
	 * @usage 		song()->opening
	 */
	protected function getOpeningWithSongFromData() {

		return
		$this->request()::matchTable(
		$this->config(), $this->text(),
		'<div class="theme-songs js-theme-songs opnening">(.*?)<br></div>', '<span class="theme-song">(.*?)</span>',
		[ '"(.*?)["\(]', 'by ([^\(]+)', 'eps (\d+)\-', 'eps \d+\-(\d+)' ],
		[ 'title', 'singer', 'start', 'end' ],
		static::$limit
		);
	}

	/**
	 * @return 		array
	 * @usage 		song()->ending
	 */
	protected function getEndingWithSongFromData() {

		return
		$this->request()::matchTable(
		$this->config(), $this->text(),
		'<div class="theme-songs js-theme-songs ending">(.*?)<br></div>', '<span class="theme-song">(.*?)</span>',
		[ '"(.*?)["\(]', 'by ([^\(]+)', 'eps (\d+)\-\d+', 'eps (\d+)\-?', 'eps \d+\-(\d+)' ],
		[ 'title', 'singer', 'start', 'start', 'end' ],
		static::$limit
		);
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
		[ '<a href="[^"]*/manga/(\d+)[^"]*">.*?</a>', '<a href="[^"]+">(.*?)</a>' ],
		[ 'id', 'title' ],
		static::$limit
		);
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
		[ '<a href="[^"]*/anime/(\d+)[^"]*">.*?</a>', '<a href="[^"]+">(.*?)</a>' ],
		[ 'id', 'title' ],
		static::$limit
		);
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
		[ '<a href="[^"]*/anime/(\d+)[^"]*">.*?</a>', '<a href="[^"]+">(.*?)</a>' ],
		[ 'id', 'title' ],
		static::$limit
		);
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
		[ '<a href="[^"]*/anime/(\d+)[^"]*">.*?</a>', '<a href="[^"]+">(.*?)</a>' ],
		[ 'id', 'title' ],
		static::$limit
		);
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
		[ '<a href="[^"]*/anime/(\d+)[^"]*">.*?</a>', '<a href="[^"]+">(.*?)</a>' ],
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
		[ '<a href="[^"]*/anime/(\d+)[^"]*">.*?</a>', '<a href="[^"]+">(.*?)</a>' ],
		[ 'id', 'title' ],
		static::$limit
		);
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
		[ '<a href="[^"]*/anime/(\d+)[^"]*">.*?</a>', '<a href="[^"]+">(.*?)</a>' ],
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
		[ '<a href="[^"]*/anime/(\d+)[^"]*">.*?</a>', '<a href="[^"]+">(.*?)</a>' ],
		[ 'id', 'title' ],
		static::$limit
		);
	}

	/**
	 * @return 		string
	 * @usage 		trailer
	 */
	protected function getTrailerFromData() {

		$data = $this->request()::match( '<div class="video-promotion">.*?<a[^>]+href="(.*?)"[^>]+>' );

		if ( $data == FALSE ) return FALSE;

		$data = $this->text()->replace( '\?.+', '', $data );
		$data = str_replace( 'embed/', 'watch?v=', $data );

		return $data;
	}

	/**
	 * @return 		string
	 * @usage 		link
	 */
	public function link() {

		return $this->request()::$url;
	}
}