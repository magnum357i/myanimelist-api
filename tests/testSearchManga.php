<?php

use PHPUnit\Framework\TestCase;

class testSearchManga extends TestCase {

	public function testGetMangaSearch() {

		$mal = new \myanimelist\Search\Manga( 'bleach' );

		$mal->sendRequestOrGetData();

		$success = TRUE;

		if ( !$mal->isSuccess() )                     $success = FALSE;
		if ( $mal->setLimit( 3 )->results === FALSE ) $success = FALSE;

		$this->assertTrue( $success );
	}
}