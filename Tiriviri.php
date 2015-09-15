<?php
/**
 * Copyright 2013, Kerem Gunes <http://qeremy.com/>.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may
 * not use this file except in compliance with the License. You may obtain
 * a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 */

/**
 * @object  Tiriviri
 * @version v1.0
 * @author  Kerem Gunes <qeremy@gmail>
 */
class Tiriviri
{
    /**
     * Multibyte charset.
     * @const string
     */
    const CHARSET = 'utf-8';

    /**
     * Run method commands.
     * @const string
     */
    const CMD_EA   = 'e-a',
          CMD_II   = 'ı-i',
          CMD_ININ = 'ın-in',
          CMD_DEDA = 'de-da';

    /**
     * All chars.
     * @var array
     */
    static $charsAll = [
        'a', 'ı', 'A', 'I', 'e', 'i', 'E', 'İ', 'o', 'u', 'O', 'U',
        'ö', 'ü', 'Ö', 'Ü', 'b', 'c', 'd', 'f', 'g', 'h', 'j', 'k',
        'l', 'm', 'n', 'p', 'q', 'r', 's', 't', 'v', 'w', 'x', 'y',
        'z', 'ç', 'ğ', 'ş'
    ];

    /**
     * Vowels.
     * @var array
     */
    static $charsVowel = [
        'a', 'ı', 'A', 'I', 'e', 'i', 'E', 'İ', 'o', 'u', 'O', 'U',
        'ö', 'ü', 'Ö', 'Ü'
    ];

    /**
     * Consonants.
     * @var array
     */
    static $charsConsonant = [
        'b', 'c', 'd', 'f', 'g', 'h', 'j', 'k', 'l', 'm', 'n', 'p',
        'q', 'r', 's', 't', 'v', 'w', 'x', 'y', 'z', 'ç', 'ğ', 'ş'
    ];

    /**
     * Partials.
     * @var array
     */
    static $charsVowel1 = ['a', 'ı', 'A', 'I'],
           $charsVowel2 = ['e', 'i', 'E', 'İ'],
           $charsVowel3 = ['o', 'u', 'O', 'U'],
           $charsVowel4 = ['ö', 'ü', 'Ö', 'Ü'];

    /**
     * Lower-case hepler.
     *
     * @param  string $s
     * @return string
     */
    public static function toLower($s) {
        // fix trouble chars
        $s = str_replace(
            ['I', 'İ'],
            ['ı', 'i'],
        $s);

        return mb_strtolower($s, self::CHARSET);
    }

    /**
     * Suffix appender.
     *
     * @param  string $word
     * @param  string $cmd
     * @return string
     */
    public static function run($word, $cmd = self::CMD_ININ) {
        $chars = [];
        $temps = array_reverse(preg_split('~~u', $word, -1, PREG_SPLIT_NO_EMPTY));
        $lastVowel = null;
        foreach ($temps as $temp) {
            $temp = self::toLower($temp);
            // fill out chars
            if (in_array($temp, self::$charsAll)) {
                $chars[] = $temp;
                // find last vowel
                if (is_null($lastVowel) && in_array($temp, self::$charsVowel)) {
                    $lastVowel = $temp;
                }
            }
        }

        // set as none default
        $rval = '';

        // let's do it!
        switch ($cmd) {
            // Ali'nin, Kerem'in
            case (self::CMD_ININ):
                    if (in_array($lastVowel, self::$charsVowel1)) $rval = 'nın';
                elseif (in_array($lastVowel, self::$charsVowel2)) $rval = 'nin';
                elseif (in_array($lastVowel, self::$charsVowel3)) $rval = 'nun';
                elseif (in_array($lastVowel, self::$charsVowel4)) $rval = 'nün';
                $lastLetter = $chars[0];
                // delete if not vowel
                if (!in_array($lastLetter, self::$charsVowel)) {
                    $rval = mb_substr($rval, 1, null, self::CHARSET);
                }
                break;
            // Ali'de, Ali'den, Ali'deki... Kerem'de, Kerem'den, Kerem'deki
            case (self::CMD_DEDA):
                    if (in_array($lastVowel, self::$charsVowel1)) $rval = 'da';
                elseif (in_array($lastVowel, self::$charsVowel2)) $rval = 'de';
                elseif (in_array($lastVowel, self::$charsVowel3)) $rval = 'da';
                elseif (in_array($lastVowel, self::$charsVowel4)) $rval = 'de';
                $lastLetter = $chars[0];
                // hard consonants or something like that... :)
                if (preg_match('~[pçtksşhf]~u', $lastLetter)) {
                    $rval = 't'. $rval[1];
                }
                break;
            // Ali'ye, Kerem'e
            case (self::CMD_EA):
                    if (in_array($lastVowel, self::$charsVowel1)) $rval = 'ya';
                elseif (in_array($lastVowel, self::$charsVowel2)) $rval = 'ye';
                elseif (in_array($lastVowel, self::$charsVowel3)) $rval = 'ya';
                elseif (in_array($lastVowel, self::$charsVowel4)) $rval = 'ye';
                $lastLetter = $chars[0];
                // delete if not consonant
                if (in_array($lastLetter, self::$charsConsonant)) {
                    $rval = mb_substr($rval, 1, null, self::CHARSET);
                }
                break;
            // Ali'yi, Kerem'i
            case (self::CMD_II):
                    if (in_array($lastVowel, self::$charsVowel1)) $rval = 'yı';
                elseif (in_array($lastVowel, self::$charsVowel2)) $rval = 'yi';
                elseif (in_array($lastVowel, self::$charsVowel3)) $rval = 'yu';
                elseif (in_array($lastVowel, self::$charsVowel4)) $rval = 'yü';
                $lastLetter = $chars[0];
                // delete if not consonant
                if (in_array($lastLetter, self::$charsConsonant)) {
                    $rval = mb_substr($rval, 1, null, self::CHARSET);
                }
                break;
        }

        return $rval;
    }
}
