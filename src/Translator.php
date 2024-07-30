<?php

namespace Siarko\Translations;

use Psr\Log\LoggerInterface;
use Siarko\Translations\Loader\LoaderInterface;

class Translator
{

    protected string $language = 'en';

    /**
     *
     * 'language'  User's language.
     *             If omitted then the best matching value
     *             from Accept-Language header will be used.
     *
     * 'default-language' Default language (language for t() method)
     *
     * 'accept-language' If set, will be used instead of $_SERVER['HTTP_ACCEPT_LANGUAGE']
     *
     * 'available' Available languages. if not set or empty
     *             then any language will be accepted.
     *
     * 'synonyms'  Synonyms for language codes
     */
    protected array $options = [
        'language' => null,
        'default-language' => 'en',
        'accept-language' => null,
        'max-languages' => 99,
        'available' => [],
        'synonyms' => [
            'gb' => 'en',
            'us' => 'en',
            'cn' => 'zh',
            'hk' => 'zh',
            'tw' => 'zh',
        ]
        // uk and us are synonyms for en.
        // if HTTP_ACCEPT_LANGUAGE is set to 'gb' or 'us'
        // then 'en' will be used instead.
    ];


    /**
     * Translate constructor.
     *
     * @param LoaderInterface $loader messages loader
     * @param array $options same as self::options
     * @param LoggerInterface|null $logger PSR-3 compatible logging library (ex. Monolog)
     */
    public function __construct(
        protected LoaderInterface $loader,
        protected PluralProvider $pluralProvider,
        array $options = [],
        protected ?LoggerInterface $logger = null
    )
    {
        if(!array_key_exists('available', $options)){
            $options['available'] = $this->loader->getLanguages();
        }
        $this->setOptions($options);
        $this->setLanguage($this->getLanguage(true));
    }

    /**
     * @return LoggerInterface|null
     */
    public function getLogger(): ?LoggerInterface
    {
        return $this->logger;
    }

    /**
     * @param LoggerInterface $logger
     *
     * @return $this
     */
    public function setLogger(LoggerInterface $logger): static
    {
        $this->logger = $logger;
        return $this;
    }

    /**
     * @param string $message
     * @param array  $context
     */
    public function addLoggerWarning(string $message, array $context = [])
    {
        if (null !== $this->logger) {
            $this->logger->warning($message, $context);
        }
    }

    /**
     * Set specified options
     *
     * @param array $options options to set
     *
     * @return void
     */
    public function setOptions(array $options)
    {
        foreach ($options as $key => $value) {
            $this->options[$key] = $value;
        }
    }

    /**
     * Set language for translations with method t() and plural()
     *
     * @param string $language language code
     *
     * @return void
     */
    public function setLanguage(string $language)
    {
        $this->language = $language;
        $this->loader->setLanguage($language);
    }

    /**
     * Get translation of message for language
     *
     * @param string $string to translate
     *
     * @return string|array
     */
    protected function getMessage(string $string): string|array
    {
        return $this->loader->get($string);
    }

    /**
     * Get current language
     *
     * @param bool $force force get language
     *
     * @return string $language language code
     */
    public function getLanguage(bool $force = false): string
    {
        if (empty($this->language) || $force) {
            if (!empty($this->options['language'])) {
                $acceptLanguage = $this->options['language'];
            } elseif (!empty($this->options['accept-language'])) {
                $acceptLanguage = $this->options['accept-language'];
            } elseif (!empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
                $acceptLanguage = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
            } else {
                $acceptLanguage = $this->options['default-language'];
            }
            $languages = $this->detectLanguages($acceptLanguage);
            $this->language = $this->getBestMatchingLanguage($languages);
        }
        return $this->language;
    }

    /**
     * Get best matching locale code based on options
     *
     * @param string[] $languages detected locales
     */
    protected function getBestMatchingLanguage(array $languages): string
    {
        if (!empty($this->options['available'])) {
            foreach ($languages as $langUser) {
                $shortLang = $this->shortLanguageCode($langUser);
                foreach ($this->options['available'] as $langAvailable) {
                    if ($langUser == $langAvailable
                        || (isset($this->options['synonyms'][$langUser])
                            && $this->options['synonyms'][$langUser] == $langAvailable)
                    ) {
                        return $langAvailable;
                    } elseif ($shortLang == $langAvailable
                        || (isset($this->options['synonyms'][$shortLang])
                            && $this->options['synonyms'][$shortLang] == $langAvailable)
                    ) {
                        return $langAvailable;
                    }
                }
            }
        }
        return $this->options['default-language'];
    }

    /**
     * Returns short (2-3 characters) locale code
     *
     * @param string $language long locale code (ex. en-US, zh_HKG, de-CH, etc.)
     *
     * @return string
     */
    protected function shortLanguageCode(string $language): string
    {
        if (strlen($language) > 2) {
            $dashPos = strpos($language, '-');
            if ($dashPos > 0) {
                $language = substr($language, 0, $dashPos);
            } else {
                $underscorePos = strpos($language, '_');
                if ($underscorePos > 0) {
                    $language = substr($language, 0, $underscorePos);
                }
            }
        }
        return $language;
    }

    /**
     * Detect acceptable locales by accept-language browser header.
     *
     * @param string $acceptLanguage accept-language browser header
     * @param int $resolution     resolution of locale qualities
     *
     * @return string[]
     */
    protected function detectLanguages(string $acceptLanguage, int $resolution = 100): array
    {
        $tags = array_map('trim', explode(',', $acceptLanguage));
        $languages = [];
        $languagesOrder = [];
        foreach ($tags as $tag) {
            $split = array_map('trim', explode(';', $tag, 2));
            if (empty($split[1])) {
                $q = $resolution;
            } else {
                $qArr = array_map('trim', explode('=', $split[1], 2));
                if (!empty($qArr) && !empty($qArr[1]) && is_numeric($qArr[1])) {
                    $q = floor($qArr[1] * $resolution);
                } else {
                    $q = 0;
                }
            }
            $languages[] = $split[0];
            $languagesOrder[] = $q;
        }
        array_multisort($languagesOrder, SORT_DESC, $languages, SORT_DESC);
        return array_slice($languages, 0, $this->options['max-languages']);
    }


    /**
     * Show translated message
     *
     * @param string     $string string to translate.
     * @param array $args   vsprintf with these arguments will be used if set.
     *
     * @return string translated string.
     */
    public function translate(string $string, array ...$args): string
    {
        if(!empty($args)){$args = current($args);}
        $string = $this->getMessage($string);
        if(is_array($string)){
            $string = current($string);
        }
        return vsprintf($string, $args);
    }


    /**
     * Chooses plural translate based on $x
     *
     * @param string $string string to translate divided with "|" character.
     * @param int $x plural variable.
     * @param array $args vsprintf with these arguments will be used
     *                       if set (optional).
     *
     * @return string translated string.
     */
    public function plural(string $string, int $x, ...$args): string
    {
        $string = $this->getMessage($string);
        if(!is_array($string) || count($string) == 1){
            return $string;
        }

        $plural = $this->pluralProvider->get($this->language, $x);
        $string = (array_key_exists($plural, $string) ? $string[$plural] : $string[0]);
        return vsprintf($string, $args);
    }

}