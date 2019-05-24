<?php

use PHPUnit\Framework\TestCase;

class WidgetAnimeCalendarTest extends TestCase {

    private $mal;

    protected function setUp(): void {

		$this->mal = new \MyAnimeList\Widget\AnimeCalendar;
		$this->mal->sendRequestOrGetData();
		$this->mal->config()->enablecache = TRUE;
	}

	public function testMonday(): void {

		$this->assertTrue( ( $this->mal->setLimit( 3 )->monday == FALSE ) ? FALSE : TRUE );
	}

	public function testTuesday(): void {

		$this->assertTrue( ( $this->mal->setLimit( 3 )->tuesday == FALSE ) ? FALSE : TRUE );
	}

	public function testWednesday(): void {

		$this->assertTrue( ( $this->mal->setLimit( 3 )->wednesday == FALSE ) ? FALSE : TRUE );
	}

	public function testThursday(): void {

		$this->assertTrue( ( $this->mal->setLimit( 3 )->thursday == FALSE ) ? FALSE : TRUE );
	}

	public function testFriday(): void {

		$this->assertTrue( ( $this->mal->setLimit( 3 )->friday == FALSE ) ? FALSE : TRUE );
	}

	public function testSaturday(): void {

		$this->assertTrue( ( $this->mal->setLimit( 3 )->saturday == FALSE ) ? FALSE : TRUE );
	}

	public function testSunday(): void {

		$this->assertTrue( ( $this->mal->setLimit( 3 )->sunday == FALSE ) ? FALSE : TRUE );
	}
}