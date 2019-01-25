<?php

include( '../autoload.php' );

$mal = new myanimelist\Types\Character( $id );

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
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">title()->self</span></td>';
	echo '<td class="align-middle">';

	if ( $mal->title()->self !== FALSE ) {

		echo $mal->title()->self;
	}
	else {

		echo '<span class="text-danger">Not found.</span>';
	}

	echo '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">title()->nickname</span></td>';
	echo '<td class="align-middle">';

	if ( $mal->title()->nickname !== FALSE ) {

		echo $mal->title()->nickname;
	}
	else {

		echo '<span class="text-danger">Not found.</span>';
	}

	echo '</td>';
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
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">recentanime</span></td>';
	echo '<td class="align-middle">';

	if ( $mal->setLimit( 10 )->recentanime !== FALSE ) {

		echo '<ul>';

		foreach ( $mal->recentanime as $a ) {

			echo "<li><a href=\"{$a[ 'link' ]}\" target=\"_blank\">{$a[ 'title' ]}</a> ({$a[ 'role' ]})</li>";
		}

		echo '</ul>';
	}
	else {

		echo '<span class="text-danger">Not found.</span>';
	}

	echo '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">recentmanga</span></td>';
	echo '<td class="align-middle">';

	if ( $mal->setLimit( 10 )->recentmanga !== FALSE ) {

		echo '<ul>';

		foreach ( $mal->recentmanga as $m ) {

			echo "<li><a href=\"{$m[ 'link' ]}\" target=\"_blank\">{$m[ 'title' ]}</a> ({$m[ 'role' ]})</li>";
		}

		echo '</ul>';
	}
	else {

		echo '<span class="text-danger">Not found.</span>';
	}

	echo '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">voiceactors</span></td>';
	echo '<td class="align-middle">';

	if ( $mal->setLimit( 10 )->voiceactors !== FALSE ) {

		echo '<ul>';

		foreach ( $mal->voiceactors as $v ) {

			echo "<li><a href=\"{$v[ 'people_link' ]}\" target=\"_blank\">{$v[ 'people_name' ]}</a> ({$v[ 'people_lang' ]})</li>";
		}

		echo '</ul>';
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