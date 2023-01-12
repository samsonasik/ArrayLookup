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
- [x] Verify exact times: `once()`, `twice()`, `times()`
- [x] Search data: `first()`, `last()`

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

*1. `AtLeast::once()`*

It verify that data has filtered found item at least once.

```php
$data = [1, 2, 3];
$filter = static fn($datum): bool => $datum === 1;

var_dump(\ArrayLookup\AtLeast::once($data, $filter)) // true

$data = [1, 2, 3];
$filter = static fn($datum): bool => $datum === 4;

var_dump(\ArrayLookup\AtLeast::once($data, $filter)) // false

// WITH key array included, pass $key variable as 2nd arg on  filter to be used in filter

$data = [1, 2, 3];
$filter = static fn($datum, $key): bool => $datum === 1 && $key >= 0;

var_dump(\ArrayLookup\AtLeast::once($data, $filter)) // true

$data = [1, 2, 3];
$filter = static fn($datum, $key): bool => $datum === 4 && $key >= 0;

var_dump(\ArrayLookup\AtLeast::once($data, $filter)) // false
```

*2. `AtLeast::twice()`*

It verify that data has filtered found items at least twice.

```php
$data = [1, "1", 3];
$filter = static fn($datum): bool => $datum == 1;

var_dump(\ArrayLookup\AtLeast::twice($data, $filter)) // true

$data = [1, "1", 3];
$filter = static fn($datum): bool => $datum === 1;

var_dump(\ArrayLookup\AtLeast::twice($data, $filter)) // false

// WITH key array included, pass $key variable as 2nd arg on  filter to be used in filter

$data = [1, "1", 3];
$filter = static fn($datum, $key): bool => $datum == 1 && $key >= 0;

var_dump(\ArrayLookup\AtLeast::twice($data, $filter)) // true

$data = [1, "1", 3];
$filter = static fn($datum, $key): bool => $datum === 1 && $key >= 0;

var_dump(\ArrayLookup\AtLeast::twice($data, $filter)) // false
```

*3. `AtLeast::times()`*

It verify that data has filtered found items at least times passed in 3rd arg.

```php
$data = [false, null, 0];
$filter = static fn($datum): bool => ! $datum;
$times = 3;

var_dump(\ArrayLookup\AtLeast::times($data, $filter, $times)) // true

$data = [1, null, 0];
$filter = static fn($datum): bool => ! $datum;
$times = 3;

var_dump(\ArrayLookup\AtLeast::times($data, $filter, $times)) // false

// WITH key array included, pass $key variable as 2nd arg on  filter to be used in filter

$data = [false, null, 0];
$filter = static fn($datum, $key): bool => ! $datum && $key >= 0;
$times = 3;

var_dump(\ArrayLookup\AtLeast::times($data, $filter, $times)) // true

$data = [1, null, 0];
$filter = static fn($datum, $key): bool => ! $datum && $key >= 0;
$times = 3;

var_dump(\ArrayLookup\AtLeast::times($data, $filter, $times)) // false
```

**B. Only**
---------------

*1. `Only::once()`*

It verify that data has filtered found item exactly found only once.

```php
$data = [1, 2, 3];
$filter = static fn($datum): bool => $datum === 1;

var_dump(\ArrayLookup\Only::once($data, $filter)) // true


$data = [1, "1", 3]
$filter = static fn($datum): bool => $datum == 1;

var_dump(\ArrayLookup\Only::once($data, $filter)) // false

// WITH key array included, pass $key variable as 2nd arg on  filter to be used in filter

$data = [1, 2, 3];
$filter = static fn($datum, $key): bool => $datum === 1 && $key >= 0;

var_dump(\ArrayLookup\Only::once($data, $filter)) // true


$data = [1, "1", 3]
$filter = static fn($datum, $key): bool => $datum == 1  && $key >= 0;

var_dump(\ArrayLookup\Only::once($data, $filter)) // false
```

*2. `Only::twice()`*

It verify that data has filtered found items exactly found only twice.

