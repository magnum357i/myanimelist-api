<?php

include( 'autoload.php' );

$mal = new myanimelist\Types\Character( 40 );

$mal->_charactername();
$mal->_nickname();
$mal->_poster();
$mal->_description();
$mal->_favorites();
$mal->_recentanime( 5 );
$mal->_recentmanga( 5 );
$mal->_voiceactors( 1 );

$data = $mal->output();

if ( count( $data ) <= 3 )
{
	die( 'There is a problem retrieving data.' );
}

var_dump( $data );