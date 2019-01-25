<?php

/**
 * Simple Cache Class
 *
 * @package	 		MyAnimeList API
 * @author     		Magnum357 [https://github.com/magnum357i/]
 * @copyright  		2018
 * @license    		http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace myanimelist\Helper;

class Cache {

	/**
	 * File Items
	 */
	public $file = [
		'name'    => NULL,
		'ext'     => 'json',
		'content' => NULL
	];

	/**
	 * Image Items
	 */
	public $image = [
		'name' => NULL,
		'ext'  => 'jpg'
	];

	public $folders = [
		'file'  => 'json',
		'image' => 'cover',
		'main'  => 'cache'
	];

	/**
	 * Extra Options
	 */
	public $extra = [];

	/**
	 * Cache Expired Time By Day
	 */
	public $expiredByDay = 2;

	/**
	 * MAL Id
	 */
	public $id;

	/**
	 * MAL Type
	 */
	public $type;

	/**
	 * Root path
	 */
	public $root = '';

	/**
	 * Cache dir
	 */
	public $dir = '';

	/**
	 * Set parameters
	 *
	 * @param 		int 			$id 				MAL id
	 * @param 		string 			$type 				MAL type
	 * @return 		void
	 */
	public function __construct( $id, $type ) {

		$this->id   = $id;
		$this->type = $type;

		if ( $this->file[ 'name' ] == NULL )  $this->file[ 'name' ]  = $id;
		if ( $this->image[ 'name' ] == NULL ) $this->image[ 'name' ] = $id;
		if ( empty( $this->root ) )           $this->root            = __DIR__;
		if ( empty( $this->dir ) )            $this->dir             = '../../..';
	}

	/**
	 * If cache file exists
	 *
	 * @return 		bool
	 */
	public function check() {

		$path = $this->fixSeperator( implode( '/', [ $this->root, $this->dir, $this->folders[ 'main' ], $this->folders[ 'file' ], $this->type ] ) );
		$f    = $this->fixSeperator( $path . '/' . $this->file[ 'name' ] . '.' . $this->file[ 'ext' ] );

		return ( file_exists( $f ) ) ? TRUE : FALSE;
	}

	/**
	 * Get data from cache file
	 *
	 * @return 		array
	 */
	public function get() {

		$path = $this->fixSeperator( implode( '/', [ $this->root, $this->dir, $this->folders[ 'main' ], $this->folders[ 'file' ], $this->type ] ) );
		$f       = $this->fixSeperator( $path . '/' . $this->file[ 'name' ] . '.' . $this->file[ 'ext' ] );
		$exp_day = ( $this->expiredByDay > 1 ) ? $this->expiredByDay : 1;

		try {

			$jsonFile = json_decode( file_get_contents( $f ), TRUE );
		}
		catch ( \Exception $e ) {

			throw new \Exception( "[MyAnimeList Cache Error] Getting Data: {$e}" );
		}

		$timeExpired = strtotime( "-{$exp_day} days" );
		$timeData    = $jsonFile[ 'time' ];

		if ( $timeExpired > $timeData ) {

			return NULL;
		}

		return $jsonFile[ 'data' ];
	}

	/**
	 * Write cache file
	 *
	 * @return 		void
	 */
	public function set() {

		if ( empty( $this->file[ 'content' ] ) ) return NULL;

		$path = $this->fixSeperator( implode( '/', [ $this->root, $this->dir, $this->folders[ 'main' ], $this->folders[ 'file' ], $this->type ] ) );

		if ( !file_exists( $path ) ) {

			mkdir( $path, 0777, TRUE );
		}

		$f = $this->fixSeperator( $path . '/' . $this->file[ 'name' ] . '.' . $this->file[ 'ext' ] );

		try {

			file_put_contents( $f, json_encode( [ 'time' => time(), 'data' => $this->file[ 'content' ] ] ) );
		}
		catch ( \Exception $e ) {

			throw new \Exception( "[MyAnimeList Cache Error] Writing Data: {$e}" );
		}
	}

	/**
	 * Converts slashes to a different direction
	 *
	 * @param 		$path 				Path
	 * @param 		$direction 			Bending direction
	 * @return 		string
	 */
	protected function fixSeperator( $path, $direction='left' ) {

		if ( $direction == 'left' )  $path = str_replace( '/', '\\', $path );
		if ( $direction == 'right' ) $path = str_replace( '\\', '/', $path );

		return $path;
	}

	/**
	 * Save poster
	 *
	 * @param 		$url 			External url of a image
	 * @return 		string
	 */
	public function savePoster( $url ) {

		$path = $this->fixSeperator( implode( '/', [ $this->root, $this->dir, $this->folders[ 'main' ], $this->folders[ 'image' ], $this->type ] ) );
		$f    = $this->fixSeperator( $path . '/' . $this->image[ 'name' ] . '.' . $this->image[ 'ext' ] );

		if ( !file_exists( $path ) ) {

			mkdir( $path, 0777, TRUE );
		}

		try {

			copy( $url, $f );
		}
		catch ( \Exception $e ) {

			throw new \Exception( "[MyAnimeList Cache Error] Writing Data: {$e}" );
		}

		return $this->fixSeperator( str_replace( $this->fixSeperator( $_SERVER['DOCUMENT_ROOT'] ), '', $path ) . '/' . $this->image[ 'name' ] . '.' . $this->image[ 'ext' ], 'right' );
	}
}