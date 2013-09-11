<?php

namespace AmericanReading\Restructure\Item;

use AmericanReading\Restructure\Interfaces\StorageInterface;
use AmericanReading\Restructure\Storage\SetStorage;

/**
 * Collection of one type of item with items associated by IDs.
 *
 * The result of this item is a numeric array of unique items.
 */
class SetItem extends MapItem
{
    /**
     * @return StorageInterface
     */
    public function getNewStorage()
    {
        return new SetStorage();
    }
}
