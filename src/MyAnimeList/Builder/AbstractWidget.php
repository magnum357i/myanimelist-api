<?php

namespace MyAnimeList\Builder;

abstract class AbstractWidget extends AbstractBuilder {

	/**
	 * @var 		array 			Saving folders
	 */
	public static $folders = [

		'main'  => 'widget',
		'file'  => 'json',
		'image' => NULL
	];

	/**
	 * @var 		array 			base_url/?
	 */
	protected $urlPatterns = [

		'newanime'      => 'anime/season',
		'upcominganime' => 'anime/season/later',
		'animecalendar' => 'anime/season/schedule'
	];

	/**
	 * Url query
	 *
	 * @return 		string
	 */
	protected function url() {

		return $this->urlPatterns[ static::$type ];
	}

	/**
	 * File name for cache
	 *
	 * @return 		string
	 */
	protected function getFileName() {

		return 'widget';
	}
}