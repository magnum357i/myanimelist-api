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
use \MyAnimeList\Helper\Text;

class People extends AbstractPage {

	/**
	 * @var 		array 			Key list for all purposes
	 */
	public $keyList = [ 'name', 'poster', 'description', 'socialFacebook', 'socialTwitter', 'socialWebsite', 'height', 'weight', 'birth', 'death', 'category', 'age', 'statisticFavorite', 'recentVoice', 'recentWork', 'tabItems', 'tabBase', 'bloodtype', 'city', 'country' ];

	/**
	 * @return 		string
	 * @usage 		name
	 */
	protected function getNameFromData() {

		$data = $this->request()::match( '<h1 class="title-name[^"]*">(.*?)</h1>' );

		if ( $data == FALSE ) return FALSE;

		if ( $this->config()->reversename ) $data = Text::reverseName( $data );

		return $data;
	}

	/**
	 * @return 		string
	 * @usage 		poster
	 */
	protected function getPosterFromData() {

		$data = $this->request()::match( [ '(https://myanimelist.cdn-dena.com/images/voiceactors/[0-9]+/[0-9]+\.jpg)', '(https://cdn.myanimelist.net/images/voiceactors/[0-9]+/[0-9]+\.jpg)' ] );

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

		$data = $this->request()::match( '<span class="dark_text">more:</span></div><div[^>]+">(.*?)</div>.*?</td>', '<br>' );

		if ( $data == FALSE ) return FALSE;

		$data = Text::replace( '\(deployads = window.deployads \|\| \[\]\).push\(\{\}\);', '', $data, 'si' );
		$data = Text::replace( '[^\n]+:[^\n]+',                                            '', $data, 'si' );

		return Text::descCleaner( $data );
	}

	/**
	 * @return 		string
	 * @usage 		socialFacebook
	 */
	protected function getFacebookWithSocialFromData() {

		return $this->request()::match( [ '<a href="https://w*\.?facebook\.com/([^"]+)"[^>]+>facebook</a>', 'facebook: \@<a[^>]+>(.*?)</a>' ] );
	}

	/**
	 * @return 		string
	 * @usage 		socialTwitter
	 */
	protected function getTwitterWithSocialFromData() {

		return $this->request()::match( [ '<a href="https://w*\.?twitter\.com/([^"]+)"[^>]+>twitter</a>', 'twitter: \@<a[^>]+>(.*?)</a>' ] );
	}

	/**
	 * @return 		string
	 * @usage 		socialWebsite
	 */
	protected function getWebsiteWithSocialFromData() {

		$data = $this->request()::match( 'website:\s*</span>\s*<a href="([^"]+)">' );

		if ( $data == FALSE ) return FALSE;

		return ( $data != 'http://' ) ? $data : FALSE;
	}

