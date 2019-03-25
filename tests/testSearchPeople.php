<?php

use PHPUnit\Framework\TestCase;

class testSearchPeople extends TestCase {

	public function testGetPeopleSearch() {

		$mal = new \myanimelist\Search\People( 'ichigo' );

		$mal->sendRequestOrGetData();

		$success = TRUE;

		if ( !$mal->isSuccess()                )      $success = FALSE;
		if ( $mal->setLimit( 3 )->results === FALSE ) $success = FALSE;

		$this->assertTrue( $success );
	}
}