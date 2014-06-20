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
 * @class Tiriviri v0.1
 *
 * Tiriviri object
 * Fixes noun suffixes (case suffixes) in Türkçe
 */
class Tiriviri
{
    const CMD_EA   = 'e-a';
    const CMD_II   = 'ı-i';
    const CMD_ININ = 'ın-in';
    const CMD_DEDA = 'de-da';

    // All chars
    static $chars_all = array('a', 'ı', 'A', 'I', 'e', 'i', 'E', 'İ', 'o', 'u', 'O', 'U', 'ö', 'ü', 'Ö', 'Ü', 'b', 'c', 'd', 'f', 'g', 'h', 'j', 'k', 'l', 'm', 'n', 'p', 'q', 'r', 's', 't', 'v', 'w', 'x', 'y', 'z', 'ç', 'ğ', 'ş');
    // Vowels
    static $chars_vowel = array('a', 'ı', 'A', 'I', 'e', 'i', 'E', 'İ', 'o', 'u', 'O', 'U', 'ö', 'ü', 'Ö', 'Ü');
    // Consonants
    static $chars_consonant = array('b', 'c', 'd', 'f', 'g', 'h', 'j', 'k', 'l', 'm', 'n', 'p', 'q', 'r', 's', 't', 'v', 'w', 'x', 'y', 'z', 'ç', 'ğ', 'ş');
    static $chars_vowel1 = array('a', 'ı', 'A', 'I');
    static $chars_vowel2 = array('e', 'i', 'E', 'İ');
    static $chars_vowel3 = array('o', 'u', 'O', 'U');
    static $chars_vowel4 = array('ö', 'ü', 'Ö', 'Ü');

    public static function toLower($s) {
        return mb_strtolower(str_replace(array('I', 'İ'), array('ı', 'i'), $s), 'UTF-8');
    }

    public static function run($word, $cmd = '') {
        $chars = array();
        $split = array_reverse(preg_split('//u', $word, -1, PREG_SPLIT_NO_EMPTY));
        foreach ($split as $s) {
            $s = self::toLower($s);
            if (in_array($s, self::$chars_all)) {
                $chars[] = $s;
            }
        }
        $last_vowel = null;
        foreach ($chars as $char) {
            if (in_array($char, self::$chars_vowel)) {
                $last_vowel = $char;
                break;
            }
        }

        if ($cmd == '' || $cmd == self::CMD_ININ) {
                if (in_array($last_vowel, self::$chars_vowel1)) $rval = 'nın';
            elseif (in_array($last_vowel, self::$chars_vowel2)) $rval = 'nin';
            elseif (in_array($last_vowel, self::$chars_vowel3)) $rval = 'nun';
            elseif (in_array($last_vowel, self::$chars_vowel4)) $rval = 'nün';
            $last_letter = $chars[0];
            // Delete (if not vowel)
            if (!in_array($last_letter, self::$chars_vowel)) {
                $rval = mb_substr($rval, 1);
            }
        } elseif ($cmd == self::CMD_DEDA) {
                if (in_array($last_vowel, self::$chars_vowel1)) $rval = 'da';
            elseif (in_array($last_vowel, self::$chars_vowel2)) $rval = 'de';
            elseif (in_array($last_vowel, self::$chars_vowel3)) $rval = 'da';
            elseif (in_array($last_vowel, self::$chars_vowel4)) $rval = 'de';
            $last_letter = $chars[0];
            // Hard consonants or something like that... :)
            if (preg_match('/[pçtksşhf]/u', $last_letter)) {
                $rval = 't'. $rval[1];
            }
        } elseif ($cmd == self::CMD_EA) {
                if (in_array($last_vowel, self::$chars_vowel1)) $rval = 'ya';
            elseif (in_array($last_vowel, self::$chars_vowel2)) $rval = 'ye';
            elseif (in_array($last_vowel, self::$chars_vowel3)) $rval = 'ya';
            elseif (in_array($last_vowel, self::$chars_vowel4)) $rval = 'ye';
            $last_letter = $chars[0];
            // Delete (if not consonant)
            if (in_array($last_letter, self::$chars_consonant)) {
                $rval = mb_substr($rval, 1);
            }
        } elseif ($cmd == self::CMD_II) {
                if (in_array($last_vowel, self::$chars_vowel1)) $rval = 'yı';
            elseif (in_array($last_vowel, self::$chars_vowel2)) $rval = 'yi';
            elseif (in_array($last_vowel, self::$chars_vowel3)) $rval = 'yu';
            elseif (in_array($last_vowel, self::$chars_vowel4)) $rval = 'yü';
            $last_letter = $chars[0];
            // Delete (if not consonant)
            if (in_array($last_letter, self::$chars_consonant)) {
                $rval = mb_substr($rval, 1);
            }
        }
        return $rval;
    }
}