	/**
	 * @return 		string
	 * @usage 		height
	 */
	protected function getHeightFromData() {

		$data = $this->request()::match( 'height[^:]*:([^<]+)<br />' );

		if ( $data == FALSE ) return FALSE;

		preg_match( '@([\d\.,]+)\s+cm@', $data, $out );

		if ( !empty( $out[ 1 ] ) ) return Text::roundNumber( $out[ 1 ] );

		preg_match( '@([\d\.,]+)[\'"]\s*([\d\.,]+)[\'"]@', $data, $out );

		if ( !empty( $out ) ) {

			$feet = $out[ 1 ];
			$inc  = $out[ 2 ];

			return Text::roundNumber( ( $feet * 30.48 ) + ( $inc * 2.54 ) );
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

		preg_match( '@([\d\.,]+)\s*kg@', $data, $out );

		if ( !empty( $out[ 1 ] ) ) return Text::roundNumber( $out[ 1 ] );

		preg_match( '@([\d\.,]+)\s*lbs@', $data, $out );

		if ( !empty( $out[ 1 ] ) ) return Text::roundNumber( $out[ 1 ] / 2.2046 );

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

		if ( !empty( $out ) ) return Text::originalDate( $out[ 1 ], $out[ 2 ], $out[ 3 ] );

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

		if ( !empty( $out ) ) return Text::originalDate( $out[ 1 ], $out[ 2 ], $out[ 3 ] );

		preg_match( '/died.*?(\w+)\s+(\d+)[^,]*,\s+(\d+)/si', $data, $out );

		if ( !empty( $out ) ) return Text::originalDate( $out[ 1 ], $out[ 2 ], $out[ 3 ] );

		preg_match( '/death.*?(\w+)\s+(\d+)[^,]*,\s+(\d+)/si', $data, $out );

		if ( !empty( $out ) ) return Text::originalDate( $out[ 1 ], $out[ 2 ], $out[ 3 ] );

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

		$birth = $this->birth;
		$death = $this->death;

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
	 * @usage 		bloodtype
	 */
	protected function getBloodtypeFromData() {

		return $this->request()::match( 'blood\s*type[^:]*:([^<]+)<br />' );
	}

	/**
	 * @var 	string
	 */
	protected $whereFrom = '';

	/**
	 * @return 		string
	 */
	protected function whereFrom() {

		if ( $this->whereFrom != '' ) return $this->whereFrom;

		$this->whereFrom = $this->request()::match( [

			'birth\s*place[^:]*: ([^<]+)<', 'home\s*town[^:]*: ([^<]+)<br />',
			'born in ([^\.]+)\.',           'lives in ([^\.]+)\.'
		] );

		return $this->whereFrom;
	}

	/**
	 * @return 		string
	 * @usage 		city
	 */
	protected function getCityFromData() {

		$whereFrom = $this->whereFrom();

		if ( $whereFrom == FALSE ) return FALSE;

		$whereFrom = Text::listValue( $whereFrom, ',' );

		return ( !empty( $whereFrom ) ) ? $whereFrom[ 0 ] : FALSE;
	}

	/**
	 * @return 		string
	 * @usage 		country
	 */
	protected function getCountryFromData() {

		$whereFrom = $this->whereFrom();

		if ( $whereFrom != FALSE ) {

			$whereFrom = Text::listValue( $whereFrom, ',' );

			$allowedCountries = [ 'Japan', 'USA', 'Italy', 'Germany', 'China', 'Belgium', 'United States', 'U.S.A.' ];
			$country          = end( $whereFrom );

			if ( in_array( $country, $allowedCountries ) ) return str_replace( [ 'U.S.A.', 'United States' ], 'USA', $country );
		}

		$language = $this->request()::match( [ '(\w+) voice', '(\w+) actor', '(\w+) actress' ] );

		if ( $language != FALSE ) {

			$allowedLanguages = [

				'German'  => 'Germany', 'Brazilian' => 'Brazil', 'Spanish'  => 'Spain', 'French' => 'France',
				'Belgian' => 'Belgium', 'Mexican'   => 'USA',    'American' => 'USA'
			];

			if ( isset( $allowedLanguages[ $language ] ) ) return $allowedLanguages[ $language ];
		}

		if ( $this->request()::match( [ '(actor in korea)', '(actress in korea)', '(kbs voice)' ] ) != FALSE ) return 'Korea';

		return FALSE;
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

		$data = $this->request()::match( '<span class="dark_text">member favorites:</span>(.*?)</div>' );

		if ( $data == FALSE ) return FALSE;

		return Text::replace( '[^0-9]+', '', $data );
	}

	/**
	 * @return 		array
	 * @usage 		recentVoice
	 */
	protected function getVoiceWithRecentFromData() {

		return
		$this->request()::matchTable(
		$this->config(),
		'voice acting roles</h2></div><table.*?>(.+?)</table>', '<tr>(.*?)</tr>',
		[
		'<a href="[^"]+anime/(\d+)[^"]+">[^<]+</a>', '<a href="[^"]+anime/\d+[^"]+">([^<]+)</a>', 'data-src="([^"]+myanimelist.net/r/[\dx]+/images/anime/\d+/\d+\.jpg[^"]+)"',
		'<a href="[^"]+character/(\d+)[^"]+">[^<]+</a>', '<a href="[^"]+character/\d+[^"]+">([^<]+)</a>', 'data-src="([^"]+myanimelist.net/r/[\dx]+/images/characters/\d+/\d+\.jpg[^"]+)"'
		],
		[ 'aid', 'atitle', 'aposter', 'cid', 'cname', 'cposter' ],
		static::$limit, NULL, TRUE, 'aid'
		);
	}

	/**
	 * @return 		array
	 * @usage 		recentWork
	 */
	protected function getWorkWithRecentFromData() {

		return
		$this->request()::matchTable(
		$this->config(),
		'anime staff positions</div><table.*?>(.+?)</table>', '<tr>(.*?)</tr>',
		[ '<a href="[^"]+anime/(\d+)[^"]+">[^<]+</a>', '<a href="[^"]+anime/\d+[^"]+">([^<]+)</a>', '<small>([^<]+)</small>', 'data-src="([^"]+myanimelist.net/r/[\dx]+/images/anime/\d+/\d+\.jpg[^"]+)"' ],
		[ 'id', 'title', 'work', 'poster' ],
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