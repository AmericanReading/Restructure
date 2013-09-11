<?php

namespace AmericanReading\Restructure\Storage;

/**
 * Storage representing unique lists of data. The expand() method returns a numeric array.
 */
class SetStorage extends KeyValueStorage
{
    /**
     * @return array Numeric array of unique values.
     */
    public function expand()
    {
        return array_values((array) parent::expand());
    }
}
