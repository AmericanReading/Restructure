<?php

namespace AmericanReading\Restructure\Storage;

use AmericanReading\Restructure\Interfaces\StorageInterface;
use stdClass;

/**
 * Storage representing maps of data. The expand() method returns a stdClass array.
 */
class KeyValueStorage implements StorageInterface
{
    /** @var array Associative array for holding values. */
    private $data;

    public function __construct()
    {
        $this->data = array();
    }

    /**
     * Return the value associated with the given $id. null and empty strings return null.
     *
     * @param mixed $id
     * @return mixed|null
     */
    public function get($id = null)
    {
        if ($id === null || $id === '') {
            return null;
        }
        if (isset($this->data[$id])) {
            return $this->data[$id];
        }
        return null;
    }

    /**
     * Store a new value associated with the given $id. If $id is null or empty, $value is ignored.
     *
     * @param mixed $value
     * @param null $id
     */
    public function update($value, $id = null)
    {
        if ($id === null || $id === '') {
            return;
        }
        $this->data[$id] = $value;
    }

    /**
     * Return stdClass object representing the key-value pairs of the data;
     */
    public function expand()
    {
        $expanded = new stdClass();
        foreach ($this->data as $name => $storage) {
            /** @var StorageInterface $storage */
            $expanded->{$name} = $storage->expand();
        }
        return $expanded;
    }
}
