<?php

use PHPUnit\Framework\TestCase;

class testPagePeople extends TestCase {

	public function testGetPeoplePage() {

		$mal = new \myanimelist\Page\People( 75 );

		$mal->sendRequestOrGetData();

		$success = TRUE;

		if ( !$mal->isSuccess() )                         $success = FALSE;
		if ( $mal->name === FALSE )                       $success = FALSE;
		if ( $mal->poster === FALSE )                     $success = FALSE;
		if ( $mal->description === FALSE )                $success = FALSE;
		if ( $mal->statistic()->favorite === FALSE )      $success = FALSE;
		if ( $mal->setLimit( 5 )->recentvoice === FALSE ) $success = FALSE;
		if ( $mal->setLimit( 5 )->recentwork === FALSE )  $success = FALSE;

		$this->assertTrue( $success );
	}
}