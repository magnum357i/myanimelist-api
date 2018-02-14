<?php

include( 'autoload.php' );

$mal = new myanimelist\Types\People( 80 );

$mal->_name();
$mal->_poster();
$mal->_description();
$mal->_favorites();
$mal->_recentvoice( 5 );
$mal->_recentwork( 5 );
$mal->_link();

$data = $mal->output();

if ( count( $data ) <= 3 )
{
	die( 'There is a problem retrieving data.' );
}

var_dump( $data );