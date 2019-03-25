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
	 * Saving directories
	 */
	protected $folders = [

		'file'  => NULL,
		'image' => NULL,
		'main'  => NULL
	];

	/**
	 * Cache folder
	 */
	protected $cacheFolder = 'myanimelist';

	/**
	 * Type
	 */
	protected $type = NULL;

	/**
	 * Cache expired time by Day
	 */
	protected $expiredByDay = 2;

	/**
	 * Cache path
	 */
	protected $path = NULL;

	/**
	 * Set parameters
	 *
	 * @param 		$type 			MAL Type
	 * @param 		$folders 			Saving directories
	 * @return 		void
	 */
	public function __construct( $type, $folders=[] ) {

		foreach( $this->folders as $k => $v ) $this->folders[ $k ] = $folders[ $k ];

		$this->type = $type;

		$this->setPath( __DIR__ . '../../../../cache' );
	}

	/**
	 * Set path to save
	 *
	 * @param 		$root 			Root path
	 * @param 		$path 			Path to save
	 * @return 		bool
	 */
	public function setPath( $path ) {

		$this->path = $path;
	}

	/**
	 * Set expired time
	 *
	 * @param 		$days 			Number in days
	 * @return 		bool
	 */
	public function setExpiredTime( $days ) {

		$this->expiredByDay = $days;
	}

	/**
	 * If file exists
	 *
	 * @param 		$fileName 				File name
	 * @return 		bool
	 */
	public function checkFile( $fileName ) {

		$path = $this->fixSeparator( implode( '/', [ $this->path, $this->cacheFolder, $this->folders[ 'main' ], $this->folders[ 'file' ], $this->type ] ) );
		$f    = $this->fixSeparator( $path . '/' . $fileName . '.json' );

		return ( file_exists( $f ) ) ? TRUE : FALSE;
	}

	/**
	 * Is cache expired?
	 *
	 * @param 		$time 				Timestamp
	 * @return 		array
	 */
	public function expired( $time ) {

		$expDay  = ( $this->expiredByDay > 1 ) ? $this->expiredByDay : 1;
		$expTime = strtotime( "-{$expDay} days" );

		if ( $expTime < $time ) {

			return TRUE;
		}
		else {

			return FALSE;
		}
	}

	/**
	 * Read data from cache file
	 *
	 * @param 		$fileName 				File name
	 * @return 		array
	 */
	public function readFile( $fileName ) {

		$path    = $this->fixSeparator( implode( '/', [ $this->path, $this->cacheFolder, $this->folders[ 'main' ], $this->folders[ 'file' ], $this->type ] ) );
		$f       = $this->fixSeparator( $path . '/' . $fileName . '.json' );

		try {

			$content = json_decode( file_get_contents( $f ), TRUE );
		}
		catch ( \Exception $e ) {

			throw new \Exception( "[MyAnimeList Cache Error] {$e}" );
		}

		return $content;
	}

	/**
	 * Write cache file
	 *
	 * @param 		$fileName 				File name with extension
	 * @return 		void
	 */
	public function writeFile( $fileName, $content ) {

		if ( empty( $content ) ) return NULL;

		$path = $this->fixSeparator( implode( '/', [ $this->path, $this->cacheFolder, $this->folders[ 'main' ], $this->folders[ 'file' ], $this->type ] ) );

		if ( !file_exists( $path ) ) mkdir( $path, 0777, TRUE );

		$f = $this->fixSeparator( $path . '/' . $fileName . '.json' );

		try {

			file_put_contents( $f, json_encode( $content ) );
		}
		catch ( \Exception $e ) {

			throw new \Exception( "[MyAnimeList Cache Error] {$e}" );
		}
	}

	/**
	 * Converts slashes by OS
	 *
	 * @param 		$path 				Path
	 * @return 		string
	 */
	protected function fixSeparator( $path ) {

		return str_replace( [ '/', '\\' ],  DIRECTORY_SEPARATOR, $path );
	}

	/**
	 * Save poster
	 *
	 * @param 		$fileName 				File name with extension
	 * @param 		$url 					External url of a image
	 * @param 		$overWrite 				Force to write
	 * @return 		string
	 */
	public function savePoster( $fileName, $url, $overWrite=FALSE ) {

		$path = $this->fixSeparator( implode( '/', [ $this->path, $this->cacheFolder, $this->folders[ 'main' ], $this->folders[ 'image' ], $this->type ] ) );
		$f    = $this->fixSeparator( $path . '/' . $fileName );

		if ( !file_exists( $path ) ) {

			mkdir( $path, 0777, TRUE );
		}
		else {

			if ( $overWrite == FALSE AND file_exists( $f ) == TRUE ) return NULL;
		}

		try {

			copy( $url, $f );
		}
		catch ( \Exception $e ) {

			throw new \Exception( "[MyAnimeList Cache Error] {$e}" );
		}

		return $this->fixSeparator( str_replace( $this->fixSeparator( $_SERVER[ 'DOCUMENT_ROOT' ] ), '', $path ) . '/' . $fileName );
	}
}