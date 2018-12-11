# myanimelist-api
This is an api developed to get information from anime, manga, character and people pages of MyAnimelist.

### Required
* CURL
* PHP 7

### Anime Variables

```php
$mal->title()->original;
$mal->title()->english;
$mal->title()->japanese;
$mal->title()->sysnonmys;
$mal->poster;
$mal->description;
$mal->category;
$mal->status;
$mal->broadcast()->day;
$mal->broadcast()->hour;
$mal->broadcast()->minute;
$mal->members;
$mal->popularity;
$mal->favorites;
$mal->rating;
$mal->rank;
$mal->vote;
$mal->point;
$mal->genres;
$mal->source;
$mal->firstepisode()->month;
$mal->firstepisode()->day;
$mal->firstepisode()->year;
$mal->lastepisode()->month;
$mal->lastepisode()->day;
$mal->lastepisode()->year;
$mal->episode;
$mal->studios;
$mal->duration;
$mal->premiered()->season;
$mal->premiered()->year;
$mal->year;
$mal->voice;
$mal->staff;
$mal->related()->adaptation;
$mal->related()->prequel;
$mal->related()->sequel;
$mal->related()->parentstory;
$mal->related()->sidestory;
$mal->related()->spinoff;
$mal->related()->alternativeversion;
$mal->related()->other;
$mal->related()->sequel;
$mal->link;
$mal->trailer;
```

