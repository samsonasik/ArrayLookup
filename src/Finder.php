<?php

declare(strict_types=1);

namespace ArrayLookup;

use Traversable;
use Webmozart\Assert\Assert;

use function array_reverse;
use function iterator_to_array;

final class Finder
{
    /**
     * @param mixed[]|iterable        $data
     * @param callable(mixed $datum): bool $filter
     */
    public static function first(iterable $data, callable $filter): mixed
    {
        return self::locateFirst($data, $filter);
    }

    /**
     * @param mixed[]|iterable        $data
     * @param callable(mixed $datum): bool $filter
     */
    public static function last(iterable $data, callable $filter): mixed
    {
        return self::locateFirst($data, $filter, true);
    }

    /**
     * @param mixed[]|iterable        $data
     * @param callable(mixed $datum): bool $filter
     */
    private static function locateFirst(iterable $data, callable $filter, bool $flip = false): mixed
    {
        if ($data instanceof Traversable) {
            $data = iterator_to_array($data);
        }

        if ($flip) {
            $data = array_reverse($data);
        }

        foreach ($data as $datum) {
            $isFound = $filter($datum);

            // returns of callable must be bool
            Assert::boolean($isFound);

            if (! $isFound) {
                continue;
            }

            return $datum;
        }

        return null;
    }
}
