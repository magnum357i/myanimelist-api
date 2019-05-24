<?php

/**
 * Simple Cache Class
 *
 * @package	 		MyAnimeList API
 * @author     		Magnum357 [https://github.com/magnum357i/]
 * @copyright  		2018
 * @license    		http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace MyAnimeList\Cache;

use \MyAnimeList\Cache\CacheInterface;

class Cache implements CacheInterface {

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

		foreach( $this->folders as $key => $value ) $this->folders[ $key ] = $folders[ $key ];

		$this->type = $type;

		$this->setPath( __DIR__ . '../../../../cache' );
	}

	/**
	 * Set path to save
	 *
	 * @param 		$root 			Root path
	 * @param 		$path 			Path to save
	 * @return 		void
	 */
	public function setPath( $path ) {

		$this->path = $path;
	}

	/**
	 * File size in bytes
	 *
	 * @param 		$fileName 				File name
	 * @param 		$type 					'poster' or 'file'?
	 * @return 		bool
	 */
	public function fileSize( $fileName, $type ) {

		$file = NULL;

		if ( $type == 'poster' ) {

			$path = $this->fixSeparator( implode( '/', [ $this->path, $this->cacheFolder, $this->folders[ 'main' ], $this->folders[ 'image' ], $this->type ] ) );
			$file = $this->fixSeparator( $path . '/' . $fileName . '.jpg' );
		}
		else if ( $type == 'file' ) {

			$path = $this->fixSeparator( implode( '/', [ $this->path, $this->cacheFolder, $this->folders[ 'main' ], $this->folders[ 'file' ], $this->type ] ) );
			$file = $this->fixSeparator( $path . '/' . $fileName . '.json' );
		}

		return filesize( $file );
	}

	/**
	 * If file exists
	 *
	 * @param 		$fileName 				File name
	 * @return 		bool
	 */
	public function hasFile( $fileName ) {

		$path = $this->fixSeparator( implode( '/', [ $this->path, $this->cacheFolder, $this->folders[ 'main' ], $this->folders[ 'file' ], $this->type ] ) );
		$file = $this->fixSeparator( $path . '/' . $fileName . '.json' );

		return ( file_exists( $file ) ) ? TRUE : FALSE;
	}

	/**
	 * Read data from cache file
	 *
	 * @param 		$fileName 				File name
	 * @return 		array
	 */
	public function readFile( $fileName ) {

		$path = $this->fixSeparator( implode( '/', [ $this->path, $this->cacheFolder, $this->folders[ 'main' ], $this->folders[ 'file' ], $this->type ] ) );
		$file = $this->fixSeparator( $path . '/' . $fileName . '.json' );

		try {

			$content = json_decode( file_get_contents( $file ), TRUE );
		}
		catch ( \Exception $e ) {

			throw new \Exception( "[MyAnimeList Cache Error] {$e}" );
		}

		return $content;
	}

	/**
	 * Write cache file
	 *
	 * @param 		$fileName 				File name
	 * @param 		$content 				File content
	 * @return 		void
	 */
	public function writeFile( $fileName, $content ) {

		if ( empty( $content ) ) return NULL;

		$path = $this->fixSeparator( implode( '/', [ $this->path, $this->cacheFolder, $this->folders[ 'main' ], $this->folders[ 'file' ], $this->type ] ) );

		if ( !is_dir( $path ) ) mkdir( $path, 0700, TRUE );

		$file = $this->fixSeparator( $path . '/' . $fileName . '.json' );

		try {

			file_put_contents( $file, json_encode( $content ) );
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
	 * @param 		$fileName 				File name
	 * @param 		$url 					External url of a image
	 * @param 		$overWrite 				Force to write
	 * @return 		string
	 */
	public function savePoster( $fileName, $url, $overWrite=FALSE ) {

		$path   = $this->fixSeparator( implode( '/', [ $this->path, $this->cacheFolder, $this->folders[ 'main' ], $this->folders[ 'image' ], $this->type ] ) );
		$poster = $this->fixSeparator( $path . '/' . $fileName . '.jpg' );

		if ( !is_dir( $path ) ) mkdir( $path, 0700, TRUE );

		if ( $overWrite == TRUE OR !file_exists( $poster ) ) {

			try {

				copy( $url, $poster );
			}
			catch ( \Exception $e ) {

				throw new \Exception( "[MyAnimeList Cache Error] {$e}" );
			}
		}

		return $this->fixSeparator( str_replace( $this->fixSeparator( filter_input( INPUT_SERVER, 'DOCUMENT_ROOT', FILTER_SANITIZE_STRING ) ), '', $poster ) );
	}
}