# Simple php parser for Seasonvar site

This simple PHP package for parsing serials pages on Seasonvar site.

## Usage

```php
$parser = SeasonvarParser::load("http://seasonvar.ru/serial-331-Zvzdnij_Put__Voyadzher-6-season.html");
$commonData = $parser->getCommonData();
$seasons = $parser->getSeasons();
$actors = $parser->getActors();
$tags = $parser->getTags();
$playlists = $parser->getPlaylists();
$parser->clear();
```
Example
```php
Array
(
    [0] => Array
        (
            [serialTitle] => Сериал Звездный путь: Вояджер/Star Trek: Voyager  6 сезон онлайн
            [serialDescription] => Не успел звездолет сойти с конвейера и начать первый ознакомительный полет в космосе, как неожиданно попадает в переделку. Космический корабль очутился в необычном месте, обладающем паранормальными свойствами. Странным образом корабль и его пассажиры переносятся в одну из неизученных частей Вселенной. 
  Члены экипажа оказываются ребятами неробкого десятка и не опускают руки. Наоборот, их глаза загораются от возможности исследовать тайные места космоса, то, что они никогда не мечтали увидеть. Их вера сильна. Весь состав корабля стремятся вернуться туда, где их дом. Конечно разница в 70 световых лет – это серьезно, но экипаж готов к решительным действиям.
  Путешествие будет захватывающим, ведь впереди много неизведанного: от обнаружения других культур до временных казусов.
            [serialInfo] => Array
                (
                    [Оригинал:] => Star Trek: Voyager
                    [Жанр] => фантастические
                    [Страна] => США
                    [Вышел] => 1999
                    [Режиссер] => Рик Берман
                    [Топ 1999 года в жанре:] => Array
                        (
                            [0] => фантастические — 8 место
                        )

                )

            [serialRating] => Array
                (
                    [IMDB] => 7.80
                    [КиноПоиск] => 8.02
                )

        )

    [1] => Array
        (
            [0] => Array
                (
                    [url] => /serial-78-Zvzdnij_Put__Voyadzher-1-season.html
                    [title] => Сериал Звездный путь: Вояджер/Star Trek: Voyager 1 сезон 
                )

           .....
            [6] => Array
                (
                    [url] => /serial-954--Zvzdnij_Put__Voyadzher-7-season.html
                    [title] => Сериал Звездный путь: Вояджер/Star Trek: Voyager 7 сезон 
                )

        )

    [2] => Array
        (
            [0] => Array
                (
                    [url] => /actor/Кейт Малгрю
                    [img] => http://cdn.bigsv.ru/a/s/0/344.jpg
                    [name] => Кейт Малгрю
                )

           .....
            [9] => Array
                (
                    [url] => /actor/Тарик Эргин
                    [img] => http://cdn.bigsv.ru/a/s/4/46294.jpg
                    [name] => Тарик Эргин
                )

        )

    [3] => Array
        (
            [Теги (top 10):] => Array
                (
                    [0] => космический корабль
                    [1] => звёздный путь
                    [2] => космос
                    [3] => Научная фантастика
                    [4] => Инопланетяне
                )

        )

    [4] => Array
        (
            [] => Array
                (
                    [0] => Array
                        (
                            [title] => 1 серия SD<br>
                            [file] => http://data01-cdn.datalock.ru/fi2lm/6bdad7565dfdb7d0f1411c7fc75cbda2/7f_Zvyozdnyi.put.Voyadjer.6.sezon.01.seriia.iz.26.2000.a1.11.09.12.mp4
                            [subtitle] => 
                            [galabel] => 331_155094
                            [id] => 1
                            [vars] => 155094
                        )

                    ....
                    [25] => Array
                        (
                            [title] => 26 серия SD<br>
                            [file] => http://data01-cdn.datalock.ru/fi2lm/6bdad7565dfdb7d0f1411c7fc75cbda2/7f_Zvyozdnyi.put.Voyadjer.6.sezon.26.seriia.iz.26.2000.a1.11.09.12.mp4
                            [subtitle] => 
                            [galabel] => 331_155119
                            [id] => 26
                            [vars] => 155119
                        )

                )

           .....

            [SoftBox] => Array
                (
                )

        )

)
```

## License

This package is open-sourced software licensed under the [MIT](https://opensource.org/licenses/MIT) license.
