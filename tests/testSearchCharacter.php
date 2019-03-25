<?php

use PHPUnit\Framework\TestCase;

class TestAnime extends TestCase {

	public function testGetCharacterSearch() {

		$mal = new \myanimelist\Search\Character( 'ichigo' );

		$mal->sendRequestOrGetData();

		$success = TRUE;

		if ( !$mal->isSuccess() )                     $success = FALSE;
		if ( $mal->setLimit( 3 )->results === FALSE ) $success = FALSE;

		$this->assertTrue( $success );
	}
}