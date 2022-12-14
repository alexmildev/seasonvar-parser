<?php

declare(strict_types=1);

namespace AlexMilDev\SeasonvarParser\Tests;

use AlexMilDev\SeasonvarParser\SeasonvarParser;
use PHPUnit\Framework\TestCase;

class SeasonvarParserTest extends TestCase
{
    /** @test */
    public function it_can_load_html_code()
    {
        $existData = file_get_contents(__DIR__."/htmls/serial.html");

        $parserLoadedData = SeasonvarParser::load($existData);

        $this->assertEquals($parserLoadedData, $existData);
    }


}
