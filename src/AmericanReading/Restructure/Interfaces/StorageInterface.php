<?php

namespace AmericanReading\Restructure\Interfaces;

/**
 * Wraps data for use internal inside items.
 */
interface StorageInterface
{
    /**
     * Given an identifier, return a value.
     *
     * @param mixed $id
     * @return mixed
     */
    public function get($id = null);

    /**
     * Provide a new value for the wrapped data, or optionally a member of the data by ID.
     *
     * @param mixed $value
     * @param mixed $id
     */
    public function update($value, $id = null);

    /**
     * Return a represnetation of the stored data.
     *
     * @return mixed
     */
    public function expand();
}
