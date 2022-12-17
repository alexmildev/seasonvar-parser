<?php

declare(strict_types=1);

namespace AlexMilDev\SeasonvarParser;

use Exception;
use simple_html_dom;

/**
 *
 */
class SeasonvarParser
{
    /**
     * Base url of the site
     */
    protected const BASE_URL = "http://seasonvar.ru";

    /**
     * Voices of the serial
     *
     * @var array|string[]
     */
    protected array $voices = [
        '',
        'HDRezka',
        'BaibaKo',
        'FOX',
        'LostFilm',
        'NewStudio',
        'AniDub',
        'Оригинал',
        'Пифагор',
        'Субтитры',
        'кубик в кубе',
        'TVShows',
        'Трейлеры',
        'turok1990',
        'AniLibria',
        'DubLik',
        'RuDub',
        'AniMaunt',
        'SoftBox'
    ];

    private mixed $parser;

    /**
     * @param string $pageUrl
     * @return SeasonvarParser
     */
    public static function load(string $pageUrl = self::BASE_URL): SeasonvarParser
    {
        return new static($pageUrl);
    }

    /**
     * @param string $pageUrl
     */
    public function __construct(string $pageUrl = self::BASE_URL)
    {
        $this->parser = new simple_html_dom();
        $this->parser->load_file($pageUrl);
    }

    /**
     * Get common info of serial
     *
     * @return array
     */
    public function getCommonData(): array
    {
        $result = [];

        $result["serialTitle"] = $this->getSerialTitle();
        $result["serialDescription"] = $this->getSerialDescription();
        $result["serialInfo"] = $this->getSerialInfo();
        $result["serialRating"] = $this->getSerialRating();

        return $result;
    }

    /**
     * Get actors of the serial
     *
     * @return array
     */
    public function getActors(): array
    {
        return $this->getSerialActors();
    }

    /**
     * Get tags of the serial
     *
     * @return array
     */
    public function getTags(): array
    {
        return $this->getSerialTags();
    }

    /**
     * Get seasons of the serial
     *
     * @return array
     */
    public function getSeasons(): array
    {
        return $this->getSerialSeasons();
    }

    /**
     *  Get playlists of the serial
     *
     * @param array|null $voices
     * @return array
     * @throws Exception
     */
    public function getPlaylists(array $voices = null): array
    {
        return $this->getSerialPlaylists($this->getVoicesForParse($voices));
    }

    /**
     * Due to php5 circular references memory leak, after creating DOM object, you must call
     *
     * @return void
     */
    public function clear(): void
    {
        $this->parser->clear();
        unset($this->parser);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->parser->plaintext;
    }

    /**
     * @return string
     */
    private function getSerialTitle(): string
    {
        $titleTag = ".pgs-sinfo-title";

        return count($this->parser->find($titleTag)) ? $this->parser->find($titleTag,
            0)->plaintext : '';
    }

    /**
     * @return string
     */
    private function getSerialDescription(): string
    {
        $tag = ".pgs-sinfo-info p";

        return count($this->parser->find($tag)) ? $this->parser->find($tag,
            0)->plaintext : '';
    }

    /**
     * @return array
     */
    private function getSerialActors(): array
    {
        $actors = [];
        $tag = "div.pgs-sinfo-actor a";

        if (count($this->parser->find($tag))) {
            foreach ($this->parser->find($tag) as $item) {
                $actors[] = [
                    'url' => $item->href,
                    'img' => $item->find('img', 0)->src,
                    'name' => $item->find('span', 0)->plaintext,
                ];
            }
        }

        return $actors;
    }

    /**
     * @return array
     */
    private function getSerialInfo(): array
    {
        $infoArray = [];
        $tag = "div.pgs-sinfo_list";

        if (count($this->parser->find($tag))) {
            if (str_contains($this->parser->find($tag, 0)->innertext, "<br>")) {
                $originalTitleKey = trim(explode(":", explode("<br>", $this->parser->find($tag, 0)->innertext)[0])[0]);
                $originalTitleValue = $this->parser->find($tag, 0)->find("span", 0)->plaintext;
                $infoArray[$originalTitleKey] = $originalTitleValue;

                $alternativeTitleKey = trim(explode(":",
                    explode("<br>", $this->parser->find($tag, 0)->innertext)[1])[0]);
                $alternativeTitleValue = $this->parser->find($tag, 0)->find("span", 1)->plaintext;
                $infoArray[$alternativeTitleKey] = $alternativeTitleValue;
            } else {
                $infoArray[trim(str_replace(trim($this->parser->find($tag, 0)->find("span", 0)->plaintext), '',
                    trim($this->parser->find($tag, 0)->plaintext)))] =
                    trim($this->parser->find($tag, 0)->find("span",
                        0)->plaintext);
            }

            $genreKey = explode(":", explode("<br>", $this->parser->find($tag, 1)->innertext)[0])[0];
            $genreValue = $this->parser->find($tag, 1)->find("span", 0)->plaintext;

            $infoArray[$genreKey] = $genreValue;

            $countryKey = explode(":", explode("<br>", $this->parser->find($tag, 1)->innertext)[1])[0];
            $countryValue = $this->parser->find($tag, 1)->find("span", 1)->plaintext;

            $infoArray[$countryKey] = $countryValue;

            $createdKey = trim(explode(":", $this->parser->find($tag, 2)->plaintext)[0]);
            $createdValue = $this->parser->find($tag, 2)->find("span", 0)->plaintext;
            $infoArray[$createdKey] = $createdValue;

            $producerKey = explode(":", $this->parser->find($tag, 2)->find("div", 0)->plaintext)[0];
            $producerValue = $this->parser->find($tag, 2)->find("span", 1)->plaintext;
            $infoArray[$producerKey] = $producerValue;


            $tag = "div.pgs-sinfo_svraiting";

            if (count($this->parser->find($tag))) {
                $rating = [];
                foreach ($this->parser->find($tag, 0)->find("div", 0)->find("div") as $div) {
                    $rating[] = trim($div->plaintext);
                }
                $infoArray[$this->parser->find($tag, 0)->find("span", 0)->plaintext] = $rating;
            }
        }

        return $infoArray;
    }

