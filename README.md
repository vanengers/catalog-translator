# catalog-translator
Translates a whole catalog and saves it to disk for re-use.
By default we are using Deepl for translations, but you can use any translator you want.

<br>
This is for development usage. This should not be used in a production environment.

## Install
`` composer require --dev vanengers/catalog-translator``

## Usage
````php
$translator = new Translator(
    MessageCatalogue $extracted,
    array $translateTo => [],
    ?ITranslateClient $external = null,
    string $translations_config_file = ''
);
````

## Example
First create a catalog with the base language keys, this is the language you want to translate from. So the key is the same as the value.
````php
$extracted = new \Symfony\Component\Translation\MessageCatalogue('en-GB',[
    'domain' => [
        'This is a base language key' => 'This is a base language key'
    ]
]);
````

Create an array of locales to translate to.

````php
$translateTo = ['nl-NL', 'de-DE'];
````

Load an external translator, in this case we are using Deepl.
````php
$external = new \Vanengers\CatalogTranslator\Client\DeeplClient([
    'api_key' => 'your-deepl-api-key'
]);
````

Location of the translations json file to retrieve and store translations. This file will be created if it does not exist.
Existing translations will be used if the file exists and not be translated again.
````php
$translations_config_file = __DIR__ . '/translations.json';
````

## External Translators
You can use any translator you want, as long as it implements the ITranslateClient.
````php 
class CustomTranslator implements ITranslateClient {}
````

### Available translators

#### Deepl
````php
$external = new \Vanengers\CatalogTranslator\Client\DeeplClient([
    'api_key' => 'your-deepl-api-key'
]);
````

## Languages
Each external translator has its own set of languages it supports. You can retrieve these languages by calling the getSourceLanguages() or getTargetLanguages() methods on the translator.

````php
$languages = $external->getSourceLanguages();
````

The iso and locale codes from the external translator are not always the same as the ones. Convert them using the config/languages.json file.
You should do this with every External client so it matches the languages in the config file. 
<br><br>
<b>We use locales throughout the application</b>, but the external translator might use iso codes. This differs per external translator<br>
````php
public function translate(string $text, string $source, string $target): string
{
    $source = $this->fromLocaleToIso($source);
    $target = $this->fromLocaleToIso($source, true);
    return $this->api->translateText($text, $source, $target);
}
````

And when parsing the languages from the external translator, convert them to locales. In this case we have Language objects from Deepl. But this differs per external translator.
````php 
public function getSourceLanguages(): array
{
    return array_map(function(Language $language) {
        return new Lang($language->code, $language->name, $this->fromIsoToLocale($language->code, false));
    }, $this->api->getSourceLanguages());
}
````

### Exceptions in iso/locale conversion
Within the catalog-translator we use locales. For example: en-GB is supported here. When converting the locale en-GB to iso, we get "gb", officially this is a correct iso language code. 
<br>
But Deepl doesn't allow this code to be a language source. So translate "gb" to "en"  when it's a source language.
<br>
Deepl does however accept "en-gb" as an iso-code for a target language.
<br><br>
So keep in mind when implementing a new external translator! Use the languages.json file to convert the iso codes to locales and vice versa.