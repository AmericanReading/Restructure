<?php

namespace AmericanReading\Restructure\Interfaces;

use AmericanReading\Restructure\Interfaces\StorageInterface;

interface ItemInterface
{
    /**
     * Instantiate and return a new storage object for use in this instance.
     * @return StorageInterface
     */
    public function getNewStorage();

    /**
     * Scan the contents of the passed $row and update the passed $storage appropriately.
     *
     * @param array|object $row One row from a result set to process
     * @param StorageInterface $storage Object to update with information from $row
     */
    public function read($row, StorageInterface $storage);
}
