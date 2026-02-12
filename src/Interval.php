<?php

declare(strict_types=1);

namespace ArrayLookup;

use ArrayLookup\Assert\Filter;
use Traversable;
use Webmozart\Assert\Assert;

final class Interval
{
    /**
     * @param array<int|string, mixed>|Traversable<int|string, mixed> $data
     * @param callable(mixed $datum, int|string|null $key): bool $filter
     */
    public static function isInclusiveOf(
        iterable $data,
        callable $filter,
        int $min,
        int $max
    ): bool {
        Assert::greaterThan($min, 0);
        Assert::greaterThan($max, 0);
        Assert::lessThanEq($min, $max);
        Filter::boolean($filter);

        $totalFound = 0;
        foreach ($data as $key => $datum) {
            $isFound = $filter($datum, $key);

            if (! $isFound) {
                continue;
            }

            ++$totalFound;

            if ($totalFound > $max) {
                return false;
            }
        }

        return $totalFound >= $min;
    }

    /**
     * @param array<int|string, mixed>|Traversable<int|string, mixed> $data
     * @param callable(mixed $datum, int|string|null $key): bool $filter
     */
    public static function isExclusiveOf(
        iterable $data,
        callable $filter,
        int $min,
        int $max
    ): bool {
        Assert::greaterThan($min, 0);
        Assert::greaterThan($max, 0);
        Assert::lessThan($min, $max);
        Filter::boolean($filter);

        if ($max - $min <= 1) {
            return false;
        }

        $totalFound = 0;
        foreach ($data as $key => $datum) {
            $isFound = $filter($datum, $key);

            if (! $isFound) {
                continue;
            }

            ++$totalFound;

            if ($totalFound >= $max) {
                return false;
            }
        }

        return $totalFound > $min && $totalFound < $max;
    }
}
