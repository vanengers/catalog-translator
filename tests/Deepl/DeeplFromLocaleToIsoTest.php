<?php

namespace Vanengers\CatalogTranslator\Tests\Deepl;

use PHPUnit\Framework\TestCase;
use Vanengers\CatalogTranslator\Tests\Mocks\Deepl\DeeplTranslatorMock;

class DeeplFromLocaleToIsoTest extends TestCase
{
    public function testAllApplicationLanguagesTranslateLocaleToIsoDeeplSourceNl()
    {
        $deepl = new DeeplTranslatorMock([]);

        $iso = 'nl-nl';
        $locale = $deepl->fromLocaleToIso($iso, false);
        $this->assertEquals('nl', $locale);
    }

    public function testAllApplicationLanguagesTranslateLocaleToIsoDeeplSourceNlUpper()
    {
        $deepl = new DeeplTranslatorMock([]);

        $iso = 'nl-NL';
        $locale = $deepl->fromLocaleToIso($iso, false);
        $this->assertEquals('nl', $locale);
    }

    public function testAllApplicationLanguagesTranslateLocaleToIsoDeeplTargetNl()
    {
        $deepl = new DeeplTranslatorMock([]);

        $iso = 'nl-nl';
        $locale = $deepl->fromLocaleToIso($iso, true);
        $this->assertEquals('nl', $locale);
    }

    public function testAllApplicationLanguagesTranslateLocaleToIsoDeeplTargetNlUpper()
    {
        $deepl = new DeeplTranslatorMock([]);

        $iso = 'nl-NL';
        $locale = $deepl->fromLocaleToIso($iso, true);
        $this->assertEquals('nl', $locale);
    }

    ///////
    ///
    ///////

    public function testAllApplicationLanguagesTranslateLocaleToIsoDeeplSourceGB()
    {
        $deepl = new DeeplTranslatorMock([]);

        $iso = 'en-gb';
        $locale = $deepl->fromLocaleToIso($iso, false);
        $this->assertEquals('en', $locale);
    }

    public function testAllApplicationLanguagesTranslateLocaleToIsoDeeplSourceGBUpper()
    {
        $deepl = new DeeplTranslatorMock([]);

        $iso = 'en-GB';
        $locale = $deepl->fromLocaleToIso($iso, false);
        $this->assertEquals('en', $locale);
    }

    public function testAllApplicationLanguagesTranslateLocaleToIsoDeeplTargetGB()
    {
        $deepl = new DeeplTranslatorMock([]);

        $iso = 'en-gb';
        $locale = $deepl->fromLocaleToIso($iso, true);
        $this->assertEquals('en-gb', $locale);
    }

    public function testAllApplicationLanguagesTranslateLocaleToIsoDeeplTargetGBUpper()
    {
        $deepl = new DeeplTranslatorMock([]);

        $iso = 'en-GB';
        $locale = $deepl->fromLocaleToIso($iso, true);
        $this->assertEquals('en-gb', $locale);
    }

    ///////
    ///
    ///////

    public function testAllApplicationLanguagesTranslateLocaleToIsoDeeplSourceUS()
    {
        $deepl = new DeeplTranslatorMock([]);

        $iso = 'en-gb';
        $locale = $deepl->fromLocaleToIso($iso, false);
        $this->assertEquals('en', $locale);
    }

    public function testAllApplicationLanguagesTranslateLocaleToIsoDeeplSourceUSUpper()
    {
        $deepl = new DeeplTranslatorMock([]);

        $iso = 'en-GB';
        $locale = $deepl->fromLocaleToIso($iso, false);
        $this->assertEquals('en', $locale);
    }

    public function testAllApplicationLanguagesTranslateLocaleToIsoDeeplTargetUS()
    {
        $deepl = new DeeplTranslatorMock([]);

        $iso = 'en-us';
        $locale = $deepl->fromLocaleToIso($iso, true);
        $this->assertEquals('en-us', $locale);
    }

    public function testAllApplicationLanguagesTranslateLocaleToIsoDeeplTargetUSUpper()
    {
        $deepl = new DeeplTranslatorMock([]);

        $iso = 'en-US';
        $locale = $deepl->fromLocaleToIso($iso, true);
        $this->assertEquals('en-us', $locale);
    }
}
