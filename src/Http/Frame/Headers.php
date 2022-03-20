<?php

namespace Fastwf\Core\Http\Frame;

use Fastwf\Api\Utils\ArrayProxy;

/**
 * Array proxy used to access to headers.
 * 
 * This class must be used for the request headers because the property access can be done case insensitive.
 */
class Headers extends ArrayProxy {

    public function __construct($array = []) {
        parent::__construct(self::normalize($array));
    }

    /// Override methods

    /**
     * {@inheritDoc}
     */
    public function update($array = []) {
        parent::update(self::normalize($array));
    }

    /**
     * {@inheritDoc}
     */
    public function has($key) {
        return parent::has(\strtolower($key));
    }

    /**
     * {@inheritDoc}
     */
    public function set($key, $value) {
        parent::set(\strtolower($key), $value);
    }

    /**
     * {@inheritDoc}
     */
    public function get($key, $default = null, $array = false) {
        return parent::get(\strtolower($key), $default, $array);
    }

    /**
     * {@inheritDoc}
     */
    public function remove($key) {
        parent::remove(\strtolower($key));
    }

    /**
     * Lower all keys of the array.
     *
     * @param array $array the array to normalize
     * @return array the array with key in lowercase
     */
    public static function normalize(&$array) {
        $mapped = [];

        foreach ($array as $key => $value) {
            $mapped[\strtolower($key)] = $value;
        }

        return $mapped;
    }

}