```php
$data = [1, "1", 3];
$filter = static fn($datum): bool => $datum == 1;

var_dump(\ArrayLookup\Only::twice($data, $filter)) // true

$data = [true, 1, new stdClass()];
$filter = static fn($datum): bool => (bool) $datum;

var_dump(\ArrayLookup\Only::twice($data, $filter)) // false

// WITH key array included, pass $key variable as 2nd arg on  filter to be used in filter

$data = [1, "1", 3];
$filter = static fn($datum, $key): bool => $datum == 1 && $key >= 0;

var_dump(\ArrayLookup\Only::twice($data, $filter)) // true

$data = [true, 1, new stdClass()];
$filter = static fn($datum, $key): bool => (bool) $datum && $key >= 0;

var_dump(\ArrayLookup\Only::twice($data, $filter)) // false
```

*3. `Only::times()`*

It verify that data has filtered found items exactly found only same with times passed in 3rd arg.

```php
$data = [false, null, 1];
$filter = static fn($datum): bool => ! $datum;
$times = 2;

var_dump(\ArrayLookup\Only::times($data, $filter, $times)) // true


$data = [false, null, 0];
$filter = static fn($datum): bool => ! $datum;
$times = 2;

var_dump(\ArrayLookup\Only::times($data, $filter, $times)) // false

// WITH key array included, pass $key variable as 2nd arg on  filter to be used in filter

$data = [false, null, 1];
$filter = static fn($datum, $key): bool => ! $datum && $key >= 0;
$times = 2;

var_dump(\ArrayLookup\Only::times($data, $filter, $times)) // true


$data = [false, null, 0];
$filter = static fn($datum, $key): bool => ! $datum && $key >= 0;
$times = 2;

var_dump(\ArrayLookup\Only::times($data, $filter, $times)) // false
```

**3. Finder**
---------------

*1. `Finder::first()`*

It search first data filtered found.

```php
$data = [1, 2, 3];
$filter = static fn($datum): bool => $datum === 1;

var_dump(\ArrayLookup\Finder::first($data, $filter)) // 1

$filter = static fn($datum): bool => $datum == 1000;
var_dump(\ArrayLookup\Finder::first($data, $filter)) // null

// RETURN the Array key, pass true to 3rd arg

$filter = static fn($datum): bool => $datum === 1;

var_dump(\ArrayLookup\Finder::first($data, $filter, true)) // 0

$filter = static fn($datum): bool => $datum == 1000;
var_dump(\ArrayLookup\Finder::first($data, $filter, true)) // null

// WITH key array included, pass $key variable as 2nd arg on  filter to be used in filter

$filter = static fn($datum, $key): bool => $datum === 1 && $key >= 0;

var_dump(\ArrayLookup\Finder::first($data, $filter)) // 1

$filter = static fn($datum, $key): bool => $datum == 1000 && $key >= 0;
var_dump(\ArrayLookup\Finder::first($data, $filter)) // null
```

*2. `Finder::last()`*

It search last data filtered found.

```php
$data = [6, 7, 8, 9];
var_dump(\ArrayLookup\Finder::last(
    $data,
    static fn ($datum): bool => $datum > 5
)); // 9

var_dump(\ArrayLookup\Finder::last(
    $data,
    static fn ($datum): bool => $datum < 5
)); // null

// RETURN the Array key, pass true to 3rd arg

var_dump(\ArrayLookup\Finder::last(
    $data,
    static fn ($datum): bool => $datum > 5,
    true
)); // 3

var_dump(\ArrayLookup\Finder::last(
    $data,
    static fn ($datum): bool => $datum < 5,
    true
)); // null

// WITH key array included, pass $key variable as 2nd arg on  filter to be used in filter

var_dump(\ArrayLookup\Finder::last(
    $data,
    static fn ($datum, $key): bool => $datum > 5 && $key >= 0
)); // 9

var_dump(\ArrayLookup\Finder::last(
    $data,
    static fn ($datum, $key): bool => $datum < 5 && $key >= 0
)); // null
```
