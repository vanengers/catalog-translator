<?php

namespace Vanengers\CatalogTranslator\Iso;

class IsoFilter
{
    /** @var Lang[] $languages */
    private static array $languages = [];

    /**
     * @return void
     * @author George van Engers <vanengers@gmail.com>
     * @since 06-10-2023
     */
    private static function loadLanguages(): void
    {
        if (empty(self::$languages)) {
            $data = json_decode(file_get_contents(__DIR__ . '/../../config/languages.json'), true);
            foreach ($data as $row) {
                self::$languages[] = new Lang($row['iso_code'], $row['name'], $row['locale']);
            }
        }
    }

    /**
     * @return array
     * @author George van Engers <vanengers@gmail.com>
     * @since 06-10-2023
     */
    public static function getLanguages(): array
    {
        self::loadLanguages();
        return self::$languages;
    }

    /**
     * @param string $locale
     * @return bool
     * @author George van Engers <vanengers@gmail.com>
     * @since 06-10-2023
     */
    public static function isValidLocale(string $locale): bool
    {
        self::loadLanguages();
        foreach(self::$languages as $lang) {
            if ($lang->getLocale() == $locale) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $locale
     * @return ?string
     * @author George van Engers <vanengers@gmail.com>
     * @since 07-10-2023
     */
    public static function getIsoByLocale(string $locale): ?string
    {
        self::loadLanguages();
        foreach(self::$languages as $lang) {
            if (strtolower($lang->getLocale()) == strtolower($locale)) {
                return strtolower($lang->getIso());

            }
        }

        return false;
    }

    public static function getLocaleByIso(string $iso): ?string
    {
        self::loadLanguages();
        foreach(self::$languages as $lang) {
            if (strtolower($lang->getIso()) == strtolower($iso)) {
                return strtolower($lang->getLocale());

            }
        }

        return false;
    }
}