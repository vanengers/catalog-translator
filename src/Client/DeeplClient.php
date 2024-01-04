<?php

namespace Vanengers\CatalogTranslator\Client;

use DeepL\DeepLException;
use DeepL\Language;
use DeepL\Translator;
use Vanengers\CatalogTranslator\Iso\IsoFilter;
use Vanengers\CatalogTranslator\Iso\Lang;

class DeeplClient implements ITranslateClient
{
    private Translator $api;

    public function __construct(array $options = [])
    {
        $this->api = new Translator(array_key_exists('api_key', $options) ? $options['api_key'] : '');
    }

    /**
     * @throws DeepLException
     */
    public function translate(string $text, string $source, string $target): string
    {
        $source = $this->fromLocaleToIso($source);
        $target = $this->fromLocaleToIso($target, true);
        return $this->api->translateText($text, $source, $target);
    }

    /**
     * @return Lang[]
     * @throws DeepLException
     */
    public function getSourceLanguages(): array
    {
        return array_map(function(Language $language) {
            return new Lang($language->code, $language->name, $this->fromIsoToLocale($language->code, false));
        }, $this->api->getSourceLanguages());
    }

    public function getTargetLanguages(): array
    {
        return array_map(function(Language $language) {
            return new Lang($language->code, $language->name, $this->fromIsoToLocale($language->code, true));
        }, $this->api->getTargetLanguages());
    }

    public function canConnect(): bool
    {
        return !$this->api->getUsage()->anyLimitReached();
    }

    public function fromIsoToLocale(string $iso, bool $target = false): string
    {
        $locale = IsoFilter::getLocaleByIso($iso);

        // do exceptions here, but only for target
        $exceptions = [
            'gb' => 'en-GB',
            'en' => 'en-US',
            'en-gb' => 'en-GB',
            'en-us' => 'en-US',
        ];

        if ($target && array_key_exists($locale, $exceptions)) {
            $locale = $exceptions[$locale];
        }

        if (empty($locale) && array_key_exists(strtolower($iso), $exceptions)) {
            $locale = $exceptions[strtolower($iso)];
        }

        return strtolower($locale);
    }

    public function fromLocaleToIso(string $locale, bool $target = false): string
    {
        $iso = IsoFilter::getIsoByLocale($locale);

        // do exceptions here, but only for target
        $exceptionsTarget = [
            'en-gb' => 'en-gb',
            'en' => 'en-us',
            'gb' => 'en-gb',
        ];

        $exceptionsSource = [
            'en-gb' => 'en',
            'en' => 'en',
            'gb' => 'en',
        ];

        if ($target && array_key_exists(strtolower($iso), $exceptionsTarget)) {
            $iso = $exceptionsTarget[$iso];
        }

        if (!$target && array_key_exists(strtolower($iso), $exceptionsSource)) {
            $iso = $exceptionsSource[$iso];
        }

        if (empty($iso) && array_key_exists(strtolower($locale), $exceptionsTarget)) {
            $iso = $exceptionsTarget[$locale];
        }

        return strtolower($iso);
    }
}