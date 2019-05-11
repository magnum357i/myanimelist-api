<?php

/**
 * MyAnimeList Anime Page API
 *
 * @package	 		MyAnimeList API
 * @author     		Magnum357 [https://github.com/magnum357i/]
 * @copyright  		2018
 * @license    		http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace MyAnimeList\Page;

use MyAnimeList\Builder\AbstractPage;

class Anime extends AbstractPage {

	/**
	 * Set type
	 */
	public static $type = 'anime';

	/**
	 * Patterns for externalLink
	 */
	protected static $externalLinks = [ 'genre' => 'anime/genre/{s}', 'producer' => 'anime/producer/{s}', 'character' => 'character/{s}', 'people' => 'people/{s}', 'anime' => 'anime/{s}', 'manga' => 'manga/{s}' ];

	/**
	 * @return 		string
	 * @usage 		titleOriginal
	 */
	protected function getOriginalWithTitleFromData() {

		$data = $this->request()::match( '<span itemprop="name">(.*?)</span>' );

		if ( $data == FALSE ) return FALSE;

		return $this->text()->replace( '\s*\(.+\)', '', $data, 'si' );
	}

	/**
	 * @return 		string
	 * @usage 		titleEnglish
	 */
	protected function getEnglishWithTitleFromData() {

		return $this->request()::match( '<span class="dark_text">english:</span>(.*?)</div>' );
	}

	/**
	 * @return 		string
	 * @usage 		titleJapanese
	 */
	protected function getJapaneseWithTitleFromData() {

		return $this->request()::match( '<span class="dark_text">japanese:</span>(.*?)</div>' );
	}

	/**
	 * @return 		string
	 * @usage 		titleOthers
	 */
	protected function getOthersWithTitleFromData() {

		return $this->request()::match( '<span class="dark_text">synonyms:</span>(.*?)</div>' );
	}

	/**
	 * @return 		string
	 * @usage 		poster
	 */
	protected function getPosterFromData() {

		$data = $this->request()::match( [ '(https://myanimelist.cdn-dena.com/images/anime/[0-9]+/[0-9]+\.jpg)', '(https://cdn.myanimelist.net/images/anime/[0-9]+/[0-9]+\.jpg)' ] );

		if ( $data == FALSE ) return FALSE;

		if ( $this->config()::isOnCache() ) {

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
	 * @usage 		backgruond
	 */
	protected function getBackgroundFromData() {

		$data = $this->request()::match( '<span itemprop="description">.*?</span><h2[^>]*>.*?</h2>(.*?)<div[^>]*>', '<br>' );

		if ( $data === FALSE OR $this->text()->validate( [ 'mode' => 'regex', 'regex_code' => 'no background information', 'regex_flags' => 'si' ], $data ) ) return FALSE;

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
	 * @usage 		scoreVote
	 */
	protected function getVoteWithScoreFromData() {

		$data = $this->getVoterawWithScoreFromData();

		if ( $data == FALSE ) return FALSE;

		return $this->text()->formatK( $data );
	}

	/**
	 * @return 		string
	 * @usage 		scoreVoteraw
	 */
	protected function getVoterawWithScoreFromData() {

		$data = $this->request()::match( 'scored by <span itemprop="ratingCount">(.*?)</span> users' );

		if ( $data == FALSE ) return FALSE;

		return $this->text()->replace( '[^0-9]+', '', $data );
	}

	/**
	 * @return 		string
	 * @usage 		scorePoint
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

		if ( $data == FALSE OR $this->text()->validate( [ 'mode' => 'regex', 'regex_code' => 'unknown', 'regex_flags' => 'si' ], $data ) ) return FALSE;

		return $data;
	}

	/**
	 * @return 		array
	 * @usage 		airedFirst
	 */
	protected function getFirstWithAiredFromData() {

		$data = $this->request()::match( '<span class="dark_text">aired:</span>(.*?)</div>' );

		if ( $data == FALSE ) return FALSE;

		preg_match( '/(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)\s*(\d+),\s*(\d+)/', $data, $out );

		if ( !empty( $out ) ) return $this->text()->originalDate( $out[ 1 ], $out[ 2 ], $out[ 3 ] );

		return FALSE;
	}

	/**
	 * @return 		array
	 * @usage 		airedLast
	 */
	protected function getLastWithAiredFromData() {

		$data = $this->request()::match( '<span class="dark_text">aired:</span>(.*?)</div>' );

		if ( $data == FALSE ) return FALSE;

		preg_match( '/to\s*(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)\s*(\d+),\s*(\d+)/', $data, $out );

		if ( !empty( $out ) ) return $this->text()->originalDate( $out[ 1 ], $out[ 2 ], $out[ 3 ] );

		preg_match( '/to\s*\?/', $data, $out );

		if ( !empty( $out ) ) return 'no';

		return FALSE;
	}

	/**
	 * @return 		string
	 * @usage 		episode
	 */
	protected function getEpisodeFromData() {

		$data = $this->request()::match( '<span class="dark_text">episodes:</span>(.*?)</div>' );

		return $this->text()->validate( [ 'mode' => 'number' ], $data ) ? $data : FALSE;
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
	 * @usage 		duration
	 */
	protected function getDurationFromData() {

		$data = $this->request()::match( '<span class="dark_text">duration:</span>(.*?)</div>' );

		preg_match( '/(\d+) hr\. (\d+) min\./', $data, $out );

		if ( !empty( $out ) ) return [ 'hour' => $out[ 1 ], 'minute' => $out[ 2 ] ];

		preg_match( '/(\d+) min\./', $data, $out );

		if ( !empty( $out ) ) return [ 'hour' => '0', 'minute' => $out[ 1 ] ];
	}

	/**
	 * @return 		array
	 * @usage 		premiered
	 */
	protected function getPremieredFromData() {

		$data = $this->request()::match( '<span class="dark_text">premiered:</span>(.*?)</div>' );

		preg_match( '/^(\w+) (\d+)$/', $data, $out );

		if ( empty( $out ) ) return FALSE;

		$seasons = [ 'Summer', '1', 'Spring' => '2', 'Winter' => '3', 'Fall' => '4' ];

		if ( !isset( $seasons[ $out[ 1 ] ] ) ) return FALSE;

		return [ 'seasonIndex' => $seasons[ $out[ 1 ] ], 'seasonTitle' => $out[ 1 ], 'year' => $out[ 2 ] ];
	}

	/**
	 * @return 		string
	 * @usage 		statisticRank
	 */
	protected function getRankWithStatisticFromData() {

		$data = $this->request()::match( '<span class="dark_text">ranked:</span>(.*?)<sup>' );

		if ( $data == FALSE ) return FALSE;

		$data = str_replace( '#', '', $data );

		return $this->text()->validate( [ 'mode' => 'number' ], $data ) ? $data : FALSE;
	}

	/**
	 * @return 		string
	 * @usage 		statisticPopularity
	 */
	protected function getPopularityWithStatisticFromData() {

		$data = $this->request()::match( '<span class="dark_text">popularity:</span>(.*?)</div>' );

		if ( $data == FALSE ) return FALSE;

		$data = str_replace( '#', '', $data );

		return $this->text()->validate( [ 'mode' => 'number' ], $data ) ? $data : FALSE;
	}

	/**
	 * @return 		string
	 * @usage 		statisticMember
	 */
	protected function getMemberWithStatisticFromData() {

		$data = $this->getMemberrawWithStatisticFromData();

		if ( $data == FALSE ) return FALSE;

		return $this->text()->formatK( $data );
	}

	/**
	 * @return 		string
	 * @usage 		statisticMemberraw
	 */
	protected function getMemberrawWithStatisticFromData() {

		$data = $this->request()::match( '<span class="dark_text">members:</span>(.*?)</div>' );

		if ( $data == FALSE ) return FALSE;

		return $this->text()->replace( '[^0-9]+', '', $data );
	}

	/**
	 * @return 		string
	 * @usage 		statisticFavorite
	 */
	protected function getFavoriteWithStatisticFromData() {

		$data = $this->getFavoriterawWithStatisticFromData();

		if ( $data == FALSE ) return FALSE;

		return $this->text()->formatK( $data );
	}

	/**
	 * @return 		string
	 * @usage 		statisticFavoriteraw
	 */
	protected function getFavoriterawWithStatisticFromData() {

		$data = $this->request()::match( '<span class="dark_text">favorites:</span>(.*?)</div>' );

		if ( $data == FALSE ) return FALSE;

		return $this->text()->replace( '[^0-9]+', '', $data );
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
	 * @usage 		broadcast
	 */
	protected function getBroadcastFromData() {

		$data = $this->request()::match( '<span class="dark_text">broadcast:</span>(.*?)</div>' );

		preg_match( '/(\w+) at (\d+):(\d+) \(\w+\)/', $data, $out );

		if ( !isset( $out[ 1 ] ) ) return FALSE;

		$days = [ 'Mondays' => '1', 'Tuesdays' => '2', 'Wednesdays' => '3', 'Thursdays' => '4', 'Fridays' => '5', 'Saturdays' => '6', 'Sundays' => '7' ];

		if ( !isset( $days[ $out[ 1 ] ] ) ) return FALSE;

		return !empty( $out ) ? [ 'dayIndex' => $days[ $out[ 1 ] ], 'dayTitle' => $out[ 1 ], 'hour' => $out[ 2 ], 'minute' => $out[ 3 ] ] : FALSE;
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
	 * @usage 		songOpening
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
	 * @usage 		songEnding
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
	 * @usage 		relatedAdaptation
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
	 * @usage 		relatedSequel
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
	 * @usage 		relatedPrequel
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
	 * @usage 		relatedParentstory
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
	 * @usage 		relatedSidestory
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
	 * @usage 		relatedOther
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
	 * @usage 		relatedSpinoff
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
	 * @usage 		relatedAlternativeversion
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

		preg_match( '@embed/([\w\-\_]+)@', $data, $out );

		return ( isset( $out[ 1 ] ) ) ? $out[ 1 ] : FALSE;
	}

	/**
	 *
	 * @return 		array
	 * @usage 		tabItems
	 */
	protected function getItemsWithTabFromData() {

		return
		$this->request()::matchTable(
		$this->config(), $this->text(),
		'<div id="horiznav_nav"[^>]*>(.*?)</div>', '<li>(.*?)</li>',
		[ '<a\s*href="[^"]+/([^"/]+)">[^<>]+</a>', '<a\s*href="[^"]+/[^"/]+">([^<>]+)</a>' ],
		[ 'href', 'title' ],
		static::$limit
		);
	}

	/**
	 *
	 * @return 		string
	 * @usage 		tabBase
	 */
	protected function getBaseWithTabFromData() {

		$data = $this->request()::match( '<div id="horiznav_nav"[^>]*>(.*?)</div>', '<a>' );

		if ( $data == FALSE ) return FALSE;

		preg_match( '@<a\s*href="[^"]*/(\w+/\d+/[^"/]+/)\w+">@si', $data, $link );

		if ( !isset( $link[ 1 ] ) ) return FALSE;

		return $this->request()::SITE . $link[ 1 ];
	}
}