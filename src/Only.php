<?php

declare(strict_types=1);

namespace ArrayLookup;

use Webmozart\Assert\Assert;

final class Only
{
    /**
     * @param mixed[]        $data
     * @param callable(mixed $datum): bool $callable
     */
    public static function once(array $data, callable $callable): bool
    {
        return self::onlyFoundTimes($data, $callable, 1);
    }

    /**
     * @param mixed[]        $data
     * @param callable(mixed $datum): bool $callable
     */
    public static function twice(array $data, callable $callable): bool
    {
        return self::onlyFoundTimes($data, $callable, 2);
    }

    /**
     * @param mixed[]        $data
     * @param callable(mixed $datum): bool $callable
     */
    public static function times(array $data, callable $callable, int $count): bool
    {
        return self::onlyFoundTimes($data, $callable, $count);
    }

    /**
     * @param mixed[]        $data
     * @param callable(mixed $datum): bool $callable
     */
    private static function onlyFoundTimes(array $data, callable $callable, int $maxCount): bool
    {
        // usage must be higher than 0
        Assert::greaterThan($maxCount, 0);

        $totalFound = 0;
        foreach ($data as $datum) {
            $isFound = $callable($datum);

            // returns of callable must be bool
            Assert::boolean($isFound);

            if (! $isFound) {
                continue;
            }

            // already passed
            if ($totalFound === $maxCount) {
                return false;
            }

            ++$totalFound;
        }

        return $totalFound === $maxCount;
    }
}
