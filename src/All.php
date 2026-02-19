<?php

declare(strict_types=1);

namespace ArrayLookup;

use ArrayLookup\Assert\Filter;
use Traversable;

final class All
{
    /**
     * @param array<int|string, mixed>|Traversable<int|string, mixed> $data
     * @param callable(mixed $datum, int|string|null $key): bool $filter
     */
    public static function match(iterable $data, callable $filter): bool
    {
        Filter::boolean($filter);

        $isNonEmpty = false;

        foreach ($data as $key => $datum) {
            $isNonEmpty = true;

            if (! $filter($datum, $key)) {
                return false;
            }
        }

        // when empty data is given, then nothing is match
        return $isNonEmpty;
    }

    /**
     * @param array<int|string, mixed>|Traversable<int|string, mixed> $data
     * @param callable(mixed $datum, int|string|null $key): bool $filter
     */
    public static function none(iterable $data, callable $filter): bool
    {
        Filter::boolean($filter);

        foreach ($data as $key => $datum) {
            if ($filter($datum, $key)) {
                return false;
            }
        }

        return true;
    }
}
