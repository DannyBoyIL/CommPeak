<?php

namespace App\Traits;

trait Continents {

    static $code;
    
    static $dialCode = [
        1 => 'NA',
        2 => ['AF', 246 => 'AS', 297 => 'SA', 298 => 'EU', 299 => 'NA'],
        3 => 'EU',
        4 => 'EU',
        5 => ['SA', 50 => 'NA', 52 => 'NA', 53 => 'NA', 59 => [590 => 'NA', 596 => 'NA']],
        6 => ['OC', 60 => 'AS', 62 => 'AS', 63 => 'AS', 65 => 'AS', 66 => 'AS', 67 => [670 => 'AS', 673 => 'AS']],
        7 => 'AS',
        8 => 'AS',
        9 => 'AS'
    ];
    
    private function resolver($array, $phone, $i) {

        static $key = 0;
        $prefix = substr($phone, 0, $i);

        if (is_array($array)) {

            if (array_key_exists($prefix, $array)) {
                $key = $prefix;
                $array = $array[$prefix];
                return $this->resolver($array, $phone, ++$i);
            }
            $key = 0;
            $prefix = substr($phone, 0, ++$i);
        }

        if (!is_array($array)) {
            return $array;
        }
        return array_key_exists($prefix, static::$code) ? static::$code[$prefix] : static::$code[$key];
    }

    public function getContinentByPhone($phone, $i = 1) {
        static::$code = static::$dialCode[substr($phone, 0, $i)];
        return $this->resolver(static::$code, $phone, ++$i);
    }

    public function old_getContinentByPhone($phone) {

        $prefix[] = substr($phone, 0, 1);

        if (is_array(static::$dialCode[$prefix[0]])) {

            $prefix[] = substr($phone, 0, 2);

            if ($i = array_key_exists($prefix[1], static::$dialCode[$prefix[0]])) {

                if ($i = is_array(static::$dialCode[$prefix[0]][$prefix[1]])) {

                    $prefix[] = substr($phone, 0, 3);

                    if ($i = array_key_exists($prefix[2], static::$dialCode[$prefix[0]][$prefix[1]])) {

                        return static::$dialCode[$prefix[0]][$prefix[1]][$prefix[2]];
                    }
                    return static::$dialCode[$prefix[0]][intval($i)];
                }
                return static::$dialCode[$prefix[0]][$prefix[1]];
            }

            $prefix[] = substr($phone, 0, 3);

            if (array_key_exists($prefix[2], static::$dialCode[$prefix[0]])) {
                return static::$dialCode[$prefix[0]][$prefix[2]];
            }
            return static::$dialCode[$prefix[0]][intval($i)];
        }
        return static::$dialCode[$prefix[0]];
    }
}
