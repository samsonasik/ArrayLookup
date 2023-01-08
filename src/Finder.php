<?php

declare(strict_types=1);

namespace ArrayLookup;

use Webmozart\Assert\Assert;

use function array_reverse;

final class Finder
{
    /**
     * @param mixed[]        $data
     * @param callable(mixed $datum): bool $filter
     */
    public static function first(array $data, callable $filter): mixed
    {
        return self::locateFirst($data, $filter);
    }

    /**
     * @param mixed[]        $data
     * @param callable(mixed $datum): bool $filter
     */
    public static function last(array $data, callable $filter): mixed
    {
        return self::locateFirst($data, $filter, true);
    }

    /**
     * @param mixed[]        $data
     * @param callable(mixed $datum): bool $filter
     */
    private static function locateFirst(array $data, callable $filter, bool $flip = false): mixed
    {
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
