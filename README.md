ArrayLookup
===============

[![Latest Version](https://img.shields.io/github/release/samsonasik/ArrayLookup.svg?style=flat-square)](https://github.com/samsonasik/ArrayLookup/releases)
![ci build](https://github.com/samsonasik/ArrayLookup/workflows/ci%20build/badge.svg)
[![Code Coverage](https://codecov.io/gh/samsonasik/ArrayLookup/branch/main/graph/badge.svg)](https://codecov.io/gh/samsonasik/ArrayLookup)
[![PHPStan](https://img.shields.io/badge/style-level%20max-brightgreen.svg?style=flat-square&label=phpstan)](https://github.com/phpstan/phpstan)
[![Downloads](https://poser.pugx.org/samsonasik/array-lookup/downloads)](https://packagist.org/packages/samsonasik/array-lookup)

Introduction
------------

ArrayLookup is a fast array lookup library.

Features
--------

- [x] Search at least data, `once()`, `twice()`, `times()`


Installation
------------

**Require this library uses [composer](https://getcomposer.org/).**

```sh
composer require samsonasik/array-utils
```

Usage
-----

1. `AtLeast::once()`

It verify that data has filtered found item at least once.

```php
$data = [1, 2, 3];
$callable = static fn($datum): bool => $datum === 1;

var_dump(\ArrayLookup\AtLeast::once($data, $callable)) // true
```

2. `AtLeast::twice()`

It verify that data has filtered found item at least twice.

```php
$data = [1, "1", 3];
$callable = static fn($datum): bool => $datum == 1;

var_dump(\ArrayLookup\AtLeast::twice($data, $callable)) // true
```

3. `AtLeast::times()`

It verify that data has filtered found item at least times passed in 3rd arg.

```php
$data = [false, null, 0];
$callable = static fn($datum): bool => ! $datum;
$times = 3;

var_dump(\ArrayLookup\AtLeast::times($data, $callable, $times)) // true
```


