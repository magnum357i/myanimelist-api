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

class CacheAdapter implements CacheInterface {

	/**
	 * Permissions
	 */
	const FOLDER_PERM = 0755;
	const FILE_PERM   = 0666;

	/**
	 * @var 		array 			Saving directories
	 */
	protected $paths = [

		'file'  => NULL,
		'image' => NULL
	];

	/**
	 * @var 		string 			Cache folder
	 */
	protected $cacheFolder = 'myanimelist';

	/**
	 * @var 		string 			Cache path
	 */
	protected $uploadPath = '';

	/**
	 * Set parameters
	 *
	 * @param 		$type 				MAL Type
	 * @param 		$folders 			Saving directories
	 * @return 		void
	 */
	public function __construct( $type, $folders=[] ) {

		$this->paths[ 'file' ]  = ( $folders[ 'file' ]  != NULL ) ? $this->fixSeparator( implode( '/', [ $this->cacheFolder, $folders[ 'main' ], $folders[ 'file' ],  $type ] ) ) : NULL;
		$this->paths[ 'image' ] = ( $folders[ 'image' ] != NULL ) ? $this->fixSeparator( implode( '/', [ $this->cacheFolder, $folders[ 'main' ], $folders[ 'image' ], $type ] ) ) : NULL;

		$this->setPath( __DIR__ . '/../../../cache' );
	}

	/**
	 * Set path to save
	 *
	 * @param 		$path 			Path to save
	 * @return 		void
	 */
	public function setPath( $path ) {

		$path      = $this->fixSeparator( $path );
		$seperator = DIRECTORY_SEPARATOR;
		$count     = 1;

		while ( $count > 0 ) $path = preg_replace( "@[^\\{$seperator}]+{$seperator}\.\.\\{$seperator}@", '', $path, 1, $count );

		$this->uploadPath = $path;
	}

	/**
	 * Create path if not
	 *
	 * @return 		void
	 */
	public function createPathIfNot() {

		foreach ( [ 'file', 'image' ] as $type ) {

			if ( $this->paths[ $type ] != NULL ) {

				$path = $this->uploadPath . DIRECTORY_SEPARATOR . $this->paths[ $type ];

				if( !is_dir( $path ) ) {

					if( !@mkdir( $path, static::FOLDER_PERM, TRUE ) OR !@chmod( $path, static::FOLDER_PERM ) ) {

						throw new \Exception( "[MyAnimeList Cache Error] Directory could not create" );
					}

					@file_put_contents( $path . '/index.html', '' );
				}
			}
		}
	}

	/**
	 * File size in bytes
	 *
	 * @param 		$fileName 				File name
	 * @param 		$type 					'poster'|'file'
	 * @return 		bool
	 */
	public function fileSize( $fileName, $type ) {

		$file = NULL;

		if ( $type == 'poster' ) {

			$file = implode( DIRECTORY_SEPARATOR, [ $this->uploadPath, $this->paths[ 'image' ], "{$fileName}.json" ] );
		}
		else if ( $type == 'file' ) {

			$file = implode( DIRECTORY_SEPARATOR, [ $this->uploadPath, $this->paths[ 'file' ], "{$fileName}.json" ] );
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

		$file = implode( DIRECTORY_SEPARATOR, [ $this->uploadPath, $this->paths[ 'file' ], "{$fileName}.json" ] );

		return ( file_exists( $file ) ) ? TRUE : FALSE;
	}

	/**
	 * Read data from cache file
	 *
	 * @param 		$fileName 				File name
	 * @return 		array
	 */
	public function readFile( $fileName ) {

		$file    = implode( DIRECTORY_SEPARATOR, [ $this->uploadPath, $this->paths[ 'file' ], "{$fileName}.json" ] );
		$content = @file_get_contents( $file );

		if ( !$content ) throw new \Exception( "[MyAnimeList Cache Error] File could not read" );

		return json_decode( $content, TRUE );
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

		$this->createPathIfNot();

		$file = implode( DIRECTORY_SEPARATOR, [ $this->uploadPath, $this->paths[ 'file' ], "{$fileName}.json" ] );

		if ( !@file_put_contents( $file, json_encode( $content ) ) ) throw new \Exception( "[MyAnimeList Cache Error] File could not save" );

		@chmod( $file, static::FILE_PERM );
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

		$this->createPathIfNot();

		$poster = implode( DIRECTORY_SEPARATOR, [ $this->uploadPath, $this->paths[ 'image' ], "{$fileName}.json" ] );

		if ( $overWrite == TRUE OR !file_exists( $poster ) ) {

			if( !@copy( $url, $poster ) ) throw new \Exception( "[MyAnimeList Cache Error] Poster could not save" );

			@chmod( $poster, static::FILE_PERM );
		}

		return $this->fixSeparator( str_replace( $this->fixSeparator( filter_input( INPUT_SERVER, 'DOCUMENT_ROOT', FILTER_SANITIZE_STRING ) ), '', $poster ) );
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
}