###### Content of JSON File
```json

Array
(
    ["titleoriginal"] => Naruto
    ["titleenglish"] => Naruto
    ["titlejapanese"] => ナルト
    ["titlesysnonmys"] => NARUTO
    ["poster"] => https://myanimelist.cdn-dena.com/images/anime/13/17405.jpg
    ["description"] => Moments prior to Naruto Uzumaki&amp;#039;s birth, a huge demon known as the Kyuubi, the Nine-Tailed Fox, attacked Konohagakure, the Hidden Leaf Village, and wreaked havoc. In order to put an end to the Kyuubi&amp;#039;s rampage, the leader of the village, the Fourth Hokage, sacrificed his life and sealed the monstrous beast inside the newborn Naruto.&lt;br /&gt;
&lt;br /&gt;
Now, Naruto is a hyperactive and knuckle-headed ninja still living in Konohagakure. Shunned because of the Kyuubi inside him, Naruto struggles to find his place in the village, while his burning desire to become the Hokage of Konohagakure leads him not only to some great new friends, but also some deadly foes.
    ["type"] => TV
    ["status"] => Finished Airing
    ["broadcast"] => Array
        (
            ["day"] => Thursdays
            ["hour"] => 19
            ["minute"] => 30
        )

    ["members"] => 922K
    ["popularity"] => #10
    ["favorites"] => 33K
    ["rating"] => PG-13
    ["rank"] => #770
    ["vote"] => 612K
    ["point"] => 7.86
    ["genres"] => Array
        (
            ["0"] => Action
            ["1"] => Comedy
            ["2"] => Super Power
            ["3"] => Martial Arts
            ["4"] => Shounen
        )

    ["source"] => Manga
    ["firstepisode"] => Array
        (
            ["month"] => Oct
            ["day"] => 3
            ["year"] => 2002
        )

    ["lastepisode"] => Array
        (
            ["month"] => Feb
            ["day"] => 8
            ["year"] => 2007
        )

    ["episode"] => 220
    ["studios"] => Array
        (
            ["0"] => Studio Pierrot
        )

    ["duration"] => Array
        (
            ["hour"] => 0
            ["min"] => 23
        )

    ["premiered"] => Array
        (
            ["season"] => Fall
            ["year"] => 2002
        )

    ["year"] => 2002
    ["voice"] => Array
        (
            ["0"] => Array
                (
                    ["character_link"] => https://myanimelist.net/character/17
                    ["character_name"] => Naruto Uzumaki
                    ["people_link"] => https://myanimelist.net/people/15
                    ["people_name"] => Junko Takeuchi
                    ["people_lang"] => Japanese
                )

            ["1"] => Array
                (
                    ["character_link"] => https://myanimelist.net/character/85
                    ["character_name"] => Kakashi Hatake
                    ["people_link"] => https://myanimelist.net/people/21
                    ["people_name"] => Kazuhiko Inoue
                    ["people_lang"] => Japanese
                )

            ["2"] => Array
                (
                    ["character_link"] => https://myanimelist.net/character/13
                    ["character_name"] => Sasuke Uchiha
                    ["people_link"] => https://myanimelist.net/people/16
                    ["people_name"] => Noriaki Sugiyama
                    ["people_lang"] => Japanese
                )

        )

    ["staff"] => Array
        (
            ["0"] => Array
                (
                    ["people_link"] => https://myanimelist.net/people/16407
                    ["people_name"] => Noriko Kobayashi
                    ["people_positions_list"] => Array
                        (
                            ["0"] => Producer
                        )

                )

            ["1"] => Array
                (
                    ["people_link"] => https://myanimelist.net/people/5843
                    ["people_name"] => Hayato Date
                    ["people_positions_list"] => Array
                        (
                            ["0"] => Director
                            ["1"] => Episode Director
                            ["2"] => Storyboard
                        )

                )

            ["2"] => Array
                (
                    ["people_link"] => https://myanimelist.net/people/8110
                    ["people_name"] => Yasunori Ebina
                    ["people_positions_list"] => Array
                        (
                            ["0"] => Sound Director
                        )

                )

        )

    ["adaptation"] => Array
        (
            ["0"] => Array
                (
                    ["link"] => https://myanimelist.net/manga/11
                    ["title"] => Naruto
                )

        )

    ["sequel"] => Array
        (
            ["0"] => Array
                (
                    ["link"] => https://myanimelist.net/anime/1735
                    ["title"] => Naruto: Shippuuden
                )

        )

    ["sidestory"] => Array
        (
            ["0"] => Array
                (
                    ["link"] => https://myanimelist.net/anime/442
                    ["title"] => Naruto Movie 1: Dai Katsugeki!! Yuki Hime Shinobu Houjou Dattebayo!
                )

            ["1"] => Array
                (
                    ["link"] => https://myanimelist.net/anime/594
                    ["title"] => Naruto: Takigakure no Shitou - Ore ga Eiyuu Dattebayo!
                )

            ["2"] => Array
                (
                    ["link"] => https://myanimelist.net/anime/761
                    ["title"] => Naruto: Akaki Yotsuba no Clover wo Sagase
                )

            ["3"] => Array
                (
                    ["link"] => https://myanimelist.net/anime/936
                    ["title"] => Naruto Movie 2: Dai Gekitotsu! Maboroshi no Chiteiiseki Dattebayo!
                )

            ["4"] => Array
                (
                    ["link"] => https://myanimelist.net/anime/1074
                    ["title"] => Naruto Narutimate Hero 3: Tsuini Gekitotsu! Jounin vs. Genin!! Musabetsu Dairansen taikai Kaisai!!
                )

            ["5"] => Array
                (
                    ["link"] => https://myanimelist.net/anime/2144
                    ["title"] => Naruto Movie 3: Dai Koufun! Mikazuki Jima no Animaru Panikku Dattebayo!
                )

            ["6"] => Array
                (
                    ["link"] => https://myanimelist.net/anime/7367
                    ["title"] => Naruto: The Cross Roads
                )

        )

    ["link"] => https://myanimelist.net/anime/20
    ["trailer"] => https://www.youtube.com/watch?v=j2hiC9BmJlQ
)
```

### Manga Variables

```php
$mal->title()->original;
$mal->title()->english;
$mal->title()->japanese;
$mal->poster;
$mal->description;
$mal->type;
$mal->rank;
$mal->vote;
$mal->point;
$mal->genres;
$mal->popularity;
$mal->members;
$mal->favorites;
$mal->status;
$mal->published()->first()->month;
$mal->published()->first()->day;
$mal->published()->first()->year;
$mal->published()->last()->month;
$mal->published()->last()->day;
$mal->published()->last()->year;
$mal->authors;
$mal->volume;
$mal->chapter;
$mal->serialization;
$mal->chapterdate()->first()->month;
$mal->chapterdate()->first()->day;
$mal->chapterdate()->first()->year;
$mal->chapterdate()->last()->month;
$mal->chapterdate()->last()->day;
$mal->chapterdate()->last()->year;
$mal->year;
$mal->characters;
$mal->related()->adaptation;
$mal->related()->sequel;
$mal->related()->prequel;
$mal->related()->parentstory;
$mal->related()->sidestory;
$mal->related()->other;
$mal->related()->spinoff;
$mal->related()->alternativeversion;
$mal->link;
```

