<?php

namespace Fastwf\Core\Utils;

use Fastwf\Core\Utils\StringUtil;

/**
 * A proxy that allows to access to array values.
 */
class ArrayProxy {

    /**
     * The array of data storage.
     *
     * @var array
     */
    protected $array;

    /**
     * True when thee data storage array is a php super global array.
     *
     * @var bool
     */
    protected $superGlobal;

    /**
     * Initialize the proxy with the given array
     *
     * @param array $array the array to use as storage ([] by default)
     * @param bool $isSuperGlobal true when it's a super global array like $_GET or $_POST
     */
    public function __construct($array = [], $isSuperGlobal = false) {
        $this->array = $array;
        $this->superGlobal = $isSuperGlobal;
    }

    /**
     * Returns the parameter keys.
     *
     * @return array An array of parameter keys
     */
    public function keys() {
        return array_keys($this->array);
    }

    /**
     * Adds values by mapping key on existing keys.
     * New key are added and existing keys are replaced.
     *
     * @param array $array
     */
    public function update($array = []) {
        $this->array = array_replace($this->array, $array);
    }

    /**
     * Returns true if the key is defined.
     *
     * @param string $key the key to verify
     * @return bool true if the key exists, false otherwise
     */
    public function has($key) {
        return \array_key_exists($key, $this->array);
    }

    /**
     * Sets a value associated to the key.
     *
     * @param string $key the key to set
     * @param mixed $value The value
     */
    public function set($key, $value) {
        $this->array[$key] = $value;
    }

    /**
     * Returns a parameter by name.
     *
     * @param string $key the key to extract
     * @param mixed $default The default value if the parameter key does not exist
     * @param bool $array true when the array must be extracted
     * @return mixed
     */
    public function get($key, $default = null, $array = false) {
        // When the key to extract is an array and the array is a super global, add '[]' to the key.
        //  Because php create an array from request only if the var name finish with '[]',
        //  to simplify the access from the array proxy, the '[]' is hidden to the user
        if ($array && $this->superGlobal && !StringUtil::endsWith($key, '[]')) {
            $key = $key . "[]";
        }

        return \array_key_exists($key, $this->array) ? $this->array[$key] : $default;
    }

    /**
     * Returns a parameter by name as int.
     *
     * @param string $key the key to extract
     * @param mixed $default The default value if the parameter key does not exist
     * @param bool $array true when the array must be extracted
     * @return int|array|null when key exists, an int or an array of int, else null when array is false or [] otherwise
     */
    public function getInt($key, $default = null, $array = false) {
        if ($array) {
            return self::arrayMap(function ($item) { return \intval($item); }, $this->get($key, null, true));
        } else {
            return \intval($this->get($key, $default));
        }
    }

    /**
     * Returns a parameter by name as float.
     *
     * @param string $key the key to extract
     * @param mixed $default The default value if the parameter key does not exist
     * @param bool $array true when the array must be extracted
     * @return float|array|null when key exists, an float or an array of float, else null when array is false or [] otherwise
     */
    public function getFloat($key, $default = null, $array = false) {
        if ($array) {
            return self::arrayMap(function ($item) { return \floatval($item); }, $this->get($key, null, true));
        } else {
            return \floatval($this->get($key, $default));
        }
    }

    /**
     * Returns a parameter by name as bool.
     *
     * @param string $key the key to extract
     * @param mixed $default The default value if the parameter key does not exist
     * @param bool $array true when the array must be extracted
     * @return bool|array|null when key exists, an bool or an array of bool, else null when array is false or [] otherwise
     */
    public function getBoolean($key, $default = null, $array = false) {
        if ($array) {
            return self::arrayMap(function ($item) { return \boolval($item); }, $this->get($key, null, true));
        } else {
            return \boolval($this->get($key, $default));
        }
    }

    /**
     * Removes the value associated to the key.
     *
     * @param string $key the key to remove from array
     */
    public function remove($key) {
        unset($this->array[$key]);
    }

    /**
     * Call the array_map method when the $array parameter is not null.
     * 
     * It can be considered as safe_array_map function.
     *
     * @param callable $callback
     * @param array|null $array the array to map
     * @return array|null the array transformed
     */
    private static function arrayMap($callback, $array) {
        if ($array !== null) {
            return \array_map($callback, $array);
        }

        return null;
    }

}
