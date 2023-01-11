<?php

declare(strict_types=1);

namespace ArrayLookup;

use ArrayIterator;
use ArrayObject;
use Traversable;
use Webmozart\Assert\Assert;

use function current;
use function end;
use function iterator_to_array;
use function key;
use function prev;

final class Finder
{
    /**
     * @param array<int|string, mixed>|Traversable<int|string, mixed> $data
     * @param callable(mixed $datum, int|string|null $key=): bool $filter
     */
    public static function first(iterable $data, callable $filter, bool $includeKey = false): mixed
    {
        foreach ($data as $key => $datum) {
            $isFound = $filter($datum, $includeKey ? $key : null);

            // returns of callable must be bool
            Assert::boolean($isFound);

            if (! $isFound) {
                continue;
            }

            return $datum;
        }

        return null;
    }

    /**
     * @param Traversable<int|string, mixed> $traversable
     * @return array<int|string, mixed>
     */
    private static function resolveArrayFromTraversable(Traversable $traversable): array
    {
        if ($traversable instanceof ArrayIterator || $traversable instanceof ArrayObject) {
            return $traversable->getArrayCopy();
        }

        return iterator_to_array($traversable);
    }

    /**
     * @param array<int|string, mixed>|Traversable<int|string, mixed> $data
     * @param callable(mixed $datum, int|string|null $key=): bool $filter
     */
    public static function last(iterable $data, callable $filter, bool $includeKey = false): mixed
    {
        // convert to array when data is Traversable instance
        if ($data instanceof Traversable) {
            $data = self::resolveArrayFromTraversable($data);
        }

        // ensure data is array for end(), key(), current(), prev() usage
        Assert::isArray($data);

        // Use end(), key(), current(), prev() usage instead of array_reverse()
        // to avoid immediatelly got "Out of memory" on many data
        // see https://3v4l.org/IHo2H vs https://3v4l.org/Wqejc

        // go to end of array
        end($data);

        $key = key($data);

        // key = null means no longer current data
        while ($key !== null) {
            $current = current($data);
            $isFound = $filter($current, $includeKey ? $key : null);

            // returns of callable must be bool
            Assert::boolean($isFound);

            if (! $isFound) {
                // go to previous row
                prev($data);

                // re-set key variable with new key value
                $key = key($data);

                continue;
            }

            return $current;
        }

        return null;
    }
}
