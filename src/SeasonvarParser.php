<?php

declare(strict_types=1);

namespace AlexMilDev\SeasonvarParser;

use HungCP\PhpSimpleHtmlDom\HtmlDomParser;
use JsonSerializable;

/**
 *
 */
class SeasonvarParser implements JsonSerializable
{
    /**
     * Html code from index page http://seasonvar.ru
     * @var string
     */
    protected string $pageCode;

    /**
     * Set html code from index page http://seasonvar.ru
     *
     * @param string $pageCode
     * @return SeasonvarParser
     */

    public static function load(string $pageCode)
    {
        return new static($pageCode);
    }

    /**
     * * Set html code from index page http://seasonvar.ru
     *
     * @param string $pageCode
     */
    public function __construct(string $pageCode)
    {
        $this->pageCode = $pageCode;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->pageCode;
    }

    public function jsonSerialize()
    {
        return json_encode($this->pageCode);
    }
}