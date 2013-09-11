<?php

namespace AmericanReading\Restructure\Item;

use AmericanReading\Restructure\Interfaces\ItemInterface;
use AmericanReading\Restructure\Interfaces\StorageInterface;
use AmericanReading\Restructure\Storage\KeyValueStorage;

/**
 * Group of Items mapped to field names.
 *
 * The result from this item is a stdClass object with a member for each item name.
 */
class CompoundItem extends Collection
{
    /**
     * Create a new CompoundItem. Accepts an array and adds each member of the array to itself
     * using addItem. Each array member must be either:
     *  1. A string indicating the name of the field.
     *  2. An array with entries to pass to addItem as arguments.
     *
     * @param array $items
     */
    public function __construct(array $items = null)
    {
        $this->items = array();

        if ($items) {
            foreach ($items as $item) {
                if (is_array($item)) {
                    // Invloke $this->addItem() and pass the array as arguments.
                    call_user_func_array(array($this, 'addItem'), $item);
                } else {
                    $this->addItem($item);
                }
            }
        }
    }

    /**
     * @param $name
     * @param ItemInterface|string $item
     * @param callable $processCallable
     */
    public function addItem($name, $item = null, $processCallable = null)
    {
        if ($item === null) {
            $item = $name;
        }

        if ($item instanceof ItemInterface) {
            $this->items[$name] = $item;
        } else {
            $this->items[$name] = new Item($item, $processCallable);
        }
    }

    public function getNewStorage()
    {
        return new KeyValueStorage();
    }

    public function read($row, StorageInterface $storage)
    {
        foreach ($this->items as $name => $item) {
            /** @var ItemInterface $item */
            $subStorage = $storage->get($name);
            if ($subStorage === null) {
                $subStorage = $item->getNewStorage();
            }
            $item->read($row, $subStorage);
            $storage->update($subStorage, $name);
        }
    }
}
