Restructure
===========

PHP Library for Manipulating Arrays of Data

## Wait, What's It Do?

Okay, let's say that you queried your database and fetched the result set into
an array of objects or arrays. Your data is a flat list of rows, but you'd like
to convert it to a heirarchy. For example, you'd like to convert this set of
rows:

show         | character
------------ | ---------
The Simpsons | Homer
The Simpsons | Lisa
The Simpsons | Bart
Futurama     | Leela
Futurama     | Fry
Family Guy   | Peter

```json
[
    {
        "show": "The Simpsons",
        "characters": ["Homer", "Lisa", "Bart"]
    },
    {
        "show": "Futurama",
        "characters": ["Leela", "Fry"]
    },
    {
        "show": "Family Guy",
        "characters": ["Peter"]
    }
]
```

You can transform your data this way by doing this:

```php
// Create a collection of character names, grouped by the 'character' field
$characterNameSet = new SetItem(new Item('character'), 'character');

// Create an object that reads the show name and adds to a list of characters.
$show = new CompoundItem();
$show->addItem('show');
$show->addItem('characters', $characterNameSet);
$shows = new SetItem($show, 'show');

// Convert the array of rows to an array of objects as shown above.
$restructured = $shows->readAll($rows);
```

## Item Classes

Restructure provides a number of classes for working with data of various types. You can find these in the namespace `AmericanReading\Restructure\Item`.

### Item

`Item` is the simplist of the classes. Use an `Item` to extract a scalar value (`string`, `int`, `float`, `bool`).

#### Simple

To create an `Item`, pass the name of the field to extract to the constructor.

```php
// This Item will extract the character field from a row.
$name = new Item('character');
```

#### Advanced

You can also pass a callable as a second parameter to the constructor to process the value when it is read. For example, assume `characterId` is a numeric field in the source result set. You can ensure the value read from this field is cast as an `int` by passing `'intval'` as the second parameter.

```php
// Call intval() on the value read from the `characterId` field.
$id = new Item('characterId', 'intval');
```

The first parameter does not need to be a column name to read from. It can also be a callable that accepts the row to read as an argument and returns a value. For example, this would return a full name for a person, assuming the source rows contain `firstName` and `lastName` fields.

```php
// Combine multiple fields to return a full name.
$fullname = new Item(function ($row) {
    $row = (object) $row;
    return $row->firstName . ' ' . $row->lastName;
});
```

**Note:** be careful when suppling your own callables to account for `$row` being an array or object. Unless you are sure you will only ever use one or the other, it's a good idea to normalize before trying to read members.

### CompoundItem

`CompoundItem` allows you to group multiple items together to create key-value pairs.

Create a `CompoundItem`, then use the `->addItem()` method to add items. Here's what it might look like to create a `CompoundItem` to represent a person.

```php
// Start by building the individual Items
$name = new Item('name');
$id = new Item('personId', 'intval');

// Assign these to members of a CompoundItem
$person = new CompoundItem();
$person->addItem('name', $name);
$person->addItem('personId', $id);
```

You can skip the step of creating the `$name` and `$id` instances. If the second parameter passed to `->addItem()` is not an Item (i.e., any object that implements the `ItemInterface` interface), the method creates an `Item` for you, passing the parameters on to the constructor. You may also omit the second parameter, and `->addItem()` will use the string passed as the first parameter in its place. The following are equivalent to the previous example.



```php
$person = new CompoundItem();
$person->addItem('name', 'name');
$person->addItem('personId', 'person', 'intval');
```

```php
$person = new CompoundItem();
$person->addItem('name');
$person->addItem('personId', null, 'intval');
```

You can also pass an array to the constructor. Each member of the array is treated as a set or variables to send to `->addItem()`. Here's the example using an array.

```php
$person = new CompoundItem(array(
    'name',
    array('personId', null, 'intval')
));
```

### ListItem

So far, the classes we've seen have been for representing single pieces of data. The next classes, `ListItem`, `MapItem`, and `SetItem` are used to represent collections.

`ListItem` is the simplist. Use a `ListItem` to create a non-grouped, non-unique collection of items. All of the items in a collection must use the same `Item` class, which we designate with the `->setItem()` method, or by passing to the constructor. Here's an example making a list of people, using our `$person` variable from the example above.

```php
$people = new ListItem();
$people->setItem($person);
```

After we create the instance, we can use the instance to transform our record set into an array of objects representing people.

```php
$rows = PDO::fetchAll(); // Fetch a result set.
$result = $people->readAll($rows);
```

`$result` will be an array containing one stdClass object for each row in `$rows`.

### SetItem

A `SetItem` produces a numeric array just as a `ListItem` does, but with an important difference: it allows you to group items togther. Here's an example similar to the example at the top. We'll create a list of character names, and we'll group these on the field `characterId`.

```php
// Item to extract the 'character' field. This is the character's name.
$characterName = new Item('character');
// SetItem to group characters by the 'characterId' field
$characterNameSet = new SetItem($characterName, 'characterId');
```

As with `ListItem`, you can call the `SetItem` instance's `->readAll()` method to get the transformed structure.

### MapItem

A `MapItem` works exacly like a `SetItem` with one difference: The result is not a numeric array, but a stdClass object of key-value pairs. The keys are the values of the field used to group.

## Examples

For more examples, see the `examples` directory bundled with this library.
