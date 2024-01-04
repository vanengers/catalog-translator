<?php

namespace Vanengers\CatalogTranslator\Translate;

use DeepL\Language;
use Exception;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Translation\MessageCatalogue;
use Vanengers\CatalogTranslator\Client\ITranslateClient;
use Vanengers\CatalogTranslator\Iso\IsoFilter;
use Vanengers\CatalogTranslator\Iso\Lang;

class Translator
{
    /** @var MessageCatalogue $extractedCatalogue */
    private MessageCatalogue $extractedCatalogue;

    /** @var array $translateTo */
    private array $translateTo;

    /** @var string $locale */
    private string $locale;

    /** @var array|mixed $translations */
    private array $translations = [];

    /** @var MessageCatalogue[] $catalogs */
    private array $catalogs = [];

    /** @var ?ITranslateClient $translator */
    private ?ITranslateClient $translator;

    /** @var Filesystem fs */
    private Filesystem $fs;

    /** @var string translations_config_file */
    private string $translations_config_file;

    public function __construct(MessageCatalogue $extracted, array $translateTo = [], ?ITranslateClient $external = null,
                                string $translations_config_file = ''
    )
    {
        $this->extractedCatalogue = $extracted;
        $this->locale = $extracted->getLocale();
        $this->translateTo = $translateTo;
        $this->translations_config_file = $translations_config_file;
        $this->translator = $external;

        foreach($this->translateTo as $locale) {
            if (!array_key_exists($locale, $this->catalogs)) {
                $this->catalogs[$locale] = new MessageCatalogue($locale);
            }
        }

        if (!array_key_exists($this->locale, $this->catalogs)) {
            $this->catalogs[$this->locale] = new MessageCatalogue($this->locale);
        }

        $this->fs = new Filesystem();
    }

    /**
     * @return void
     * @throws Exception
     * @since 07-10-2023
     * @author George van Engers <vanengers@gmail.com>
     */
    public function init(): void
    {
        try {
            $this->translator->canConnect();
        }
        catch (Exception $e) {
            throw new Exception('FATAL ERROR; CANNOT CONTINUE: Most-likely an Invalid API key | Or other Client error');
        }

        $this->initTranslations();
        $this->syncUpCatalogues();
        $this->saveTranslationsToDisk();
    }

    /**
     * @return void
     * @author George van Engers <vanengers@gmail.com>
     * @since 06-10-2023
     */
    private function initTranslations(): void
    {
        if (!$this->fs->exists($this->translations_config_file)) {
            $this->fs->dumpFile($this->translations_config_file, json_encode([], JSON_PRETTY_PRINT));
        }
        $translations = json_decode(file_get_contents($this->translations_config_file), true);
        $this->translations = $translations ?? [];
    }

    /**
     * @return void
     * @author George van Engers <vanengers@gmail.com>
     * @since 06-10-2023
     */
    private function saveTranslationsToDisk(): void
    {
        file_put_contents($this->translations_config_file, json_encode($this->translations, JSON_PRETTY_PRINT));
    }

    /**
     * @return array|MessageCatalogue[]
     * @author George van Engers <vanengers@gmail.com>
     * @since 06-10-2023
     */
    public function getNewCatalogs(): array
    {
        return $this->catalogs;
    }

    /**
     * @return void
     * @throws Exception
     * @since 06-10-2023
     * @author George van Engers <vanengers@gmail.com>
     */
    private function syncUpCatalogues(): void
    {
        foreach($this->extractedCatalogue->all() as $domain => $messages) {
            $allMetaData = $this->extractedCatalogue->getMetadata('', '');
            foreach($messages as $id => $message) {
                $meta = $allMetaData[$domain][$message] ?? [];
                foreach($this->catalogs as $loc => $catalog) {
                    if (!$catalog->has($id, $domain)) {
                        $catalog->add([$id => $this->translate($id, $message, $loc)], $domain);
                        $catalog->setMetadata($id, $meta, $domain);
                    }
                    if (isset($metadata[$domain])) {
                        foreach ($metadata[$domain] as $key => $value) {
                            $catalog->setMetadata($key, $value, $domain);
                        }
                    }
                }
            }
        }
    }

    /**
     * @param string $id
     * @param string $message
     * @param string $getLocale
     * @return string
     * @throws Exception
     * @since 06-10-2023
     * @author George van Engers <vanengers@gmail.com>
     */
    private function translate(string $id, string $message, string $getLocale) : string
    {
        if (array_key_exists($getLocale, $this->translations)) {
            if (array_key_exists($id, $this->translations[$getLocale])) {
                return $this->translations[$getLocale][$id];
            }
        }

        if ($getLocale != $this->locale) {
            $message = $this->remoteTranslate($message, $getLocale);
        }

        $this->translations[$getLocale][$id] = $message;
        return $this->translations[$getLocale][$id];
    }

    /** @var bool[] $canceledRemoteTranslations */
    private array $canceledRemoteTranslations = [];

    /**
     * @param string $message
     * @param string $locale
     * @return string
     * @throws Exception
     * @since 07-10-2023
     * @author George van Engers <vanengers@gmail.com>
     */
    private function remoteTranslate(string $message, string $locale) : string
    {
        if (!$this->verifyTranslatable($locale)) {
            if (!array_key_exists($locale, $this->canceledRemoteTranslations)) {
                $this->canceledRemoteTranslations[$locale] = true;
            }

            return $message;
        }

        $result = $this->translator->translate($message,$this->locale, $locale);


        if (!empty($result)) {
            $message = $result;
        }

        return $message;
    }

    /** @var Language[] $sourceLangs */
    private array $sourceLangs = [];

    /** @var Language[] $targetLangs */
    private array $targetLangs = [];

    /** @var bool[] $_localeVerfiyCache */
    private array $_localeVerfiyCache = [];

    /**
     * @param string $locale
     * @return bool
     * @throws Exception
     * @since 03-01-2024
     * @author George van Engers <george@dewebsmid.nl>
     */
    private function verifyTranslatable(string $locale): bool
    {
        if (array_key_exists($locale, $this->_localeVerfiyCache)) {
            return $this->_localeVerfiyCache[$locale];
        }

        $source = $this->translator->fromLocaleToIso($this->locale);
        $target = $this->translator->fromLocaleToIso($locale, true);

        if (empty($this->sourceLangs)) {
            $this->sourceLangs = $this->arrayMap($this->translator->getSourceLanguages());
        }
        $src = false;
        foreach ($this->sourceLangs as $lang) {
            /** @var Lang $lang */
            if (strtolower($lang->getIso()) == strtolower($source)) {
                $src = true;
                break;
            }
        }
        if (empty($this->targetLangs)) {
            $this->targetLangs = $this->arrayMap($this->translator->getTargetLanguages());
        }
        $trg = false;
        foreach ($this->targetLangs as $lang) {
            /** @var Lang $lang */
            if (strtolower($lang->getIso()) == strtolower($target)) {
                $trg = true;
                break;
            }
        }

        $this->_localeVerfiyCache[$locale] = $src && $trg;
        return $this->_localeVerfiyCache[$locale];
    }

    /**
     * @param Language[] $getTargetLanguages
     * @return Language[]
     * @author George van Engers <vanengers@gmail.com>
     * @since 07-10-2023
     */
    private function arrayMap(array $getTargetLanguages): array
    {
        $data = [];
        foreach($getTargetLanguages as $lang) {
            /** @var Lang $lang */
            $data[strtolower($lang->getIso())] = $lang;
        }
        return $data;
    }
}