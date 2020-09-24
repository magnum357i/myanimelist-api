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
use \MyAnimeList\Helper\Text;

class Anime extends AbstractPage {

	/**
	 * @var 		array 			Key list for all purposes
	 */
	public $keyList = [

		'titleOriginal', 'titleEnglish', 'titleJapanese', 'titleOthers', 'poster', 'description', 'background', 'category', 'rating', 'licensors', 'producers',
		'scoreVote', 'scorePoint', 'genres', 'source', 'airedFirst', 'airedLast', 'episode', 'studios', 'duration', 'premiered',
		'statisticRank', 'statisticPopularity', 'statisticMember', 'statisticFavorite', 'status', 'broadcast', 'year', 'voice', 'staff',
		'songOpening', 'songEnding', 'relatedAdaptation', 'relatedSequel', 'relatedPrequel', 'relatedParentstory', 'relatedSidestory',
		'relatedOther', 'relatedSpinoff', 'relatedAlternativeversion', 'relatedSummary', 'relatedAlternativesetting', 'trailer', 'tabItems', 'tabBase'
	];

	/**
	 * Enable timezone conversion
	 *
	 * @param 		string 				$timezone 				'default' or Timezone
	 * @return 		bool
	 */
	public function timezone( $timezone='default' ) {

		if ( $this->broadcast[ 'timezone' ] != $timezone ) $this->broadcast = Text::convertTimezone( $timezone, $this->airedFirst, $this->broadcast );

		return $this;
	}

	/**
	 * @return 		string
	 * @usage 		titleOriginal
	 */
	protected function getOriginalWithTitleFromData() {

		$data = $this->request()::match( '<h1 class="title-name[^"]*">(.*?)</h1>' );

		if ( $data == FALSE ) return FALSE;

		$data = Text::replace( '\s*\(.+\)', '', $data, 'si' );

		return $data;
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

		if ( $this->config()->enablecache ) {

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

		$data = $this->request()::match( '<p itemprop="description">(.*?)</p>', '<br>' );

		if ( $data == FALSE ) return FALSE;

		return Text::descCleaner( $data );
	}

	/**
	 * @return 		string
	 * @usage 		backgruond
	 */
	protected function getBackgroundFromData() {

		$data = $this->request()::match( '<h2>background</h2></div>(.*?)<div[^>]*>' );

		if ( $data === FALSE OR Text::validate( $data, 'string', [ 'regex' => 'no background information', 'flags' => 'si' ] ) ) return FALSE;

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

		if ( Text::validate( $data, 'string', [ 'regex' => 'none', 'flags' => 'si' ], $data ) ) return FALSE;

		$data = str_replace( [ '- Teens 13 or older', '(violence & profanity)', '- Mild Nudity', ' - All Ages', ' ' ], '', $data );
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
		$this->config(),
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
		$this->config(),
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

		return Text::formatK( $data );
	}

	/**
	 * @return 		string
	 * @usage 		scoreVoteraw
	 */
	protected function getVoterawWithScoreFromData() {

		return $this->request()::match( 'scored by <span itemprop="ratingCount"[^>]*>(\d+)</span>' );
	}

	/**
	 * @return 		string
	 * @usage 		scorePoint
	 */
	protected function getPointWithScoreFromData() {

		$data = $this->request()::match( [ '<span class="dark_text">score:</span>(.*?)<sup>', '<span itemprop="ratingValue">(.*?)</span>' ] );

		if ( $data == FALSE ) return FALSE;

		$data = Text::replace( '[^0-9]+', '', $data );

		if ( !Text::validate( $data, 'number' ) ) return FALSE;

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
		$this->config(),
		'<span class="dark_text">genres:</span>(.*?)</div>', 	'(<a href=[^>]+>.*?</a>)',
		[ '<a href="[^"]+genre/(\d+)[^"]+"[^>]+>.*?</a>', '<a href="[^"]+"[^>]+>(.*?)</a>' ],
		[ 'id', 'title' ],
		static::$limit );
	}

	/**
	 * @return 		string
	 * @usage 		source
	 */
	protected function getSourceFromData() {

		$data = $this->request()::match( '<span class="dark_text">source:</span>(.*?)</div>' );

		if ( $data == FALSE OR Text::validate( $data, 'string', [ 'regex' => 'unknown', 'flags' => 'si' ] ) ) return FALSE;

		return $data;
	}

	/**
	 * @return 		array
	 * @usage 		airedFirst
	 */
	protected function getFirstWithAiredFromData() {

		$data = $this->request()::match( '<span class="dark_text">aired:</span>(.*?)</div>' );

		if ( $data == FALSE ) return FALSE;

		preg_match( '/(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)\s*(\d{1,2}),\s*(\d{4})/', $data, $out );

		if ( !empty( $out ) ) return Text::originalDate( $out[ 1 ], $out[ 2 ], $out[ 3 ] );

		preg_match( '/(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec),?\s*(\d{4})/', $data, $out );

		if ( !empty( $out ) ) return Text::originalDate( $out[ 1 ], '01', $out[ 2 ] );

		return FALSE;
	}

