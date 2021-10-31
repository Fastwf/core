<?php

namespace Fastwf\Core\Session;

use Fastwf\Core\Utils\ArrayProxy;

/**
 * Session object that allows to hold data in session.
 */
class Session extends ArrayProxy
{

    protected $modified = false;
    
    protected $cleared = false;
    protected $modifications = [];

    public function __construct(&$array = [], $isSuperGlobal = false)
    {
        parent::__construct([], $isSuperGlobal);
        // The array proxy use a copy of the array
        //  For session usage, using the reference is required to proxy $_SESSION super global
        $this->array = &$array;
    }

    /// OVERRIDE METHODS

    public function update($array = [])
    {
        parent::update($array);

        $this->modified = true;
        $this->modifications = \array_replace($this->modifications, $array);
    }

    public function set($key, $value)
    {
        parent::set($key, $value);

        $this->modified = true;
        $this->modifications[$key] = $value;
    }

    public function remove($key)
    {
        parent::remove($key);

        $this->modified = true;
        unset($this->modifications[$key]);
    }

    /// EXTENSIONS

    /**
     * Allows to clear all data in the array.
     *
     * @param array $array the array reference.
     */
    private function clearArray(&$array)
    {
        foreach(\array_keys($array) as $key)
        {
            unset($array[$key]);
        }
    }

    /**
     * Clear the session data.
     * 
     * @return void
     */
    public function clear()
    {
        $this->clearArray($this->array);

        $this->modified = true;

        $this->cleared = true;
        $this->modifications = [];
    }

    /**
     * Identify if the array have been updated.
     *
     * @return boolean true when modification occurred.
     */
    public function isModified()
    {
        return $this->modified;
    }

    /**
     * Apply modifications observed to the array in parameter.
     *
     * @param array $array the array to update from internal changes observed
     * @return void
     */
    public function applyModifications(&$array)
    {
        // When clear was called, remove all keys
        if ($this->cleared)
        {
            $this->clearArray($array);
        }

        // Update the array from modifications observed
        foreach ($this->modifications as $key => $value)
        {
            $array[$key] = $value;
        }
    }

    /**
     * Allows to refresh the internal array by replacing with the array in parameter.
     *
     * @param array $array the reference to the new array.
     */
    public function refresh(&$array)
    {
        $this->array = $array;
    }

}
