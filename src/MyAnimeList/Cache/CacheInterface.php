<?php

namespace MyAnimeList\Cache;

interface CacheInterface {

	/**
	 * If file exists
	 *
	 * @param 		$fileName 				File name
	 * @return 		bool
	 */
	public function hasFile( $fileName );

	/**
	 * Read data from cache file
	 *
	 * @param 		$fileName 				File name
	 * @return 		array
	 */
	public function readFile( $fileName );

	/**
	 * Write cache file
	 *
	 * @param 		$fileName 				File name
	 * @param 		$content 				File content
	 * @return 		void
	 */
	public function writeFile( $fileName, $content );

	/**
	 * Save poster
	 *
	 * @param 		$fileName 				File name
	 * @param 		$url 					External url of a image
	 * @param 		$overWrite 				Force to write
	 * @return 		string
	 */
	public function savePoster( $fileName, $url, $overWrite=FALSE );
}