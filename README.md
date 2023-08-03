# php-utils-ds: PHP Java-Like Collections (List, Set, Map)

## Overview

php-utils-ds is a PHP library that provides Java-like implementations for List, Set, and Map.
It offers data structures such as ArrayList, Set, and Map, which closely resemble their Java counterparts, making it easier for Java developers to work with PHP.

This library is compatible with PHP 8.2 and above.

## Features

- `ArrayList`: A dynamic array that provides methods to manipulate and access its elements.
- `Set`: A collection that contains no duplicate elements. It is similar to a mathematical set.
- `Map`: An object that maps keys to values. It cannot contain duplicate keys; each key can map to at most one value.

## Installation

You can install the php-utils-ds library via Composer. Run the following command:

```bash
composer require vk-php-utils/ds
```

## Usage

Here are some examples of how to use the php-utils-ds library:

### ArrayList

```php
use VKPHPUtils\DS\ArrayList;

$list = new ArrayList(1, 2, 3);
$list->add(4);
$list->remove(2);

foreach ($list as $element) {
    echo $element . PHP_EOL;
}
```

### Set

```php
use VKPHPUtils\DS\Set;

$set = new Set(1, 2, 3, 3, 4);
$set->add(5);
$set->remove(2);

foreach ($set as $element) {
    echo $element . PHP_EOL;
}

```

### Map

```php
use VKPHPUtils\DS\Map;

/** @var User $user1 **/
$user1 = ...;
/** @var User $user2 **/
$user2 = ...;

/** @var Map<User, Address> $addressMap **/
$addressMap = new Map();
$addressMap->put($user1, new Address('Apple str 18'));
$addressMap[$user2] = new Address('Banana str 19'); // php way

echo $map->get($user1)->street; // Output: Apple str 18

// php way
echo $map[$user1]->street; // Output: Apple str 18
```

## Contributing

Contributions to the php-utils-ds library are welcome.
If you find a bug or want to add a new feature, feel free to open an issue or submit a pull request.

## License

php-utils-ds is licensed under the [MIT License](./LICENSE.md).

