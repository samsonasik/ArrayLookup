<?php

declare(strict_types=1);

namespace ArrayLookup;

use Webmozart\Assert\Assert;

final class Only
{
    /**
     * @param mixed[]|iterable        $data
     * @param callable(mixed $datum): bool $filter
     */
    public static function once(iterable $data, callable $filter): bool
    {
        return self::onlyFoundTimes($data, $filter, 1);
    }

    /**
     * @param mixed[]|iterable        $data
     * @param callable(mixed $datum): bool $filter
     */
    public static function twice(iterable $data, callable $filter): bool
    {
        return self::onlyFoundTimes($data, $filter, 2);
    }

    /**
     * @param mixed[]|iterable        $data
     * @param callable(mixed $datum): bool $filter
     */
    public static function times(iterable $data, callable $filter, int $count): bool
    {
        return self::onlyFoundTimes($data, $filter, $count);
    }

    /**
     * @param mixed[]|iterable        $data
     * @param callable(mixed $datum): bool $filter
     */
    private static function onlyFoundTimes(iterable $data, callable $filter, int $maxCount): bool
    {
        // usage must be higher than 0
        Assert::greaterThan($maxCount, 0);

        $totalFound = 0;
        foreach ($data as $datum) {
            $isFound = $filter($datum);

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