###### Content of JSON File
```json
Array
(
    ["titleoriginal"] => Berserk
    ["titleenglish"] => Berserk
    ["titlejapanese"] => ベルセルク
    ["poster"] => https://myanimelist.cdn-dena.com/images/manga/1/157931.jpg
    ["description"] => Guts, a former mercenary now known as the &quot;Black Swordsman,&quot; is out for revenge. After a tumultuous childhood, he finally finds someone he respects and believes he can trust, only to have everything fall apart when this person takes away everything important to Guts for the purpose of fulfilling his own desires. Now marked for death, Guts becomes condemned to a fate in which he is relentlessly pursued by demonic beings.&lt;br /&gt;
&lt;br /&gt;
Setting out on a dreadful quest riddled with misfortune, Guts, armed with a massive sword and monstrous strength, will let nothing stop him, not even death itself, until he is finally able to take the head of the one who stripped him&mdash;and his loved one&mdash;of their humanity.
    ["type"] => Manga
    ["rank"] => #1
    ["vote"] => 85K
    ["point"] => 9.3
    ["genres"] => Array
        (
            ["0"] => Action
            ["1"] => Adventure
            ["2"] => Demons
            ["3"] => Drama
            ["4"] => Fantasy
            ["5"] => Horror
            ["6"] => Supernatural
            ["7"] => Military
            ["8"] => Psychological
            ["9"] => Seinen
        )

    ["popularity"] => #7
    ["members"] => 179K
    ["favorites"] => 39
    ["status"] => Publishing
    ["published"] => Array
        (
            ["first_month"] => Aug
            ["first_day"] => 25
            ["first_year"] => 1989
            ["last"] => no
        )

    ["authors"] => Array
        (
            ["0"] => Kentarou  Miura (Story &amp; Art)
        )

    ["serialization"] => Young Animal
    ["firstchapter"] => Array
        (
            ["month"] => Aug
            ["day"] => 25
            ["year"] => 1989
        )

    ["year"] => 1989
    ["characters"] => Array
        (
            ["0"] => Array
                (
                    ["character_link"] => https://myanimelist.net/character/422
                    ["character_name"] => Guts
                    ["character_role"] => Main
                )

            ["1"] => Array
                (
                    ["character_link"] => https://myanimelist.net/character/424
                    ["character_name"] => Griffith
                    ["character_role"] => Main
                )

            ["2"] => Array
                (
                    ["character_link"] => https://myanimelist.net/character/423
                    ["character_name"] => Casca
                    ["character_role"] => Main
                )

            ["3"] => Array
                (
                    ["character_link"] => https://myanimelist.net/character/7864
                    ["character_name"] => Schierke
                    ["character_role"] => Main
                )

            ["4"] => Array
                (
                    ["character_link"] => https://myanimelist.net/character/5060
                    ["character_name"] => Puck
                    ["character_role"] => Main
                )

            ["5"] => Array
                (
                    ["character_link"] => https://myanimelist.net/character/5059
                    ["character_name"] => Serpico
                    ["character_role"] => Main
                )

            ["6"] => Array
                (
                    ["character_link"] => https://myanimelist.net/character/7857
                    ["character_name"] => Farnese de Vandimion
                    ["character_role"] => Main
                )

        )

    ["adaptation"] => Array
        (
            ["0"] => Array
                (
                    ["link"] => https://myanimelist.net/anime/12113
                    ["title"] => Berserk: Ougon Jidai-hen II - Doldrey Kouryaku
                )

            ["1"] => Array
                (
                    ["link"] => https://myanimelist.net/anime/10218
                    ["title"] => Berserk: Ougon Jidai-hen I - Haou no Tamago
                )

            ["2"] => Array
                (
                    ["link"] => https://myanimelist.net/anime/12115
                    ["title"] => Berserk: Ougon Jidai-hen III - Kourin
                )

            ["3"] => Array
                (
                    ["link"] => https://myanimelist.net/anime/33
                    ["title"] => Kenpuu Denki Berserk
                )

            ["4"] => Array
                (
                    ["link"] => https://myanimelist.net/anime/32379
                    ["title"] => Berserk
                )

        )

    ["other"] => Array
        (
            ["0"] => Array
                (
                    ["link"] => https://myanimelist.net/manga/92299
                    ["title"] => Berserk: Shinen no Kami 2
                )

            ["1"] => Array
                (
                    ["link"] => https://myanimelist.net/manga/101515
                    ["title"] => Challenging the Manga Dojos
                )

            ["2"] => Array
                (
                    ["link"] => https://myanimelist.net/manga/106677
                    ["title"] => Berserk: Honou Ryuu no Kishi
                )

        )

    ["link"] => https://myanimelist.net/manga/2
)
```

