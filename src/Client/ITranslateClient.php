<?php

namespace Vanengers\CatalogTranslator\Client;

interface ITranslateClient
{
    public function __construct(array $options = []);
    public function translate(string $text, string $source, string $target): string;
    public function getSourceLanguages(): array;
    public function getTargetLanguages(): array;
    public function canConnect(): bool;

    public function fromIsoToLocale(string $iso, bool $target = false): string;
    public function fromLocaleToIso(string $locale, bool $target = false): string;
}