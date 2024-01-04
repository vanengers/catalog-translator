<?php

namespace Vanengers\CatalogTranslator\Tests\Iso;

use PHPUnit\Framework\TestCase;
use Vanengers\CatalogTranslator\Iso\IsoFilter;

class FromIsoToLocaleTest extends TestCase
{
    public function testCanTransformIsoToLocaleUs()
    {
        $iso = 'en';
        $locale = IsoFilter::getLocaleByIso($iso);
        $this->assertEquals('en-us', $locale);
    }

    public function testCanTransformIsoToLocaleGb()
    {
        $iso = 'gb';
        $locale = IsoFilter::getLocaleByIso($iso);
        $this->assertEquals('en-gb', $locale);
    }

    public function testCanTransformIsoToLocaleNl()
    {
        $iso = 'nl';
        $locale = IsoFilter::getLocaleByIso($iso);
        $this->assertEquals('nl-nl', $locale);
    }

    public function testCanTransformIsoToLocaleGa()
    {
        $iso = 'ga';
        $locale = IsoFilter::getLocaleByIso($iso);
        $this->assertEquals('ga-ie', $locale);
    }

    public function testCanTransformIsoToLocaleAllLanguages()
    {
        $languages = json_decode(file_get_contents(__DIR__ . '/../../config/languages.json'), true);
        foreach($languages as $key => $language) {
            $iso = $language['iso_code'];
            $locale = IsoFilter::getLocaleByIso($iso);
            $this->assertEquals(strtolower($language['locale']), $locale);
        }
    }
}
