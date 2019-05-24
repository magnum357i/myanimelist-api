<?php

use PHPUnit\Framework\TestCase;

class WidgetUpcomingAnimeTest extends TestCase {

    private $mal;

    protected function setUp(): void {

		$this->mal = new \MyAnimeList\Widget\UpcomingAnime;
		$this->mal->sendRequestOrGetData();
		$this->mal->config()->enablecache = TRUE;
	}

	public function testTv(): void {

		$this->assertTrue( ( $this->mal->setLimit( 3 )->tv == FALSE ) ? FALSE : TRUE );
	}

	public function testOna(): void {

		$this->assertTrue( ( $this->mal->setLimit( 3 )->ona == FALSE ) ? FALSE : TRUE );
	}

	public function testOva(): void {

		$this->assertTrue( ( $this->mal->setLimit( 3 )->ova == FALSE ) ? FALSE : TRUE );
	}

	public function testMovie(): void {

		$this->assertTrue( ( $this->mal->setLimit( 3 )->movie == FALSE ) ? FALSE : TRUE );
	}

	public function testSpecial(): void {

		$this->assertTrue( ( $this->mal->setLimit( 3 )->special == FALSE ) ? FALSE : TRUE );
	}
}