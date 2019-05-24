<?php

use PHPUnit\Framework\TestCase;

class PageCharacterTest extends TestCase {

    private $mal;

    protected function setUp(): void {

		$this->mal = new \MyAnimeList\Page\Character( 417 );
		$this->mal->sendRequestOrGetData();
		$this->mal->config()->enablecache = TRUE;
	}

	public function testTitleSelf(): void {

		$this->assertTrue( ( $this->mal->titleSelf == FALSE ) ? FALSE : TRUE );
	}

	public function testTitleNickname(): void {

		$this->assertTrue( ( $this->mal->titleNickname == FALSE ) ? FALSE : TRUE );
	}

	public function testPoster(): void {

		$this->assertTrue( ( $this->mal->poster == FALSE ) ? FALSE : TRUE );
	}

	public function testDescription(): void {

		$this->assertTrue( ( $this->mal->description == FALSE ) ? FALSE : TRUE );
	}

	public function testStatisticFavorite(): void {

		$this->assertTrue( ( $this->mal->statisticFavorite == FALSE ) ? FALSE : TRUE );
	}

	public function testRecentAnime(): void {

		$this->assertTrue( ( $this->mal->setLimit( 10 )->recentAnime == FALSE ) ? FALSE : TRUE );
	}

	public function testRecentManga(): void {

		$this->assertTrue( ( $this->mal->setLimit( 10 )->recentManga == FALSE ) ? FALSE : TRUE );
	}

	public function testVoiceactors(): void {

		$this->assertTrue( ( $this->mal->setLimit( 10 )->voiceactors == FALSE ) ? FALSE : TRUE );
	}

	public function testHeight(): void {

		$this->assertTrue( ( $this->mal->height == FALSE ) ? FALSE : TRUE );
	}

	public function testWeight(): void {

		$this->assertTrue( ( $this->mal->weight == FALSE ) ? FALSE : TRUE );
	}

	public function testTabBase(): void {

		$this->assertTrue( ( $this->mal->tabBase == FALSE ) ? FALSE : TRUE );
	}

	public function testTabItems(): void {

		$this->assertTrue( ( $this->mal->tabItems == FALSE ) ? FALSE : TRUE );
	}
}