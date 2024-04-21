<?php

declare(strict_types=1);

namespace ArrayLookup;

use Traversable;
use Webmozart\Assert\Assert;

use function count;

final class Only
{
    /**
     * @param array<int|string, mixed>|Traversable<int|string, mixed> $data
     * @param callable(mixed $datum, int|string|null $key=): bool $filter
     */
    public static function once(iterable $data, callable $filter): bool
    {
        return self::onlyFoundTimes($data, $filter, 1);
    }

    /**
     * @param array<int|string, mixed>|Traversable<int|string, mixed> $data
     * @param callable(mixed $datum, int|string|null $key=): bool $filter
     */
    public static function twice(iterable $data, callable $filter): bool
    {
        return self::onlyFoundTimes($data, $filter, 2);
    }

    /**
     * @param array<int|string, mixed>|Traversable<int|string, mixed> $data
     * @param callable(mixed $datum, int|string|null $key=): bool $filter
     */
    public static function times(iterable $data, callable $filter, int $count): bool
    {
        return self::onlyFoundTimes($data, $filter, $count);
    }

    /**
     * @param array<int|string, mixed>|Traversable<int|string, mixed> $data
     * @param callable(mixed $datum, int|string|null $key=): bool $filter
     */
    private static function onlyFoundTimes(
        iterable $data,
        callable $filter,
        int $maxCount
    ): bool {
        // usage must be higher than 0
        Assert::greaterThan($maxCount, 0);

        if (count($data) < $maxCount) {
            return false;
        }

        $totalFound = 0;
        foreach ($data as $key => $datum) {
            $isFound = $filter($datum, $key);

            // returns of callable must be bool
            Assert::boolean($isFound);

            if (! $isFound) {
                continue;
            }

            // total found already passed maxCount but found new one? stop
            if ($totalFound === $maxCount) {
                return false;
            }

            ++$totalFound;
        }

        return $totalFound === $maxCount;
    }
}
