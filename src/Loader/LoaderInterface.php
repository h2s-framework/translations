<?php

namespace Siarko\Translations\Loader;

use Psr\Container\ContainerInterface;

/**
 * Interface LoaderInterface
 * @package DElfimov\Translate\Loader
 */
interface LoaderInterface extends ContainerInterface
{

    /**
     * Determines whether a language is available.
     * @param string $language language code
     * @return bool
     */
    public function hasLanguage(string $language): bool;

    /**
     * Set a language for a messages container
     * @param string $language language code
     * @return void
     */
    public function setLanguage(string $language): void;

    /**
     * Return list of available languages
     * @return array
     */
    public function getLanguages(): array;

    /**
     * Fetches messages.
     * @param string $message
     * @return string|array translated message
     */
    public function get(string $message): string|array;

    /**
     * Determines whether a translation is available.
     * @param string $message
     * @return bool
     */
    public function has(string $message): bool;
}
