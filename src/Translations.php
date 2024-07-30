<?php

namespace Siarko\Translations;

class Translations
{

    private static ?Translations $instance = null;

    /**
     * @param Translator $translator
     * @param TranslationsProvider $translationsProvider
     */
    public function __construct(
        protected readonly Translator $translator,
        protected readonly TranslationsProvider $translationsProvider
    )
    {
        static::$instance = $this;
    }

    /**
     * @return Translations
     */
    public static function getInstance(): Translations
    {
        return static::$instance;
    }

    /**
     * @param string $language
     */
    public function setLanguage(string $language = 'en')
    {
        $this->translator->setLanguage($language);
    }

    /**
     * @param string $text
     * @param array $arguments
     * @return string
     */
    public function translate(string $text, array $arguments): string
    {
        return $this->translator->translate($text, $arguments);
    }

}