<?php

namespace AmericanReading\Restructure\Storage;

use AmericanReading\Restructure\Interfaces\StorageInterface;

/**
 * Storage representing lists of data. The expand() method returns a numeric array.
 */
class ListStorage implements StorageInterface
{
    /** @var array List array for holding values. */
    private $data;

    /**
     * This method always returns null.
     *
     * @param null $id
     * @return mixed|null
     */
    public function get($id = null)
    {
        return null;
    }

    /**
     * Append a new member to the list. $id is ignored.
     *
     * @param mixed $value
     * @param null $id
     */
    public function update($value, $id = null)
    {
        $this->data[] = $value;
    }

    /**
     * @return array List array representing the data.
     */
    public function expand()
    {
        $expanded = array();
        foreach ($this->data as $storage) {
            /** @var StorageInterface $storage */
            $expanded[] = $storage->expand();
        }
        return $expanded;
    }
}
