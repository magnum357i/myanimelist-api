<?php

/**
 * MyAnimeList Character Page API
 *
 * @package	 		MyAnimeList API
 * @author     		Magnum357 [https://github.com/magnum357i/]
 * @copyright  		2018
 * @license    		http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace MyAnimeList\Page;

use MyAnimeList\Builder\AbstractPage;

class Character extends AbstractPage {

	/**
	 * Key list for all purposes
	 */
	public $keyList = [ 'titleSelf', 'titleNickname', 'category', 'poster', 'age', 'height', 'weight', 'statisticFavorite', 'recentAnime', 'recentManga', 'voiceactors', 'tabItems', 'tabBase', 'bloodtype' ];

	/**
	 * @return 		string
	 * @usage 		titleSelf
	 */
	protected function getSelfWithTitleFromData() {

		$data = $this->request()::match( '</div><h1.*?>(.*?)</h1></div><div id="content" ?>' );

		if ( $data == FALSE ) return FALSE;

		if ( $this->config()->reversename ) $data = $this->text()->reverseName( $data, 3 );

		$data = $this->text()->replace( '\s*".+"\s*', ' ', $data );

		return $data;
	}

	/**
	 * @return 		string
	 * @usage 		titleNickname
	 */
	protected function getNicknameWithTitleFromData() {

		return $this->request()::match( '</div><h1.*?>.*?"(.*?)".*?</h1></div><div id="content" ?>' );
	}

	/**
	 * @return 		string
	 * @usage 		category
	 */
	protected function getCategoryFromData() {

		return 'character';
	}

	/**
	 * @return 		string
	 * @usage 		poster
	 */
	protected function getPosterFromData() {

		$data = $this->request()::match( [ '(https://myanimelist.cdn-dena.com/images/characters/[0-9]+/[0-9]+\.jpg)', '(https://cdn.myanimelist.net/images/characters/[0-9]+/[0-9]+\.jpg)' ] );

		if ( $data == FALSE ) return FALSE;

		if ( $this->config()->enablecache ) {

			$newPoster = $this->cache()->savePoster( $this->getImageName(), $data );
			$data      = $newPoster;
		}

		return $data;
	}

	protected $fullDescription = NULL;

	/**
	 * @return 		string
	 */
	protected function fullDescription() {

		if ( $this->fullDescription != NULL ) return $this->fullDescription;

		$this->fullDescription = $this->request()::match( '<div class="breadcrumb ?"[^>]*>.*?</div></div>.*?<div.*?>.*?</div>(.*?)<div[^>]*>voice actors</div>', "<br><span><input>" );

		return $this->fullDescription;
	}

	/**
	 * @return 		string
	 * @usage 		description
	 */
	protected function getDescriptionFromData() {

		$description = $this->fullDescription();

		if ( $description == FALSE ) return FALSE;

		$data = $description;
		$data = $this->text()->replace( '<span class="spoiler_content"[^>]+>\s*<input[^>]+>\s*<br>(.*?)<\/span>',  '[spoiler]$1[/spoiler]', $data, 'si' );
		$data = $this->text()->replace( '\(deployads = window.deployads \|\| \[\]\).push\(\{\}\);',                                     '', $data, 'si' );
		$data = $this->text()->replace( '[^\n]+:[^\n]+',                                                                                '', $data, 'si' );
		$data = $this->text()->descCleaner( $data );
		$data = strip_tags( $data );
		$data = trim( $data );

		return $data;
	}

	/**
	 * @return 		string (current)
	 * @usage 		age
	 */
	protected function getAgeFromData() {

		$description = "\n" . strip_tags( $this->fullDescription() );

		if ( $description == FALSE ) return FALSE;

		preg_match( '@\n[^\n]*age\s*:([^\n]+)\n@i', $description, $out );

		if ( !isset( $out[ 1 ] ) ) return FALSE;

		$data = 'lorem' . $out[ 1 ];
		$data = $this->text()->replace( '\(.*?\)', '', $data, 'si' );

		preg_match( '@.+[^\d](\d+)@', $data, $out );

		return ( !empty( $out[ 1 ] ) ) ? $out[ 1 ] : FALSE;
	}

	/**
	 * @return 		string (current and in cm)
	 * @usage 		height
	 */
	protected function getHeightFromData() {

		$description = "\n" . strip_tags( $this->fullDescription() );

		if ( $description == FALSE ) return FALSE;

		preg_match( '@\n[^\n]*height\s*:([^\n]+)\n@si', $description, $out );

		if ( !isset( $out[ 1 ] ) ) return FALSE;

		$data = 'lorem' . $out[ 1 ];
		$data = $this->text()->replace( '\(.*?\)', '', $data, 'si' );

		preg_match( '@.+[^\d\.,]+([\d\.,]+)\s*cm@', $data, $out );

		if ( !empty( $out[ 1 ] ) ) return $this->text()->roundNumber( $out[ 1 ] );

		preg_match( '@.+[^\d]([\d\.,]+)[\'"]\s*([\d\.,]+)[\'"]@', $data, $out );

		if ( !empty( $out ) ) {

			$feet = $out[ 1 ];
			$inc  = $out[ 2 ];

			return $this->text()->roundNumber( ( $feet * 30.48 ) + ( $inc * 2.54 ) );
		}

		return FALSE;
	}

	/**
	 * @return 		string (current and in kg)
	 * @usage 		weight
	 */
	protected function getWeightFromData() {

		$description = "\n" . strip_tags( $this->fullDescription() );

		if ( $description == FALSE ) return FALSE;

		preg_match( '@\n[^\n]*weight\s*:([^\n]+)\n@si', $description, $out );

		if ( !isset( $out[ 1 ] ) ) return FALSE;

		$data = 'lorem' . $out[ 1 ];
		$data = $this->text()->replace( '\(.*?\)', '', $data, 'si' );

		preg_match( '@.+[^\d\.,]([\d\.,]+)\s*kg@', $data, $out );

		if ( !empty( $out[ 1 ] ) ) return $this->text()->roundNumber( $out[ 1 ] );

		preg_match( '@.+[^\d\.,]([\d\.,]+)\s*lbs?@', $data, $out );

		if ( !empty( $out[ 1 ] ) ) return $this->text()->roundNumber( $out[ 1 ] / 2.2046 );

		return FALSE;
	}

	/**
	 * @return 		string
	 * @usage 		bloodtype
	 */
	protected function getBloodtypeFromData() {

		$description = "\n" . strip_tags( $this->fullDescription() );

		if ( $description == FALSE ) return FALSE;

		preg_match( '@\n[^\n]*blood\s*type\s*:([^\n]+)\n@i', $description, $out );

		return ( !empty( $out[ 1 ] ) ) ? $out[ 1 ] : FALSE;
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

		$data = $this->request()::match( 'member favorites:\s*([\d,]+)' );

		if ( $data == FALSE ) return FALSE;

		return $this->text()->replace( '[^0-9]+', '', $data );
	}

	/**
	 * @return 		array
	 * @usage 		recentAnime
	 */
	protected function getAnimeWithRecentFromData() {

		return
		$this->request()::matchTable(
		$this->config(), $this->text(),
		'<div class="normal_header">animeography</div>.*?<table.*?(.*?)</table>', '<tr>(.*?)</tr>',
		[ '<a href="[^"]+anime/(\d+)[^"]+">[^<>]+</a>', '<a href="[^"]+anime/\d+[^"]+">([^<>]+)</a>', '<small>([^<]+)</small>' ],
		[ 'id', 'title', 'role' ],
		static::$limit, NULL, TRUE
		);
	}

	/**
	 * @return 		array
	 * @usage 		recentManga
	 */
	protected function getMangaWithRecentFromData() {

		return
		$this->request()::matchTable(
		$this->config(), $this->text(),
		'<div class="normal_header">mangaography</div>.*?<table.*?(.*?)</table>', '<tr>(.*?)</tr>',
		[ '<a href="[^"]+manga/(\d+)[^"]+">[^<>]+</a>', '<a href="[^"]+manga/\d+[^"]+">([^<>]+)</a>', '<small>([^<]+)</small>' ],
		[ 'id', 'title', 'role' ],
		static::$limit, NULL, TRUE, 'id'
		);
	}

	/**
	 * @return 		array
	 * @usage 		voiceactors
	 */
	protected function getVoiceactorsFromData() {

		$lang = 'japanese';

		return
		$this->request()::matchTable(
		$this->config(), $this->text(),
		'voice actors</div>(.+</table>.*<br>)', '<tr>(.*?)</tr>',
		[
		'<a href="[^"]+people/(\d+)[^"]+">[^<]+</a>.*?<div[^>]+><small>' . $lang . '</small>',
		'<a href="[^"]+people/\d+[^"]+">([^<]+)</a>.*?<div[^>]+><small>' . $lang . '</small>',
		'src="([^"]+myanimelist.net/images/voiceactors/\d+/[\d\w]+\.jpg)".+<a href="[^"]+people/(\d+)[^"]+">[^<]+</a>.*?<div[^>]+><small>' . $lang . '</small>'
		],
		[ 'id', 'name', 'poster' ],
		static::$limit
		);
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