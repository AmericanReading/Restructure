<?php

/***************************************************************************************************
 * Example 1: Characters List
 *
 * Demonstrate how to restructure rows into a list of objects representing characters. The list will
 * contain one character object per row, which means we'll get two Barts. That's okay; example 2
 * will show how to make a set of unique characters.
 *
 * Note: The examples work with data from the CSV file examples/data/shows.csv. Take a look at the
 * contents of this file to get a feel for the data.
 *
 * The shows.csv file contains these columns:
 *     showId, characterId, petId, petTypeId, show, character, petName, petType
 *
 * When read from the csv_to_array() function, the result is an array of associative arrays with
 * a member for each column. Somthing like:
 *
 *     array(
 *         [0] => array(
 *             'showId' => 1,
 *             'characterId' => 1,
 *     ...
 *
 * This could just as easily be a fetched result set or any other array of arrays or objects.
 **************************************************************************************************/

use AmericanReading\Restructure\Item\CompoundItem;
use AmericanReading\Restructure\Item\ListItem;

// Autoloader for examples. You should use vendor/autoload.php generated by Composer.
require_once('lib/autoload.php');
require_once('lib/csv_to_array.php');

// Read the data from the CSV as a list of associative arrays.
// This could just as easily be a fetched result set or any other array of arrays or objects.
$rows = csv_to_array('data/shows.csv');

// Define an item instance for reading a character.
// We'll use a CompoundItem. This allows us to add a number of members which will be read from
// the fields in a row. When we finish building our character and have it read the first row
// of the data, we'll get a result that looks like this (show in JSON for simplicity):
//
//     {
//         "name": "Homer",
//         "show": "The Simpsons",
//         "characterId": 1
//     }
//
$character = new CompoundItem();

// Add a field for the character's name.
// Our restructured object will have a "name" member, so we pass "name" as the first paramter.
// We want to extract the value of the "character" field from the row, so we'll pass "character"
// as the second parameter.
$character->addItem('name', 'character');

// Next, we'll add the "show" member. Since the name of our property is that same as the name of
// the column we want to read from the row, we can omit the second parameter. addItem() will use
// the first parameter for both.
$character->addItem('show');

// Our last member is "characterId". Since the name we our using is the same as the name of the
// columm we want to read, we don't have to pass a second parameter.
//
// There is a third parameter here though. This parameter is a callable to apply to the value
// extracted from the column. In this case, I want to ensure that my extracted value is an integer,
// so I pass 'intval' as the third parameter.
$character->addItem('characterId', null, 'intval');

// One character by itself isn't very interesting. What we need to do next is build a collection
// item to read a series of characters. The simplest of these is the ListItem which adds an item
// for each row in your array of rows.
$charactersList = new ListItem();

// Collections can only build items of one type. We can assign which type to use with setItem().
$charactersList->setItem($character);

// Now, we can read the results.
$restructured = $charactersList->readAll($rows);
print json_encode($restructured);

// You should end up with a list of characters that includes two Barts. We'll fix that in the next
// example where we create a set.
