<?php

declare(strict_types=1);

namespace ArrayLookup\Tests;

use ArrayLookup\All;
use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class AllTest extends TestCase
{
    /**
     * @param int[]|string[] $data
     */
    #[DataProvider('matchDataProvider')]
    public function testMatch(array $data, callable $filter, bool $expected): void
    {
        $this->assertSame(
            $expected,
            All::match($data, $filter)
        );
    }

    /**
     * @return Iterator<mixed>
     */
    public static function matchDataProvider(): Iterator
    {
        yield [
            [1, 2, 3],
            static fn($datum): bool => $datum > 0,
            true,
        ];
        yield [
            [1, 0, 3],
            static fn($datum): bool => $datum > 0,
            false,
        ];
        yield [
            ['abc', 'def'],
            static fn(string $datum, int $key): bool => $datum !== '' && $key >= 0,
            true,
        ];
        yield [
            [],
            static fn($datum): bool => $datum !== null,
            false,
        ];
    }

    /**
     * @param int[]|string[] $data
     */
    #[DataProvider('noneDataProvider')]
    public function testNone(array $data, callable $filter, bool $expected): void
    {
        $this->assertSame(
            $expected,
            All::none($data, $filter)
        );
    }

    /**
     * @return Iterator<mixed>
     */
    public static function noneDataProvider(): Iterator
    {
        yield [
            [1, 2, 3],
            static fn($datum): bool => $datum === 4,
            true,
        ];
        yield [
            [1, 2, 3],
            static fn($datum): bool => $datum === 2,
            false,
        ];
        yield [
            ['abc', 'def'],
            static fn(string $datum, int $key): bool => $key === 0 && $datum === 'abc',
            false,
        ];
        yield [
            [],
            static fn($datum): bool => $datum !== null,
            true,
        ];
    }
}
