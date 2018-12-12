<html>
<head>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<style>
		body .table, body div {
			width: 80%;
			margin: 40px auto;
		}

		.table td:nth-child(1) {
			white-space: nowrap;
		}

		ul {
			list-style-type: none;
			margin: 0;
			padding: 0;
			display: inline;
		}

		li {
			display: inline;
		}

		li:after {
			content: ', ';
		}

		li:last-child:after {
			content: '';
		}
	</style>
</head>
<body>

<?php

include( '../autoload.php' );

if ( !isset( $_GET[ 'id' ] ) ) die( 'Please send id parameter in get method' );

$mal = new myanimelist\Types\People( $_GET[ 'id' ] );

$mal->config()->reverseName         = TRUE;
$mal->config()->cache               = TRUE;
$mal->config()->curl[ 'userAgent' ] = 'Mozilla/5.0 (Windows NT 5.1; rv:31.0) Gecko/20100101 Firefox/31.0';

$mal->cache()->expiredByDay = 2;
$mal->cache()->root         = __DIR__;
$mal->cache()->dir          = 'upload';

// Request or get data from cache

$mal->get();

if ( $mal->isSuccess() ) {

	echo '<table class="table table-striped table-sm table-bordered">';
	echo '<thead>';
	echo '<th scope="col" class="align-middle">Usage</th>';
	echo '<th scope="col" class="align-middle">Value</th>';
	echo '</thead>';
	echo '<tbody>';
	echo '<tr>';
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">name</span></td>';
	echo '<td class="align-middle">';

	if ( $mal->name !== FALSE ) {

		echo $mal->name;
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
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">favorites</span></td>';
	echo '<td class="align-middle">';

	if ( $mal->favorites !== FALSE ) {

		echo $mal->favorites;
	}
	else {

		echo '<span class="text-danger">Not found.</span>';
	}

	echo '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">recentwork</span></td>';
	echo '<td class="align-middle">';

	if ( $mal->setLimit( 10 )->recentwork !== FALSE ) {

		echo '<ul>';

		foreach ( $mal->recentwork as $w ) {

			echo "<li><a href=\"{$w[ 'link' ]}\" target=\"_blank\">{$w[ 'title' ]}</a> ({$w[ 'work' ]})</li>";
		}

		echo '</ul>';
	}
	else {

		echo '<span class="text-danger">Not found.</span>';
	}

	echo '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">recentvoice</span></td>';
	echo '<td class="align-middle">';

	if ( $mal->setLimit( 10 )->recentvoice !== FALSE ) {

		echo '<ul>';

		foreach ( $mal->recentvoice as $v ) {

			echo "<li><a href=\"{$v[ 'anime_link' ]}\" target=\"_blank\">{$v[ 'anime_title' ]}</a> / <a href=\"{$v[ 'character_link' ]}\" target=\"_blank\">{$v[ 'character_name' ]}</a></li>";
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

	echo '<div class="alert alert-primary" role="alert">';
	echo 'Elapsed time: <b>' . $mal->elapsedTime() . '</b>';
	echo '</div>';
}
else {

	die( 'There is a problem retrieving data.' );
}

?>

</body>
</html>