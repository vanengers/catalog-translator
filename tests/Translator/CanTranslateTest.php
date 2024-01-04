<?php

namespace Vanengers\CatalogTranslator\Tests\Translator;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Translation\MessageCatalogue;
use Vanengers\CatalogTranslator\Tests\Mocks\Deepl\DeeplTranslatorMock;
use Vanengers\CatalogTranslator\Translate\Translator;

class CanTranslateTest extends TestCase
{
    public function testCanTranslateUSNL()
    {
        $testCatalog = new MessageCatalogue('en-US');
        $testCatalog->add(['This is a test' => 'This is a test']);

        $config = __DIR__.'/../Mocks/Translator/translationsTest3.json';

        $api = new Translator($testCatalog, ['nl-NL'], new DeeplTranslatorMock([]),
            $config);

        $api->init();

        $trans = json_decode(file_get_contents($config), true);

        $fs = new Filesystem();
        $fs->remove($config);

        $this->assertNotEmpty($trans);
        $this->assertEquals('nl-NL'.'This is a test', $trans['nl-NL']['This is a test']);
    }

    public function testCanTranslateGBNL()
    {
        $testCatalog = new MessageCatalogue('en-GB');
        $testCatalog->add(['This is a double test' => 'This is a double test']);

        $config = __DIR__.'/../Mocks/Translator/translationsTest2.json';

        $api = new Translator($testCatalog, ['nl-NL'], new DeeplTranslatorMock([]),$config);

        $api->init();

        $trans = json_decode(file_get_contents($config), true);

        $fs = new Filesystem();
        $fs->remove($config);

        $this->assertNotEmpty($trans);
        $this->assertEquals('nl-NL'.'This is a double test', $trans['nl-NL']['This is a double test']);
    }
}
