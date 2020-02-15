<?php

/**
 * MyAnimeList Manga Page API
 *
 * @package	 		MyAnimeList API
 * @author     		Magnum357 [https://github.com/magnum357i/]
 * @copyright  		2018
 * @license    		http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace MyAnimeList\Page;

use MyAnimeList\Builder\AbstractPage;
use \MyAnimeList\Helper\Text;

class Manga extends AbstractPage {

	/**
	 * @var 		array 			Key list for all purposes
	 */
	public $keyList = [

		'titleOriginal', 'titleEnglish', 'titleJapanese', 'titleOthers', 'poster', 'description', 'background', 'category', 'authors',
		'scoreVote', 'scorePoint', 'genres', 'publishedFirst', 'publishedLast', 'volume', 'chapter', 'serialization',
		'statisticRank', 'statisticPopularity', 'statisticMember', 'statisticFavorite', 'status', 'year', 'characters',
		'relatedAdaptation', 'relatedSequel', 'relatedPrequel', 'relatedParentstory', 'relatedSidestory',
		'relatedOther', 'relatedSpinoff', 'relatedAlternativeversion', 'relatedAlternativesetting', 'tabItems', 'tabBase'
	];

	/**
	 * @return 		string
	 * @usage 		titleOriginal
	 */
	protected function getOriginalWithTitleFromData() {

		$data = $this->request()::match( '<span itemprop="name">(.*?)</span>', '<span>' );

		if ( $data == FALSE ) return FALSE;

		$data = Text::replace( '<span[^>]+>.+', '', $data, 'si' );
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

		$data = $this->request()::match( [ '(https://myanimelist.cdn-dena.com/images/manga/[0-9]+/[0-9]+\.jpg)', '(https://cdn.myanimelist.net/images/manga/[0-9]+/[0-9]+\.jpg)' ] );

		if ( $data == FALSE ) return FALSE;

		if ( $this->config()->enablecache ) {

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

		$data = Text::replace( '\s*Included one\-shot.+',    '', $data, 'si' );
		$data = Text::replace( 'this series is on hiatus.+', '', $data, 'si' );

		return Text::descCleaner( $data );
	}

	/**
	 * @return 		string
	 * @usage 		backgruond
	 */
	protected function getBackgroundFromData() {

		return $this->request()::match( '<span itemprop="description">.*?</span><h2[^>]*>.*?</h2>(.*?)<div[^>]*>', '<br>' );
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
	 * @return 		array
	 * @usage 		genres
	 */
	protected function getGenresFromData() {

		return
		$this->request()::matchTable(
		$this->config(),
		'<span class="dark_text">genres:</span>(.*?)</div>', '(<a href=[^>]+>.*?</a>)',
		[ '<a href="[^"]+genre/(\d+)[^"]+"[^>]+>.*?</a>', '<a href="[^"]+"[^>]+>(.*?)</a>' ],
		[ 'id', 'title' ],
		static::$limit
		);
	}

	/**
	 * @return 		string
	 * @usage 		statisticPopularity
	 */
	protected function getpopularityWithStatisticFromData() {

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
	 * @usage 		authors
	 */
	protected function getAuthorsFromData() {

		return
		$this->request()::matchTable(
		$this->config(),
		'<span class="dark_text">authors:</span>(.*?)</div>', '(<a[^>]+>.*?</a>[^<,]+)',
		[ '<a href="[^"]+people/(\d+)[^"]+">.*?</a>', '<a href="[^"]+people/\d+[^"]+">(.*?)</a>', '<a href="[^"]+people/\d+[^"]+">.*?</a>\s+\(([^)]+)\)' ],
		[ 'id', 'name', 'job' ],
		static::$limit
		);
	}

	/**
	 * @return 		string
	 * @usage 		volume
	 */
	protected function getVolumeFromData() {

		$data = $this->request()::match('<span class="dark_text">volumes:</span>(.*?)</div>');

		if ( $data == FALSE OR !Text::validate( $data, 'number' ) ) return FALSE;

		return $data;
	}

	/**
	 * @return 		string
	 * @usage 		chapter
	 */
	protected function getChapterFromData() {

		$data = $this->request()::match( '<span class="dark_text">chapters:</span>(.*?)</div>' );

		if ( $data == FALSE OR !Text::validate( $data, 'number' ) ) return FALSE;

		return $data;
	}

	/**
	 * @return 		array
	 * @usage 		serialization
	 */
	protected function getSerializationFromData() {

		return
		$this->request()::matchTable(
		$this->config(),
		'<span class="dark_text">serialization:</span>(.*?)</div>', '(<a href=[^>]+>.*?</a>)',
		[ '<a href="[^"]+manga/magazine/(\d+)[^"]+"[^>]+>.*?</a>', '<a href="[^"]+"[^>]+>(.*?)</a>' ],
		[ 'id', 'title' ],
		static::$limit
		);
	}

	/**
	 * @return 		array
	 * @usage 		publishedFirst
	 */
	protected function getFirstWithPublishedFromData() {

		$data = $this->request()::match( '<span class="dark_text">published:</span>(.*?)</div>' );

		if ( $data == FALSE ) return FALSE;

		preg_match( '/(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)\s*(\d{1,2}),\s*(\d{4})/', $data, $out );

		if ( !empty( $out ) ) return Text::originalDate( $out[ 1 ], $out[ 2 ], $out[ 3 ] );

		preg_match( '/(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec),?\s*(\d{4})/', $data, $out );

		if ( !empty( $out ) ) return Text::originalDate( $out[ 1 ], '01', $out[ 2 ] );

		return FALSE;
	}

	/**
	 * @return 		array|no
	 * @usage 		publishedLast
	 */
	protected function getLastWithPublishedFromData() {

		$data = $this->request()::match( '<span class="dark_text">published:</span>(.*?)</div>' );

		if ( $data == FALSE ) return FALSE;

		preg_match( '/to\s*(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)\s*(\d{1,2}),\s*(\d{4})/', $data, $out );

		if ( !empty( $out ) ) return Text::originalDate( $out[ 1 ], $out[ 2 ], $out[ 3 ] );

		preg_match( '/to\s*\?/', $data, $out );

		if ( !empty( $out ) ) return 'no';

		return FALSE;
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
		$this->config(),
		'</div>characters</h2><div.*?">(.+?</table>)</div></div>', '<table[^>]*>(.*?)</table>',
		[ '<a href="[^"]+character/(\d+)[^"]+">[^<]+</a>', '<a href="[^"]+character/\d+[^"]+">([^<]+)</a>', '<small>([^<]+)</small>', 'data-src="([^"]+myanimelist.net/r/[\dx]+/images/characters/\d+/\d+\.jpg[^"]+)"' ],
		[ 'id', 'name', 'role', 'poster' ],
		static::$limit
		);
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

		return $this->relatedTable( 'parentstory' );
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