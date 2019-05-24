<?php

use PHPUnit\Framework\TestCase;

class SearchPeopleTest extends TestCase {

    private $mal;

    protected function setUp(): void {

		$this->mal = new \MyAnimeList\Search\People( 'ichigo' );
		$this->mal->sendRequestOrGetData();
		$this->mal->config()->enablecache = TRUE;
	}

	public function testResult(): void {

		$this->assertTrue( ( $this->mal->setLimit( 3 )->results == FALSE ) ? FALSE : TRUE );
	}
}