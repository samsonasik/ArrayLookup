ArrayLookup
===============

[![Latest Version](https://img.shields.io/github/release/samsonasik/ArrayLookup.svg?style=flat-square)](https://github.com/samsonasik/ArrayLookup/releases)
![ci build](https://github.com/samsonasik/ArrayLookup/workflows/ci%20build/badge.svg)
[![Code Coverage](https://codecov.io/gh/samsonasik/ArrayLookup/branch/main/graph/badge.svg)](https://codecov.io/gh/samsonasik/ArrayLookup)
[![PHPStan](https://img.shields.io/badge/style-level%20max-brightgreen.svg?style=flat-square&label=phpstan)](https://github.com/phpstan/phpstan)
[![Downloads](https://poser.pugx.org/samsonasik/array-lookup/downloads)](https://packagist.org/packages/samsonasik/array-lookup)

Introduction
------------

ArrayLookup is a fast array lookup library that help you verify and search array data.

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
$callable = static fn($datum): bool => $datum === 1;

var_dump(\ArrayLookup\AtLeast::once($data, $callable)) // true

$data = [1, 2, 3];
$callable = static fn($datum): bool => $datum === 4;

var_dump(\ArrayLookup\AtLeast::once($data, $callable)) // false
```

*2. `AtLeast::twice()`*

It verify that data has filtered found item at least twice.

```php
$data = [1, "1", 3];
$callable = static fn($datum): bool => $datum == 1;

var_dump(\ArrayLookup\AtLeast::twice($data, $callable)) // true

$data = [1, "1", 3];
$callable = static fn($datum): bool => $datum === 1;

var_dump(\ArrayLookup\AtLeast::twice($data, $callable)) // false
```

*3. `AtLeast::times()`*

It verify that data has filtered found item at least times passed in 3rd arg.

```php
$data = [false, null, 0];
$callable = static fn($datum): bool => ! $datum;
$times = 3;

var_dump(\ArrayLookup\AtLeast::times($data, $callable, $times)) // true

$data = [1, null, 0];
$callable = static fn($datum): bool => ! $datum;
$times = 3;

var_dump(\ArrayLookup\AtLeast::times($data, $callable, $times)) // false
```

**B. Only**
---------------

*1. `Only::once()`*

It verify that data has filtered found item exactly found only once.

```php
$data = [1, 2, 3];
$callable = static fn($datum): bool => $datum === 1;

var_dump(\ArrayLookup\Only::once($data, $callable)) // true


$data = [1, "1", 3]
$callable = static fn($datum): bool => $datum == 1;

var_dump(\ArrayLookup\Only::once($data, $callable)) // false
```

*2. `Only::twice()`*

It verify that data has filtered found item exactly found only once.

```php
$data = [1, "1", 3];
$callable = static fn($datum): bool => $datum == 1;

var_dump(\ArrayLookup\Only::twice($data, $callable)) // true

$data = [true, 1, new stdClass()];
$callable = static fn($datum): bool => (bool) $datum;

var_dump(\ArrayLookup\Only::twice($data, $callable)) // false
```

*3. `Only::times()`*

It verify that data has filtered found item exactly found only same with times passed in 3rd arg.

```php
$data = [false, null, 1];
$callable = static fn($datum): bool => ! $datum;
$times = 2;

var_dump(\ArrayLookup\Only::times($data, $callable, $times)) // true


$data = [false, null, 0];
$callable = static fn($datum): bool => ! $datum;
$times = 2;

var_dump(\ArrayLookup\Only::times($data, $callable, $times)) // false
```

**3. Finder**
---------------

*1. `Finder::first()`*

It search first data filtered found.

```php
$data = [1, 2, 3];
$callable = static fn($datum): bool => $datum === 1;

var_dump(\ArrayLookup\Finder::first($data, $callable)) // 1

$callable = static fn($datum): bool => $datum == 1000;
var_dump(\ArrayLookup\Finder::first($data, $callable)) // null
```

*2. `Finder::last()`*

It search first data filtered found.

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
```
