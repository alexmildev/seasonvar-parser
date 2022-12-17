<?php

declare(strict_types=1);

namespace AlexMilDev\SeasonvarParser\Tests;

use AlexMilDev\SeasonvarParser\SeasonvarParser;
use PHPUnit\Framework\TestCase;

class SeasonvarParserTest extends TestCase
{
    /** @test */
    public function it_can_load_page()
    {
        $parser = SeasonvarParser::load("http://seasonvar.ru/serial-331-Zvzdnij_Put__Voyadzher-6-season.html");
        $commonData = $parser->getCommonData();
        $seasons = $parser->getSeasons();
        $actors = $parser->getActors();
        $tags = $parser->getTags();
        $playlists = $parser->getPlaylists();
        $parser->clear();



//        $parserLoadedData = SeasonvarParser::load("http://seasonvar.ru/serial-31659-Zolotaya_lihoradka_Burnaya_reka-5-season.html")->getTags();
//        $parserLoadedData = SeasonvarParser::load("http://seasonvar.ru/serial-34758-333.html")->getTags();
print_r([$commonData, $seasons, $actors, $tags, $playlists]);
//        $this->assertStringContainsString("Сериал Звездный путь: Вояджер 6 сезон Star Trek: Voyager смотреть онлайн бесплатно!", (string)$parserLoadedData);
    }


}
