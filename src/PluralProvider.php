<?php

namespace Siarko\Translations;

class PluralProvider
{
    /**
     * The plural rules are derived from code of the Zend Framework (2010-09-25),
     * which is subject to the new BSD license
     * (http://framework.zend.com/license/new-bsd).
     * Copyright (c) 2005-2010 Zend Technologies USA Inc.
     * (http://www.zend.com)
     * https://github.com/zendframework/zf1/blob/master/library/Zend/Translate/Plural.php
     *
     * @param string $locale language code
     * @param int    $x      plural variable
     *
     * @return integer index of plural form rule.
     */
    public function get(string $locale, int $x): int
    {
        switch ($locale) {
            case 'az':
            case 'bo':
            case 'dz':
            case 'id':
            case 'ja':
            case 'jv':
            case 'ka':
            case 'km':
            case 'kn':
            case 'ko':
            case 'ms':
            case 'th':
            case 'tr':
            case 'vi':
            case 'zh':
                $index = 0;
                break;

            case 'af':
            case 'bn':
            case 'bg':
            case 'ca':
            case 'da':
            case 'de':
            case 'el':
            case 'en':
            case 'eo':
            case 'es':
            case 'et':
            case 'eu':
            case 'fa':
            case 'fi':
            case 'fo':
            case 'fur':
            case 'fy':
            case 'gl':
            case 'gu':
            case 'ha':
            case 'he':
            case 'hu':
            case 'is':
            case 'it':
            case 'ku':
            case 'lb':
            case 'ml':
            case 'mn':
            case 'mr':
            case 'nah':
            case 'nb':
            case 'ne':
            case 'nl':
            case 'nn':
            case 'no':
            case 'om':
            case 'or':
            case 'pa':
            case 'pap':
            case 'ps':
            case 'pt':
            case 'so':
            case 'sq':
            case 'sv':
            case 'sw':
            case 'ta':
            case 'te':
            case 'tk':
            case 'ur':
            case 'zu':
                $index = ($x == 1) ? 0 : 1;
                break;

            case 'am':
            case 'bh':
            case 'fil':
            case 'fr':
            case 'gun':
            case 'hi':
            case 'ln':
            case 'mg':
            case 'nso':
            case 'xbr':
            case 'ti':
            case 'wa':
                $index = (($x == 0) || ($x == 1)) ? 0 : 1;
                break;

            case 'be':
            case 'bs':
            case 'hr':
            case 'ru':
            case 'sr':
            case 'uk':
                $index = (
                    ($x % 10 == 1) && ($x % 100 != 11)
                ) ? (
                0
                ) : (
                (
                    ($x % 10 >= 2)
                    && ($x % 10 <= 4)
                    && (($x % 100 < 10) || ($x % 100 >= 20))
                ) ? 1 : 2
                );
                break;

            case 'cs':
            case 'sk':
                $index = ($x == 1) ? 0 : ((($x >= 2) && ($x <= 4)) ? 1 : 2);
                break;

            case 'ga':
                $index = ($x == 1) ? 0 : (($x == 2) ? 1 : 2);
                break;

            case 'lt':
                $index = (
                    ($x % 10 == 1) && ($x % 100 != 11)
                ) ? (
                0
                ) : (
                (($x % 10 >= 2) && (($x % 100 < 10) || ($x % 100 >= 20))) ? 1 : 2
                );
                break;

            case 'sl':
                $index = (
                    $x % 100 == 1
                ) ? (
                0
                ) : (
                ($x % 100 == 2) ? 1 : ((($x % 100 == 3) || ($x % 100 == 4)) ? 2 : 3)
                );
                break;

            case 'mk':
                $index = ($x % 10 == 1) ? 0 : 1;
                break;

            case 'mt':
                $index = (
                    $x == 1
                ) ? (
                0
                ) : (
                (
                    ($x == 0) || (($x % 100 > 1) && ($x % 100 < 11))
                ) ? (
                1
                ) : ((($x % 100 > 10) && ($x % 100 < 20)) ? 2 : 3)
                );
                break;

            case 'lv':
                $index = ($x == 0) ? 0 : ((($x % 10 == 1) && ($x % 100 != 11)) ? 1 : 2);
                break;

            case 'pl':
                $index = (
                    $x == 1
                ) ? (
                0
                ) : (
                (
                    ($x % 10 >= 2)
                    && ($x % 10 <= 4)
                    && (($x % 100 < 12) || ($x % 100 > 14))
                ) ? 1 : 2
                );
                break;

            case 'cy':
                $index = (
                    $x == 1
                ) ? (
                0
                ) : (($x == 2) ? 1 : ((($x == 8) || ($x == 11)) ? 2 : 3));
                break;

            case 'ro':
                $index = (
                    $x == 1
                ) ? (
                0
                ) : ((($x == 0) || (($x % 100 > 0) && ($x % 100 < 20))) ? 1 : 2);
                break;

            case 'ar':
                $index = (
                    $x == 0
                ) ? (
                0
                ) : (
                ($x == 1) ? 1 : (
                ($x == 2) ? 2 : (
                (
                    ($x >= 3)
                    && ($x <= 10)
                ) ? (
                3
                ) : (
                (($x >= 11) && ($x <= 99)) ? 4 : 5
                )
                )
                )
                );
                break;

            default:
                $index = 0;
                break;
        }
        return $index;
    }
}