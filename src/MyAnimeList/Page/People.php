<?php

/**
 * MyAnimeList People Page API
 *
 * @package	 		MyAnimeList API
 * @author     		Magnum357 [https://github.com/magnum357i/]
 * @copyright  		2018
 * @license    		http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace MyAnimeList\Page;

use MyAnimeList\Builder\AbstractPage;

class People extends AbstractPage {

	/**
	 * Set type
	 */
	public static $type = 'people';

	/**
	 * Patterns for externalLink
	 */
	protected static $externalLinks = [ 'character' => 'character/{s}', 'people' => 'people/{s}', 'anime' => 'anime/{s}', 'manga' => 'manga/{s}' ];

	/**
	 * @return 		string
	 * @usage 		name
	 */
	protected function getNameFromData() {

		$data = $this->request()::match( '</div><h1.*?>(.*?)</h1></div><div id="content" ?>' );

		if ( $data == FALSE ) return FALSE;

		if ( $this->config()::isOnNameConverting() ) $data = $this->text()->reverseName( $data );

		return $data;
	}

	/**
	 * @return 		string
	 * @usage 		poster
	 */
	protected function getPosterFromData() {

		$data = $this->request()::match( [ '(https://myanimelist.cdn-dena.com/images/voiceactors/[0-9]+/[0-9]+\.jpg)', '(https://cdn.myanimelist.net/images/voiceactors/[0-9]+/[0-9]+\.jpg)' ] );

		if ( $data == FALSE ) return FALSE;

		if ( $this->config()::isOnCache() ) {

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

		$data = $this->request()::match( '<span class="dark_text">more:</span></div><div[^>]+">(.*?)</div>.*?</td>', '<br>' );

		if ( $data == FALSE ) return FALSE;

		$data = $this->text()->replace( '\(deployads = window.deployads \|\| \[\]\).push\(\{\}\);', '', $data, 'si' );
		$data = $this->text()->replace( '[^\n]+:[^\n]+',                                            '', $data, 'si' );

		return $this->text()->descCleaner( $data );
	}

	/**
	 * @return 		string
	 * @usage 		socialFacebook
	 */
	protected function getFacebookWithSocialFromData() {

		return $this->request()::match( '<a href="https://www.facebook.com/([^"]+)"[^>]+>facebook</a>' );
	}

	/**
	 * @return 		string
	 * @usage 		socialTwitter
	 */
	protected function getTwitterWithSocialFromData() {

		return $this->request()::match( 'twitter: \@<a[^>]+>(.*?)</a>' );
	}

	/**
	 * @return 		string
	 * @usage 		socialWebsite
	 */
	protected function getWebsiteWithSocialFromData() {

		$data = $this->request()::match( 'website:\s*</span>\s*<a href="([^"]+)">' );

		return ( $data != 'http://' ) ? $data : FALSE;
	}

	/**
	 * @return 		string
	 * @usage 		height
	 */
	protected function getHeightFromData() {

		$data = $this->request()::match( 'height[^:]*:([^<]+)<br />' );

		if ( $data == FALSE ) return FALSE;

		$data = 'lorem' . $data;

		preg_match( '@.+[^\d,\.]([\d,\.]+)\s+cm@', $data, $out );

		if ( !empty( $out[ 1 ] ) ) return $this->text()->replace( '[\.,].+', '', $out[ 1 ] );

		$data = str_replace( '&#039;', "'", $data );

		preg_match( '@.+[^\d](\d+)[\'"]\s*(\d+)[\'"]@', $data, $out );

		if ( !empty( $out ) ) {

			$feet = $out[ 1 ];
			$inc  = $out[ 2 ];

			return $this->text()->replace( '[\.,].+', '', ( $feet * 30.48 ) + ( $inc * 2.54 ) );
		}

		return FALSE;
	}

	/**
	 * @return 		string
	 * @usage 		weight
	 */
	protected function getWeightFromData() {

		$data = $this->request()::match( 'weight[^:]*: ([^<]+)<br />' );

		if ( $data == FALSE ) return FALSE;

		$data = 'lorem' . $data;

		preg_match( '@.+[^\d,\.]([\d,\.]+)\s+kg@', $data, $out );

		if ( !empty( $out[ 1 ] ) ) return $this->text()->replace( '[\.,].+', '', $out[ 1 ] );

		preg_match( '@.+[^\d,\.]([\d]+)\s+lbs@', $data, $out );

		if ( !empty( $out[ 1 ] ) ) return $this->text()->replace( '[\.,].+', '', $out[ 1 ] / 2.2046 );

		return FALSE;
	}

	/**
	 * @return 		array
	 * @usage 		birth
	 */
	protected function getBirthFromData() {

		$data = $this->request()::match( '<span class="dark_text">birthday:</span>(.*?)</div>' );

		if ( $data == FALSE ) return FALSE;

		preg_match( '@(\w+)\s+(\d+),\s+(\d{4})@si', $data, $out );

		if ( !empty( $out ) ) return $this->text()->originalDate( $out[ 1 ], $out[ 2 ], $out[ 3 ] );

		return FALSE;
	}

	/**
	 * @return 		array
	 * @usage 		death
	 */
	protected function getDeathFromData() {

		$data = $this->request()->match( '<div class="people-informantion-more[^"]*">(.*?)</div>' );

		if ( $data == FALSE ) return FALSE;

		preg_match( '/passed\s+away\s+on\s+(\w+)\s+(\d+)[^,]*,\s+(\d+)/si', $data, $out );

		if ( !empty( $out ) ) return $this->text()->originalDate( $out[ 1 ], $out[ 2 ], $out[ 3 ] );

		preg_match( '/died.*?(\w+)\s+(\d+)[^,]*,\s+(\d+)/si', $data, $out );

		if ( !empty( $out ) ) return $this->text()->originalDate( $out[ 1 ], $out[ 2 ], $out[ 3 ] );

		preg_match( '/death.*?(\w+)\s+(\d+)[^,]*,\s+(\d+)/si', $data, $out );

		if ( !empty( $out ) ) return $this->text()->originalDate( $out[ 1 ], $out[ 2 ], $out[ 3 ] );

		return FALSE;
	}

	/**
	 * @return 		string
	 * @usage 		category
	 */
	protected function getCategoryFromData() {

		return 'people';
	}

	/**
	 * @return 		string
	 * @usage 		age
	 */
	protected function getAgeFromData() {

		$birth = ( isset( static::$data[ 'birth' ] ) ) ? static::$data[ 'birth' ] : $this->getBirthFromData();
		$death = ( isset( static::$data[ 'death' ] ) ) ? static::$data[ 'death' ] : $this->getDeathFromData();

		if ( $birth == FALSE ) return FALSE;

		$date1 = new \DateTime( sprintf( '%s-%s-%s', $birth[ 'year' ], $birth[ 'month' ], $birth[ 'day' ] ) );

		if ( $death != FALSE ) {

			$date2 = new \DateTime( sprintf( '%s-%s-%s', $death[ 'year' ], $death[ 'month' ], $death[ 'day' ] ) );
		}
		else {

			$date2 = new \DateTime( 'now' );
		}

		$diff = $date1->diff( $date2 );

		return $diff->format( '%y' );
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

		$data = $this->request()::match( '<span class="dark_text">member favorites:</span>(.*?)</div>' );

		if ( $data == FALSE ) return FALSE;

		return $this->text()->replace( '[^0-9]+', '', $data );
	}

	/**
	 * @return 		array
	 * @usage 		recentVoice
	 */
	protected function getVoiceWithRecentFromData() {

		return
		$this->request()::matchTable(
		$this->config(), $this->text(),
		'voice acting roles</div><table.*?>(.+?)</table>', '<tr>(.*?)</tr>',
		[
		'<a href="[^"]+anime/(\d+)[^"]+">[^<]+</a>', '<a href="[^"]+anime/\d+[^"]+">([^<]+)</a>',
		'<a href="[^"]+character/(\d+)[^"]+">[^<]+</a>', '<a href="[^"]+character/\d+[^"]+">([^<]+)</a>'
		],
		[
		'anime_id', 'anime_title',
		'character_id', 'character_name'
		],
		static::$limit, NULL, TRUE, 'anime_id'
		);
	}

	/**
	 * @return 		array
	 * @usage 		recentWork
	 */
	protected function getWorkWithRecentFromData() {

		return
		$this->request()::matchTable(
		$this->config(), $this->text(),
		'anime staff positions</div><table.*?>(.+?)</table>', '<tr>(.*?)</tr>',
		[ '<a href="[^"]+anime/(\d+)[^"]+">[^<]+</a>', '<a href="[^"]+anime/\d+[^"]+">([^<]+)</a>', '<small>([^<]+)</small>' ],
		[ 'id', 'title', 'work' ],
		static::$limit, NULL, TRUE, 'id'
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