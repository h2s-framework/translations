<?php

declare(strict_types=1);

namespace Siarko\Translations\Bootstrap;
class AppTranslationsRegistrar
{

    public function __construct(
        private \Siarko\Translations\Translations $translations
    )
    {
    }

}