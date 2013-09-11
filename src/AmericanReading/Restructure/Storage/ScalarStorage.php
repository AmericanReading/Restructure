<?php

namespace AmericanReading\Restructure\Storage;

use AmericanReading\Restructure\Interfaces\StorageInterface;

/**
 * Storage for basic PHP datatypes string, bool, int, float. This class can store only one value
 * at a time and has no concept of $id or key-value pairs.
 */
class ScalarStorage implements StorageInterface
{
    /** @var mixed a scalar data */
    private $value;

    /**
     * Return the value. This class ignores $id.
     * @param null $id
     * @return mixed
     */
    public function get($id = null)
    {
        return $this->value;
    }

    /**
     * @param mixed $value The new scalar value to store.
     * @param null $id
     */
    public function update($value, $id = null)
    {
        $this->value = $value;
    }

    /**
     * @return mixed The scalar value stored in the instance.
     */
    public function expand()
    {
        return $this->value;
    }
}
