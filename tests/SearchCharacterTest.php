<?php

use PHPUnit\Framework\TestCase;

class SearchCharacter extends TestCase {

	public function test_all_data() {

		$mal = new \MyAnimeList\Search\Character( 'ichigo' );

		$mal->sendRequestOrGetData();

		$success = TRUE;

		if ( !$mal->isSuccess() )                     $success = FALSE;
		if ( $mal->setLimit( 3 )->results === FALSE ) $success = FALSE;

		$this->assertTrue( $success );
	}
}