    /**
     * @return array
     */
    private function getSerialTags(): array
    {
        $result = [];
        $tag = ".b-taglist.dl";
        if (count($this->parser->find($tag))) {
            $tags = [];
            if (count($this->parser->find($tag, 0)->find("a"))) {
                foreach ($this->parser->find($tag, 0)->find("a") as $key) {
                    $tags[] = trim($key->innertext);
                }
            }

            if (count($this->parser->find(".b-taglist.dl", 0)->find(".b-taglist-dt"))) {
                $result[$this->parser->find(".b-taglist.dl", 0)->find(".b-taglist-dt", 0)->plaintext] = $tags;
            }
        }

        return $result;
    }

    /**
     * @return array
     */
    private function getSerialSeasons(): array
    {
        $result = [];

        if (count($this->parser->find("li.act", 0)->find("a"))) {
            foreach ($this->parser->find("li.act", 0)->find("a") as $item) {
                $result[] = [
                    "url" => $item->href,
                    "title" => str_replace(" >>> ", "", $item->plaintext),
                ];
            }
        }

        return $result;
    }

    /**
     * @param array $voices
     * @return array
     */
    private function getSerialPlaylists(array $voices): array
    {
        $secureMark = $this->getSecureMark();
        $seasonId = $this->getSeasonId();
        $result = [];
        foreach ($voices as $voice) {
            $voiceLink = "http://seasonvar.ru/playls2/$secureMark/trans$voice/$seasonId/plist.txt";
            $result[$voice] = $this->getVoicePlaylist($voiceLink);
        }
        return $result;
    }

    /**
     * @return string
     */
    private function getSecureMark(): string
    {
        return str_replace(" '", "", explode("',",
            explode(":", trim($this->parser->find('.pgs-player', 0)->find('script', 1)->innertext))[1])[0]);
    }

    /**
     * @return string
     */
    private function getSeasonId(): string
    {
        return $this->parser->find("div.pgs-sinfo", 0)->getAttribute('data-id-season');
    }

    /**
     * @param string $voiceLink
     * @return array
     */
    private function getVoicePlaylist(string $voiceLink): array
    {
        $response = $this->sendRequest($voiceLink);
//        var_dump($response);
        if (is_string($response) && $response !== "[]") {
            $response = $this->convertPlaylist($response);
        } else {
            $response = array();
        }
        return $response;
    }

    /**
     * @param $url
     * @return bool|string
     */
    private function sendRequest($url): bool|string
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Accept: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    /**
     * @param string $data
     * @return array
     */
    private function convertPlaylist(string $data): array
    {
        $json = (array)json_decode($data, true);
        foreach ($json as $key => $item) {
            $json[$key]["file"] = $this->decodeUrl($item["file"]);
        }
        return $json;
    }

    /**
     * @param string $file
     * @return string
     */
    private function decodeUrl(string $file): string
    {
        return utf8_decode(base64_decode(str_replace(["//b2xvbG8=", "#2"], "", $file)));
    }

    /**
     * @return array
     */
    private function getSerialRating(): array
    {
        $tag = ".pgs-sinfo_list.rating";
        $rating = [];
        if (count($this->parser->find($tag))) {
            foreach ($this->parser->find($tag) as $key) {
                $rating[explode(":", $key->innertext)[0]] = $key->find("span", 0)->plaintext;
            }
        }
        return $rating;
    }

    /**
     * @param array|null $voices
     * @return array|string[]
     * @throws Exception
     */
    private function getVoicesForParse(?array $voices): array
    {
        if ($voices) {
            $setVoices = !array_diff($voices,
                $this->voices) ? $voices : throw new Exception("No translations were found");
        } else {
            $setVoices = $this->voices;
        }
        return $setVoices;
    }
}