<?php

declare(strict_types=1);

namespace ArrayLookup;

use Webmozart\Assert\Assert;

use function rsort;

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
    public static function last(array $data, callable $callable): mixed
    {
        return self::locateFirst($data, $callable, true);
    }

    /**
     * @param mixed[]        $data
     * @param callable(mixed $datum): bool $callable
     */
    private static function locateFirst(array $data, callable $callable, bool $flip = false): mixed
    {
        if ($flip) {
            rsort($data);
        }

        foreach ($data as $datum) {
            $isFound = $callable($datum);

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