### Character Variables

```php
$mal->title()->self;
$mal->title()->nickname;
$mal->poster;
$mal->description;
$mal->favorites;
$mal->recentanime;
$mal->recentmanga;
$mal->voiceactors;
```

###### Content of JSON File
```json
Array
(
    ["charactername"] => Monkey D. Luffy 
    ["nickname"] => Lucy Mugiwara, Straw Hat
    ["poster"] => https://myanimelist.cdn-dena.com/images/characters/9/310307.jpg
    ["description"] => Luffy is the captain of the Straw Hat Pirates and is best friends with all of them and values them over all else. At first glance, Luffy does not appear to be very intelligent, often seeing things in childish manner and can easily be amazed by the simplest things. However, because he views the world in a straightforward and simple manner, he is occasionally the only person who can see past the events and see what should be done. &lt;br /&gt;
&lt;br /&gt;
Luffy seems to have an unstoppable appetite, a characteristic that is common to the Japanese archetype of the (at times simple-minded) young male hero/adventurer with a heart of gold; perhaps the hunger more so in Luffy&amp;#039;s case due to having an elastic stomach. Luffy is also another one of the several characters given the middle initial &quot;D.&quot;&lt;br /&gt;
&lt;br /&gt;
Although Luffy is fairly lightheaded and a funny character, he has an unstoppable sense of determination, optimism and commitment and will do anything to stand up for his friends and comrades. Along with that, he has great courage to back it up as well as unbelievable strength. Ever since consuming the devil fruit he was shown to be not worried about his inability to swim, much like his brother. Much of these traits are common among D&amp;#039;s. His only display of true fear is towards his grandfather, to the point that he is intimidated at the mere mention of him.&lt;br /&gt;
&lt;br /&gt;
Luffy never kills any of his enemies, no matter how cold-hearted they are; instead, he frequently sends the villain flying, knocking them out or beating them to a point that they are almost near death, which results in some of the villains searching for revenge, such as Buggy the Clown and Alvida. Oda explains that it&amp;#039;s not a question of morality so much as a matter of punishing the villains for their crimes - he feels that killing the villains lets them off too lightly, whereas he considers letting them live to see their dreams be ruined a far more fitting punishment.&lt;br /&gt;
&lt;br /&gt;
Luffy&amp;#039;s dream is to find the One Piece and become Pirate King. He knows that to achieve his goal, he will have to defeat many strong opponents, including the World Government and his childhood hero Shanks.&lt;br /&gt;
&lt;br /&gt;

&lt;br /&gt;
Before the start of the series, Dragon left Luffy in Garp&amp;#039;s care. Dragon first appears after a lightning bolt suddenly destroys the gallows where Luffy was to be executed by Buggy the Clown and then saves Luffy from being captured by Captain Smoker. When he saved Luffy from Smoker, a mighty gust of eerie wind swept through Loguetown. It is undetermined whether or not he was the cause behind the gust of wind or the lightning; however, the story leads to believe there is more to him than it seems. Recently, he has taken over an island in the South Blue and is about to head to the North Blue when he commented, after noticing Luffy&amp;#039;s newest bounty, given to him after invading Enies Lobby, that father and son will meet sometime soon.&lt;/span&gt;
    ["favorites"] => 46K
    ["recentanime"] => Array
        (
            ["0"] => Array
                (
                    ["link"] => https://myanimelist.net/anime/36215
                    ["title"] => One Piece: Episode of East Blue - Luffy to 4-nin no Nakama no Daibouken
                    ["role"] => Main
                )

            ["1"] => Array
                (
                    ["link"] => https://myanimelist.net/anime/33606
                    ["title"] => One Piece Film: Gold Episode 0 - 711 ver.
                    ["role"] => Main
                )

            ["2"] => Array
                (
                    ["link"] => https://myanimelist.net/anime/33338
                    ["title"] => One Piece: Heart of Gold
                    ["role"] => Main
                )

            ["3"] => Array
                (
                    ["link"] => https://myanimelist.net/anime/32051
                    ["title"] => One Piece: Adventure of Nebulandia
                    ["role"] => Main
                )

            ["4"] => Array
                (
                    ["link"] => https://myanimelist.net/anime/31490
                    ["title"] => One Piece Film: Gold
                    ["role"] => Main
                )

        )

    ["recentmanga"] => Array
        (
            ["0"] => Array
                (
                    ["link"] => https://myanimelist.net/manga/94534
                    ["title"] => One Piece: Loguetown-hen
                    ["role"] => Main
                )

            ["1"] => Array
                (
                    ["link"] => https://myanimelist.net/manga/94533
                    ["title"] => One Piece: Taose! Kaizoku Ganzack
                    ["role"] => Main
                )

            ["2"] => Array
                (
                    ["link"] => https://myanimelist.net/manga/86972
                    ["title"] => One Piece Party
                    ["role"] => Main
                )

            ["3"] => Array
                (
                    ["link"] => https://myanimelist.net/manga/25146
                    ["title"] => One Piece x Toriko
                    ["role"] => Main
                )

            ["4"] => Array
                (
                    ["link"] => https://myanimelist.net/manga/14414
                    ["title"] => One Piece Log Book Omake
                    ["role"] => Main
                )

        )

    ["voiceactors"] => Array
        (
            ["0"] => Array
                (
                    ["people_link"] => https://myanimelist.net/people/75
                    ["people_name"] => Mayumi Tanaka
                    ["people_lang"] => Japanese
                )

        )

)
```

