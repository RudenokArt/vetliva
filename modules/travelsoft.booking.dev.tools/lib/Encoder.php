<?php

namespace travelsoft\booking;

/**
 * Класс шифрования информации
 */
class Encoder {

    public static function encode (array $value) {
        return base64_encode(gzcompress(serialize($value), 9));
    }

    public static function decode ($value) {
        return unserialize(gzuncompress(base64_decode($value)));
    }

    public static function hash ($value, $salt) {
         return md5($value . $salt);
    }

    public static function checkhash ($value, $hash, $salt) {
          return md5 ($value . $salt) === $hash;
    }
    
}
