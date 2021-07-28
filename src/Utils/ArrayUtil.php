<?php

namespace Fastwf\Core\Utils;

use Fastwf\Core\Exceptions\KeyError;

/**
 * Utility class that help to perform some actions on arrays.
 */
class ArrayUtil {

    /**
     * Try to access to the key by extracting the value from the array.
     * 
     * When failed, a KeyError is thrown.
     *
     * @param array $array The array where find the value
     * @param mixed $key The key to find
     * @return mixed The value extracted
     * @throws KeyError when the key is missing
     */
    public static function get($array, $key) {
        if (\array_key_exists($key, $array)) {
            return $array[$key];
        }

        throw new KeyError("The key '$key' is missing");
    }

    /**
     * Access to the value associated to the key in the array or return the default value.
     *
     * @param array $array The array where find the value
     * @param mixed $key The key to find
     * @param mixed $default The default value to return when key is missing
     * @return mixed The value extracted
     */
    public static function getSafe($array, $key, $default = null) {
        if (\array_key_exists($key, $array)) {
            return $array[$key];
        }

        return $default;
    }

}