	/**
	 * @return 		array|no
	 * @usage 		airedLast
	 */
	protected function getLastWithAiredFromData() {

		$data = $this->request()::match( '<span class="dark_text">aired:</span>(.*?)</div>' );

		if ( $data == FALSE ) return FALSE;

		preg_match( '/to\s*(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)\s*(\d{1,2}),\s*(\d{4})/', $data, $out );

		if ( !empty( $out ) ) return Text::originalDate( $out[ 1 ], $out[ 2 ], $out[ 3 ] );

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

		return Text::validate( $data, 'number' ) ? $data : FALSE;
	}

	/**
	 * @return 		array
	 * @usage 		studios
	 */
	protected function getStudiosFromData() {

		return
		$this->request()::matchTable(
		$this->config(),
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

		if ( $data == FALSE ) return FALSE;

		preg_match( '/(\d+) hr\. (\d+) min\./', $data, $out );

		if ( !empty( $out ) ) return [ 'hour' => $out[ 1 ], 'minute' => $out[ 2 ], 'total' => (string) ( $out[ 1 ] * 60 + $out[ 2 ] ) ];

		preg_match( '/(\d+) min\./', $data, $out );

		if ( !empty( $out ) ) return [ 'hour' => '0', 'minute' => $out[ 1 ], 'total' => $out[ 1 ] ];

		return FALSE;
	}

	/**
	 * @return 		array
	 * @usage 		premiered
	 */
	protected function getPremieredFromData() {

		$data = $this->request()::match( '<span class="dark_text">premiered:</span>(.*?)</div>' );

		if ( $data == FALSE ) return FALSE;

		preg_match( '/^(Spring|Summer|Fall|Winter) (\d+)$/', $data, $out );

		if ( empty( $out ) ) return FALSE;

		$seasons = [ 'Spring' => '1', 'Summer' => '2', 'Fall' => '3', 'Winter' => '4' ];

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

		return Text::validate( $data, 'number' ) ? $data : FALSE;
	}

	/**
	 * @return 		string
	 * @usage 		statisticPopularity
	 */
	protected function getPopularityWithStatisticFromData() {

		$data = $this->request()::match( '<span class="dark_text">popularity:</span>(.*?)</div>' );

		if ( $data == FALSE ) return FALSE;

		$data = str_replace( '#', '', $data );

		return Text::validate( $data, 'number' ) ? $data : FALSE;
	}

	/**
	 * @return 		string
	 * @usage 		statisticMember
	 */
	protected function getMemberWithStatisticFromData() {

		$data = $this->getMemberrawWithStatisticFromData();

		if ( $data == FALSE ) return FALSE;

		return Text::formatK( $data );
	}

	/**
	 * @return 		string
	 * @usage 		statisticMemberraw
	 */
	protected function getMemberrawWithStatisticFromData() {

		$data = $this->request()::match( '<span class="dark_text">members:</span>(.*?)</div>' );

		if ( $data == FALSE ) return FALSE;

		return Text::replace( '[^0-9]+', '', $data );
	}

	/**
	 * @return 		string
	 * @usage 		statisticFavorite
	 */
	protected function getFavoriteWithStatisticFromData() {

		$data = $this->getFavoriterawWithStatisticFromData();

		if ( $data == FALSE ) return FALSE;

		return Text::formatK( $data );
	}

	/**
	 * @return 		string
	 * @usage 		statisticFavoriteraw
	 */
	protected function getFavoriterawWithStatisticFromData() {

		$data = $this->request()::match( '<span class="dark_text">favorites:</span>(.*?)</div>' );

		if ( $data == FALSE ) return FALSE;

		return Text::replace( '[^0-9]+', '', $data );
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

		if ( $data == FALSE ) return FALSE;

		preg_match( '/(\w+) at (\d+):(\d+) \(\w+\)/', $data, $out );

		if ( !isset( $out[ 1 ] ) ) return FALSE;

		$days = [ 'Mondays' => 1, 'Tuesdays' => 2, 'Wednesdays' => 3, 'Thursdays' => 4, 'Fridays' => 5, 'Saturdays' => 6, 'Sundays' => 7 ];

		if ( !isset( $days[ $out[ 1 ] ] ) ) return FALSE;

		return [ 'timezone' => 'Asia/Tokyo', 'dayIndex' => (string) $days[ $out[ 1 ] ], 'dayTitle' => $out[ 1 ], 'hour' => $out[ 2 ], 'minute' => $out[ 3 ] ];
	}

	/**
	 * @return 		string
	 * @usage 		year
	 */
	protected function getYearFromData() {

		$data = $this->request()::match( '<span class="dark_text">aired:</span>(.*?)</div>' );

		if ( $data == FALSE ) return FALSE;

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
		$this->config(),
		'</div><h2>characters & voice actors</h2>(.*?)<a name="staff">', '<tr>(.*?)</tr>',
		[
		'<a href="[^"]+/character/(\d+)/[^"]+">[^<]+</a>', '<a href="[^"]+/character/.*?/.*?">([^<]+)</a>', 'data-src="([^"]+myanimelist.net/r/[\dx]+/images/characters/\d+/\d+\.jpg[^"]+)"',
		'<a href="[^"]+/people/(\d+)/[^"]+">[^<]+</a>', '<a href="[^"]+/people/.*?/.*?">([^<]+)</a>', '<a href="[^"]+/people/.*?/.*?">[^<]+</a>.*?<small>(.*?)</small>', 'data-src="([^"]+myanimelist.net/r/[\dx]+/images/voiceactors/\d+/\d+\.jpg[^"]+)"'
		],
		[
		'cid', 'cname', 'cposter',
		'pid', 'pname', 'plang', 'pposter'
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
		$this->config(),
		'</div>.*?staff[^<]*?</h2>(.*?)<h2>', '<table.*?>(.*?)</table>',
		[
		'<a href="[^"]+/people/(\d+)/[^"]+">[^<]+</a>', '<a href="[^"]+/people/.*?/.*?">([^<]+)</a>',
		'data-src="([^"]+myanimelist.net/r/[\dx]+/images/voiceactors/\d+/\d+\.jpg[^"]+)"', '<a href="[^"]+/people/.*?/.*?">[^<]+</a>.*?<small>(.*?)</small>'
		],
		[ 'id', 'name', 'poster', 'positionlist' ],
		static::$limit
		);
	}

	/**
	 * Get song data
	 *
	 * @param 		string 			Container div
	 * @return 		array
	 */
	protected function songTable( $container ) {

		$custom_removeJapanese = function( $value ) {

			return $this->request()->reflection( $this->config(), Text::removeJapChars( $value ), 'japanese' );
		};

		return
		$this->request()::matchTable(
		$this->config(),
		$container, '<span class="theme-song">(.*?)</span>',
		[ '[\'"]([^\'"]+)[\'"],? by', [ '[\'"\)],? by (.+) \(eps?', '[\'"\)],? by (.+)' ], 'eps? (\d+)', 'eps [\d\s\-,]+[\s\-,](\d+)' ],
		[ 'title', 'singer', 'start', 'end' ],
		static::$limit,
		[ 'title' => $custom_removeJapanese, 'singer' => $custom_removeJapanese ]
		);
	}

	/**
	 * @return 		array
	 * @usage 		songOpening
	 */
	protected function getOpeningWithSongFromData() {

		return $this->songTable( '<div class="theme-songs js-theme-songs opnening">(.*?)</div>' );
	}

	/**
	 * @return 		array
	 * @usage 		songEnding
	 */
	protected function getEndingWithSongFromData() {

		return $this->songTable( '<div class="theme-songs js-theme-songs ending">(.*?)</div>' );
	}

	/**
	 * Get related table
	 *
	 * @param 		$title 			Row title
	 * @return 		array
	 */
	protected function relatedTable( $title ) {

		return
		$this->request()::matchTable(
		$this->config(),
		'<td.*?>' . $title . ':</td>.*?(<td.*?>.*?</td>)', '(<a href=[^>]+>.*?</a>)',
		[ '<a href="[^"]*/(?:anime|manga)/(\d+)[^"]*">.*?</a>', '<a href="[^"]+">(.*?)</a>' ],
		[ 'id', 'title' ],
		static::$limit
		);
	}

	/**
	 * @return 		array
	 * @usage 		relatedAdaptation
	 */
	protected function getAdaptationWithRelatedFromData() {

		return $this->relatedTable( 'adaptation' );
	}

	/**
	 * @return 		array
	 * @usage 		relatedSequel
	 */
	protected function getSequelWithRelatedFromData() {

		return $this->relatedTable( 'sequel' );
	}

	/**
	 * @return 		array
	 * @usage 		relatedPrequel
	 */
	protected function getPrequelWithRelatedFromData() {

		return $this->relatedTable( 'prequel' );
	}

	/**
	 * @return 		array
	 * @usage 		relatedParentstory
	 */
	protected function getParentstoryWithRelatedFromData() {

		return $this->relatedTable( 'parent story' );
	}

	/**
	 * @return 		array
	 * @usage 		relatedSidestory
	 */
	protected function getSidestoryWithRelatedFromData() {

		return $this->relatedTable( 'side story' );
	}

	/**
	 * @return 		array
	 * @usage 		relatedOther
	 */
	protected function getOtherWithRelatedFromData() {

		return $this->relatedTable( 'other' );
	}

	/**
	 * @return 		array
	 * @usage 		relatedSpinoff
	 */
	protected function getSpinoffWithRelatedFromData() {

		return $this->relatedTable( 'spin\-off' );
	}

	/**
	 * @return 		array
	 * @usage 		relatedSummary
	 */
	protected function getSummaryWithRelatedFromData() {

		return $this->relatedTable( 'summary' );
	}

	/**
	 * @return 		array
	 * @usage 		relatedAlternativeversion
	 */
	protected function getAlternativeversionWithRelatedFromData() {

		return $this->relatedTable( 'alternative version' );
	}

	/**
	 * @return 		array
	 * @usage 		relatedAlternativesetting
	 */
	protected function getAlternativesettingWithRelatedFromData() {

		return $this->relatedTable( 'alternative setting' );
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
		$this->config(),
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