<?php

use PHPUnit\Framework\TestCase;

class PagePeopleTwoTest extends TestCase {

	private $mal;

    protected function setUp(): void {

		$this->mal = new \MyAnimeList\Page\People( 11297 );
		$this->mal->sendRequestOrGetData();
		$this->mal->config()->enablecache = TRUE;
	}

	public function testStatisticFavorite(): void {

		$this->assertTrue( ( $this->mal->statisticFavorite == FALSE ) ? FALSE : TRUE );
	}

	public function testWeight(): void {

		$this->assertTrue( ( $this->mal->weight == FALSE ) ? FALSE : TRUE );
	}

	public function testHeight(): void {

		$this->assertTrue( ( $this->mal->height == FALSE ) ? FALSE : TRUE );
	}

	public function testRecentWork(): void {

		$this->assertTrue( ( $this->mal->setLimit( 3 )->recentWork == FALSE ) ? FALSE : TRUE );
	}

	public function testRecentVoice(): void {

		$this->assertTrue( ( $this->mal->setLimit( 3 )->recentVoice == FALSE ) ? FALSE : TRUE );
	}

	public function testDescription(): void {

		$this->assertTrue( ( $this->mal->description == FALSE ) ? FALSE : TRUE );
	}
}