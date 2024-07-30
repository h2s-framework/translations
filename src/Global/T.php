<?php

function T(string $text, ...$arguments){
    $translator = \Siarko\Translations\Translations::getInstance();
    return $translator->translate($text, $arguments);
}