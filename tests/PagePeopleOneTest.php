<?php

use PHPUnit\Framework\TestCase;

class PagePeopleOneTest extends TestCase {

	private $mal;

    protected function setUp(): void {

		$this->mal = new \MyAnimeList\Page\People( 9411 );
		$this->mal->sendRequestOrGetData();
		$this->mal->config()->enablecache = TRUE;
	}

	public function testName(): void {

		$this->assertTrue( ( $this->mal->name == FALSE ) ? FALSE : TRUE );
	}

	public function testPoster(): void {

		$this->assertTrue( ( $this->mal->poster == FALSE ) ? FALSE : TRUE );
	}

	public function testBirth(): void {

		$this->assertTrue( ( $this->mal->birth == FALSE ) ? FALSE : TRUE );
	}

	public function testDeath(): void {

		$this->assertTrue( ( $this->mal->death == FALSE ) ? FALSE : TRUE );
	}

	public function testTabBase(): void {

		$this->assertTrue( ( $this->mal->tabBase == FALSE ) ? FALSE : TRUE );
	}

	public function testTabItems(): void {

		$this->assertTrue( ( $this->mal->tabItems == FALSE ) ? FALSE : TRUE );
	}
}