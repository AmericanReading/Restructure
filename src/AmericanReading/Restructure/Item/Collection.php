<?php

namespace AmericanReading\Restructure\Item;

use AmericanReading\Restructure\Interfaces\ItemInterface;

/**
 * Abstract class defining a group of items.
 */
abstract class Collection implements ItemInterface
{
    /**
     * Read the contents of the rows and return a restructured result.
     *
     * @param array $rows
     * @return mixed
     */
    public function readAll(array $rows)
    {
        $storage = $this->getNewStorage();
        foreach ($rows as $row) {
            $this->read($row, $storage);
        }
        return $storage->expand();
    }
}
