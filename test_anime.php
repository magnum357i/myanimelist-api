<?php

include( 'autoload.php' );

$mal = new myanimelist\Types\Anime( 40 );

$mal->_titleoriginal();
$mal->_titleenglish();
$mal->_titlejapanese();
$mal->_titlesysnonmys();
$mal->_poster();
$mal->_description();
$mal->_type();
$mal->_status();
$mal->_broadcast();
$mal->_members();
$mal->_popularity();
$mal->_favorites();
$mal->_rating();
$mal->_rank();
$mal->_vote();
$mal->_point();
$mal->_genres();
$mal->_source();
$mal->_firstepisode();
$mal->_lastepisode();
$mal->_episode();
$mal->_studios();
$mal->_duration();
$mal->_premiered();
$mal->_year();
$mal->_voice( 3 );
$mal->_staff( 3 );
$mal->_related( 'adaptation',         10 );
$mal->_related( 'prequel',            10 );
$mal->_related( 'sequel',             10 );
$mal->_related( 'parentstory',        10 );
$mal->_related( 'sidestory',          10 );
$mal->_related( 'spinoff',            10 );
$mal->_related( 'alternativeversion', 10 );
$mal->_related( 'other',              10 );
$mal->_related( 'sequel',             10 );
$mal->_link();
$mal->_trailer();

$data = $mal->output();

if ( count( $data ) <= 3 )
{
	die( 'There is a problem retrieving data.' );
}

var_dump( $data );