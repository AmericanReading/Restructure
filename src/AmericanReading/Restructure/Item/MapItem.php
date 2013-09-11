<?php

namespace AmericanReading\Restructure\Item;

use AmericanReading\Restructure\Interfaces\ItemInterface;
use AmericanReading\Restructure\Interfaces\StorageInterface;
use AmericanReading\Restructure\Storage\KeyValueStorage;

/**
 * Collection of one type of item with items associated by IDs.
 *
 * The result from this item is a stdClass object with a member for each ID.
 */
class MapItem extends ListItem
{
    /** @var callable  Function that returns an ID given a row. This ID is used as the key. */
    protected $readIndexCallable;

    public function __construct(ItemInterface $item = null, $indexOn = null)
    {
        $this->item = $item;

        if ($indexOn !== null) {
            if (is_callable($indexOn)) {
                $this->setReadIndexCallable($indexOn);
            } else {
                $this->setReadIndexField($indexOn);
            }
        } else {
            $this->setReadIndexCallable(
                function ($row) {
                    return sha1(serialize($row));
                }
            );
        }
    }

    /**
     * @param $callable
     */
    public function setReadIndexCallable($callable)
    {
        $this->readIndexCallable = $callable;
    }

    /**
     * @param string $fieldName
     */
    public function setReadIndexField($fieldName)
    {
        $this->setReadIndexCallable(
            function ($row) use ($fieldName) {
                if (is_array($row)) {
                    return $row[$fieldName];
                } else {
                    return $row->$fieldName;
                }
            }
        );
    }

    /**
     * @return \AmericanReading\Restructure\Interfaces\StorageInterface
     */
    public function getNewStorage()
    {
        return new KeyValueStorage();
    }

    public function read($row, StorageInterface $storage)
    {
        $callable = $this->readIndexCallable;
        $id = $callable($row);

        if ($id === null || $id === '') {
            return;
        }

        $subStorage = $storage->get($id);
        if ($subStorage === null) {
            $subStorage = $this->item->getNewStorage();
        }

        $this->item->read($row, $subStorage);
        $storage->update($subStorage, $id);
    }
}
