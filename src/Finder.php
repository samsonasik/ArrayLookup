<?php

declare(strict_types=1);

namespace ArrayLookup;

use Webmozart\Assert\Assert;

use function array_reverse;

final class Finder
{
    /**
     * @param mixed[]        $data
     * @param callable(mixed $datum): bool $callable
     */
    public static function first(array $data, callable $callable): mixed
    {
        return self::locateFirst($data, $callable);
    }

    /**
     * @param mixed[]        $data
     * @param callable(mixed $datum): bool $callable
     */
    public static function firstTwo(array $data, callable $callable): mixed
    {
        return self::locateFirst($data, $callable, false, 2);
    }

    /**
     * @param mixed[]        $data
     * @param callable(mixed $datum): bool $callable
     */
    public static function firstTimes(array $data, callable $callable, int $times): mixed
    {
        return self::locateFirst($data, $callable, false, $times);
    }

    /**
     * @param mixed[]        $data
     * @param callable(mixed $datum): bool $callable
     */
    public static function last(array $data, callable $callable): mixed
    {
        return self::locateFirst($data, $callable, true);
    }

    /**
     * @param mixed[]        $data
     * @param callable(mixed $datum): bool $callable
     */
    public static function lastTwo(array $data, callable $callable): mixed
    {
        return self::locateFirst($data, $callable, true, 2);
    }

    /**
     * @param mixed[]        $data
     * @param callable(mixed $datum): bool $callable
     */
    public static function lastTimes(array $data, callable $callable, int $times): mixed
    {
        return self::locateFirst($data, $callable, true, $times);
    }

    /**
     * @param mixed[]        $data
     * @param callable(mixed $datum): bool $callable
     */
    private static function locateFirst(array $data, callable $callable, bool $flip = false, int $maxCount = 1): mixed
    {
        if ($flip) {
            $data = array_reverse($data);
        }

        $totalFound = 0;
        foreach ($data as $datum) {
            $isFound = $callable($datum);

            // returns of callable must be bool
            Assert::boolean($isFound);

            if (! $isFound) {
                continue;
            }

            ++$totalFound;

            if ($totalFound < $maxCount) {
                continue;
            }

            return $datum;
        }

        return null;
    }
}
