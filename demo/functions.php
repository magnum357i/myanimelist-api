<?php

function outputFormatter( $o ) {

	$o = htmlspecialchars( $o );
	$o = htmlentities( $o );
	$o = "<pre>{$o}</pre>";

	return $o;
}

function timeAgo( $unix, $precision=0, $microTime=FALSE ) {

	if ( $microTime == FALSE ) {

		$timeAgo = '';
		$diff    = time() - $unix;
		$min     = 60;
		$hour    = 60 * $min;
		$day     = $hour * 24;
		$month   = $day * 30;

		if( $diff < $min )        $timeAgo = $diff                              . 'S';
		elseif ( $diff < $hour )  $timeAgo = round( $diff / $min,  $precision ) . 'M';
		elseif ( $diff < $day )   $timeAgo = round( $diff / $hour, $precision ) . 'H';
		elseif ( $diff < $month ) $timeAgo = round( $diff / $day,  $precision ) . 'D';

		return $timeAgo;
	}

	$duration = microtime( TRUE ) - $unix;
	$hours    = (int) ( $duration / 60 / 60 );
	$minutes  = (int) ( $duration / 60 ) - $hours * 60;
	$seconds  = $duration - $hours * 60 * 60 - $minutes * 60;

	return number_format( (float) $seconds, $precision, '.', '' ) . 'S';
}

function humanFileSize( $bytes ) {

	$size = 0;

	switch ( $bytes ) {

		case $bytes > 1048576: $size = number_format( $bytes / 1024 / 1024, 1 ) . 'M'; break;
		case $bytes > 1024:    $size = number_format( $bytes / 1024, 1 )        . 'K'; break;
		case $bytes < 1024:    $size = number_format( $bytes )                  . 'B'; break;
	}

	return $size;
}

function spoilerBBcode( $content ) {

	return
	'<div class="bg-info text-light p-3 m-3">
		<h4>SPOILER!</h4>
		<div>' . $content . '</div>
	</div>';
}

function statisticDashboard( $fileName, $isPoster ) {

	global $mal, $ram1, $time;

	echo '<h3>Statistics</h3>';
	echo '<div class="p-3 bg-warning mb-5">';
		echo '<div class="row text-center">';

			if ( $mal->config()->enablecache ) {

				echo '<div class="col-sm">';
					echo '<div class="h6">EDITED TIME</div>';
					echo '<span class="display-4">' . timeAgo( $mal->editedTime() ) . '</span>';
				echo '</div>';
			}

			echo '<div class="col-sm">';
				echo '<div class="h6">ELAPSED TIME</div>';
				echo '<span class="display-4">' . timeAgo( $time, 2, TRUE ) . '</span>';
			echo '</div>';

			if ( $mal->request()->isSent() ) {

				echo '<div class="col-sm">';
					echo '<div class="h6">REQUEST TIME</div>';
					echo '<span class="display-4">' . \MyAnimeList\Helper\Text::roundNumber( $mal->request()->info()[ 'total_time' ], 2 ) . 'S</span>';
				echo '</div>';
				echo '<div class="col-sm">';
					echo '<div class="h6">PAGE SIZE</div>';
					echo '<span class="display-4">' . humanFileSize( $mal->request()->info()[ 'size_download' ] ) . '</span>';
				echo '</div>';
			}

			if ( $mal->config()->enablecache AND $mal->cache()->hasFile( $fileName ) ) {

				echo '<div class="col-sm">';
					echo '<div class="h6">CACHE FILE SIZE</div>';
					echo '<span class="display-4">' . humanFileSize( $mal->cache()->fileSize( $fileName, 'file' ) ) . '</span>';
				echo '</div>';

				if ( $isPoster == TRUE ) {

					echo '<div class="col-sm">';
						echo '<div class="h6">POSTER SIZE</div>';
						echo '<span class="display-4">' . humanFileSize( $mal->cache()->fileSize( $fileName, 'poster' ) ) . '</span>';
					echo '</div>';
				}
			}

			$ram2 = memory_get_usage();

			echo '<div class="col-sm">';
				echo '<div class="h6">RAM USAGE SIZE (theatrical)</div>';
				echo '<span class="display-4">' . humanFileSize( $ram2 - $ram1 ) . '</span>';
			echo '</div>';

		echo '</div>';
	echo '</div>';
}

function jsonContent() {

	global $mal;

	if ( $mal->config()->enablecache ) {

		echo '<h3>JSON Content</h3>';
		echo '<div class="p-3 bg-dark mb-5 json">';
		echo '<small>';
		echo '<pre class="text-white">';
		echo json_encode( json_decode( $mal, TRUE ), JSON_PRETTY_PRINT );
		echo '</pre>';
		echo '</small>';
		echo '</div>';
	}
}

function hoverPoster( $value ) {

	return "data-toggle=\"tooltip\" data-html=\"true\" title=\"<img src='" . $value . "'>\"";
}