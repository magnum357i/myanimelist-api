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

$mal = new myanimelist\Types\Character( $_GET[ 'id' ] );

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
	echo '<td class="align-middle text-light"><span class="bg-secondary py-0 px-2 shadow-sm small">recentanime</span></td>';
	echo '<td class="align-middle">';

	$mal->setLimit( 10 );

	if ( $mal->recentanime !== FALSE ) {

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

	$mal->setLimit( 10 );

	if ( $mal->recentmanga !== FALSE ) {

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

	$mal->setLimit( 10 );

	if ( $mal->voiceactors !== FALSE ) {

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