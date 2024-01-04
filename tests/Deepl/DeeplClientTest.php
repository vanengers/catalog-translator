<?php

namespace Vanengers\CatalogTranslator\Tests\Deepl;

use PHPUnit\Framework\TestCase;
use Vanengers\CatalogTranslator\Client\DeeplClient;
use Vanengers\CatalogTranslator\Tests\Mocks\Deepl\DeeplTranslatorMock;

class DeeplClientTest extends TestCase
{
    public function testCanTranslate()
    {
        $client = new DeeplTranslatorMock([]);
        $text = 'Hello world';
        $source = 'en-US';
        $target = 'nl-NL';
        $translation = $client->translate($text, $source, $target);

        $this->assertNotEmpty($translation);
        $this->assertEquals($target.$text, $translation);
    }
}
