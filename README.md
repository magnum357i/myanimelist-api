![](https://travis-ci.org/magnum357i/myanimelist-api.svg?branch=master)
![](https://img.shields.io/github/last-commit/magnum357i/myanimelist-api.svg)
![](https://img.shields.io/github/license/magnum357i/myanimelist-api.svg)

# myanimelist-api
This is an api developed to get information from pages of anime, manga, character and people on MyAnimelist. It works by scanning the html code of the page requested, so this library crashes when it changes.

# Required
* CURL
* PHP 7

# How to Install?

```bash
$ composer require magnum357i/myanimelist-api
```

# How to use?

### Anime

###### Example
```php
// Create object
$mal = new myanimelist\Types\Anime( 20 );

// Send request
$mal->get();

// Is not 404 page or (cache enabled) cache file exists
if ( $mal->isSuccess() ) {

   echo $mal->title()->original;
   echo $mal->title()->english;
   echo $mal->title()->japanese;
   echo $mal->title()->sysnonmys;
   echo $mal->poster;
   echo $mal->description;
   echo $mal->category;
   echo $mal->status;
   echo $mal->broadcast()->day;
   echo $mal->broadcast()->hour;
   echo $mal->broadcast()->minute;
   echo $mal->statistic()->rank;
   echo $mal->statistic()->member;
   echo $mal->statistic()->memberraw;
   echo $mal->statistic()->popularity;
   echo $mal->statistic()->favorite;
   echo $mal->statistic()->favoriteraw;
   echo $mal->rating;
   echo $mal->score()->vote;
   echo $mal->score()->voteraw;
   echo $mal->score()->point;
   echo $mal->genres;
   echo $mal->source;
   echo $mal->aired()->first()->month;
   echo $mal->aired()->first()->day;
   echo $mal->aired()->first()->year;
   echo $mal->aired()->last()->month;
   echo $mal->aired()->last()->day;
   echo $mal->aired()->last()->year;
   echo $mal->episode;
   echo $mal->studios;
   echo $mal->duration()->minute;
   echo $mal->duration()->hour;
   echo $mal->producers;
   echo $mal->licensors;
   echo $mal->premiered()->season;
   echo $mal->premiered()->year;
   echo $mal->year;
   echo $mal->voice;
   echo $mal->staff;
   echo $mal->related()->adaptation;
   echo $mal->related()->prequel;
   echo $mal->related()->sequel;
   echo $mal->related()->parentstory;
   echo $mal->related()->sidestory;
   echo $mal->related()->spinoff;
   echo $mal->related()->alternativeversion;
   echo $mal->related()->other;
   echo $mal->related()->sequel;
   echo $mal->link;
   echo $mal->trailer;
}
else {

   echo 'No data.';
}
```

###### Content of JSON File

```json
{  
   "time":1545090961,
   "data":{  
      "titleoriginal":"Naruto",
      "titleenglish":"Naruto",
      "titlejapanese":"\u30ca\u30eb\u30c8",
      "titlesysnonmys":"NARUTO",
      "poster":"\/test\/demos\/upload\/cache\/cover\/anime\/20.jpg",
      "description":"Moments prior to Naruto Uzumaki&#039;s birth, a huge demon known as the Kyuubi, the Nine-Tailed Fox, attacked Konohagakure, the Hidden Leaf Village, and wreaked havoc. In order to put an end to the Kyuubi&#039;s rampage, the leader of the village, the Fourth Hokage, sacrificed his life and sealed the monstrous beast inside the newborn Naruto.<br \/>\r\n<br \/>\r\nNow, Naruto is a hyperactive and knuckle-headed ninja still living in Konohagakure. Shunned because of the Kyuubi inside him, Naruto struggles to find his place in the village, while his burning desire to become the Hokage of Konohagakure leads him not only to some great new friends, but also some deadly foes.",
      "category":"TV",
      "status":"Finished Airing",
      "broadcast":{  
         "day":"Thursdays",
         "hour":"19",
         "minute":"30"
      },
      "rank":"#714",
      "member":{  
         "simple":"1068K",
         "full":"1067620"
      },
      "popularity":"#10",
      "favorite":{  
         "simple":"38K",
         "full":"38464"
      },
      "rating":"PG-13",
      "vote":{  
         "simple":"702K",
         "full":"702014"
      },
      "point":"7.8",
      "genres":[  
         {  
            "link":"https:\/\/myanimelist.net\/anime\/genre\/1\/Action",
            "name":"Action"
         },
         {  
            "link":"https:\/\/myanimelist.net\/anime\/genre\/2\/Adventure",
            "name":"Adventure"
         },
         {  
            "link":"https:\/\/myanimelist.net\/anime\/genre\/4\/Comedy",
            "name":"Comedy"
         },
         {  
            "link":"https:\/\/myanimelist.net\/anime\/genre\/31\/Super_Power",
            "name":"Super Power"
         },
         {  
            "link":"https:\/\/myanimelist.net\/anime\/genre\/17\/Martial_Arts",
            "name":"Martial Arts"
         },
         {  
            "link":"https:\/\/myanimelist.net\/anime\/genre\/27\/Shounen",
            "name":"Shounen"
         }
      ],
      "source":"Manga",
      "aired":{  
         "first_month":"Oct",
         "first_day":"3",
         "first_year":"2002",
         "last_month":"Feb",
         "last_day":"8",
         "last_year":"2007"
      },
      "episode":"220",
      "studios":[  
         {  
            "link":"https:\/\/myanimelist.net\/anime\/producer\/1\/Studio_Pierrot",
            "name":"Studio Pierrot"
         }
      ],
      "producers":[  
         {  
            "link":"https:\/\/myanimelist.net\/anime\/producer\/16\/TV_Tokyo",
            "name":"TV Tokyo"
         },
         {  
            "link":"https:\/\/myanimelist.net\/anime\/producer\/17\/Aniplex",
            "name":"Aniplex"
         },
         {  
            "link":"https:\/\/myanimelist.net\/anime\/producer\/1365\/Shueisha",
            "name":"Shueisha"
         }
      ],
      "licensors":[  
         {  
            "link":"https:\/\/myanimelist.net\/anime\/producer\/119\/Viz_Media",
            "name":"Viz Media"
         }
      ],
      "duration":{  
         "hour":0,
         "min":"23"
      },
      "premiered":{  
         "season":"Fall",
         "year":"2002"
      },
      "year":"2002",
      "voice":[  
         {  
            "character_link":"https:\/\/myanimelist.net\/character\/17",
            "character_name":"Naruto Uzumaki",
            "people_link":"https:\/\/myanimelist.net\/people\/15",
            "people_name":"Junko Takeuchi",
            "people_lang":"Japanese"
         },
         {  
            "character_link":"https:\/\/myanimelist.net\/character\/85",
            "character_name":"Kakashi Hatake",
            "people_link":"https:\/\/myanimelist.net\/people\/21",
            "people_name":"Kazuhiko Inoue",
            "people_lang":"Japanese"
         },
         {  
            "character_link":"https:\/\/myanimelist.net\/character\/13",
            "character_name":"Sasuke Uchiha",
            "people_link":"https:\/\/myanimelist.net\/people\/16",
            "people_name":"Noriaki Sugiyama",
            "people_lang":"Japanese"
         },
         {  
            "character_link":"https:\/\/myanimelist.net\/character\/145",
            "character_name":"Sakura Haruno",
            "people_link":"https:\/\/myanimelist.net\/people\/300",
            "people_name":"Chie Nakamura",
            "people_lang":"Japanese"
         },
         {  
            "character_link":"https:\/\/myanimelist.net\/character\/14",
            "character_name":"Itachi Uchiha",
            "people_link":"https:\/\/myanimelist.net\/people\/412",
            "people_name":"Hideo Ishikawa",
            "people_lang":"Japanese"
         }
      ],
      "staff":[  
         {  
            "people_link":"https:\/\/myanimelist.net\/people\/16407",
            "people_name":"Noriko Kobayashi",
            "people_positions_list":[  
               "Producer"
            ]
         },
         {  
            "people_link":"https:\/\/myanimelist.net\/people\/5843",
            "people_name":"Hayato Date",
            "people_positions_list":[  
               "Director",
               "Episode Director",
               "Storyboard"
            ]
         },
         {  
            "people_link":"https:\/\/myanimelist.net\/people\/8110",
            "people_name":"Yasunori Ebina",
            "people_positions_list":[  
               "Sound Director"
            ]
         }
      ],
      "adaptation":[  
         {  
            "link":"https:\/\/myanimelist.net\/manga\/11",
            "title":"Naruto"
         }
      ],
      "sequel":[  
         {  
            "link":"https:\/\/myanimelist.net\/anime\/1735",
            "title":"Naruto: Shippuuden"
         }
      ],
      "sidestory":[  
         {  
            "link":"https:\/\/myanimelist.net\/anime\/442",
            "title":"Naruto Movie 1: Dai Katsugeki!! Yuki Hime Shinobu Houjou Dattebayo!"
         },
         {  
            "link":"https:\/\/myanimelist.net\/anime\/594",
            "title":"Naruto: Takigakure no Shitou - Ore ga Eiyuu Dattebayo!"
         }
      ],
      "trailer":"https:\/\/www.youtube.com\/watch?v=j2hiC9BmJlQ",
      "link":"https:\/\/myanimelist.net\/anime\/20"
   }
}
```

### Manga

###### Example

```php
// Create object
$mal = new myanimelist\Types\Manga( 2 );

// Send request
$mal->get();

// Is not 404 page or (cache enabled) cache file exists
if ( $mal->isSuccess() ) {

   echo $mal->title()->original;
   echo $mal->title()->english;
   echo $mal->title()->japanese;
   echo $mal->poster;
   echo $mal->description;
   echo $mal->type;
   echo $mal->score()->vote;
   echo $mal->score()->voteraw;
   echo $mal->score()->point;
   echo $mal->genres;
   echo $mal->statistic()->rank;
   echo $mal->statistic()->popularity;
   echo $mal->statistic()->member;
   echo $mal->statistic()->memberraw;
   echo $mal->statistic()->favorite;
   echo $mal->statistic()->favoriteraw;
   echo $mal->status;
   echo $mal->published()->first()->month;
   echo $mal->published()->first()->day;
   echo $mal->published()->first()->year;
   echo $mal->published()->last()->month;
   echo $mal->published()->last()->day;
   echo $mal->published()->last()->year;
   echo $mal->authors;
   echo $mal->volume;
   echo $mal->chapter;
   echo $mal->serialization;
   echo $mal->year;
   echo $mal->characters;
   echo $mal->related()->adaptation;
   echo $mal->related()->sequel;
   echo $mal->related()->prequel;
   echo $mal->related()->parentstory;
   echo $mal->related()->sidestory;
   echo $mal->related()->other;
   echo $mal->related()->spinoff;
   echo $mal->related()->alternativeversion;
   echo $mal->link;
}
else {

   echo 'No data.';
}
```

###### Content of JSON File

```json
{  
   "time":1545092025,
   "data":{  
      "titleoriginal":"Berserk",
      "titleenglish":"Berserk",
      "titlejapanese":"\u30d9\u30eb\u30bb\u30eb\u30af",
      "poster":"\/test\/demos\/upload\/cache\/cover\/manga\/2.jpg",
      "description":"Guts, a former mercenary now known as the \"Black Swordsman,\" is out for revenge. After a tumultuous childhood, he finally finds someone he respects and believes he can trust, only to have everything fall apart when this person takes away everything important to Guts for the purpose of fulfilling his own desires. Now marked for death, Guts becomes condemned to a fate in which he is relentlessly pursued by demonic beings.<br \/>\r\n<br \/>\r\nSetting out on a dreadful quest riddled with misfortune, Guts, armed with a massive sword and monstrous strength, will let nothing stop him, not even death itself, until he is finally able to take the head of the one who stripped him\u2014and his loved one\u2014of their humanity.",
      "category":"Manga",
      "vote":{  
         "simple":"100K",
         "full":"100127"
      },
      "point":"9.3",
      "genres":[  
         {  
            "link":"https:\/\/myanimelist.net\/manga\/genre\/1\/Action",
            "name":"Action"
         },
         {  
            "link":"https:\/\/myanimelist.net\/manga\/genre\/2\/Adventure",
            "name":"Adventure"
         },
         {  
            "link":"https:\/\/myanimelist.net\/manga\/genre\/6\/Demons",
            "name":"Demons"
         },
         {  
            "link":"https:\/\/myanimelist.net\/manga\/genre\/8\/Drama",
            "name":"Drama"
         },
         {  
            "link":"https:\/\/myanimelist.net\/manga\/genre\/10\/Fantasy",
            "name":"Fantasy"
         },
         {  
            "link":"https:\/\/myanimelist.net\/manga\/genre\/14\/Horror",
            "name":"Horror"
         },
         {  
            "link":"https:\/\/myanimelist.net\/manga\/genre\/37\/Supernatural",
            "name":"Supernatural"
         },
         {  
            "link":"https:\/\/myanimelist.net\/manga\/genre\/38\/Military",
            "name":"Military"
         },
         {  
            "link":"https:\/\/myanimelist.net\/manga\/genre\/40\/Psychological",
            "name":"Psychological"
         },
         {  
            "link":"https:\/\/myanimelist.net\/manga\/genre\/41\/Seinen",
            "name":"Seinen"
         }
      ],
      "rank":"#1",
      "member":{  
         "simple":"213K",
         "full":"213499"
      },
      "popularity":"#6",
      "favorite":{  
         "simple":"46K",
         "full":"46239"
      },
      "status":"Publishing",
      "published":{  
         "first_month":"Aug",
         "first_day":"25",
         "first_year":"1989",
         "last_month":"no",
         "last_day":"no",
         "last_year":"no"
      },
      "authors":[  
         "Kentarou  Miura (Story & Art)"
      ],
      "serialization":[  
         {  
            "link":"https:\/\/myanimelist.net\/manga\/magazine\/2\/Young_Animal",
            "name":"Young Animal"
         }
      ],
      "year":"1989",
      "characters":[  
         {  
            "character_link":"https:\/\/myanimelist.net\/character\/422",
            "character_name":"Guts",
            "character_role":"Main"
         },
         {  
            "character_link":"https:\/\/myanimelist.net\/character\/424",
            "character_name":"Griffith",
            "character_role":"Main"
         },
         {  
            "character_link":"https:\/\/myanimelist.net\/character\/423",
            "character_name":"Casca",
            "character_role":"Main"
         },
         {  
            "character_link":"https:\/\/myanimelist.net\/character\/7864",
            "character_name":"Schierke",
            "character_role":"Main"
         },
         {  
            "character_link":"https:\/\/myanimelist.net\/character\/5060",
            "character_name":"Puck",
            "character_role":"Main"
         }
      ],
      "adaptation":[  
         {  
            "link":"https:\/\/myanimelist.net\/anime\/12113",
            "title":"Berserk: Ougon Jidai-hen II - Doldrey Kouryaku"
         },
         {  
            "link":"https:\/\/myanimelist.net\/anime\/10218",
            "title":"Berserk: Ougon Jidai-hen I - Haou no Tamago"
         }
      ],
      "other":[  
         {  
            "link":"https:\/\/myanimelist.net\/manga\/92299",
            "title":"Berserk: Shinen no Kami 2"
         },
         {  
            "link":"https:\/\/myanimelist.net\/manga\/106677",
            "title":"Berserk: Honou Ryuu no Kishi"
         }
      ],
      "link":"https:\/\/myanimelist.net\/manga\/2"
   }
}
```

### Character

###### Example

```php
// Create object
$mal = new myanimelist\Types\Character( 40 );

// Send request
$mal->get();

// Is not 404 page or (cache enabled) cache file exists
if ( $mal->isSuccess() ) {

   echo $mal->title()->self;
   echo $mal->title()->nickname;
   echo $mal->poster;
   echo $mal->description;
   echo $mal->statistic()->favorite;
   echo $mal->statistic()->favoriteraw;
   echo $mal->recentanime;
   echo $mal->recentmanga;
   echo $mal->voiceactors;
}
else {

   echo 'No data.';
}
```

###### Content of JSON File
```json
{  
   "time":1545092569,
   "data":{  
      "charactername":"Monkey D. Luffy ",
      "nickname":"Lucy Mugiwara, Straw Hat",
      "poster":"\/test\/demos\/upload\/cache\/cover\/character\/40.jpg",
      "description":"Luffy is the captain of the Straw Hat Pirates and is best friends with all of them and values them over all else. At first glance, Luffy does not appear to be very intelligent, often seeing things in a childish manner and can easily be amazed by the simplest things. However, because he views the world in a straightforward and simple manner, he is occasionally the only person who can see past the events and see what should be done.<br \/>\r\n<br \/>\r\nLuffy seems to have an unstoppable appetite, a characteristic that is common to the Japanese archetype of the (at times simple-minded) young male hero\/adventurer with a heart of gold; perhaps the hunger more so in Luffy&#039;s case due to having an elastic stomach. Luffy is also another one of the several characters given the middle initial \"D.\"<br \/>\r\n<br \/>\r\nAlthough Luffy is fairly lightheaded and a funny character, he has an unstoppable sense of determination, optimism, and commitment and will do anything to stand up for his friends and comrades. Along with that, he has great courage to back it up as well as unbelievable strength. Ever since consuming the devil fruit he was shown to be not worried about his inability to swim, much like his brother. Much of these traits are common among D&#039;s. His only display of true fear is towards his grandfather, to the point that he is intimidated at the mere mention of him.<br \/>\r\n<br \/>\r\nLuffy never kills any of his enemies, no matter how cold-hearted they are; instead, he frequently sends the villain flying, knocking them out or beating them to a point that they are almost near death, which results in some of the villains searching for revenge, such as Buggy the Clown and Alvida. Oda explains that it&#039;s not a question of morality so much as a matter of punishing the villains for their crimes - he feels that killing the villains lets them off too lightly, whereas he considers letting them live to see their dreams be ruined a far more fitting punishment.<br \/>\r\n<br \/>\r\nLuffy&#039;s dream is to find the One Piece and become Pirate King. He knows that to achieve his goal, he will have to defeat many strong opponents, including the World Government and his childhood hero Shanks.<br \/>\r\n<br \/>\r\n\n<br \/>\r\nBefore the start of the series, Dragon left Luffy in Garp&#039;s care. Dragon first appears after a lightning bolt suddenly destroys the gallows where Luffy was to be executed by Buggy the Clown and then saves Luffy from being captured by Captain Smoker. When he saved Luffy from Smoker, a mighty gust of eerie wind swept through Loguetown. It is undetermined whether or not he was the cause of the gust of wind or the lightning; however, the story leads to believe there is more to him than it seems. Recently, he has taken over an island in the South Blue and is about to head to the North Blue when he commented, after noticing Luffy&#039;s newest bounty, given to him after invading Enies Lobby, that father and son will meet sometime soon.<\/span>",
      "category":"character",
      "favorite":{  
         "simple":"55K",
         "full":"54583"
      },
      "recentanime":[  
         {  
            "link":"https:\/\/myanimelist.net\/anime\/37902",
            "title":"One Piece: Episode of Sorajima",
            "role":"Main"
         },
         {  
            "link":"https:\/\/myanimelist.net\/anime\/36215",
            "title":"One Piece: Episode of East Blue - Luffy to 4-nin no Nakama no Daibouken",
            "role":"Main"
         },
         {  
            "link":"https:\/\/myanimelist.net\/anime\/33606",
            "title":"One Piece Film: Gold Episode 0 - 711 ver.",
            "role":"Main"
         },
         {  
            "link":"https:\/\/myanimelist.net\/anime\/33338",
            "title":"One Piece: Heart of Gold",
            "role":"Main"
         },
         {  
            "link":"https:\/\/myanimelist.net\/anime\/32051",
            "title":"One Piece: Adventure of Nebulandia",
            "role":"Main"
         },
         {  
            "link":"https:\/\/myanimelist.net\/anime\/31490",
            "title":"One Piece Film: Gold",
            "role":"Main"
         },
         {  
            "link":"https:\/\/myanimelist.net\/anime\/31289",
            "title":"One Piece: Episode of Sabo - 3 Kyoudai no Kizuna Kiseki no Saikai to Uketsugareru Ishi",
            "role":"Main"
         },
         {  
            "link":"https:\/\/myanimelist.net\/anime\/28683",
            "title":"One Piece: Episode of Alabasta - Prologue",
            "role":"Main"
         },
         {  
            "link":"https:\/\/myanimelist.net\/anime\/25161",
            "title":"One Piece 3D2Y: Ace no shi wo Koete! Luffy Nakama Tono Chikai",
            "role":"Main"
         },
         {  
            "link":"https:\/\/myanimelist.net\/anime\/23935",
            "title":"Kyutai Panic Adventure Returns!",
            "role":"Main"
         }
      ],
      "recentmanga":[  
         {  
            "link":"https:\/\/myanimelist.net\/manga\/94534",
            "title":"One Piece: Loguetown-hen",
            "role":"Main"
         },
         {  
            "link":"https:\/\/myanimelist.net\/manga\/94533",
            "title":"One Piece: Taose! Kaizoku Ganzack",
            "role":"Main"
         },
         {  
            "link":"https:\/\/myanimelist.net\/manga\/86972",
            "title":"One Piece Party",
            "role":"Main"
         },
         {  
            "link":"https:\/\/myanimelist.net\/manga\/25146",
            "title":"One Piece x Toriko",
            "role":"Main"
         },
         {  
            "link":"https:\/\/myanimelist.net\/manga\/14414",
            "title":"One Piece Log Book Omake",
            "role":"Main"
         },
         {  
            "link":"https:\/\/myanimelist.net\/manga\/11926",
            "title":"Jump Super Stars",
            "role":"Main"
         },
         {  
            "link":"https:\/\/myanimelist.net\/manga\/5114",
            "title":"Romance Dawn",
            "role":"Main"
         },
         {  
            "link":"https:\/\/myanimelist.net\/manga\/881",
            "title":"Cross Epoch",
            "role":"Main"
         },
         {  
            "link":"https:\/\/myanimelist.net\/manga\/793",
            "title":"Wanted!",
            "role":"Main"
         },
         {  
            "link":"https:\/\/myanimelist.net\/manga\/13",
            "title":"One Piece",
            "role":"Main"
         }
      ],
      "voiceactors":{  
         "0":{  
            "people_link":"https:\/\/myanimelist.net\/people\/75",
            "people_name":"Mayumi Tanaka",
            "people_lang":"Japanese"
         },
         "2":{  
            "people_link":"https:\/\/myanimelist.net\/people\/622",
            "people_name":"Urara Takano",
            "people_lang":"Japanese"
         }
      },
      "link":"https:\/\/myanimelist.net\/character\/40"
   }
}
```

### People

###### Example

```php
// Create object
$mal = new myanimelist\Types\Manga( 80 );

// Send request
$mal->get();

// Is not 404 page or (cache enabled) cache file exists
if ( $mal->isSuccess() ) {

   echo $mal->name;
   echo $mal->poster;
   echo $mal->description;
   echo $mal->statistic()->favorite;
   echo $mal->statistic()->favoriteraw;
   echo $mal->recentvoice;
   echo $mal->recentwork;
   echo $mal->link;
}
else {

   echo 'No data.';
}
```

###### Content of JSON File
```json
{  
   "time":1545092628,
   "data":{  
      "name":"Aya Hisakawa",
      "poster":"\/test\/demos\/upload\/cache\/cover\/people\/80.jpg",
      "category":"people",
      "favorite":{  
         "simple":"445",
         "full":"445"
      },
      "recentwork":[  
         {  
            "link":"https:\/\/myanimelist.net\/anime\/2937",
            "title":"Bishoujo Senshi Sailor Moon R: Make Up! Sailor Senshi",
            "work":"Theme Song Performance"
         },
         {  
            "link":"https:\/\/myanimelist.net\/anime\/2737",
            "title":"Kouryuu no Mimi: Mina no Shou",
            "work":"Theme Song Performance"
         },
         {  
            "link":"https:\/\/myanimelist.net\/anime\/2453",
            "title":"Dennou Sentai Voogie&#039;s\u2605Angel",
            "work":"Theme Song Performance"
         },
         {  
            "link":"https:\/\/myanimelist.net\/anime\/2072",
            "title":"Idol Project",
            "work":"Theme Song Performance"
         },
         {  
            "link":"https:\/\/myanimelist.net\/anime\/1436",
            "title":"Lupin III: Twilight Gemini no Himitsu",
            "work":"Theme Song Performance"
         },
         {  
            "link":"https:\/\/myanimelist.net\/anime\/1280",
            "title":"Mamono Hunter Youko",
            "work":"Theme Song Performance"
         },
         {  
            "link":"https:\/\/myanimelist.net\/anime\/1280",
            "title":"Mamono Hunter Youko",
            "work":"Theme Song Lyrics"
         },
         {  
            "link":"https:\/\/myanimelist.net\/anime\/1233",
            "title":"Aozora Shoujo-tai",
            "work":"Theme Song Performance"
         },
         {  
            "link":"https:\/\/myanimelist.net\/anime\/1133",
            "title":"Tenchi Muyou! GXP",
            "work":"Theme Song Performance"
         },
         {  
            "link":"https:\/\/myanimelist.net\/anime\/1078",
            "title":"Cardcaptor Sakura: Kero-chan ni Omakase!",
            "work":"Theme Song Lyrics"
         }
      ],
      "recentvoice":[  
         {  
            "anime_link":"https:\/\/myanimelist.net\/anime\/37988",
            "anime_title":"Uchuu Senkan Tiramis\u00f9 II",
            "character_link":"https:\/\/myanimelist.net\/character\/165082",
            "character_name":"Leblanc Spyri"
         },
         {  
            "anime_link":"https:\/\/myanimelist.net\/anime\/37595",
            "anime_title":"Cardcaptor Sakura: Clear Card-hen Recap",
            "character_link":"https:\/\/myanimelist.net\/character\/2897",
            "character_name":"Keroberos"
         },
         {  
            "anime_link":"https:\/\/myanimelist.net\/anime\/36946",
            "anime_title":"Dragon Ball Super Movie: Broly",
            "character_link":"https:\/\/myanimelist.net\/character\/678",
            "character_name":"Bulma"
         },
         {  
            "anime_link":"https:\/\/myanimelist.net\/anime\/36946",
            "anime_title":"Dragon Ball Super Movie: Broly",
            "character_link":"https:\/\/myanimelist.net\/character\/2733",
            "character_name":"Bra"
         },
         {  
            "anime_link":"https:\/\/myanimelist.net\/anime\/36902",
            "anime_title":"Mahou Shoujo Ore",
            "character_link":"https:\/\/myanimelist.net\/character\/157925",
            "character_name":"Sayori Uno"
         },
         {  
            "anime_link":"https:\/\/myanimelist.net\/anime\/36298",
            "anime_title":"Robot Girls Z: Fukkatsu no Chika Teikoku!? Robot Girls Z vs. Nazo no Sandai Shuyaku Robo!",
            "character_link":"https:\/\/myanimelist.net\/character\/95599",
            "character_name":"Baron Ashura"
         },
         {  
            "anime_link":"https:\/\/myanimelist.net\/anime\/35320",
            "anime_title":"Cardcaptor Sakura: Clear Card-hen Prologue - Sakura to Futatsu no Kuma",
            "character_link":"https:\/\/myanimelist.net\/character\/2897",
            "character_name":"Keroberos"
         },
         {  
            "anime_link":"https:\/\/myanimelist.net\/anime\/35134",
            "anime_title":"Koukyoushihen Eureka Seven Hi-Evolution 1",
            "character_link":"https:\/\/myanimelist.net\/character\/6718",
            "character_name":"Ray Beams"
         },
         {  
            "anime_link":"https:\/\/myanimelist.net\/anime\/35062",
            "anime_title":"Mahoutsukai no Yome",
            "character_link":"https:\/\/myanimelist.net\/character\/154696",
            "character_name":"Akiko Hatori"
         },
         {  
            "anime_link":"https:\/\/myanimelist.net\/anime\/34712",
            "anime_title":"Kujira no Kora wa Sajou ni Utau",
            "character_link":"https:\/\/myanimelist.net\/character\/149269",
            "character_name":"Taisha"
         }
      ],
      "link":"https:\/\/myanimelist.net\/people\/80"
   }
}
```

# Configuration

### Reverse Names

If true, the name order is reversed firstname-lastname instead of lastname-firstname.

```php
// Create object
$mal = new myanimelist\Types\Manga( 1 );

// Conf
$mal->config()->reverseName = TRUE;

// Send request
$mal->get();

// Test
echo $mal->authors;

// Output
// reverse name option is true: Naoki Urasawa
// reverse name option is false: Urasawa, Naoki
```

### Enable Cache

If true, the cache system enabled.

```php
// Create object
$mal = new myanimelist\Types\Anime( 1 );

// Conf
$mal->config()->cache = TRUE;

$mal->cache()->expiredByDay       = 5; // default is 2
$mal->cache()->root               = __DIR__; // the path of the file of cache class
$mal->cache()->dir                = 'upload'; // default is upload
$mal->cache()->file[ 'name' ]     = 'myjson_id1'; // default is id to request
$mal->cache()->file[ 'ext' ]      = 'json'; // default is json
$mal->cache()->image[ 'name' ]    = 'myimage_id1'; // default is id to request
$mal->cache()->image[ 'ext' ]     = 'jpg'; // default is jpg
$mal->cache()->folders[ 'main' ]  = 'myanimelist'; // default is cache - folder where folders of json and image will be saved
$mal->cache()->folders[ 'file' ]  = 'files'; // default is json - folder where json files will be saved
$mal->cache()->folders[ 'image' ] = 'images'; // default is cover folder where images will be saved

## Folder Hierarchy: [myanimelist] / [files], [images]
## Just change the root and the dir.

// Send request
$mal->get();

// Test
echo $mal->title()->english;
echo $mal->poster;

// Note
// Backs up the values you use.
// So, based on the above values, this will be create a file named myjson_id1.json
// and inside of the file writes the english title of the anime.
// Also a poster named myimage_id1.jpg is saved.
// After all, it will return false even if you call another value until the cache expires.
// Please don't forget this.
```

### cURL Settings

```php
// Create object
$mal = new myanimelist\Types\People( 1 );

// Conf
$mal->config()->curl[ 'returnTransfer' ] = TRUE; // default is true
$mal->config()->curl[ 'header' ]         = FALSE; // default is false
$mal->config()->curl[ 'userAgent' ]      = 'Mozilla/5.0 (Windows NT 5.1; rv:31.0) Gecko/20100101 Firefox/31.0'; // default is 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.110 Safari/537.36'
$mal->config()->curl[ 'followLocation' ] = TRUE; // default is false
$mal->config()->curl[ 'connectTimeout' ] = 30; // default is 15
$mal->config()->curl[ 'timeout' ]        = 150; // default is 60
$mal->config()->curl[ 'ssl_verifyHost' ] = TRUE; // default is false
$mal->config()->curl[ 'ssl_verifypeer' ] = TRUE; // default is false

## Default settings are recommended.

// Send request
$mal->get();
```