### People Variables

```php
$mal->name;
$mal->poster;
$mal->description;
$mal->favorites;
$mal->recentvoice;
$mal->recentwork;
$mal->link;
```

###### Content of JSON File
```json
Array
(
    ["name"] => Aya Hisakawa
    ["poster"] => https://myanimelist.cdn-dena.com/images/voiceactors/2/30717.jpg
    ["favorites"] => 422
    ["recentvoice"] => Array
        (
            ["0"] => Array
                (
                    ["anime_link"] => https://myanimelist.net/anime/36902
                    ["anime_title"] => Mahou Shoujo Ore
                    ["character_link"] => https://myanimelist.net/character/157925
                    ["character_name"] => Sayori Uno
                )

            ["1"] => Array
                (
                    ["anime_link"] => https://myanimelist.net/anime/35320
                    ["anime_title"] => Cardcaptor Sakura: Clear Card-hen Prologue - Sakura to Futatsu no Kuma
                    ["character_link"] => https://myanimelist.net/character/2897
                    ["character_name"] => Keroberos
                )

            ["2"] => Array
                (
                    ["anime_link"] => https://myanimelist.net/anime/35134
                    ["anime_title"] => Koukyoushihen: Eureka Seven - Hi-Evolution 1
                    ["character_link"] => https://myanimelist.net/character/6718
                    ["character_name"] => Ray Beams
                )

            ["3"] => Array
                (
                    ["anime_link"] => https://myanimelist.net/anime/35062
                    ["anime_title"] => Mahoutsukai no Yome
                    ["character_link"] => https://myanimelist.net/character/154696
                    ["character_name"] => Akiko Hatori
                )

            ["4"] => Array
                (
                    ["anime_link"] => https://myanimelist.net/anime/34712
                    ["anime_title"] => Kujira no Kora wa Sajou ni Utau
                    ["character_link"] => https://myanimelist.net/character/149269
                    ["character_name"] => Taisha
                )

        )

    ["recentwork"] => Array
        (
            ["0"] => Array
                (
                    ["link"] => https://myanimelist.net/anime/2937
                    ["title"] => Bishoujo Senshi Sailor Moon R: Make Up! Sailor Senshi
                    ["work"] => Theme Song Performance
                )

            ["1"] => Array
                (
                    ["link"] => https://myanimelist.net/anime/2737
                    ["title"] => Kouryuu no Mimi: Mina no Shou
                    ["work"] => Theme Song Performance
                )

            ["2"] => Array
                (
                    ["link"] => https://myanimelist.net/anime/2453
                    ["title"] => Dennou Sentai Voogie&amp;#039;s★Angel
                    ["work"] => Theme Song Performance
                )

            ["3"] => Array
                (
                    ["link"] => https://myanimelist.net/anime/2072
                    ["title"] => Idol Project
                    ["work"] => Theme Song Performance
                )

            ["4"] => Array
                (
                    ["link"] => https://myanimelist.net/anime/1436
                    ["title"] => Lupin III: Twilight Gemini no Himitsu
                    ["work"] => Theme Song Performance
                )

        )

    ["link"] => https://myanimelist.net/people/80
)
```