<?php

namespace Vanengers\CatalogTranslator\Tests\Iso;

use PHPUnit\Framework\TestCase;
use Vanengers\CatalogTranslator\Iso\IsoFilter;

class FromLocaleToIsoTest extends TestCase
{
    public function testCanTransformLocaleToIsoUs()
    {
        $locale = 'en-US';
        $iso = IsoFilter::getIsoByLocale($locale);
        $this->assertEquals('en', $iso);
    }

    public function testCanTransformLocaleToIsoGb()
    {
        $locale = 'en-GB';
        $iso = IsoFilter::getIsoByLocale($locale);
        $this->assertEquals('gb', $iso);
    }

    public function testCanTransformLocaleToIsoNl()
    {
        $locale = 'nl-NL';
        $iso = IsoFilter::getIsoByLocale($locale);
        $this->assertEquals('nl', $iso);
    }

    public function testCanTransformLocaleToIsoGa()
    {
        $locale = 'ga-IE';
        $iso = IsoFilter::getIsoByLocale($locale);
        $this->assertEquals('ga', $iso);
    }

    public function testCanTransformLocaleToIsoAllLanguages()
    {
        $languages = json_decode(file_get_contents(__DIR__ . '/../../config/languages.json'), true);
        foreach($languages as $key => $language) {
            $locale = $language['locale'];
            $iso = IsoFilter::getIsoByLocale($locale);
            $this->assertEquals(strtolower($language['iso_code']), $iso);
        }
    }
}
