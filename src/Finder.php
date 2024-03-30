<?php

declare(strict_types=1);

namespace ArrayLookup;

use ArrayIterator;
use ArrayObject;
use Traversable;
use Webmozart\Assert\Assert;

use function current;
use function end;
use function is_numeric;
use function iterator_to_array;
use function key;
use function prev;

use const PHP_INT_MAX;

final class Finder
{
    /**
     * @param array<int|string, mixed>|Traversable<int|string, mixed> $data
     * @param callable(mixed $datum, int|string|null $key=): bool $filter
     */
    public static function first(iterable $data, callable $filter, bool $returnKey = false): mixed
    {
        foreach ($data as $key => $datum) {
            $isFound = $filter($datum, $key);

            // returns of callable must be bool
            Assert::boolean($isFound);

            if (! $isFound) {
                continue;
            }

            return $returnKey ? $key : $datum;
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
    public static function last(
        iterable $data,
        callable $filter,
        bool $returnKey = false,
        bool $preserveKey = true
    ): mixed {
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

        // grab current key
        $key       = key($data);
        $resortkey = -1;

        // key = null means no longer current data
        while ($key !== null) {
            if (! $preserveKey && $returnKey) {
                ++$resortkey;
            }

            $current = current($data);
            $isFound = $filter($current, $key);

            // returns of callable must be bool
            Assert::boolean($isFound);

            if (! $isFound) {
                // go to previous row
                prev($data);

                // re-set key variable with new key value of previous row
                $key = key($data);

                continue;
            }

            if (! $returnKey) {
                return $current;
            }

            if ($preserveKey) {
                return $key;
            }

            return $resortkey;
        }

        return null;
    }

    /**
     * @param array<int|string, mixed>|Traversable<int|string, mixed> $data
     * @param callable(mixed $datum, int|string|null $key=): bool $filter
     * @return mixed[]
     */
    public static function rows(
        iterable $data,
        callable $filter,
        bool $preserveKey = false,
        int $limit = PHP_INT_MAX
    ): array {
        $rows   = [];
        $newKey = 0;
        $total  = 0;

        foreach ($data as $key => $datum) {
            $isFound = $filter($datum, $key);

            // returns of callable must be bool
            Assert::boolean($isFound);

            if (! $isFound) {
                continue;
            }

            if ($preserveKey || ! is_numeric($key)) {
                $rowKey = $key;
            } else {
                $rowKey = $newKey;
                ++$newKey;
            }

            $rows[$rowKey] = $datum;

            ++$total;
            if ($total === $limit) {
                break;
            }
        }

        return $rows;
    }
}
