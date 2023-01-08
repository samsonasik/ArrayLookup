<?php

declare(strict_types=1);

namespace ArrayLookup\Tests;

use ArrayLookup\Finder;
use PHPUnit\Framework\TestCase;

final class FinderTest extends TestCase
{
    /**
     * @dataProvider firstDataProvider
     */
    public function testFirst(array $data, callable $filter, mixed $expected): void
    {
        $this->assertSame(
            $expected,
            Finder::first($data, $filter)
        );
    }

    public function firstDataProvider(): array
    {
        return [
            [
                [1, 2, 3],
                static fn($datum): bool => $datum === 2,
                2,
            ],
            [
                [1, "1", 3],
                static fn($datum): bool => $datum === 1000,
                null,
            ],
        ];
    }

    /**
     * @dataProvider lastDataProvider
     */
    public function testLast(array $data, callable $filter, mixed $expected): void
    {
        $this->assertSame(
            $expected,
            Finder::last($data, $filter)
        );
    }

    public function lastDataProvider(): array
    {
        return [
            [
                [6, 7, 8, 9],
                static fn($datum): bool => $datum > 5,
                9,
            ],
            [
                [6, 7, 8, 9],
                static fn($datum): bool => $datum < 5,
                null,
            ],
        ];
    }
}
