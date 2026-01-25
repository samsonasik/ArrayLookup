ArrayLookup
===============

[![Latest Version](https://img.shields.io/github/release/samsonasik/ArrayLookup.svg?style=flat-square)](https://github.com/samsonasik/ArrayLookup/releases)
![ci build](https://github.com/samsonasik/ArrayLookup/workflows/ci%20build/badge.svg)
[![Code Coverage](https://codecov.io/gh/samsonasik/ArrayLookup/branch/main/graph/badge.svg)](https://codecov.io/gh/samsonasik/ArrayLookup)
[![PHPStan](https://img.shields.io/badge/style-level%20max-brightgreen.svg?style=flat-square&label=phpstan)](https://github.com/phpstan/phpstan)
[![Downloads](https://poser.pugx.org/samsonasik/array-lookup/downloads)](https://packagist.org/packages/samsonasik/array-lookup)

Introduction
------------

ArrayLookup is a fast lookup library that help you verify and search `array` and `Traversable` data.

Features
--------

- [x] Verify at least times: `once()`, `twice()`, `times()`
- [x] Verify at most times: `once()`, `twice()`, `times()`
- [x] Verify exact times: `once()`, `twice()`, `times()`
- [x] Search data: `first()`, `last()`, `rows()`, `partition()`
- [x] Collect data with filter and transform

Installation
------------

**Require this library uses [composer](https://getcomposer.org/).**

```sh
composer require samsonasik/array-lookup
```

Usage
-----

**A. AtLeast**
---------------

#### 1. `AtLeast::once()`

It verify that data has filtered found item at least once.

```php
use ArrayLookup\AtLeast;

$data = [1, 2, 3];
$filter = static fn($datum): bool => $datum === 1;

var_dump(AtLeast::once($data, $filter)) // true

$data = [1, 2, 3];
$filter = static fn($datum): bool => $datum === 4;

var_dump(AtLeast::once($data, $filter)) // false

// WITH key array included, pass $key variable as 2nd arg on  filter to be used in filter

$data = [1, 2, 3];
$filter = static fn($datum, $key): bool => $datum === 1 && $key >= 0;

var_dump(AtLeast::once($data, $filter)) // true

$data = [1, 2, 3];
$filter = static fn($datum, $key): bool => $datum === 4 && $key >= 0;

var_dump(AtLeast::once($data, $filter)) // false
```

#### 2. `AtLeast::twice()`

It verify that data has filtered found items at least twice.

```php
use ArrayLookup\AtLeast;

$data = [1, "1", 3];
$filter = static fn($datum): bool => $datum == 1;

var_dump(AtLeast::twice($data, $filter)) // true

$data = [1, "1", 3];
$filter = static fn($datum): bool => $datum === 1;

var_dump(AtLeast::twice($data, $filter)) // false

// WITH key array included, pass $key variable as 2nd arg on  filter to be used in filter

$data = [1, "1", 3];
$filter = static fn($datum, $key): bool => $datum == 1 && $key >= 0;

var_dump(AtLeast::twice($data, $filter)) // true

$data = [1, "1", 3];
$filter = static fn($datum, $key): bool => $datum === 1 && $key >= 0;

var_dump(AtLeast::twice($data, $filter)) // false
```

#### 3. `AtLeast::times()`

It verify that data has filtered found items at least times passed in 3rd arg.

```php
use ArrayLookup\AtLeast;

$data = [false, null, 0];
$filter = static fn($datum): bool => ! $datum;
$times = 3;

var_dump(AtLeast::times($data, $filter, $times)) // true

$data = [1, null, 0];
$filter = static fn($datum): bool => ! $datum;
$times = 3;

var_dump(AtLeast::times($data, $filter, $times)) // false

// WITH key array included, pass $key variable as 2nd arg on  filter to be used in filter

$data = [false, null, 0];
$filter = static fn($datum, $key): bool => ! $datum && $key >= 0;
$times = 3;

var_dump(AtLeast::times($data, $filter, $times)) // true

$data = [1, null, 0];
$filter = static fn($datum, $key): bool => ! $datum && $key >= 0;
$times = 3;

var_dump(AtLeast::times($data, $filter, $times)) // false
```

**B. AtMost**
---------------

#### 1. `AtMost::once()`

It verify that data has filtered found item at most once.

```php
use ArrayLookup\AtMost;

$data = [1, 2, 3];
$filter = static fn($datum): bool => $datum === 1;

var_dump(AtMost::once($data, $filter)) // true

$data = [1, "1", 3];
$filter = static fn($datum): bool => $datum == 1;

var_dump(AtMost::once($data, $filter)) // false

// WITH key array included, pass $key variable as 2nd arg on  filter to be used in filter

$data = ['abc', 'def', 'some test'];
$filter = static fn(string $datum, int $key): bool => $datum === 'def' && $key === 1;

var_dump(AtMost::once($data, $filter)) // true

$data = ['abc', 'def', 'some test'];
$filter = static fn(string $datum, int $key): bool => $key > 0;

var_dump(AtMost::once($data, $filter)) // false
```

#### 2. `AtMost::twice()`

It verify that data has filtered found items at most twice.

```php
use ArrayLookup\AtMost;

$data = [1, "1", 2];
$filter = static fn($datum): bool => $datum == 1;

var_dump(AtMost::twice($data, $filter)) // true

$data = [1, "1", 2, 1];
$filter = static fn($datum): bool => $datum == 1;

var_dump(AtMost::twice($data, $filter)) // false
```

#### 3. `AtMost::times()`

It verify that data has filtered found items at most times passed in 3rd arg.

```php
use ArrayLookup\AtMost;

$data = [false, null, 0];
$filter = static fn($datum): bool => ! $datum;
$times = 3;

var_dump(AtMost::times($data, $filter, $times)) // true

$data = [false, null, 0, 0];
$filter = static fn($datum): bool => ! $datum;
$times = 3;

var_dump(AtMost::times($data, $filter, $times)) // false
```

**C. Only**
---------------

#### 1. `Only::once()`

It verify that data has filtered found item exactly found only once.

```php
use ArrayLookup\Only;

$data = [1, 2, 3];
$filter = static fn($datum): bool => $datum === 1;

var_dump(Only::once($data, $filter)) // true


$data = [1, "1", 3]
$filter = static fn($datum): bool => $datum == 1;

var_dump(Only::once($data, $filter)) // false

// WITH key array included, pass $key variable as 2nd arg on  filter to be used in filter

$data = [1, 2, 3];
$filter = static fn($datum, $key): bool => $datum === 1 && $key >= 0;

var_dump(Only::once($data, $filter)) // true


$data = [1, "1", 3]
$filter = static fn($datum, $key): bool => $datum == 1  && $key >= 0;

var_dump(Only::once($data, $filter)) // false
```

#### 2. `Only::twice()`

It verify that data has filtered found items exactly found only twice.

```php
use ArrayLookup\Only;

$data = [1, "1", 3];
$filter = static fn($datum): bool => $datum == 1;

var_dump(Only::twice($data, $filter)) // true

$data = [true, 1, new stdClass()];
$filter = static fn($datum): bool => (bool) $datum;

var_dump(Only::twice($data, $filter)) // false

// WITH key array included, pass $key variable as 2nd arg on  filter to be used in filter

$data = [1, "1", 3];
$filter = static fn($datum, $key): bool => $datum == 1 && $key >= 0;

var_dump(Only::twice($data, $filter)) // true

$data = [true, 1, new stdClass()];
$filter = static fn($datum, $key): bool => (bool) $datum && $key >= 0;

var_dump(Only::twice($data, $filter)) // false
```

#### 3. `Only::times()`

It verify that data has filtered found items exactly found only same with times passed in 3rd arg.

```php
use ArrayLookup\Only;

$data = [false, null, 1];
$filter = static fn($datum): bool => ! $datum;
$times = 2;

var_dump(Only::times($data, $filter, $times)) // true


$data = [false, null, 0];
$filter = static fn($datum): bool => ! $datum;
$times = 2;

var_dump(Only::times($data, $filter, $times)) // false

// WITH key array included, pass $key variable as 2nd arg on  filter to be used in filter

$data = [false, null, 1];
$filter = static fn($datum, $key): bool => ! $datum && $key >= 0;
$times = 2;

var_dump(Only::times($data, $filter, $times)) // true


$data = [false, null, 0];
$filter = static fn($datum, $key): bool => ! $datum && $key >= 0;
$times = 2;

var_dump(Only::times($data, $filter, $times)) // false
```

**D. Finder**
---------------

#### 1. `Finder::first()`

It search first data filtered found.

```php
use ArrayLookup\Finder;

$data = [1, 2, 3];
$filter = static fn($datum): bool => $datum === 1;

var_dump(Finder::first($data, $filter)) // 1

$filter = static fn($datum): bool => $datum == 1000;
var_dump(Finder::first($data, $filter)) // null

// RETURN the Array key, pass true to 3rd arg

$filter = static fn($datum): bool => $datum === 1;

var_dump(Finder::first($data, $filter, true)) // 0

$filter = static fn($datum): bool => $datum == 1000;
var_dump(Finder::first($data, $filter, true)) // null

// WITH key array included, pass $key variable as 2nd arg on  filter to be used in filter

$filter = static fn($datum, $key): bool => $datum === 1 && $key >= 0;

var_dump(Finder::first($data, $filter)) // 1

$filter = static fn($datum, $key): bool => $datum == 1000 && $key >= 0;
var_dump(Finder::first($data, $filter)) // null
```

#### 2. `Finder::last()`

It search last data filtered found.

```php
use ArrayLookup\Finder;

$data = [6, 7, 8, 9];
var_dump(Finder::last(
    $data,
    static fn ($datum): bool => $datum > 5
)); // 9

var_dump(Finder::last(
    $data,
    static fn ($datum): bool => $datum < 5
)); // null

// RETURN the Array key, pass true to 3rd arg

// ... with PRESERVE original key
var_dump(Finder::last(
    $data,
    static fn ($datum): bool => $datum > 5,
    true
)); // 3

// ... with RESORT key, first key is last record
var_dump(Finder::last(
    $data,
    static fn ($datum): bool => $datum > 5,
    true,
    false
)); // 0

var_dump(Finder::last(
    $data,
    static fn ($datum): bool => $datum < 5,
    true
)); // null

// WITH key array included, pass $key variable as 2nd arg on  filter to be used in filter

var_dump(Finder::last(
    $data,
    static fn ($datum, $key): bool => $datum > 5 && $key >= 0
)); // 9

var_dump(Finder::last(
    $data,
    static fn ($datum, $key): bool => $datum < 5 && $key >= 0
)); // null
```

#### 3. `Finder::rows()`

It get rows data filtered found.

```php
use ArrayLookup\Finder;

$data = [6, 7, 8, 9];
var_dump(Finder::rows(
    $data,
    static fn($datum): bool => $datum > 6
)); // [7, 8, 9]

var_dump(Finder::rows(
    $data,
    static fn ($datum): bool => $datum < 5
)); // []

// ... with PRESERVE original key
var_dump(Finder::rows(
    $data,
    static fn ($datum): bool => $datum > 6,
    true
)); // [1 => 7, 2 => 8, 3 => 9]

var_dump(Finder::rows(
    $data,
    static fn ($datum): bool => $datum < 5,
    true
)); // []

// WITH key array included, pass $key variable as 2nd arg on  filter to be used in filter
var_dump(Finder::rows(
    $data,
    static fn($datum, $key): bool => $datum > 6 && $key > 1
)); // [8, 9]


// WITH gather only limited found data
$data = [1, 2];
$filter = static fn($datum): bool => $datum >= 0;
$limit = 1;

var_dump(
    Finder::rows($data, $filter, limit: $limit)
); // [1]
```

#### 4. `Finder::partition()`

It splits data into two arrays: matching and non-matching items based on a filter.

```php
use ArrayLookup\Finder;

// Basic partition - split numbers into greater than 5 and not
$data = [1, 6, 3, 8, 4, 9];
$filter = static fn($datum): bool => $datum > 5;

[$matching, $notMatching] = Finder::partition($data, $filter);

var_dump($matching);    // [6, 8, 9]
var_dump($notMatching); // [1, 3, 4]

// Partition with preserved keys
[$matching, $notMatching] = Finder::partition($data, $filter, preserveKey: true);

var_dump($matching);    // [1 => 6, 3 => 8, 5 => 9]
var_dump($notMatching); // [0 => 1, 2 => 3, 4 => 4]

// Using the array key inside the filter
$data = [10, 20, 30, 40];
$keyFilter = static fn($datum, $key): bool => $key % 2 === 0;

[$even, $odd] = Finder::partition($data, $keyFilter, preserveKey: true);

var_dump($even); // [0 => 10, 2 => 30]
var_dump($odd);  // [1 => 20, 3 => 40]
```

**E. Collector**
---------------

It collect filtered data, with new transformed each data found:

**Before**

```php
$newArray = [];

foreach ($data as $datum) {
    if (is_string($datum)) {
        $newArray[] = trim($datum);
    }
}
```

**After**

```php
use ArrayLookup\Collector;

$when = fn ($datum): bool => is_string($datum);
$limit = 2;
$transform = fn ($datum): string => trim($datum);

$newArray = Collector::setUp($data)
       ->when($when) // optional, can just transform without filtering
       ->withLimit(2) // optional to only collect some data provided by limit config
       ->withTransform($transform)
       ->getResults();
```
