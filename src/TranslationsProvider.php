<?php

namespace Siarko\Translations;

use Siarko\ConfigFiles\Api\Provider\ConfigProviderInterface;
use Siarko\ConfigFiles\Provider\ConfigFileExtensionProvider;
use Siarko\Files\Api\LookupInterface;
use Siarko\Translations\Loader\LoaderInterface;

class TranslationsProvider implements LoaderInterface
{

    public const PATH_PROVIDER_POOL_TYPE = 'translation';

    protected array $languages = [];

    protected ?array $translations = [];

    protected string $language = 'en';

    /**
     * @param ConfigProviderInterface $configProvider
     * @param LookupInterface $fileLookup
     * @param ConfigFileExtensionProvider $extensionProvider
     * @param string $fileParserType
     */
    public function __construct(
        protected readonly ConfigProviderInterface $configProvider,
        protected readonly LookupInterface $fileLookup,
        protected readonly ConfigFileExtensionProvider $extensionProvider,
        protected readonly string $fileParserType = 'default'
    )
    {
    }


    /**
     * Return the list of languages
     * @return string[]
     */
    public function getLanguages(): array
    {
        if(empty($this->languages)){
            $extensions = $this->extensionProvider->getAsRegex('');
            $files = $this->fileLookup->find($extensions);
            $this->languages = array_map(fn ($file) => $file->getPathInfo()->getFileName(), $files);
        }
        return $this->languages;
    }

    /**
     * Return true if the language is present in the translations
     * @param string $language
     * @return bool
     */
    public function hasLanguage(string $language): bool
    {
        return in_array($language, $this->languages);
    }

    /**
     * Set the language for the translations
     *
     * @param string $language
     * @return void
     * @throws \JsonException
     */
    public function setLanguage(string $language): void
    {
        if (!array_key_exists($language, $this->translations)) {
            $this->translations[$language] = $this->configProvider->fetch($language);
        }
        $this->language = $language;
    }

    /**
     * Return the translation for the given message
     *
     * @param string $message
     * @return string|array
     */
    public function get(string $message): string|array
    {
        if (array_key_exists($message, $this->translations[$this->language])) {
            return $this->translations[$this->language][$message];
        } else {
            return $message;
        }
    }

    /**
     * Return true if the message is present in the translations
     * @param $message
     * @return bool
     */
    public function has($message): bool
    {
        return array_key_exists($message, $this->translations[$this->language]);
    }
}