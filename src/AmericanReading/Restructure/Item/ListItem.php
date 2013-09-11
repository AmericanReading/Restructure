<?php

namespace AmericanReading\Restructure\Item;

use AmericanReading\Restructure\Interfaces\ItemInterface;
use AmericanReading\Restructure\Interfaces\StorageInterface;
use AmericanReading\Restructure\Item\Collection;
use AmericanReading\Restructure\Storage\ListStorage;

/**
 * Collection of one type of item
 *
 * Read eads one item per row. For example, if $rows contains 100 items, this
 * instance will not group anything and will return 100 item.
 *
 * The result of this item is a numeric array of non-unique items.
 */
class ListItem extends Collection
{
    /** @var ItemInterface  The item instance to use as a template */
    protected $item;

    public function __construct(ItemInterface $item = null)
    {
        if ($item !== null) {
            $this->setItem($item);
        }
    }

    public function setItem(ItemInterface $item)
    {
        $this->item = $item;
    }

    /**
     * @return \AmericanReading\Restructure\Interfaces\StorageInterface
     */
    public function getNewStorage()
    {
        return new ListStorage();
    }

    public function read($row, StorageInterface $storage)
    {
        $newData = $this->item->getNewStorage();
        $this->item->read($row, $newData);
        $storage->update($newData);
    }
}
