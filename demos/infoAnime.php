<?php

include( '../autoload.php' );

$mal = new myanimelist\Types\Anime( $id );

$mal->config()->reverseName         = TRUE;
$mal->config()->cache               = TRUE;
$mal->config()->curl[ 'userAgent' ] = 'Mozilla/5.0 (Windows NT 5.1; rv:31.0) Gecko/20100101 Firefox/31.0';

$mal->cache()->expiredByDay = 2;
$mal->cache()->root         = __DIR__;
$mal->cache()->dir          = 'upload';

$mal->get();

if ( $mal->isSuccess() ) {

	echo '<h3>Usage</h3>';
	echo '<table class="table table-striped table-sm table-bordered">';
	echo '<thead>';
	echo '<th scope="col" class="align-middle">Variable</th>';
	echo '<th scope="col" class="align-middle">Value</th>';
	echo '</thead>';
	echo '<tbody>';
	echo '<tr>';
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">title()->original</span></td>';
	echo '<td class="align-middle">';

	if ( $mal->title()->original !== FALSE ) {

		echo $mal->title()->original;
	}
	else {

		echo '<span class="text-danger">Not found.</span>';
	}

	echo '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">title()->english</span></td>';
	echo '<td class="align-middle">';

	if ( $mal->title()->english !== FALSE ) {

		echo $mal->title()->english;
	}
	else {

		echo '<span class="text-danger">Not found.</span>';
	}

	echo '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">title()->japanese</span></td>';
	echo '<td class="align-middle">';

	if ( $mal->title()->japanese !== FALSE ) {

		echo $mal->title()->japanese;
	}
	else {

		echo '<span class="text-danger">Not found.</span>';
	}

	echo '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">title()->sysnonmys</span></td>';
	echo '<td class="align-middle">';

	if ( $mal->title()->sysnonmys !== FALSE ) {

		echo $mal->title()->sysnonmys;
	}
	else {

		echo '<span class="text-danger">Not found.</span>';
	}

	echo '</td>';
	echo '</tr>';
	echo '</tr>';
	echo '<tr>';
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">poster</span></td>';
	echo '<td class="align-middle">';

	if ( $mal->poster !== FALSE ) {

		echo '<img src="' . $mal->poster . '">';
	}
	else {

		echo '<span class="text-danger">Not found.</span>';
	}

	echo '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">description</span></td>';
	echo '<td class="align-middle">';

	if ( $mal->description !== FALSE ) {

		echo $mal->description;
	}
	else {

		echo '<span class="text-danger">Not found.</span>';
	}

	echo '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">category</span></td>';
	echo '<td class="align-middle">';

	if ( $mal->category !== FALSE ) {

		echo $mal->category;
	}
	else {

		echo '<span class="text-danger">Not found.</span>';
	}

	echo '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">status</span></td>';
	echo '<td class="align-middle">';

	if ( $mal->status !== FALSE ) {

		echo $mal->status;
	}
	else {

		echo '<span class="text-danger">Not found.</span>';
	}

	echo '</td>';
	echo '</tr>';

	if ( $mal->category == 'TV' ) {

		echo '<tr>';
		echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">broadcast()->day</span></td>';
		echo '<td class="align-middle">';

		if ( $mal->broadcast()->day !== FALSE ) {

			echo $mal->broadcast()->day;
		}
		else {

			echo '<span class="text-danger">Not found.</span>';
		}

		echo '</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">broadcast()->hour</span></td>';
		echo '<td class="align-middle">';

		if ( $mal->broadcast()->hour !== FALSE ) {

			echo $mal->broadcast()->hour;
		}
		else {

			echo '<span class="text-danger">Not found.</span>';
		}

		echo '</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">broadcast()->minute</span></td>';
		echo '<td class="align-middle">';

		if ( $mal->broadcast()->minute !== FALSE ) {

			echo $mal->broadcast()->minute;
		}
		else {

			echo '<span class="text-danger">Not found.</span>';
		}

		echo '</td>';
		echo '</tr>';
	}

	echo '<tr>';
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">statistic()->rank</span></td>';
	echo '<td class="align-middle">';

	if ( $mal->statistic()->rank !== FALSE ) {

		echo $mal->statistic()->rank;
	}
	else {

		echo '<span class="text-danger">Not found.</span>';
	}

	echo '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">statistic()->member</span></td>';
	echo '<td class="align-middle">';

	if ( $mal->statistic()->member !== FALSE ) {

		echo $mal->statistic()->member;
	}
	else {

		echo '<span class="text-danger">Not found.</span>';
	}

	echo '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">statistic()->memberraw</span></td>';
	echo '<td class="align-middle">';

	if ( $mal->statistic()->memberraw !== FALSE ) {

		echo $mal->statistic()->memberraw;
	}
	else {

		echo '<span class="text-danger">Not found.</span>';
	}

	echo '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">statistic()->popularity</span></td>';
	echo '<td class="align-middle">';

	if ( $mal->statistic()->popularity !== FALSE ) {

		echo $mal->statistic()->popularity;
	}
	else {

		echo '<span class="text-danger">Not found.</span>';
	}

	echo '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">statistic()->favorite</span></td>';
	echo '<td class="align-middle">';

	if ( $mal->statistic()->favorite !== FALSE ) {

		echo $mal->statistic()->favorite;
	}
	else {

		echo '<span class="text-danger">Not found.</span>';
	}

	echo '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">statistic()->favoriteraw</span></td>';
	echo '<td class="align-middle">';

	if ( $mal->statistic()->favoriteraw !== FALSE ) {

		echo $mal->statistic()->favoriteraw;
	}
	else {

		echo '<span class="text-danger">Not found.</span>';
	}

	echo '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">rating</span></td>';
	echo '<td class="align-middle">';

	if ( $mal->rating !== FALSE ) {

		echo $mal->rating;
	}
	else {

		echo '<span class="text-danger">Not found.</span>';
	}

	echo '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">score()->vote</span></td>';
	echo '<td class="align-middle">';

	if ( $mal->score()->vote !== FALSE ) {

		echo $mal->score()->vote;
	}
	else {

		echo '<span class="text-danger">Not found.</span>';
	}

	echo '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">score()->voteraw</span></td>';
	echo '<td class="align-middle">';

	if ( $mal->score()->voteraw !== FALSE ) {

		echo $mal->score()->voteraw;
	}
	else {

		echo '<span class="text-danger">Not found.</span>';
	}

	echo '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">score()->point</span></td>';
	echo '<td class="align-middle">';

	if ( $mal->score()->point !== FALSE ) {

		echo $mal->score()->point;
	}
	else {

		echo '<span class="text-danger">Not found.</span>';
	}

	echo '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">genres</span></td>';
	echo '<td class="align-middle">';

	if ( $mal->genres !== FALSE ) {

		echo '<ul>';

		foreach ( $mal->genres as $genre ) {

			echo "<li><a href=\"{$genre[ 'link' ]}\" target=\"_blank\">{$genre[ 'name' ]}</a></li>";
		}

		echo '</ul>';
	}
	else {

		echo '<span class="text-danger">Not found.</span>';
	}

	echo '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">source</span></td>';
	echo '<td class="align-middle">';

	if ( $mal->source !== FALSE ) {

		echo $mal->source;
	}
	else {

		echo '<span class="text-danger">Not found.</span>';
	}

	echo '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">aired()->first()->month</span></td>';
	echo '<td class="align-middle">';

	if ( $mal->aired()->first()->month !== FALSE ) {

		echo $mal->aired()->first()->month;
	}
	else {

		echo '<span class="text-danger">Not found.</span>';
	}

	echo '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">aired()->first()->day</span></td>';
	echo '<td class="align-middle">';

	if ( $mal->aired()->first()->day !== FALSE ) {

		echo $mal->aired()->first()->day;
	}
	else {

		echo '<span class="text-danger">Not found.</span>';
	}

	echo '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">aired()->first()->year</span></td>';
	echo '<td class="align-middle">';

	if ( $mal->aired()->first()->year !== FALSE ) {

		echo $mal->aired()->first()->year;
	}
	else {

		echo '<span class="text-danger">Not found.</span>';
	}

	echo '</td>';
	echo '</tr>';

	if ( $mal->category != 'Movie' ) {

		echo '<tr>';
		echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">aired()->last()->month</span></td>';
		echo '<td class="align-middle">';

		if ( $mal->aired()->last()->month !== FALSE ) {

			echo $mal->aired()->last()->month;
		}
		else {

			echo '<span class="text-danger">Not found.</span>';
		}

		echo '</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">aired()->last()->day</span></td>';
		echo '<td class="align-middle">';

		if ( $mal->aired()->last()->day !== FALSE ) {

			echo $mal->aired()->last()->day;
		}
		else {

			echo '<span class="text-danger">Not found.</span>';
		}

		echo '</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">aired()->last()->year</span></td>';
		echo '<td class="align-middle">';

		if ( $mal->aired()->last()->year !== FALSE ) {

			echo $mal->aired()->last()->year;
		}
		else {

			echo '<span class="text-danger">Not found.</span>';
		}

		echo '</td>';
		echo '</tr>';
	}

	echo '<tr>';
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">episode</span></td>';
	echo '<td class="align-middle">';

	if ( $mal->episode !== FALSE ) {

		echo $mal->episode;
	}
	else {

		echo '<span class="text-danger">Not found.</span>';
	}

	echo '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">studios</span></td>';
	echo '<td class="align-middle">';

	if ( $mal->studios !== FALSE ) {

		echo '<ul>';

		foreach ( $mal->studios as $studio ) {

			echo "<li><a href=\"{$studio[ 'link' ]}\" target=\"_blank\">{$studio[ 'name' ]}</a></li>";
		}

		echo '</ul>';
	}
	else {

		echo '<span class="text-danger">Not found.</span>';
	}

	echo '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">producers</span></td>';
	echo '<td class="align-middle">';

	if ( $mal->producers !== FALSE ) {

		echo '<ul>';

		foreach ( $mal->producers as $producer ) {

			echo "<li><a href=\"{$producer[ 'link' ]}\" target=\"_blank\">{$producer[ 'name' ]}</a></li>";
		}

		echo '</ul>';
	}
	else {

		echo '<span class="text-danger">Not found.</span>';
	}

	echo '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">licensors</span></td>';
	echo '<td class="align-middle">';

	if ( $mal->licensors !== FALSE ) {

		echo '<ul>';

		foreach ( $mal->licensors as $licensor ) {

			echo "<li><a href=\"{$licensor[ 'link' ]}\" target=\"_blank\">{$licensor[ 'name' ]}</a></li>";
		}

		echo '</ul>';
	}
	else {

		echo '<span class="text-danger">Not found.</span>';
	}

	echo '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">duration()->hour</span></td>';
	echo '<td class="align-middle">';

	if ( $mal->duration()->hour !== FALSE ) {

		echo $mal->duration()->hour;
	}
	else {

		echo '<span class="text-danger">Not found.</span>';
	}

	echo '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">duration()->min</span></td>';
	echo '<td class="align-middle">';

	if ( $mal->duration()->min !== FALSE ) {

		echo $mal->duration()->min;
	}
	else {

		echo '<span class="text-danger">Not found.</span>';
	}

	echo '</td>';
	echo '</tr>';

	if ( $mal->category == 'TV' ) {

		echo '<tr>';
		echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">premiered()->season</span></td>';
		echo '<td class="align-middle">';

		if ( $mal->premiered()->season !== FALSE ) {

			echo $mal->premiered()->season;
		}
		else {

			echo '<span class="text-danger">Not found.</span>';
		}

		echo '</td>';
		echo '</tr>';

		// Premiered works on TV only

		echo '<tr>';
		echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">premiered()->year</span></td>';
		echo '<td class="align-middle">';

		if ( $mal->premiered()->year !== FALSE ) {

			echo $mal->premiered()->year;
		}
		else {

			echo '<span class="text-danger">Not found.</span>';
		}

		echo '</td>';
		echo '</tr>';
	}

	echo '<tr>';
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">year</span></td>';
	echo '<td class="align-middle">';

	if ( $mal->year ) {

		echo $mal->year;
	}
	else {

		echo '<span class="text-danger">Not found.</span>';
	}

	echo '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">voice</span></td>';
	echo '<td class="align-middle">';

	if ( $mal->setLimit( 5 )->voice !== FALSE ) {

		echo '<ul>';

		foreach ( $mal->voice as $v ) {

			echo "<li>";

			if ( isset( $v[ 'character_link' ] ) ) {

				echo "<a href=\"{$v[ 'character_link' ]}\" target=\"_blank\">{$v[ 'character_name' ]}</a>";
			}

			// If people info has, sometimes this is not on MAL

			if ( isset( $v[ 'people_link' ] ) ) {

				echo " / <a href=\"{$v[ 'people_link' ]}\" target=\"_blank\">{$v[ 'people_name' ]}</a>";
				echo " ({$v[ 'people_lang' ]})";
			}

			echo "</li>";
		}

		echo '</ul>';
	}
	else {

		echo '<span class="text-danger">Not found.</span>';
	}

	echo '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">staff</span></td>';
	echo '<td class="align-middle">';

	if ( $mal->setLimit( 3 )->staff !== FALSE ) {

		echo '<ul>';

		foreach ( $mal->staff as $s ) {

			echo "<li>";
			echo "<a href=\"{$s[ 'people_link' ]}\" target=\"_blank\">{$s[ 'people_name' ]}</a>";
			echo "<ul>";
			echo " (";

				foreach ( $s[ 'people_positions_list' ] as $p ) {

					echo "<li>{$p}</li>";
				}

			echo ")";
			echo "</ul>";
			echo "</li>";
		}

		echo '</ul>';
	}
	else {

		echo '<span class="text-danger">Not found.</span>';
	}

	echo '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">related()->adaptation</span></td>';
	echo '<td class="align-middle">';

	if ( $mal->setLimit( 2 )->related()->adaptation !== FALSE ) {

		echo '<ul>';

		foreach ( $mal->related()->adaptation as $r ) {

			echo "<li><a href=\"{$r[ 'link' ]}\" target=\"_blank\">{$r[ 'title' ]}</a></li>";
		}

		echo '</ul>';
	}
	else {

		echo '<span class="text-danger">Not found.</span>';
	}

	echo '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">related()->prequel</span></td>';
	echo '<td class="align-middle">';

	if ( $mal->setLimit( 2 )->related()->prequel !== FALSE ) {

		echo '<ul>';

		foreach ( $mal->related()->prequel as $r ) {

			echo "<li><a href=\"{$r[ 'link' ]}\" target=\"_blank\">{$r[ 'title' ]}</a></li>";
		}

		echo '</ul>';
	}
	else {

		echo '<span class="text-danger">Not found.</span>';
	}

	echo '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">related()->sequel</span></td>';
	echo '<td class="align-middle">';

	if ( $mal->setLimit( 2 )->related()->sequel !== FALSE ) {

		echo '<ul>';

		foreach ( $mal->related()->sequel as $r ) {

			echo "<li><a href=\"{$r[ 'link' ]}\" target=\"_blank\">{$r[ 'title' ]}</a></li>";
		}

		echo '</ul>';
	}
	else {

		echo '<span class="text-danger">Not found.</span>';
	}

	echo '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">related()->parentstory</span></td>';
	echo '<td class="align-middle">';

	if ( $mal->setLimit( 2 )->related()->parentstory !== FALSE ) {

		echo '<ul>';

		foreach ( $mal->related()->parentstory as $r ) {

			echo "<li><a href=\"{$r[ 'link' ]}\" target=\"_blank\">{$r[ 'title' ]}</a></li>";
		}

		echo '</ul>';
	}
	else {

		echo '<span class="text-danger">Not found.</span>';
	}

	echo '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">related()->sidestory</span></td>';
	echo '<td class="align-middle">';

	if ( $mal->setLimit( 2 )->related()->sidestory !== FALSE ) {

		echo '<ul>';

		foreach ( $mal->related()->sidestory as $r ) {

			echo "<li><a href=\"{$r[ 'link' ]}\" target=\"_blank\">{$r[ 'title' ]}</a></li>";
		}

		echo '</ul>';
	}
	else {

		echo '<span class="text-danger">Not found.</span>';
	}

	echo '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">related()->spinoff</span></td>';
	echo '<td class="align-middle">';

	if ( $mal->setLimit( 2 )->related()->spinoff !== FALSE ) {

		echo '<ul>';

		foreach ( $mal->related()->spinoff as $r ) {

			echo "<li><a href=\"{$r[ 'link' ]}\" target=\"_blank\">{$r[ 'title' ]}</a></li>";
		}

		echo '</ul>';
	}
	else {

		echo '<span class="text-danger">Not found.</span>';
	}

	echo '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">related()->alternativeversion</span></td>';
	echo '<td class="align-middle">';

	if ( $mal->setLimit( 2 )->related()->alternativeversion !== FALSE ) {

		echo '<ul>';

		foreach ( $mal->related()->alternativeversion as $r ) {

			echo "<li><a href=\"{$r[ 'link' ]}\" target=\"_blank\">{$r[ 'title' ]}</a></li>";
		}

		echo '</ul>';
	}
	else {

		echo '<span class="text-danger">Not found.</span>';
	}

	echo '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">related()->other</span></td>';
	echo '<td class="align-middle">';

	if ( $mal->setLimit( 2 )->related()->other !== FALSE ) {

		echo '<ul>';

		foreach ( $mal->related()->other as $r ) {

			echo "<li><a href=\"{$r[ 'link' ]}\" target=\"_blank\">{$r[ 'title' ]}</a></li>";
		}

		echo '</ul>';
	}
	else {

		echo '<span class="text-danger">Not found.</span>';
	}

	echo '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">trailer</span></td>';
	echo '<td class="align-middle">';

	if ( $mal->trailer !== FALSE ) {

		echo "<a href=\"{$mal->trailer}\" target=\"_blank\">{$mal->trailer}</a>";
	}
	else {

		echo '<span class="text-danger">Not found.</span>';
	}

	echo '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">link</span></td>';
	echo '<td class="align-middle">';

	if ( $mal->link !== FALSE ) {

		echo "<a href=\"{$mal->link}\" target=\"_blank\">{$mal->link}</a>";
	}
	else {

		echo '<span class="text-danger">Not found.</span>';
	}

	echo '</td>';
	echo '</tr>';
	echo '</tbody>';
	echo '</table>';

	if ( $mal->config()->cache == TRUE ) {

		echo '<h3>JSON Content</h3>';
		echo '<div class="p-3 bg-dark text-white">';
		echo '<small>';
		echo $mal;
		echo '</small>';
		echo '</div>';
	}

	echo '<h3>Time</h3>';
	echo '<div class="alert alert-primary" role="alert">';
	echo 'Elapsed time: <b>' . $mal->elapsedTime() . '</b>';
	echo '</div>';
}
else {

	echo 'There is a problem retrieving data.';
}