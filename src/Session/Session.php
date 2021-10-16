<?php

namespace Fastwf\Core\Session;

use Fastwf\Core\Utils\ArrayProxy;

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

    /**
     * {@inheritDoc}
     */
    public function update($array = [])
    {
        parent::update($array);

        $this->modified = true;
        $this->modifications = \array_replace($this->modifications, $array);
    }

    /**
     * {@inheritDoc}
     */
    public function set($key, $value)
    {
        parent::set($key, $value);

        $this->modified = true;
        $this->modifications[$key] = $value;
    }

    /**
     * {@inheritDoc}
     */
    public function remove($key)
    {
        parent::remove($key);

        $this->modified = true;
        unset($this->modifications[$key]);
    }

    /// EXTENSIONS

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

}
