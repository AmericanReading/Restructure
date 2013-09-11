<?php

namespace AmericanReading\Restructure\Item;

use AmericanReading\Restructure\Interfaces\ItemInterface;
use AmericanReading\Restructure\Storage\ScalarStorage;
use AmericanReading\Restructure\Interfaces\StorageInterface;

/**
 * Factory for simple fields with scalar values.
 */
class Item implements ItemInterface
{
    /**
     * Callable for reading a row and returning a value. The signature must be:
     * function ($row) where $row is an associative array or object. The function must retrun the
     * value to store to a storage instance.
     *
     * @var callable
     */
    private $readValueCallable;
    /**
     * Callable for maniupating a return value from $readValueCallable before storing it to a
     * storage instance. For example, use 'intval' to convert the value to an integer.
     *
     * @var callable
     */
    private $processValueCallable;

    /**
     * Create a new instance, optionally setting readValue and processValue callables.
     *
     * @param callable|string $field
     * @param callable $processValueCallable
     */
    public function __construct($field, $processValueCallable = null)
    {
        if (is_callable($field)) {
            $this->setReadValueCallable($field);
        } else {
            $this->setReadValueField($field);
        }

        if ($processValueCallable !== null) {
            $this->setProcessValueCallable($processValueCallable);
        }
    }

    /**
     * Set a new callable to use to extract a value from a row.
     *
     * The signature must be:
     * function ($row) where $row is an associative array of object. The function must retrun the
     * value to store to a storage instance.
     *
     * @param $callable
     */
    public function setReadValueCallable($callable)
    {
        $this->readValueCallable = $callable;
    }

    /**
     * Set the readValueCallable to a function that will extract the value of the field with name
     * $fieldName from a row.
     *
     * @param string $fieldName
     */
    public function setReadValueField($fieldName)
    {
        $this->setReadValueCallable(
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
     * Set a new callable for maniupating a return value from $readValueCallable before storing it
     * to a storage instance. For example, use 'intval' to convert the value to an integer.
     *
     * @param $callable
     */
    public function setProcessValueCallable($callable)
    {
        $this->processValueCallable = $callable;
    }

    /**
     * @return ScalarStorage
     */
    public function getNewStorage()
    {
        return new ScalarStorage();
    }

    /**
     * @param array|object $row
     * @param \AmericanReading\Restructure\Interfaces\StorageInterface $storage
     */
    public function read($row, StorageInterface $storage)
    {
        // Extract a value from $row.
        $callable = $this->readValueCallable;
        $data = $callable($row);

        // Manipulate the value.
        if (is_callable($this->processValueCallable)) {
            $callable = $this->processValueCallable;
            $data = $callable($data);
        }

        // Store the value to the passed $storage.
        $storage->update($data);
    }
}
