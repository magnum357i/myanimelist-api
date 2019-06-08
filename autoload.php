<?php

require_once( __DIR__ . '/src/MyAnimeList/Helper/Text.php' );
require_once( __DIR__ . '/src/MyAnimeList/Helper/Config.php' );
require_once( __DIR__ . '/src/MyAnimeList/Helper/Request.php' );

require_once( __DIR__ . '/src/MyAnimeList/Cache/CacheInterface.php' );
require_once( __DIR__ . '/src/MyAnimeList/Cache/CacheAdapter.php' );

require_once( __DIR__ . '/src/MyAnimeList/Builder/AbstractBuilder.php' );
require_once( __DIR__ . '/src/MyAnimeList/Builder/AbstractPage.php' );
require_once( __DIR__ . '/src/MyAnimeList/Builder/AbstractSearch.php' );
require_once( __DIR__ . '/src/MyAnimeList/Builder/AbstractWidget.php' );

require_once( __DIR__ . '/src/MyAnimeList/Page/Anime.php' );
require_once( __DIR__ . '/src/MyAnimeList/Page/Character.php' );
require_once( __DIR__ . '/src/MyAnimeList/Page/Manga.php' );
require_once( __DIR__ . '/src/MyAnimeList/Page/People.php' );

require_once( __DIR__ . '/src/MyAnimeList/Search/Anime.php' );
require_once( __DIR__ . '/src/MyAnimeList/Search/Character.php' );
require_once( __DIR__ . '/src/MyAnimeList/Search/Manga.php' );
require_once( __DIR__ . '/src/MyAnimeList/Search/People.php' );

require_once( __DIR__ . '/src/MyAnimeList/Widget/NewAnime.php' );
require_once( __DIR__ . '/src/MyAnimeList/Widget/UpcomingAnime.php' );
require_once( __DIR__ . '/src/MyAnimeList/Widget/AnimeCalendar.php' );