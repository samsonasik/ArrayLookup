<?php

declare(strict_types=1);

namespace ArrayLookup\Tests;

use ArrayLookup\Only;
use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use stdClass;

final class OnlyTest extends TestCase
{
    #[DataProvider('onceDataProvider')]
    public function testOnce(array $data, callable $filter, bool $expected): void
    {
        $this->assertSame(
            $expected,
            Only::once($data, $filter)
        );
    }

    public static function onceDataProvider(): Iterator
    {
        yield [
            [1, 2, 3],
            static fn($datum): bool => $datum === 1,
            true,
        ];
        yield [
            [1, "1", 3],
            static fn($datum): bool => $datum == 1,
            false,
        ];
        yield [
            ['abc', 'def', 'some test'],
            static fn(string $datum, int $key): bool => $datum === 'def' && $key === 1,
            true,
        ];
        yield [
            ['abc', 'def', 'some test'],
            static fn(string $datum, int $key): bool => $datum === 'def' && $key === 2,
            false,
        ];
    }

    #[DataProvider('twiceDataProvider')]
    public function testTwice(array $data, callable $filter, bool $expected): void
    {
        $this->assertSame(
            $expected,
            Only::twice($data, $filter)
        );
    }

    public static function twiceDataProvider(): Iterator
    {
        yield [
            [1, "1", 2],
            static fn($datum): bool => $datum == 1,
            true,
        ];
        yield [
            [true, 1, new stdClass()],
            static fn($datum): bool => (bool) $datum,
            false,
        ];
        yield [
            ['abc', 'def', 'some test'],
            static fn(string $datum, int $key): bool => $datum !== 'abc' && $key > 0,
            true,
        ];
        yield [
            ['abc', 'def', 'some test'],
            static fn(string $datum, int $key): bool => $datum !== 'abc' && $key > 1,
            false,
        ];
    }

    #[DataProvider('timesDataProvider')]
    public function testTimes(array $data, callable $filter, bool $expected): void
    {
        $this->assertSame(
            $expected,
            Only::times($data, $filter, 3)
        );
    }

    public static function timesDataProvider(): Iterator
    {
        yield [
            [6, 7, 8, 9],
            static fn($datum): bool => $datum > 6,
            true,
        ];
        yield [
            [6, 7, 8, 9],
            static fn($datum): bool => $datum > 7,
            false,
        ];
        yield [
            ['abc', 'def', 'some test', 'another test'],
            static fn(string $datum, int $key): bool => $datum !== 'abc' && $key > 0,
            true,
        ];
        yield [
            ['abc', 'def', 'some test', 'another test'],
            static fn(string $datum, int $key): bool => $datum !== 'abc' && $key > 1,
            false,
        ];
    }
}
