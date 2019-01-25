<?php

use PHPUnit\Framework\TestCase;

class TestPeople extends TestCase {

	public function testGetPeopleData() {

		$mal = new myanimelist\Types\People( 75 );

		$mal->get();

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