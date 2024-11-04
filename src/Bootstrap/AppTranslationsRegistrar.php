<?php

declare(strict_types=1);

namespace Siarko\Translations\Bootstrap;
use Siarko\ActionRouting\HttpApplication;
use Siarko\Plugins\Config\Attribute\PluginMethod;
use Siarko\Translations\Translations;

class AppTranslationsRegistrar
{

    /**
     * Used only for loading Translations Singleton
     * @param Translations $translations
     */
    public function __construct(
        private readonly \Siarko\Translations\Translations $translations
    )
    {
    }

    /**
     * Method is here just to instantiate the class
     * @param HttpApplication $subject
     * @return void
     */
    #[PluginMethod]
    public function beforeStart(HttpApplication $subject): void {}

}