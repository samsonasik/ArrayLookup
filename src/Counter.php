<?php

declare(strict_types=1);

namespace ArrayLookup;

use Traversable;

use function count;
use function is_array;
use function iterator_count;

/**
 * @internal
 */
final class Counter
{
    /**
     * @param array<int|string, mixed>|Traversable<int|string, mixed> $data
     */
    public static function count(iterable $data): int
    {
        // php 8.1 compat
        if (is_array($data)) {
            return count($data);
        }

        return iterator_count($data);
    }
}
