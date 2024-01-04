<?php

namespace Vanengers\CatalogTranslator\Tests\Deepl;

use PHPUnit\Framework\TestCase;
use Vanengers\CatalogTranslator\Iso\IsoFilter;
use Vanengers\CatalogTranslator\Tests\Mocks\Deepl\DeeplTranslatorMock;

class DeeplFromIsoToLocaleTest extends TestCase
{
    public function testAllApplicationLanguagesTranslateIsoToLocaleDeeplSourceGb()
    {
        $deepl = new DeeplTranslatorMock([]);

        $iso = 'en';
        $locale = $deepl->fromIsoToLocale($iso);
        $this->assertEquals('en-us', $locale);
    }

    public function testAllApplicationLanguagesTranslateIsoToLocaleDeeplSourceGbGb()
    {
        $deepl = new DeeplTranslatorMock([]);

        $iso = 'gb';
        $locale = $deepl->fromIsoToLocale($iso);
        $this->assertEquals('en-gb', $locale);
    }

    public function testAllApplicationLanguagesTranslateIsoToLocaleDeeplTargetGb()
    {
        $deepl = new DeeplTranslatorMock([]);

        $iso = 'en-gb';
        $locale = $deepl->fromIsoToLocale($iso, true);
        $this->assertEquals('en-gb', $locale);
    }

    public function testAllApplicationLanguagesTranslateIsoToLocaleDeeplSourceUs()
    {
        $deepl = new DeeplTranslatorMock([]);

        $iso = 'en';
        $locale = $deepl->fromIsoToLocale($iso);
        $this->assertEquals('en-us', $locale);
    }

    public function testAllApplicationLanguagesTranslateIsoToLocaleDeeplTargetUs()
    {
        $deepl = new DeeplTranslatorMock([]);

        $iso = 'en-us';
        $locale = $deepl->fromIsoToLocale($iso, true);
        $this->assertEquals('en-us', $locale);
    }

    public function testAllApplicationLanguagesTranslateIsoToLocaleDeeplSourceNl()
    {
        $deepl = new DeeplTranslatorMock([]);

        $iso = 'nl';
        $locale = $deepl->fromIsoToLocale($iso);
        $this->assertEquals('nl-nl', $locale);
    }

    public function testAllApplicationLanguagesTranslateIsoToLocaleDeeplTargetNl()
    {
        $deepl = new DeeplTranslatorMock([]);

        $iso = 'nl';
        $locale = $deepl->fromIsoToLocale($iso, true);
        $this->assertEquals('nl-nl', $locale);
    }
}
