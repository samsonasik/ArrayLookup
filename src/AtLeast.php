<?php

declare(strict_types=1);

namespace ArrayLookup;

use ArrayLookup\Assert\Filter;
use Traversable;
use Webmozart\Assert\Assert;

final class AtLeast
{
    /**
     * @param array<int|string, mixed>|Traversable<int|string, mixed>  $data
     * @param callable(mixed $datum, int|string|null $key): bool $filter
     */
    public static function once(iterable $data, callable $filter): bool
    {
        return self::atLeastFoundTimes($data, $filter, 1);
    }

    /**
     * @param array<int|string, mixed>|Traversable<int|string, mixed> $data
     * @param callable(mixed $datum, int|string|null $key): bool $filter
     */
    public static function twice(iterable $data, callable $filter): bool
    {
        return self::atLeastFoundTimes($data, $filter, 2);
    }

    /**
     * @param array<int|string, mixed>|Traversable<int|string, mixed> $data
     * @param callable(mixed $datum, int|string|null $key): bool $filter
     */
    public static function times(iterable $data, callable $filter, int $count): bool
    {
        return self::atLeastFoundTimes($data, $filter, $count);
    }

    /**
     * @param array<int|string, mixed>|Traversable<int|string, mixed> $data
     * @param callable(mixed $datum, int|string|null $key): bool $filter
     */
    private static function atLeastFoundTimes(
        iterable $data,
        callable $filter,
        int $maxCount
    ): bool {
        // usage must be higher than 0
        Assert::greaterThan($maxCount, 0);
        // filter must be a callable with bool return type
        Filter::boolean($filter);

        $totalFound = 0;
        foreach ($data as $key => $datum) {
            $isFound = $filter($datum, $key);

            if (! $isFound) {
                continue;
            }

            ++$totalFound;

            if ($totalFound === $maxCount) {
                return true;
            }
        }

        return false;
    }
}
